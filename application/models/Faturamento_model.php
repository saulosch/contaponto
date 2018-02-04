<?php
//defined('BASEPATH') OR exit('No direct script access allowed');

class Faturamento_model extends CI_Model {

	//INICIO das funcoes para gerar o faturamento
		public function processa_faturamento()
		{	
			log_message('debug', 'FATUR Faturamento.processa_faturamento INICIO');
			
			$this->load->model('pontuacao_model');
			
			//Verifica se há faturamento para processar
			$faturamento = $this->consulta_faturamento_pendente();
			echo $faturamento['dt_fim_faturamento'];
			if ($faturamento && $this->inicia_faturamento($faturamento['id_faturamento']))
			{	
				//obtem tabela de preco dos pontos e tabela de descontos 
				$valores_ponto = $this->consulta_preco_ponto_por_periodo($faturamento['dt_inicio_faturamento'],$faturamento['dt_fim_faturamento']);
				$descontos_ponto = $this->consulta_descontos_ponto_por_periodo($faturamento['dt_fim_faturamento']);

				//consulta as lojas para realizar o faturamento
				$lojas = $this->consulta_id_lojas_faturamento();
				foreach ($lojas as $loja)
				{	
					//Inicia a trasação para que, caso ocorra algum erro, a fatura não seja gerada.
					$this->db->trans_begin(); 

					log_message('debug', 'FATUR Loja:'.$loja['id_loja']);
					
					//cria fatura "em branco" para obter o número
					$id_fatura = $this->insere_fatura($loja['id_loja'],$faturamento['dt_vencimento_padrao'],$faturamento['id_faturamento']);
				 	if ( $id_fatura )
				 	{	
				 		$valor_fatura = 0;
				 		
				 		//ITENS DA FATURA: MENSALIDADE
				 		//consulta se há mensalidade a ser cobrada no periodo e insere como item_fatura
					 	$mensalidades = $this->consulta_mensalidades_por_loja_periodo($loja['id_loja'],$faturamento['dt_inicio_faturamento'],$faturamento['dt_fim_faturamento']);
						 	
						if ($mensalidades)
						{
					 		foreach ($mensalidades as $key => $mensalidade)
					 		{
					 			$this->insere_item_fatura(array('fk_fatura' => $id_fatura,'fk_mensalidade' => $mensalidade['id_mensalidade'], 'valor' => $mensalidade['valor']));
					 			$valor_fatura += $mensalidade['valor'];
					 		}
					 	}

					 	//ITENS DA FATURA: PONTUAÇÃO
					 	//consulta pontuacoes da loja no período
					 	$pontuacoes = $this->consulta_pontuacoes_por_loja_periodo($loja['id_loja'],$faturamento['dt_inicio_faturamento'],$faturamento['dt_fim_faturamento']);

					 	if($pontuacoes)
					 	{
					 		$pontuacao_mes = array();
					 		foreach ($pontuacoes as $key => $pontuacao) 
					 		{
					 			//atualiza o registro da pontuacao com o nr da fatura
					 			$this->atualiza_pontuacao_id_fatura($pontuacao['id_pontuacao'] ,$id_fatura);
					 			//define o valor cobrado por essa pontuação
					 			$valor_cobrado_pontuacao = $pontuacao['qtd_pontos']*$valores_ponto[date('Y-m-d',strtotime($pontuacao['ts_pontuacao']))];
					 			//insere o item na fatura
					 			$this->insere_item_fatura(array(
					 				'fk_fatura' => $id_fatura,
					 				'fk_pontuacao' => $pontuacao['id_pontuacao'], 
					 				'valor' => $valor_cobrado_pontuacao));
					 			$valor_fatura += $valor_cobrado_pontuacao;
					 			
					 			$ano_pontuacao = date('Y',strtotime($pontuacao['ts_pontuacao']));
					 			$mes_pontuacao = date('m',strtotime($pontuacao['ts_pontuacao']));

					 			if (isset($pontuacao_mes[$ano_pontuacao][$mes_pontuacao]))
					 			{	
					 				$pontuacao_mes[$ano_pontuacao][$mes_pontuacao]['qtd'] += $pontuacao['qtd_pontos'];
					 				$pontuacao_mes[$ano_pontuacao][$mes_pontuacao]['valor'] += $valor_cobrado_pontuacao;
								}
					 			else
					 			{
					 				$pontuacao_mes[$ano_pontuacao][$mes_pontuacao]['qtd'] = $pontuacao['qtd_pontos'];
					 				$pontuacao_mes[$ano_pontuacao][$mes_pontuacao]['valor'] = $valor_cobrado_pontuacao;
					 			}
					 		}
					 	}

					 	//ITENS DA FATURA: CUPONS   usuario_cupom
					 	$this->load->model('cupom_model');
					 	$cupons = $this->cupom_model->consulta_usuario_cupom_por_loja($loja['id_loja'], $faturamento['dt_inicio_faturamento'], $faturamento['dt_fim_faturamento']);
					 	if ($cupons)
					 	{
					 		foreach ($cupons as $key => $cupom) {
					 			$this->insere_item_fatura(array(
					 				'fk_fatura' => $id_fatura,
					 				'fk_usuario_cupom' => $cupom['id_usuario_cupom'], 
					 				//cupom entra como credito para lojista
					 				'valor' => $cupom['preco_lojista']*(-1))); 
					 			$valor_fatura -= $cupom['preco_lojista'];
					 		}
					 	}

					 	//AJUSTES
					 	//Desconto referente à quantia de CADASTROS DE USUARIOS no período
						$qtd_cadastros = $this->consulta_cadastros_por_loja_periodo($loja['id_loja'],$faturamento['dt_inicio_faturamento'],$faturamento['dt_fim_faturamento']);
						
					 	foreach ($descontos_ponto as $desconto)
					 	{	
					 		foreach ($qtd_cadastros as $qtd_cadastro)
					 		{
						 		if($qtd_cadastro['quantidade'] >= $desconto['qtd_usuarios_de'] && $qtd_cadastro['quantidade']  <= $desconto['qtd_usuarios_ate'])
						 		{	// se algum mes tenha atingiu uma das faixas de desconto, filtra as pontuacoes do mes seguinte para aplicar o desconto
						 			if($qtd_cadastro['mes'] == 12)
						 			{
						 				$pontuacao_data = date('Y-m-d', strtotime((intval($qtd_cadastro['ano'])+1).'-01-01'));
						 			}
						 			else
						 			{
						 				$pontuacao_data = date('Y-m-d', strtotime($qtd_cadastro['ano'].'-'.(intval($qtd_cadastro['mes'])+1).'-01'));
						 			}
						 			$ano_pontuacao = date('Y', strtotime($pontuacao_data));
						 			$mes_pontuacao = date('m', strtotime($pontuacao_data));

						 			$valor_desconto = $pontuacao_mes[$ano_pontuacao][$mes_pontuacao]['valor'] * $desconto['percentual_desconto']/100; //desconto = valor total das pontuacoes da fatura * percentual de desconto de acordo com a qtd de usuarios

					 				// insere ajuste fatura com o valor do desconto
					 				$this->insere_ajuste_fatura(array (
					 					'fk_fatura' => $id_fatura,
					 					'descricao' => 'Desconto de '.($desconto['percentual_desconto'] - floor($desconto['percentual_desconto'] == 0) ? number_format($desconto['percentual_desconto'] , 0 , ',' , '.') : number_format($desconto['percentual_desconto'] , 1 , ',' , '.')).'% por cadastrar '.$qtd_cadastro['quantidade'].' consumidores em '.$qtd_cadastro['mes'].'/'.$qtd_cadastro['ano'],
					 					'valor' => (-1)*$valor_desconto, //valor negativo para subtrair da fatura.
					 					'fk_usuario_criacao' => 1));
					 				$valor_fatura -= $valor_desconto;
					 			}
					 		}
					 	}

					 	//atualiza valor fatura
					 	$this->atualiza_fatura($id_fatura,array('valor_total_ref' => $valor_fatura));
					 	echo "valor fatura: ".$valor_fatura;

				 	}
				 	
				 	//Finaliza a transação
				 	if ($this->db->trans_status() === FALSE)
					{
						$this->db->trans_rollback();
						$vars = array('loja'=>$loja,
							'faturamento'=>$faturamento
							);
						log_message('error', 'FATUR Rollback:'.print_r($vars,TRUE));
						//ENVIAR EMAIL EM CASO DE ERRO!!!
						envia_email('saulos@gmail.com','Erro no faturamento','FATUR Rollback:'.print_r($vars,TRUE));
					}
					else
					{	
						$this->db->trans_commit();
						log_message('info', 'FATUR COMMIT');
					}
				}
				$this->finaliza_faturamento($faturamento['id_faturamento'],'F');
			}
			log_message('debug', 'FATUR Faturamento.processa_faturamento FIM');
		} 
		//fim: processa_faturamento


