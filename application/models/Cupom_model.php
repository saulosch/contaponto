<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cupom_model extends CI_Model {

	public function carrega_cupons_por_cidade($cidade)
	{
		$this->db->select('*');
		$this->db->from('cupom');
		$this->db->join('loja', 'cupom.fk_loja = loja.id_loja');
		$this->db->join('segmento', 'loja.fk_segmento = segmento.id_segmento');
		$this->db->join('cidade', 'loja.fk_cidade = cidade.id_cidade');
		$this->db->where(array(
			'cupom.ts_inicio_veiculacao <=' => date('Y-m-d H:i:s'),
			'cupom.ts_fim_veiculacao >=' => date('Y-m-d H:i:s'),
			'loja.fk_cidade' => $cidade,
			'loja.status' => 'A',
		));
		$this->db->order_by('preco_pontos');
		$query = $this->db->get();

		return $query->result_array();
	}

	public function consulta_cupom_valido_por_id($id_cupom)
	{	
		$this->db->select('*');
		$this->db->from('cupom');
		$this->db->join('loja', 'cupom.fk_loja = loja.id_loja');
		$this->db->join('segmento', 'loja.fk_segmento = segmento.id_segmento');
		$this->db->join('cidade', 'loja.fk_cidade = cidade.id_cidade');
		$this->db->where(array(
			'cupom.ts_inicio_veiculacao <=' => date('Y-m-d H:i:s'),
			'cupom.ts_fim_veiculacao >=' => date('Y-m-d H:i:s'),
			'id_cupom' => $id_cupom,
			'loja.status' => 'A',
		));
		
		$query = $this->db->get();

		if ($query->num_rows() > 0) 
		{
			return $query->row_array(0);
		} else {
			return false;
		}
	}

	public function consulta_saldo_pontuacao_usuario($id_usuario)
	{
		$query = $this->db->query('SELECT sum(p.qtd_disponivel) saldo
					        FROM pontuacao p
							WHERE p.fk_usuario = ?
							AND p.validade_pontos >= CURDATE()
							AND p.ts_estorno IS NULL
							group by p.fk_usuario',array($id_usuario));
		
		//	echo "Saldo: ".$query->row_array(0)['saldo'];

		if ($query->num_rows() > 0)
		{
			return $query->row_array(0)['saldo'];
		}
		else
		{
			return false;
		}
	}

	public function consulta_pontuacao_disponivel_usuario($id_usuario)
	{
		$where = array('pontuacao.fk_usuario' => $id_usuario,
						'pontuacao.qtd_disponivel >' => 0,
						'ts_estorno' => NULL
						); 

		$this->db->order_by('pontuacao.validade_pontos ASC');
		$query = $this->db->get_where('pontuacao', $where);
		
		if ($query->num_rows() > 0) 
		{
			return $query->result_array(); 
		}
		else
		{
			return false;
		}
	}

	public function emite_cupom($id_cupom, $id_usuario)
	{	
		$retorno = FALSE;

		//obtem dados do cupom
		$cupom = $this->consulta_cupom_valido_por_id($id_cupom);
		
		//calcula data de validade do cupom
		$date = new DateTime();
		$date->add(new DateInterval('P'.$cupom['validade_dias'].'D')); //adiciona-se X dias à data informada
		$cupom['data_validade'] = $date->format('Y-m-d');

		// verifica se usuario tem pontos suficientes para consumir
		$saldo = $this->consulta_saldo_pontuacao_usuario($id_usuario);
		if ($saldo && $saldo >= $cupom['preco_pontos']) 
		{
			//Inicia a trasação para que, caso ocorra algum erro, o cupom não seja gerado.
			$this->db->trans_begin();
			
			// INSERIR CUPOM_USUARIO
			$id_usuario_cupom = $this->inserir_usuario_cupom(array(
				'fk_usuario' => $id_usuario,
				'fk_cupom' => $id_cupom,
				'codigo_cupom' => mt_rand(100000,999999),
				'dt_validade' => $cupom['data_validade'],
			));

			// CONSUMIR PONTOS NA TABELA PONTUACAO E INSERIR NA CONSUMO_PONTUACAO//
			
			//consulta registros de pontuacao
			$pontuacoes = $this->consulta_pontuacao_disponivel_usuario($id_usuario);
			
			// identifica registros cujo valor precisa ser consumido total ou parcialmente
			if ($pontuacoes) 
			{ 
				$pontuacao = reset($pontuacoes);
				$valor_pendente = $cupom['preco_pontos'];
				while ($valor_pendente > 0) 
				{
					if ($valor_pendente >= $pontuacao['qtd_disponivel'])
					{	
						//adiciona o ID do registro com valor a ter qtd_disponivel zerado a um array
						$ajustar_pontuacao['zerar_ids'][] = $pontuacao['id_pontuacao'];
						$valor_pendente -= $pontuacao['qtd_disponivel'];
						$consumo_pontuacao[] = array(
							'fk_usuario_cupom' => $id_usuario_cupom,
							'fk_pontuacao' => $pontuacao['id_pontuacao'],
							'pontos_consumidos' => $pontuacao['qtd_disponivel'],
							);
					} 
					else
					{
						$ajustar_pontuacao['id_ajuste_parcial'] = $pontuacao['id_pontuacao'];
						$ajustar_pontuacao['valor_restante'] = $pontuacao['qtd_disponivel'] - $valor_pendente;
						$consumo_pontuacao[] = array(
							'fk_usuario_cupom' => $id_usuario_cupom,
							'fk_pontuacao' => $pontuacao['id_pontuacao'],
							'pontos_consumidos' => $valor_pendente,
							);
						$valor_pendente = 0;
					}
					$pontuacao = next($pontuacoes);
				}

				//zerar registros da pontuação
				if (isset($ajustar_pontuacao['zerar_ids']))
				{
					$this->zerar_pontuacao_disponivel($ajustar_pontuacao['zerar_ids']);
				}

				//ajustar qtd_disponivel parcial
				if (isset($ajustar_pontuacao['id_ajuste_parcial']))
				{
					$this->atualiza_pontuacao_por_id($ajustar_pontuacao['id_ajuste_parcial'], 
						array('qtd_disponivel' => $ajustar_pontuacao['valor_restante']));
				}

				//Finaliza a transação
			 	if ($this->db->trans_status() === FALSE)
				{
					$this->db->trans_rollback();
				}
				else
				{	
					//$this->db->trans_rollback();
					$this->db->trans_commit();
					$retorno = $id_usuario_cupom;
				}
			}
		}
		return $retorno;	
	}

	public function zerar_pontuacao_disponivel($ids)
	{
		$this->db->where_in('id_pontuacao', $ids);
		$this->db->update('pontuacao', array('qtd_disponivel' => 0));

		return $this->db->affected_rows();
	}

	public function atualiza_pontuacao_por_id($id_pontuacao,$dados)
	{
		$this->db->where('id_pontuacao', $id_pontuacao);
		$this->db->update('pontuacao', $dados);

		return $this->db->affected_rows();
	}

	public function inserir_usuario_cupom($dados)
	{	


		$this->db->insert('usuario_cupom',$dados);
		return $this->db->insert_id();
	}


	public function consulta_cupons_emitidos_usuario($id_usuario)
	{	
		$retorno ['campos'] = array (
  		'ts_emissao' => 'Data emissão',
  		'id_usuario_cupom' => 'Nr. Cupom', 
  		'nome_fantasia' => 'Loja',
  		'titulo_cupom' => 'Cupom',
  		'dt_validade' => 'Validade',
  		'ts_utilizacao' => 'Utilizado em',
  		'status' => 'Status',
  		);

		$this->db->select('usuario_cupom.ts_emissao,
			usuario_cupom.id_usuario_cupom,
			loja.nome_fantasia,
			cupom.titulo_cupom,
			usuario_cupom.dt_validade,
			usuario_cupom.ts_utilizacao,
			usuario_cupom.ts_estorno,
			cupom.vale_compra'
		);

		$this->db->from('usuario_cupom');
		$this->db->join('cupom', 'usuario_cupom.fk_cupom = cupom.id_cupom');
		$this->db->join('loja', 'cupom.fk_loja = loja.id_loja');
		$this->db->join('segmento', 'loja.fk_segmento = segmento.id_segmento');
		$this->db->where(array('usuario_cupom.fk_usuario' => $id_usuario));

		$this->db->order_by('id_usuario_cupom DESC');
		$this->db->limit(30);
		
		$query = $this->db->get();

		if ($query->num_rows() > 0) 
		{ 
			$retorno['dados'] = $query->result_array();
			
			foreach ($retorno['dados'] as $key => $linha) 
			{
				if($linha['ts_estorno'] != NULL)
				{
					$retorno['dados'][$key]['status'] = 'Cancelado';
				}
				else
				{ 
					if($linha['ts_utilizacao'] != NULL)
					{
						$retorno['dados'][$key]['status'] = 'Utilizado';
					}
					else
					{
						if ($linha['dt_validade'] >= date('Y-m-d'))
						{
							$retorno['dados'][$key]['status'] = 'Disponível';
						}
						else
						{
							$retorno['dados'][$key]['status'] = 'Expirado';
						}
					}
				}
			}
			return $retorno;
		} else {	
			return false;
		}
	}

	public function consulta_usuario_cupom_por_id($id_usuario_cupom)
	{	
		$this->db->select('id_usuario_cupom,
			id_usuario,
			titulo_cupom,
			breve_descricao,
			dt_validade,
			nome,
			sobrenome,
			cpf,
			codigo_cupom,
			nome_fantasia,
			endereco,
			complemento,
			bairro,
			nome_cidade,
			uf,
			telefone,
			descricao_detalhada,
			usuario_cupom.ts_estorno,
			cupom.fk_loja,
			usuario_cupom.ts_utilizacao');
		$this->db->from('usuario_cupom');
		$this->db->join('cupom', 'usuario_cupom.fk_cupom = cupom.id_cupom');
		$this->db->join('usuario', 'usuario_cupom.fk_usuario = usuario.id_usuario');
		$this->db->join('loja', 'cupom.fk_loja = loja.id_loja');
		$this->db->join('cidade', 'loja.fk_cidade = cidade.id_cidade');
		$this->db->join('segmento', 'loja.fk_segmento = segmento.id_segmento');
		$this->db->where(array('usuario_cupom.id_usuario_cupom' => $id_usuario_cupom));
		
		$query = $this->db->get();

		if ($query->num_rows() > 0) 
		{
			return $query->row_array(0);
		} else {
			return false;
		}
	}

	public function consulta_usuario_cupom_valido($id_usuario_cupom,$codigo_cupom)
	{
		$retorno['dados'] =  $this->consulta_usuario_cupom_por_id($id_usuario_cupom);

		if ( ! $retorno['dados'] OR $retorno['dados']['codigo_cupom'] != $codigo_cupom) 
		{
			$retorno['erro'] = 'cupom_nao_encontrado';
		}
		else
		{
			if ($retorno['dados']['ts_utilizacao'])
			{
				$retorno['erro'] = 'cupom_ja_utilizado';
			}
			else
			{
				if ($retorno['dados']['dt_validade'] < date('Y-m-d'))
				{
					$retorno['erro'] = 'cupom_expirado';
				}
				else
				{
					if ($retorno['dados']['ts_estorno'])
					{
						$retorno['erro'] = 'cupom_estornado';
					}
					else
					{
						if ($this->session->usuario['id_loja'] != $retorno['dados']['fk_loja'])
						{
							$retorno['erro'] = 'cupom_de_outra_loja';
						}
						else
						{
							$retorno['erro'] = FALSE;
						}
					}
				}
			}
		}
		return $retorno;
	}

	public function consome_cupom($id_usuario_cupom, $fk_usuario_utilizacao )
	{
		$data = array (
			'ts_utilizacao' => date('Y-m-d H:i:s'),
			'fk_usuario_utilizacao' => $fk_usuario_utilizacao,
		);

		$this->db->where(array(
			'id_usuario_cupom' => $id_usuario_cupom,
			'dt_validade >=' => date('Y-m-d'),
			'ts_estorno' => NULL,
			'ts_utilizacao' => NULL,
		));

		$this->db->update('usuario_cupom', $data);

		return $this->db->affected_rows();
	}

	public function consulta_cupons_recebidos_por_loja($id_loja, $dias_atras = 45, $data_de = FALSE, $data_ate = FALSE)
	{
		if ($data_ate === FALSE)
		{
			$data_ate = date('Y-m-d H:i:s');
		}
		if($data_de === FALSE)
		{
			$data_de = date('Y-m-d',strtotime($data_ate)-$dias_atras*86400);
		}
		$retorno ['campos'] = array (
			'ts_utilizacao' => 'Recebido em',
			'id_usuario_cupom' => 'Nr. do cupom',
			'titulo_cupom' => 'Cupom',
			'cliente' => 'Cliente',
			'nome_lojista' => 'Recebido por'
  		);
  		// ,CONCAT_WS(" ",usuario.nome,usuario.sobrenome) cliente
		$this->db->select(
			'usuario_cupom.ts_utilizacao
			,usuario_cupom.id_usuario_cupom
			,cupom.titulo_cupom
			,usuario.email cliente
			,usuario_loja.nome nome_lojista
		');
		$this->db->from('usuario_cupom');
		$this->db->join('cupom', 'usuario_cupom.fk_cupom = cupom.id_cupom');
		$this->db->join('usuario', 'usuario_cupom.fk_usuario = usuario.id_usuario');
		$this->db->join('usuario usuario_loja', 'usuario_cupom.fk_usuario_utilizacao = usuario_loja.id_usuario');
		
		$this->db->where(array(
			'cupom.fk_loja' => $id_loja,
			'usuario_cupom.ts_utilizacao >=' => $data_de,
			'usuario_cupom.ts_utilizacao <=' => $data_ate,
		));

		$this->db->order_by('ts_utilizacao DESC, id_usuario_cupom ASC');
		$this->db->limit(100);
		
		$query = $this->db->get();

		show($this->db->last_query());

		if ($query->num_rows() > 0) 
		{ 
			$retorno['dados'] = $query->result_array();
			return $retorno;
		} else {	
			return false;
		}
	}

	public function consulta_usuario_cupom_por_loja($id_loja, $data_de, $data_ate, $limite = FALSE)
	{
		$this->db->from('usuario_cupom');
		$this->db->join('cupom', 'usuario_cupom.fk_cupom = cupom.id_cupom');
		
		$this->db->where(array(
			'cupom.fk_loja' => $id_loja,
			'usuario_cupom.ts_utilizacao >=' => $data_de,
			'usuario_cupom.ts_utilizacao <=' => $data_ate,
		));
		if ($limite)
		{
			$this->db->limit($limite);
		}
		
		$query = $this->db->get();

		if ($query->num_rows() > 0) 
		{ 
			return  $query->result_array();
		} else {	
			return false;
		}
	}
}

/* End of file Cupom_model.php */
/* Location: ./application/models/Cupom_model.php */