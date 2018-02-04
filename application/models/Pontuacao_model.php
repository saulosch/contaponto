<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pontuacao_model extends CI_Model {

	public function carrega_campos_pontuacao($tipo)
	{

		switch ($tipo) {
			case 'conceder_pontos':
				
				$campos[] = array(	
					'nome_campo' 		=> 'email',
					'tipo_campo_html' 	=> 'email',
					'label' 			=> 'E-mail',
					'obrigatorio' 		=> FALSE,
					'propriedades_html' => 'autofocus',
					'form_validation' 	=> 'valid_email|trim|max_length[100]',
					'placeholder' 		=> 'Ex.: jose.carlos@site.com.br');

				$campos[] = array(	
					'nome_campo' 		=> 'cpf',
					'tipo_campo_html' 	=> 'number',
					'label' 			=> 'CPF',
					'obrigatorio' 		=> FALSE,
					'propriedades_html' => '',
					'form_validation' 	=> 'trim|exact_length[11]|is_natural_no_zero|callback__valida_cpf',
					'placeholder' 		=> 'Apenas números. Ex.: 12345678901');
				
				$campos[] = array(	
					'nome_campo' 		=> 'valor_desconto',
					'tipo_campo_html' 	=> 'number',
					'label' 			=> 'Desconto concedido',
					'obrigatorio' 		=> TRUE,
					'propriedades_html' => 'step="0.01"',
					'form_validation' 	=> 'trim|required|is_natural_no_zero|callback__valida_limite_credito|callback__valida_cliente',
					'placeholder' 		=> 'Valor economizado pelo cliente. Ex.: 1,23');
				
				$campos[] = array(	
					'nome_campo' 		=> 'qtd_pontos',
					'tipo_campo_html' 	=> 'number',
					'label' 			=> 'Quantidade de pontos',
					'obrigatorio' 		=> TRUE,
					'propriedades_html' => '',
					'form_validation' 	=> 'trim|required|is_natural_no_zero|callback__valida_limite_credito|callback__valida_cliente',
					'placeholder' 		=> 'Apenas números. Ex.: 200');

				break;
			
			default:
				$campos = FALSE;
				break;
		}

		return $campos;
	}

	public function insere_pontuacao($pontuacao)
	{	
		$this->db->insert('pontuacao', $pontuacao);
		$id = $this->db->insert_id();
		if($id)
		{
			if(isset($pontuacao['fk_loja'])) // se é uma loja que está pontuando, ajusta limite de crédito
			{ 
				$this->consome_limite_credito($pontuacao['fk_loja'],$pontuacao['qtd_pontos']*(-1));
			}
			return true;
		}
		else
		{
			return false;
		}
	}

	public function consulta_pontuacao_por_loja($id_loja)
	{	
		$retorno ['campos'] = array (
	  		'ts_pontuacao' => 'Data',
	  		'id_pontuacao' => 'Código', 
	  		'email' => 'Consumidor',
	  		'qtd_pontos' => 'Quantidade',
	  		'pessoa_criacao' => 'Concedido por',
	  		'fk_fatura' => 'Fatura',
	  		//'data_pgto_boleto' => 'Dt. Pgto',
	  		'ts_estorno' => 'Estornado em',
			'pessoa_estorno' => 'Estornado por',
  		);

	  	$select = 'pontuacao.id_pontuacao,pontuacao.ts_pontuacao,pontuacao.qtd_pontos,fatura.data_pgto_boleto,pontuacao.ts_estorno';
	  	$this->db->join('fatura', 'pontuacao.fk_fatura = fatura.id_fatura','left');            
	  	
		$select .= ',usuario.email,usuario_criacao.nome pessoa_criacao,usuario_estorno.nome pessoa_estorno,pontuacao.fk_fatura';
		$this->db->join('usuario', 'pontuacao.fk_usuario = usuario.id_usuario');
		$this->db->join('usuario usuario_criacao', 'pontuacao.fk_usuario_criacao = usuario_criacao.id_usuario','left');
		$this->db->join('usuario usuario_estorno', 'pontuacao.fk_usuario_estorno = usuario_estorno.id_usuario','left');

		$this->db->select($select);

		$where = array('pontuacao.fk_loja' => $id_loja,
						'pontuacao.ts_pontuacao >=' => date('Y-m-d',time()-60*86400) ,// 60 dias (1 dia = 86.400 segundos)
						); 

		$this->db->order_by('pontuacao.ts_pontuacao DESC');

		$query = $this->db->get_where('pontuacao', $where);

		if ($query->num_rows() > 0) 
		{ 
			$retorno['dados'] = $query->result_array();
			return $retorno;
		} else {
			return false;
		}
	}
	
	public function consulta_pontuacao_por_usuario($id_usuario)
	{
  		$retorno ['campos'] = array (
  		'ts_pontuacao' => 'Data',
  		'id_pontuacao' => 'Código', 
  		'nome_fantasia' => 'Loja',
  		'nr_vale_ponto' => 'Nr. Vale-ponto',
  		'qtd_pontos' => 'Quantidade',
  		'qtd_disponivel' => 'Quantidade disponível',
  		'validade_pontos' => 'Validade',
  		'status' => 'Status',
  		);

	  	$select = 'pontuacao.id_pontuacao,pontuacao.ts_pontuacao,pontuacao.qtd_pontos,pontuacao.ts_estorno';

	  	//comentado pois foi retirado o vínculo com a fatura
	  	// $select = 'pontuacao.id_pontuacao,pontuacao.ts_pontuacao,pontuacao.qtd_pontos,fatura.data_pgto_boleto,pontuacao.ts_estorno';
	  	//$this->db->join('fatura', 'pontuacao.fk_fatura = fatura.id_fatura','left');            

		$select .= ',loja.nome_fantasia,loja_vale.nome_fantasia as nome_fantasia_vale,nr_vale_ponto,validade_pontos,qtd_disponivel';
		$this->db->join('loja', 'pontuacao.fk_loja = loja.id_loja', 'left');
		$this->db->join('lote_vale_ponto', 'pontuacao.fk_lote_vale_ponto = lote_vale_ponto.id_lote_vale_ponto', 'left');
		$this->db->join('loja as loja_vale', 'lote_vale_ponto.fk_loja = loja_vale.id_loja', 'left');
	
		$this->db->select($select);

		$where = array('pontuacao.fk_usuario' => $id_usuario,
						'pontuacao.ts_pontuacao >=' => date('Y-m-d',time()-60*86400) ,// 60 dias (1 dia = 86.400 segundos)
						); 

		$this->db->order_by('pontuacao.ts_pontuacao DESC');
		$this->db->limit(50);
		
		$query = $this->db->get_where('pontuacao', $where);

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
					if($linha['qtd_disponivel'] > 0)
					{
						if ($linha['validade_pontos'] >= date('Y-m-d'))
						{
							$retorno['dados'][$key]['status'] = 'Disponível';
						}
						else
						{
							$retorno['dados'][$key]['status'] = 'Expirado';
						}
					}
					else
					{
						$retorno['dados'][$key]['status'] = 'Utilizado';
					}
				}
			}
			return $retorno;
		} else {	
			return false;
		}
	}

	public function verifica_limite_credito($loja)
	{
		$query = $this->db->get_where('limite_credito',array('fk_loja' => $loja));

		if ($query->num_rows() == 0) {
			$this->insere_limite_credito($loja);
			$query = $this->db->get_where('limite_credito',array('fk_loja' => $loja));
		}

		if ($query->row_array(0))
		{
			return $query->row_array(0);
		}
		else
		{
			return FALSE;
		}
	}


	public function insere_limite_credito($loja)
	{	
		$query = $this->db->get_where('parametro',array('id_parametro' => 1));//Limite de credito lojista padrão

		$limite = ($query->row_array(0))?$query->row_array(0)['valor1']:2500;
	
		$data = array (
			'fk_loja' => $loja,
			'limite_max' => $limite,
			'saldo_atual' => $limite,
			'ts_ult_atu_saldo' => date('Y-m-d H:i:s'),
			'ts_inicio_validade' => date('Y-m-d H:i:s'),
			'ts_fim_validade' => '2030-12-31 23:59:59',
			);
		
		$this->db->insert('limite_credito', $data);

		return $this->db->insert_id();
	}

	/** 
	Função para CONSUMIR pontos do limite de crédito dos lojistas.
	*/
	public function consome_limite_credito($loja,$pontos)
	{	
		if ($pontos < 0)
		{	
			$pontos = $pontos * (-1);
			$sql = 'UPDATE limite_credito 
				SET ts_ult_atu_saldo = ?,
				saldo_atual = saldo_atual - ?
				WHERE fk_loja = ? 
				AND ts_inicio_validade <= ?
				AND `ts_fim_validade` >= ?;';
		}
		else
		{
			$sql = 'UPDATE limite_credito 
				SET ts_ult_atu_saldo = ?,
				saldo_atual = saldo_atual + ?
				WHERE fk_loja = ? 
				AND ts_inicio_validade <= ?
				AND `ts_fim_validade` >= ?;';
		}

		$data = array(
			date('Y-m-d H:i:s'), //ts_ult_atu_saldo
			$pontos, //saldo_atual
			$loja,//fk_loja
			date('Y-m-d H:i:s'),//ts_inicio_validade
			date('Y-m-d H:i:s'),//ts_fim_validade
		);

		$this->db->query($sql, $data);
		// show(array('query' => $this->db->last_query(),'linhas afetadas' => $this->db->affected_rows()));
		return $this->db->affected_rows();
	}

	public function consulta_pontuacao_nos_ultimos_dias($qtd_dias = 5)
	{
	  	$select = 'pontuacao.* ,loja.nome_fantasia';
	  	$this->db->join('loja', 'pontuacao.fk_loja = loja.id_loja','left');

		$this->db->select($select);

		$where = array('pontuacao.ts_pontuacao >=' => date('Y-m-d',time()-$qtd_dias*86400),
						'pontuacao.ts_estorno' => null,
						); 

		$this->db->order_by('pontuacao.ts_pontuacao DESC');

		$query = $this->db->get_where('pontuacao', $where);

		if ($query->num_rows() > 0) 
		{ 
			return $query->result_array();;
		}
		else
		{
			return false;
		}
	}



}
/* End of file Pontuacao_model.php */
/* Location: ./application/models/Pontuacao_model.php */