		public function consulta_faturamento_pendente()
		{
			$where = array(	'status' => 'N', //Novo
							'ts_execucao_programada <=' => date("Y-m-d H:i:s"),
							 'dt_inicio_faturamento <= dt_fim_faturamento' => null,
							 'dt_fim_faturamento <' => date("Y-m-d"),
							);
			$query = $this->db->get_where('faturamento', $where);
			if ($query->num_rows() > 0) 
			{
				log_message('debug', 'FATUR Model.'.__FUNCTION__.' '.implode("|",$query->row_array(0)));
				$faturamento = $query->row_array(0);

				//ajusta hora da data fim do faturamento
				$faturamento['dt_fim_faturamento'] = date('Y-m-d H:i:s', strtotime($faturamento['dt_fim_faturamento'].' 23:59:59'));
				return $faturamento;
			} else {
				log_message('debug', 'FATUR Model.'.__FUNCTION__.' não há.');
				return false;
			}
		}

		public function finaliza_faturamento($id_faturamento, $status)
		{
			$data = array (
				'ts_execucao_fim' => date('Y-m-d H:i:s'),
				'status' => $status, 
				);

			$this->db->where('id_faturamento', $id_faturamento);

			if ($this->db->update('faturamento', $data))
			{
				log_message('info', 'FATUR Model.'.__FUNCTION__.' '.print_r($this->db->affected_rows(),1));
			    return $this->db->affected_rows();
			}
			else
			{
				log_message('error', 'FATUR Model.'.__FUNCTION__.' '.implode("|",$this->db->error()));
				return false;
			}
		}

