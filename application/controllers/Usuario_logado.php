<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usuario_logado extends MY_Controller 
{
	public function __construct() 
	{
		parent::__construct();
		$this->load->model('usuario_model');
	}

	public function alterar_cadastro()
	{	
		if ($this->_logado())
		{
			//carrega campos
			$campos = $this->usuario_model->carrega_campos_cadastro('alteracao');
		
			$this->load->library('form_validation');
			foreach ($campos as $key => $campo) 
			{
				$this->form_validation->set_rules($campo['nome_campo'], $campo['label'], $campo['form_validation']);
			}
			$this->form_validation->set_error_delimiters('<div class="status alert alert-danger">', '</div>');
			
			//carrega dados armazenados da tablea usuario
			$usuario = $this->usuario_model->consulta_usuario_por_id($this->session->usuario['id_usuario']);
			
			// caso o usuario tenha inserido uma senha e não tenha preenchido o campo confirmar senha
			// adiciona o campo ao post para o form_validation pegar a inconsistência
			if($this->input->post('senha',true) && ! $this->input->post('confirmar_senha',true))
			{
				$_POST['confirmar_senha'] = 1;
			}
			
			if ($this->form_validation->run() == FALSE) 
			{
				if( ! $this->input->post('cadastro_submit',true)) 
				{

						$usuario['confirmar_senha'] = '';
						$usuario['senha'] = '';
						if($usuario['data_nascimento']) 
						{
							$usuario['data_nascimento'] = formata_data($usuario['data_nascimento'],'d/m/Y','');
						}
						foreach ($campos as $campo) 
						{
							$_POST[$campo['nome_campo']] = $usuario[$campo['nome_campo']];
						}
				}
				$data['pagina_atual'] = 'cadastro';
				$data['campos'] = $campos;
				$this->_carrega_pagina($data);
			} 
			else 
			{
				foreach ($campos as $key => $campo) 
				{
					if ( ! in_array($campo['nome_campo'], array ('email','cpf','senha','confirmar_senha','data_nascimento')) && $this->input->post($campo['nome_campo'],TRUE) != $usuario[$campo['nome_campo']]) 
					{
						$data[$campo['nome_campo']] = $this->input->post($campo['nome_campo'],TRUE);
					}
				}
				if ($this->input->post('senha', TRUE))
				{
					$data['senha'] = $this->input->post('senha', TRUE);
					$data['sal'] = $usuario['sal'];
				}
				
				if ($this->input->post('data_nascimento', TRUE))
				{
					$data['data_nascimento'] = formata_data($this->input->post('data_nascimento', TRUE));
				}
				else
				{
					$data['data_nascimento'] = null;
				}
				if (isset($data))
				{
					$data['email'] = $usuario['email'];
					if ($this->usuario_model->altera_usuario($data)) 
					{
						$this->_exibe_mensagem('altera_cadastro_sucesso');
					}
					else
					{
						//carrega pagina de erro.
						$this->_exibe_mensagem('altera_cadastro_erro');
					}
				}
				else
				{
					$this->_exibe_mensagem('altera_cadastro_sucesso');
				}	
			}
		}
	}

	public function extrato()
	{	
		/// ABA PONTOS
		$this->load->model('pontuacao_model');
		$data ['model']['pontuacao'] = $this->pontuacao_model->consulta_pontuacao_por_usuario($this->session->usuario['id_usuario']);
		if ($data['model']['pontuacao'])
		{
			$soma_pontos = 0;
			foreach ($data['model']['pontuacao']['dados'] as $key => $value)
			{
				switch ($value['status'])
				{
				 	case 'Disponível':
				 		$data['model']['pontuacao']['dados'][$key]['tipo_linha'] = '';
				 		$soma_pontos += $value['qtd_pontos'];
				 		break;
				 	
				 	case 'Utilizado':
				 		$data['model']['pontuacao']['dados'][$key]['tipo_linha'] = 'info';
				 		$soma_pontos += $value['qtd_pontos'];
				 		break;
				 	
				 	case 'Expirado':
				 		$data['model']['pontuacao']['dados'][$key]['tipo_linha'] = 'warning';
				 		$soma_pontos += $value['qtd_pontos'];
				 		break;
				 	
				 	default: // Cancelado
				 		$data['model']['pontuacao']['dados'][$key]['tipo_linha'] = 'danger';
				 		break;
				}
				if ( ! $value['nome_fantasia'])
				{	
					$data['model']['pontuacao']['dados'][$key]['nome_fantasia'] = $value['nome_fantasia_vale'];
				}
			}
			$data['campos_data']['pontuacao'] = array('ts_pontuacao','validade_pontos','data_pgto_boleto','ts_estorno');
			$data['abas']['pontuacao'] = 'Pontos';

			// ABA CUPONS
			$this->load->model('cupom_model');
			$data ['model']['cupom'] = $this->cupom_model->consulta_cupons_emitidos_usuario($this->session->usuario['id_usuario']);
			
			$valor_economizado = 0;

			if ($data['model']['cupom'])
			{
				foreach ($data['model']['cupom']['dados'] as $key => $value)
				{
					switch ($value['status'])
					{
					 	case 'Disponível':
					 		$data['model']['cupom']['dados'][$key]['tipo_linha'] = '';
					 		$data['model']['cupom']['dados'][$key]['link'] = 'usuario_logado/cupom/'.$value['id_usuario_cupom'];
					 		$valor_economizado += $value['vale_compra'];
					 		break;
					 	
					 	case 'Utilizado':
					 		$data['model']['cupom']['dados'][$key]['tipo_linha'] = 'info';
					 		$valor_economizado += $value['vale_compra'];
					 		break;
					 	
					 	case 'Expirado':
					 		$data['model']['cupom']['dados'][$key]['tipo_linha'] = 'warning';
					 		break;
					 	
					 	default: // Cancelado
					 		$data['model']['cupom']['dados'][$key]['tipo_linha'] = 'danger';
					 		break;
					}
				}
				$data['campos_data']['cupom'] = array('ts_emissao','dt_validade','ts_utilizacao');
				$data['abas']['cupom'] = 'Cupons';
			}

			//obtem o valor unitário do ponto para a data atual
			$this->load->model('faturamento_model');
			$preco_ponto = $this->faturamento_model->consulta_preco_ponto_por_periodo(date('Y-m-d'),date('Y-m-d'))[date('Y-m-d')];

			$valor_economizado += $preco_ponto * $soma_pontos;

			$data['mensagem_economia'] = ($valor_economizado > 0) ? 'Nos últimos 60 dias, você economizou cerca de <b>'.formata_moeda($valor_economizado).'</b> com a Contaponto Rede de Fidelidade. Parabéns!' : '';

			// ABRE A VIEW
			$data['pagina_atual'] = 'extrato';
			$this->_carrega_pagina($data);
		}
		else
		{
			$this->_exibe_mensagem('extrato_sem_dados');
		}
	}

	public function detalhe_cupom($id_cupom = false)
	{
		if ($id_cupom)
		{
			$this->_atualiza_sessao();
			$this->load->model('cupom_model');
			$data['cupom'] = $this->cupom_model->consulta_cupom_valido_por_id($id_cupom);

			if ($data['cupom']) {
				$data['saldo'] = $this->session->usuario['saldo'];

				//caso o cupom nao tenha imagem, utiliza imagem da empresa
				if ( ! $data['cupom']['imagem_principal'])
				{
					$data['cupom']['imagem_principal'] = $data['cupom']['logo'];
				}
				
				$data['pagina_atual'] = 'detalhe_cupom';
				$this->_carrega_pagina($data);


			} else {
				$this->_exibe_mensagem('cupom_nao_existe');
			}
		}
		else
		{
			redirect('/troque_seus_pontos','refresh');
		}
	}

	public function emitir_cupom($id_cupom = false)
	{
		if ($id_cupom)
		{
			$this->load->model('cupom_model');
			$cupom = $this->cupom_model->consulta_cupom_valido_por_id($id_cupom);

			if ($cupom)
			{
				$this->_atualiza_sessao();
				
				if ($this->session->usuario['saldo'] >= $cupom['preco_pontos'])
				{	
					$id_usuario_cupom = $this->cupom_model->emite_cupom($id_cupom, $this->session->usuario['id_usuario']);
					$this->_atualiza_sessao();
					if ($id_usuario_cupom)
					{
						redirect('/usuario_logado/cupom/'.$id_usuario_cupom,'refresh');
					}
					else
					{
						$this->_exibe_mensagem('erro_emitir_cupom');
					}
				} 
				else
				{
					$this->_exibe_mensagem('pontos_insuficientes');
				}
			}
			else
			{
				$this->_exibe_mensagem('cupom_nao_existe');
			}
		}
		else
		{
			redirect('/troque_seus_pontos','refresh');
		}
	}

	public function cupom($id_usuario_cupom)
	{
		if ($id_usuario_cupom)
		{
			$this->load->model('cupom_model');
			$data['cupom'] = $this->cupom_model->consulta_usuario_cupom_por_id($id_usuario_cupom);

			if ($data['cupom'] && $this->session->usuario['id_usuario'] == $data['cupom']['id_usuario']) 
			{
				if ($data['cupom']['ts_utilizacao'] OR $data['cupom']['dt_validade'] < date('Y-m-d') OR $data['cupom']['ts_estorno'])
				{
					$this->_exibe_mensagem('cupom_utilizado_ou_expirado');//
				}
				else
				{	
					$data['rodape'] = $this->load->view('includes/v_rodape_detalhe_usuario_cupom_usuario',$data, true);
					$data['pagina_atual'] = 'detalhe_usuario_cupom';
					$this->_carrega_pagina($data);
				}
			}
			else
			{
				$this->_exibe_mensagem('cupom_nao_existe');
			}
		}
		else
		{
			redirect('/usuario_logado/extrato?aba=cupom','refresh');
		}
	}

	public function cadastrar_vale_pontos()
	{	
		if ($this->session->usuario['acesso'] == 6) // Associação
		{
			$tipo = 'associacao';
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
				'nome_campo' 		=> 'nr_vale',
				'tipo_campo_html' 	=> 'number',
				'label' 			=> 'Número',
				'obrigatorio' 		=> TRUE,
				'propriedades_html' => '',
				'form_validation' 	=> 'trim|required|is_natural_no_zero|is_unique[pontuacao.nr_vale_ponto]',
				'placeholder' 		=> 'Apenas números. Ex.: 200');

			$campos[] = array(
			'nome_campo' 		=> 'cod_vale',
			'tipo_campo_html' 	=> 'input',
			'label' 			=> 'Código',
			'obrigatorio' 		=> TRUE,
			'propriedades_html' => '',
			'form_validation' 	=> 'trim|required|exact_length[6]|callback__valida_codigo_vale|callback__valida_cliente',
			'placeholder' 		=> 'Informe o código. Ex.: 012ABC');
		}
		else
		{
			$tipo = 'usuario';
			$campos[] = array(
				'nome_campo' 		=> 'nr_vale',
				'tipo_campo_html' 	=> 'number',
				'label' 			=> 'Número',
				'obrigatorio' 		=> TRUE,
				'propriedades_html' => '',
				'form_validation' 	=> 'trim|required|is_natural_no_zero|is_unique[pontuacao.nr_vale_ponto]',
				'placeholder' 		=> 'Apenas números. Ex.: 200');

			$campos[] = array(
			'nome_campo' 		=> 'cod_vale',
			'tipo_campo_html' 	=> 'input',
			'label' 			=> 'Código',
			'obrigatorio' 		=> TRUE,
			'propriedades_html' => '',
			'form_validation' 	=> 'trim|required|exact_length[6]|callback__valida_codigo_vale',
			'placeholder' 		=> 'Informe o código. Ex.: 012ABC');
		}
			
		

		$this->load->model('pontuacao_model');
		$this->load->model('vale_ponto_model');
		$this->load->model('usuario_model');
		$this->load->library('form_validation');

		foreach ($campos as $key => $campo) {
			$this->form_validation->set_rules($campo['nome_campo'], $campo['label'], $campo['form_validation']);
		}

		$this->form_validation->set_error_delimiters('<div class="status alert alert-danger">', '</div>');
		
		$data['campos'] = $campos;
		$data['titulo'] = 'Cadastrar vale pontos';
		$data['descricao'] = 'Informe os dados do vale pontos para creditar os pontos.';

		if ($this->input->post('cod_vale'))
		{
			$_POST['cod_vale'] = strtoupper($this->input->post('cod_vale'));
		}

		if ($this->form_validation->run())
		{	
			if ($this->session->usuario['acesso'] != 6) // Não é Associação
			{
				$consumidor = $this->usuario_model->consulta_usuario_por_id($this->session->usuario['id_usuario']);
			}
			else
			{
				if ( $this->input->post('email',TRUE) == "" ) {
					$consumidor = $this->usuario_model->consulta_usuario_por_cpf($this->input->post('cpf',TRUE));
				} else {
					$consumidor = $this->usuario_model->consulta_usuario_por_email($this->input->post('email',TRUE));
				}
			}
			
			//se consumidor pra receber pontos foi encontrado e o numero e codigo conferem
			if ($consumidor && $this->input->post('cod_vale') == consulta_codigo_vale_ponto($this->input->post('nr_vale'))) 
			{
				$lote = $this->vale_ponto_model->consulta_vale_pontos_por_numero_vale($this->input->post('nr_vale'));
				if ($lote)
				{
					$pontuacao = array(  ////////// AKI
						'nr_vale_ponto' => $this->input->post('nr_vale'),
						'fk_lote_vale_ponto' => $lote['id_lote_vale_ponto'],
						'fk_usuario' => $consumidor['id_usuario'],
						'qtd_pontos' => $lote['qtd_pontos'],
						'validade_pontos' => $this->_calcula_data_validade_pontos(),
						'fk_usuario_criacao' => $this->session->usuario['id_usuario'],
						'qtd_disponivel' => $lote['qtd_pontos']);

					if ($this->pontuacao_model->insere_pontuacao($pontuacao))
					{
						$this->_exibe_mensagem('cadastrar_vale_pontos_sucesso');
					}
					else
					{
						$this->_exibe_mensagem('cadastrar_vale_pontos_erro');
					}
				}
				else
				{
					$this->_exibe_mensagem('cadastrar_vale_pontos_erro_lote');
				}
			}
			else
			{
				$this->_exibe_mensagem('cadastrar_vale_pontos_erro_usuario');
			}
		}
		else
		{		
			$data['pagina_atual'] = 'cadastro';
			$this->_carrega_pagina($data);
		}
	}

	public function _valida_codigo_vale($codigo)
	{	
		if ($this->input->post('cod_vale') == consulta_codigo_vale_ponto($this->input->post('nr_vale')))
		{
			return true;
		} else {
			$this->form_validation->set_message('_valida_codigo_vale', 'Código não confere.');
			return false;
		}
	}


}

/* End of file Usuario_logado.php */
/* Location: ./application/controllers/Usuario_logado.php */