<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lojista extends MY_Controller {

	public function __construct(){
		parent::__construct();

		//$this->load->model('menu_model');
		//$this->_valida_acesso();
	}

	public function conceder_pontos()
	{	
		$this->load->model('pontuacao_model');
		$this->load->model('faturamento_model');
		$this->load->model('usuario_model');
		$this->load->model('loja_model');
				
		//obtem o valor unitário do ponto para a data atual
		$data['preco_ponto'] = $this->faturamento_model->consulta_preco_ponto_por_periodo(date('Y-m-d'),date('Y-m-d'))[date('Y-m-d')];

		//Valida os valores do formulário
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('email','E-mail','valid_email|trim|max_length[100]');
		$this->form_validation->set_rules('cpf','CPF','trim|exact_length[11]|is_natural_no_zero|callback__valida_cpf');
		$this->form_validation->set_rules('valor_desconto','Desconto concedido','trim|numeric|callback__valida_limite_credito_valor|callback__valida_cliente');
		$this->form_validation->set_rules('qtd_pontos','Quantidade de pontos','trim|is_natural_no_zero|callback__valida_limite_credito_pontos|callback__valida_cliente');
		
		
		$this->form_validation->set_error_delimiters('<div class="status alert alert-danger">', '</div>');

		$loja =  $this->loja_model->consulta_loja_por_id($this->session->usuario['id_loja']);

		$data['titulo'] = 'Conceder Pontos';
		$data['loja'] = $loja['nome_fantasia'];

		if ($this->form_validation->run())
		{	
			if ( $this->input->post('email',TRUE) == "" ) {
				$consumidor = $this->usuario_model->consulta_usuario_por_cpf($this->input->post('cpf',TRUE));
			} else {
				$consumidor = $this->usuario_model->consulta_usuario_por_email($this->input->post('email',TRUE));
			}
			
			//se consumidor pra receber pontos foi encontrado e o usuario logado tem 
			//acesso à loja que está concedendo os pontos
			if ($consumidor && $this->session->usuario['id_loja'] === $this->loja_model->consulta_loja_relacionada_por_id_usuario($this->session->usuario['id_usuario']) ) 
			{
				if ($loja['status'] == 'A')
				{	
					if ( $this->input->post('qtd_pontos',TRUE) > 0 )
					{
						$pontos_a_conceder = $this->input->post('qtd_pontos',TRUE);
					}
					elseif ( $this->input->post('valor_desconto',TRUE) > 0)
					{
						$pontos_a_conceder = $this->_converte_valor_em_ponto($this->input->post('valor_desconto',TRUE));
					}
					else
					{
						$pontos_a_conceder = 'ERRO';
					}

					$pontuacao = array(
						'fk_loja' => $this->session->usuario['id_loja'],
						'fk_usuario' => $consumidor['id_usuario'],
						'qtd_pontos' => $pontos_a_conceder,
						'validade_pontos' => $this->_calcula_data_validade_pontos(),
						'fk_usuario_criacao' => $this->session->usuario['id_usuario'],
						'qtd_disponivel' => $pontos_a_conceder);

					if ($this->pontuacao_model->insere_pontuacao($pontuacao))
					{
						$this->_exibe_mensagem('conceder_pontos_sucesso');
					}
					else
					{
						$this->_exibe_mensagem('conceder_pontos_erro');
					}
				}
				else
				{
					$this->_exibe_mensagem('conceder_pontos_erro_loja_inativa');
				}
			}
			else
			{
				$this->_exibe_mensagem('conceder_pontos_erro_usuario');
			}
		}
		else
		{		
			$data['pagina_atual'] = 'conceder_pontos';
			$this->_carrega_pagina($data);
		}
	}

	public function _valida_limite_credito_valor($valor)
	{
		$pontos = $this->_converte_valor_em_ponto($valor);

		return $this->_valida_limite_credito($pontos, 'valor');
	}

	public function _valida_limite_credito_pontos($pontos)
	{	
		return $this->_valida_limite_credito($pontos,'pontos');
	}
	
	public function _valida_limite_credito($pontos, $tipo_validacao)
	{	
		if ( $this->input->post('qtd_pontos',TRUE) > 0 && $this->input->post('valor_desconto',TRUE) > 0)
		{
			$this->form_validation->set_message('_valida_limite_credito_'.$tipo_validacao, "Informe o valor de desconto OU a quantidade de pontos, nunca os dois.");
			return false;
		}

		$this->load->model('pontuacao_model');

		$limite = $this->pontuacao_model->verifica_limite_credito($this->session->usuario['id_loja']);
		show(array('limite' =>$limite));
		$saldo = $limite['saldo_atual'];
		/** 
		 * Envia email para administrador se o limite de credito da loja ficar abaixo 
		 * de 20% do total ou se exceder o limite.
		*/
		if ($saldo >= $pontos)
		{	
			$percent_20 = $limite['limite_max']*0.2;
			if ($saldo >= $percent_20 && $saldo-$pontos < $percent_20) {
				envia_email('saulo@contaponto.com.br', //para_email,
				'Loja '.$this->session->usuario['id_loja'].' abaixo de 20% do limite de crédito', //assunto,
				'A loja '.$this->session->usuario['id_loja'].' está com um saldo de '. ($saldo-$pontos) .' pontos, de um limite máximo de '.$limite['limite_max'].' pontos.'); //mensagem,
			}
			return true;
		}
		else
		{
			$this->form_validation->set_message('_valida_limite_credito_'.$tipo_validacao, "Esta concessão excede o limite de pontos que sua loja possui atualmente: $saldo pontos. Entre em contato com a equipe Contaponto para aumentar o limite disponível.");

			envia_email('saulo@contaponto.com.br', //para_email,
				'Loja '.$this->session->usuario['id_loja'].' excedeu o limite de crédito', //assunto,
				'A loja '.$this->session->usuario['id_loja'].' tentou conceder '.$pontos.' pontos, tendo um saldo de '.$saldo.' pontos.'); //mensagem,

			return false;
		}
	}

	public function cadastra_cliente()
	{
		$this->load->model('usuario_model');
		$campos = $this->usuario_model->carrega_campos_cadastro('cadastro_lojista');

		$this->load->library('form_validation');
		foreach ($campos as $key => $campo) {
			$this->form_validation->set_rules($campo['nome_campo'], $campo['label'], $campo['form_validation']);
		}
		$this->form_validation->set_error_delimiters('<div class="status alert alert-danger">', '</div>');

			
		if ($this->form_validation->run() == FALSE) {
			$data['pagina_atual'] = 'cadastro';
			$data['campos'] = $campos;
			$this->_carrega_pagina($data);
		} else {
			
			if ($this->usuario_model->insere_usuario($this->input->post(NULL, TRUE))) {

				if ($this->_envia_email_confimacao_cadastro_lojista($this->input->post('email', TRUE))) {
					//carrega pagina de sucesso.
					$this->_exibe_mensagem('cadastro_sucesso');
				} else {
					//carrega pagina de erro.
					$this->_exibe_mensagem('cadastro_erro_email_confirmacao');
				}
			} else {
				//carrega pagina de erro.
				$this->_exibe_mensagem('cadastro_erro_inserir');
			}
		}
	}

	public function _envia_email_confimacao_cadastro_lojista($para_email)
	{	
		$this->load->model('usuario_model');
		$this->load->model('loja_model');

		$usuario = $this->usuario_model->consulta_usuario_por_email($para_email);
		$assunto = 'Confirmação de cadastro - contaponto.com.br';
		
		$data['nome'] = $usuario['nome'];
		$data['loja'] = $this->loja_model->consulta_loja_por_id($usuario['fk_loja_cadastro'])['nome_fantasia'];
		$data['link'] = "usuario/altera_senha/" . $usuario['sal'] . "/" . str_replace('@', '~', $usuario['email']). "/";
		
		$mensagem = $this->load->view('emails/v_email_confirmacao_cadastro_lojista_html',$data,true);
		
		return $this->_envia_email($para_email,$assunto,$mensagem);
	}

	public function extrato_loja()
	{	
		//carrega dados de pontos concedidos
		$this->load->model('pontuacao_model');
		$data ['model']['pontuacao'] = $this->pontuacao_model->consulta_pontuacao_por_loja($this->session->usuario['id_loja']);
		if ($data['model']['pontuacao'])
		{	
			foreach ($data['model']['pontuacao']['dados'] as $key => $value)
			{
				if ($value['ts_estorno'] != NULL)
				{
					$data['model']['pontuacao']['dados'][$key]['tipo_linha'] = 'danger';
				}
				elseif ($value['data_pgto_boleto'] == NULL) 
				{
					$data['model']['pontuacao']['dados'][$key]['tipo_linha'] = 'warning';
				}
				else
				{
					$data['model']['pontuacao']['dados'][$key]['tipo_linha'] = '';
				}
			}

			$data['campos_data']['pontuacao'] = array('ts_pontuacao','data_pgto_boleto','ts_estorno');
			$data['abas']['pontuacao'] = 'Pontos';
		}

		//carrega dados de usuarios cadastrados
		$this->load->model('usuario_model');
		$data ['model']['usuario'] = $this->usuario_model->consulta_usuarios_por_loja($this->session->usuario['id_loja']);

		if ($data['model']['usuario']) {
			$data['campos_data']['usuario'] = array('ts_criacao','data_nascimento');
			$data['abas']['usuario'] = 'Usuários Cadastrados';
		}


		//carrega dados de cupons recebidos
		$this->load->model('cupom_model');
		$data ['model']['cupom'] = $this->cupom_model->consulta_cupons_recebidos_por_loja($this->session->usuario['id_loja']);

		show($data);

		if ($data['model']['cupom']) {
			$data['campos_data']['cupom'] = array('ts_utilizacao');
			$data['abas']['cupom'] = 'Cupons Recebidos';
		}

		//exibe dados carregados na tela
		if ($data['model']['pontuacao'] OR $data['model']['usuario'])
		{
			$data['pagina_atual'] = 'extrato';
			//show($data,false);
			$this->_carrega_pagina($data);
		}
		else
		{
			$this->_exibe_mensagem('extrato_sem_dados_lojista');
		}
	}

	public function recebe_cupom()
	{
		$this->load->model('cupom_model');
		
		$campos[] = array('nome_campo' 		=> 'numero_cupom',
					'tipo_campo_html' 	=> 'number',
					'label' 			=> 'Número do cupom',
					'obrigatorio' 		=> TRUE,
					'form_validation' 	=> 'trim|required|is_natural_no_zero',
					// 'icone'				=> 'hashtag',
					'propriedades_html' => '',
					'placeholder' 		=> '');

		$campos[] = array('nome_campo' 		=> 'codigo_cupom',
					'tipo_campo_html' 	=> 'number',
					'label' 			=> 'Código do cupom',
					'obrigatorio' 		=> TRUE,
					'form_validation' 	=> 'trim|required|is_natural_no_zero',
					// 'icone'				=> 'unlock-alt',
					'propriedades_html' => '',
					'placeholder' 		=> '');

		$this->load->library('form_validation');
		foreach ($campos as $key => $campo) {
			$this->form_validation->set_rules($campo['nome_campo'], $campo['label'], $campo['form_validation']);
		}
		$this->form_validation->set_error_delimiters('<div class="status alert alert-danger">', '</div>');

		if ($this->form_validation->run() == FALSE)
		{	
			//mostra os campos para informar numero e codigo do cupom
			$data['pagina_atual'] = 'lojista_consulta_cupom';
			$data['campos'] = $campos;
			$this->_carrega_pagina($data);
		}
		else
		{
			$data = NULL;
			$this->load->model('cupom_model');
			$cupom_valido = $this->cupom_model->consulta_usuario_cupom_valido($this->input->post('numero_cupom'), $this->input->post('codigo_cupom'));
			
			if ($cupom_valido['erro'] === FALSE )
			{
				$data['cupom'] = $cupom_valido['dados'];
				//passou em todas as validacoes
				//mostra os dados do cupom com o botao para receber o cupom
				$data['numero_cupom'] = $this->input->post('numero_cupom');
				$data['codigo_cupom'] = $this->input->post('codigo_cupom');
				$data['rodape'] = $this->load->view('includes/v_rodape_detalhe_usuario_cupom_lojista',$data, true);
				$data['pagina_atual'] = 'detalhe_usuario_cupom';
				$this->_carrega_pagina($data);
			}
			else
			{
				$this->_exibe_mensagem($cupom_valido['erro']);
			}

		}
	}

	public function consome_cupom()
	{
		$this->load->model('cupom_model');
		$cupom_valido = $this->cupom_model->consulta_usuario_cupom_valido($this->input->post('numero_cupom'), $this->input->post('codigo_cupom'));
		
		if ($cupom_valido['erro'] === FALSE )
			{
				if ($this->cupom_model->consome_cupom($this->input->post('numero_cupom'),$this->session->usuario['id_usuario']) > 0)
				{
					$this->_exibe_mensagem('consumo_cupom_ok');
				}
				else
				{
					$this->_exibe_mensagem('consumo_cupom_erro');
				}
			}
			else
			{
				$this->_exibe_mensagem($cupom_valido['erro']);
			}
	}
}

/* End of file Lojista.php */
/* Location: ./application/controllers/Lojista.php */