		public function inicia_faturamento($id_faturamento)
		{
			$data = array (
				'ts_execucao_inicio' => date('Y-m-d H:i:s'),
				'status' => 'P', // Em processamento
				);

			$this->db->where('id_faturamento', $id_faturamento);

			if ($this->db->update('faturamento', $data))
			{
				log_message('info', 'FATUR Model.'.__FUNCTION__.' '.print_r($this->db->affected_rows(),1));
			    return $this->db->affected_rows();
			}
			else
			{
				log_message('error', 'FATUR Model.'.__FUNCTION__.' '.implode("|",$this->db->error()));
				return false;
			}
		}

		public function consulta_id_lojas_faturamento()
		{	
			$this->db->select('id_loja');
			$this->db->order_by('id_loja DESC');

			$query = $this->db->get('loja');

			if ($query->num_rows() > 0) 
			{
				log_message('info', 'FATUR Model.'.__FUNCTION__.' '.print_r($query->result_array(),1));
				return $query->result_array();
			} 
			else
			{
				if ($this->db->error())
				{
					log_message('error', 'FATUR Model.'.__FUNCTION__.' '.implode("|",$this->db->error()));
				}
				else
				{
					log_message('debug', 'FATUR Model.'.__FUNCTION__.' no data found');
				}
				return false;
			}
		}

		public function consulta_mensalidades_por_loja_periodo($fk_loja,$data_inicio,$data_fim)
		{
			$where = array('fk_loja' => $fk_loja,
				'ts_ini_validade <=' => $data_fim,
				'ts_fim_validade >=' => $data_inicio,
				);
			
			$query = $this->db->get_where('mensalidade', $where);
			
			if ($query->num_rows() > 0) 
			{
				foreach ($query->result_array() as $key => $mensalidade) 
				{
					if ($mensalidade) 
					{
						list($ano_ini, $mes_ini, $dia_ini) = explode('-', date('Y-m-d', strtotime($data_inicio)));
						list($ano_fim, $mes_fim, $dia_fim) = explode('-', date('Y-m-d', strtotime($data_fim)));
						
						for ($a=$ano_ini; $a <= $ano_fim  ; $a++)
						{
							$m_de = ($a==$ano_ini)?$mes_ini:1;
							$m_ate = ($a==$ano_fim)?$mes_fim:12;
						
							for ($m=$m_de; $m <= $m_ate; $m++)
							{ 	
								$data_mensalidade = str_pad($mensalidade['dia_faturamento'], 2, '0', STR_PAD_LEFT).'-'.str_pad($m, 2, '0', STR_PAD_LEFT).'-'.$a;
															
								if (strtotime($data_mensalidade) >= strtotime($data_inicio) && strtotime($data_mensalidade) <= strtotime($data_fim) && strtotime($data_mensalidade) >= strtotime($mensalidade['ts_ini_validade']) && strtotime($data_mensalidade) <= strtotime($mensalidade['ts_fim_validade'])) //se a data da mensalidade está dentro do período de faturamento e de validade do registro da mensalidade
								{
									$retorno[] = array('id_mensalidade' => $mensalidade['id_mensalidade'],
										'data_mensalidade' => $data_mensalidade,
										'valor' => $mensalidade['valor'], 
										'descricao' =>  $data_mensalidade.' - '.$mensalidade['descricao'],
									);
								}
							}	
						}
					}
				}
				if (isset($retorno))
				{ 
					log_message('info', 'FATUR Model.'.__FUNCTION__.' '.print_r($retorno,1));
					return $retorno;
				}
				else
				{
					log_message('debug', 'FATUR Model.'.__FUNCTION__.' não ha mensalidade nos parametros da pesquisa.');
					return false;
				}
			}
			else
			{
				log_message('debug', 'FATUR Model.'.__FUNCTION__.' não ha mensalidade.');
				return false;
				
			}
		}

		public function consulta_pontuacoes_por_loja_periodo($fk_loja,$data_inicio,$data_fim)
		{
			$where = array('fk_loja' => $fk_loja,
				'ts_pontuacao <=' => $data_fim,
				'ts_pontuacao >=' => $data_inicio,
				);
			
			$query = $this->db->get_where('pontuacao', $where);
			
			if ($query->num_rows() > 0) 
			{
				log_message('info', 'FATUR Model.'.__FUNCTION__.' '.print_r($query->result_array(),1));
				return $query->result_array();
			}
			else
			{
				log_message('debug', 'FATUR Model.'.__FUNCTION__.' não ha pontuacao para id_loja='.$fk_loja);
				return false;
			}
		}

		public function consulta_cadastros_por_loja_periodo($fk_loja,$data_inicio_faturamento,$data_fim_faturamento)
		{
			//define data de inicio e fim com relação ao mes anterior ao periodo que está sendo faturado
			list($ano_ini, $mes_ini, $dia_ini) = explode('-', date('Y-m-d', strtotime($data_inicio_faturamento)));
			list($ano_fim, $mes_fim, $dia_fim) = explode('-', date('Y-m-d', strtotime($data_fim_faturamento)));

			if( intval($mes_ini) == 1)
			{
				$mes_ini = 12;
				$ano_ini -= 1;
			}
			else
			{
				$mes_ini -= 1;
			}

			if( intval($mes_fim) == 1)
			{
				$mes_fim = 12;
				$ano_fim -= 1;
			}
			else
			{
				$mes_fim -= 1;
			}

			// data inicio = primeiro dia do mes anterior ao do inicio do faturamento
			$data_inicio = date('Y-m-d', strtotime($ano_ini.'-'.$mes_ini.'-01'));
			// data fim = ultimo dia do mes anterior ao do fim do faturamento (incluindo hora)
			$data_fim    = date('Y-m-t H:i:s', strtotime($ano_fim.'-'.$mes_fim.'-01 23:59:59'));

			$this->db->select('EXTRACT(YEAR FROM ts_criacao) ano, EXTRACT(MONTH FROM ts_criacao) mes, count(1) quantidade');

			$where = array('fk_loja_cadastro' => $fk_loja,
				'ts_criacao >=' => $data_inicio,
				'ts_criacao <=' => $data_fim,
			);

			$this->db->group_by('ano, mes');
			$query = $this->db->get_where('usuario', $where);
			
			log_message('debug', 'FATUR Model.'.__FUNCTION__.' '.$query->num_rows().' users cadastrados por id_loja='.$fk_loja);
			log_message('info', 'FATUR Model.'.__FUNCTION__.' '.print_r($query->result_array(),1));
			return $query->result_array();
		}

		public function consulta_preco_ponto_por_periodo($data_inicio,$data_fim)
		{
			$where = array('item' => 'P', //valor de venda do ponto
				'dt_inicio_validade <=' => $data_fim,
				'dt_fim_validade >=' => $data_inicio,
				);
			
			$query = $this->db->get_where('preco', $where);
			
			
			list($ano_ini, $mes_ini, $dia_ini) = explode('-', date('Y-m-d', strtotime($data_inicio)));
			list($ano_fim, $mes_fim, $dia_fim) = explode('-', date('Y-m-d', strtotime($data_fim)));

			for ($a=$ano_ini; $a <= $ano_fim  ; $a++)
			{
				$m_de = ($a==$ano_ini)?$mes_ini:1;
				$m_ate = ($a==$ano_fim)?$mes_fim:12;
			
				for ($m=$m_de; $m <= $m_ate; $m++)
				{ 	
					$d_de = ($m==$mes_ini)?$dia_ini:1;
					$d_ate = ($m==$mes_fim)?$dia_fim:date('t', strtotime($a.'-'.$m.'-01'));

					for ($d=$d_de; $d <= $d_ate; $d++)
					{	
						$data_chave = date('Y-m-d',strtotime($a.'-'.$m.'-'.$d));

						foreach ($query->result_array() as $preco) {
							if($preco['dt_inicio_validade'] <= $data_chave && $preco['dt_fim_validade'] >= $data_chave)
							{
								$retorno[$data_chave] = $preco['valor']/$preco['qtd_item'];
							}	
						}						
						if( ! isset($retorno[$data_chave]))
						{
							$retorno[$data_chave] = 0.05; // valor padrao
							log_message('error', 'FATUR Model.'.__FUNCTION__.' não há valor de pontos válido para o dia '.$data_chave);
						}
					}
				}	
			}
			log_message('info', 'FATUR Model.'.__FUNCTION__.' last_query '.print_r($this->db->last_query(),1));
			log_message('info', 'FATUR Model.'.__FUNCTION__.' '.print_r($retorno,1));
			return $retorno;
		}

		public function consulta_descontos_ponto_por_periodo($data = NULL)
		{
			if($data === NULL)
			{
				$data = date('Y-m-d');
			}
			$where = array( //valor de venda do ponto
				'dt_inicio_validade <=' => $data,
				'dt_fim_validade >=' => $data,
				);
			
			$query = $this->db->get_where('desconto', $where);
			
			if ($query->num_rows() > 0) 
			{
				log_message('info', 'FATUR Model.'.__FUNCTION__.' '.print_r($query->result_array(),1));
				return $query->result_array();
			}
			else
			{
				log_message('debug', 'FATUR Model.'.__FUNCTION__.' não há descontos cadastrados.');
				return array();
			}
		}

		public function insere_fatura($fk_loja,$dt_vencimento,$fk_faturamento)
		{
			$fatura = array (
				'fk_loja' => $fk_loja,
				'dt_vencimento' => $dt_vencimento,
				'fk_faturamento' => $fk_faturamento,
				);

			if ($this->db->insert('fatura', $fatura))
			{
				log_message('info', 'FATUR Model.'.__FUNCTION__.' '.print_r($this->db->insert_id(),1));
				return $this->db->insert_id();	
			} else {
				log_message('error', 'FATUR Model.'.__FUNCTION__.' '.implode("|",$this->db->error()));
				return false;
			}
		}

		public function atualiza_pontuacao_id_fatura($id_pontuacao, $id_fatura)
		{
			$this->db->where('id_pontuacao', $id_pontuacao);
			if ($this->db->update('pontuacao', array ('fk_fatura' => $id_fatura)))
			{
				log_message('info', 'FATUR Model.'.__FUNCTION__.' '.print_r(array('id_pontuacao'=> $id_pontuacao, 'fk_fatura' => $id_fatura),1));
			    return $this->db->affected_rows();
			}
			else
			{
				log_message('error', 'FATUR Model.'.__FUNCTION__.' '.implode("|",$this->db->error()));
				return false;
			}
		}

		public function insere_item_fatura($item_fatura)
		{
			if ($this->db->insert('item_fatura', $item_fatura))
			{
				log_message('info', 'FATUR Model.'.__FUNCTION__.' '.print_r(array($this->db->insert_id(),$item_fatura),1));
				return $this->db->insert_id();	
			} else {
				log_message('error', 'FATUR Model.'.__FUNCTION__.' '.implode("|",$this->db->error()));
				return false;
			}
		}

		public function insere_ajuste_fatura($ajuste_fatura)
		{
			if ($this->db->insert('ajuste_fatura', $ajuste_fatura))
			{
				log_message('info', 'FATUR Model.'.__FUNCTION__.' '.print_r(array($this->db->insert_id(),$ajuste_fatura),1));
				return $this->db->insert_id();	
			} else {
				log_message('error', 'FATUR Model.'.__FUNCTION__.' '.implode("|",$this->db->error()));
				return false;
			}
		}

		public function atualiza_fatura($id_fatura,$data)
		{
			$this->db->where('id_fatura', $id_fatura);

			if ($this->db->update('fatura', $data))
			{
				log_message('info', 'FATUR Model.'.__FUNCTION__.' '.print_r(array('id_fatura'=>$id_fatura,$data),1));
			    return $this->db->affected_rows();
			}
			else
			{
				log_message('error', 'FATUR Model.'.__FUNCTION__.' '.implode("|",$this->db->error()));
				return false;
			}
		}
	//FIM    das funcoes para gerar o faturamento

	
	public function consulta_faturas_por_loja($id_loja, $tempo_em_dias = 60)
	{	
		$retorno ['campos'] = array (
	  		'id_fatura' => 'Nr. Fatura',
	  		'dt_vencimento' => 'Vencimento',
	  		'ts_criacao' => 'Emitida em',
	  		'valor_total_ref' => 'Valor',
	  		'data_pgto_boleto' => 'Dt. Pgto',
			'data_estorno_fatura' => 'Dt. Estorno',
  		);

		$select = 'id_fatura, dt_vencimento, fatura.ts_criacao, valor_total_ref, data_pgto_boleto, data_estorno_fatura';
	  	
		$this->db->select($select);
		$this->db->join('faturamento','fatura.fk_faturamento = faturamento.id_faturamento');

		$where = array('fatura.fk_loja' => $id_loja,
						'fatura.ts_criacao >=' => date('Y-m-d',time()-$tempo_em_dias*86400) ,// 60 dias (1 dia = 86.400 segundos)
						'faturamento.status' => 'L', // status Liberado
						); 

		$this->db->order_by('fatura.dt_vencimento DESC');

		$query = $this->db->get_where('fatura', $where);

		if ($query->num_rows() > 0) 
		{ 
			$retorno['dados'] = $query->result_array();
			return $retorno;
		} else {
			return false;
		}
	}

	public function consulta_fatura_por_id($id_fatura, $fk_loja = FALSE)
	{
		$where = array('id_fatura' => $id_fatura);
		if ($fk_loja)
		{
			$where['fk_loja'] = $fk_loja;
		}
		$this->db->join('faturamento','fatura.fk_faturamento = faturamento.id_faturamento');
		$query = $this->db->get_where('fatura', $where);
		if ($query->num_rows() > 0) 
		{
			return $query->row_array(0);
		} else {
			return false;
		}
	}
	
	public function consulta_item_fatura_por_fatura($fk_fatura)
	{
		$this->db->select(
			'item_fatura.id_item_fatura
			,item_fatura.fk_mensalidade
			,item_fatura.fk_pontuacao
			,item_fatura.fk_usuario_cupom
			,item_fatura.valor
			,mensalidade.dia_faturamento dia_cobrar_mensalidade
			,mensalidade.descricao descricao_mensalidade
			,pontuacao.ts_pontuacao
			,pontuacao.qtd_pontos
			,usuario_cupom.ts_utilizacao
			,usuario_cupom.fk_cupom
		');

		$this->db->join('mensalidade','item_fatura.fk_mensalidade = mensalidade.id_mensalidade','left');
		$this->db->join('pontuacao','item_fatura.fk_pontuacao = pontuacao.id_pontuacao','left');
		$this->db->join('usuario_cupom','item_fatura.fk_usuario_cupom = usuario_cupom.id_usuario_cupom','left');

		$where = array('item_fatura.fk_fatura' => $fk_fatura);
		$this->db->order_by('ts_utilizacao, ts_pontuacao, dia_cobrar_mensalidade, fk_mensalidade');
		$query = $this->db->get_where('item_fatura', $where);
		if ($query->num_rows() > 0) 
		{
			return $query->result_array();
		} else {
			return false;
		}
	}
	
	public function consulta_ajuste_fatura_por_fatura($fk_fatura)
	{
		$where = array('fk_fatura' => $fk_fatura);
		$query = $this->db->get_where('ajuste_fatura', $where);
		if ($query->num_rows() > 0) 
		{
			return $query->result_array();
		} else {
			return false;
		}
	}

	public function atualiza_valor_referencia_fatura($id_fatura)
	{
		$valor_fatura = 0;
		$itens = $this->consulta_item_fatura_por_fatura($id_fatura);
		foreach ($itens as $key => $value)
		{
			$valor_fatura += $value['valor'];
		}
		$ajustes = $this->consulta_ajuste_fatura_por_fatura($id_fatura);
		foreach ($ajustes as $key => $value)
		{
			$valor_fatura += $value['valor'];
		}
		$fatura = $this->consulta_fatura_por_id($id_fatura);
		if ($fatura['valor_total_ref'] == $valor_fatura)
		{
			return TRUE;
		}
		else
		{
			return $this->atualiza_fatura($id_fatura,array('valor_total_ref' => $valor_fatura));
		}
	}

}

/* End of file Faturamento_model.php */
/* Location: ./application/models/Faturamento_model.php */