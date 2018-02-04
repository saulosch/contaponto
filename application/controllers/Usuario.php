<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usuario extends MY_Controller {

	public function __construct() 
	{
		parent::__construct();
		$this->load->model('usuario_model');
	}

	public function cadastro()
	{
		// show();
		$campos = $this->usuario_model->carrega_campos_cadastro('cadastro');

		$this->load->library('form_validation');
		foreach ($campos as $key => $campo) {
			$this->form_validation->set_rules($campo['nome_campo'], $campo['label'], $campo['form_validation']);
		}
		$this->form_validation->set_error_delimiters('<div class="status alert alert-danger">', '</div>');

			
		if ($this->form_validation->run() == FALSE)
		{
			$data['pagina_atual'] = 'cadastro';
			$data['campos'] = $campos;
			$this->_carrega_pagina($data);
		}
		else
		{	
			if ($this->usuario_model->insere_usuario($this->input->post(NULL, TRUE))) {

				if ($this->_envia_email_confimacao_cadastro($this->input->post('email', TRUE))) {
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

	public function _envia_email_confimacao_cadastro($para_email)
	{	
		$usuario = $this->usuario_model->consulta_usuario_por_email($para_email);
		$assunto = 'Confirmação de cadastro - Contaponto';
		$link = "usuario/confirma_cadastro/" . $usuario['sal'] . "/" . str_replace('@', '~', $usuario['email']). "/";
		$mensagem = $this->load->view('emails/v_email_confirmacao_cadastro_html',array('nome'=>$usuario['nome'],'link'=>$link),true);
		
		return $this->_envia_email($para_email,$assunto,$mensagem);
	}

	public function confirma_cadastro($sal,$email)
	{
		$email = str_replace('~', '@', $email);

		if ( $this->usuario_model->ativa_usuario($email,$sal)==1) 
		{
			$this->_exibe_mensagem('ativacao_sucesso');
		} else {
			$usuario = $this->usuario_model->consulta_usuario_por_email($email);
			
			if(isset($usuario['ativo']) && $usuario['ativo']=='A'){
				$this->_exibe_mensagem('ativacao_sucesso');
			} else {
				$this->_exibe_mensagem('ativacao_erro');
			}
		}
	}

	public function login()
	{
		$complemento = substr($this->uri->uri_string(), strpos($this->uri->uri_string(), 'login') + 6);
		$campos = $this->usuario_model->carrega_campos_cadastro('login');

		$this->load->library('form_validation');

		foreach ($campos as $key => $campo) {
			$this->form_validation->set_rules($campo['nome_campo'], $campo['label'], $campo['form_validation']);
		}

		$this->form_validation->set_error_delimiters('<div class="status alert alert-danger">', '</div>');

		if ($this->form_validation->run()) {
			
			$this->form_validation->set_rules('senha', 'senha', 'callback__valida_login');
			$logou = $this->form_validation->run();

		} else {
			$logou = FALSE;	
		}
		
		if ($logou) {
			if ($complemento) { // se existir uma pagina para carregar, redireciona pra ela
				redirect('/'.$complemento,'refresh');
			} else { //se $complemento é falso, vai para home
				$this->_carrega_pagina(array('pagina_atual' => 'home'));
			}
		} else {
			$data['pagina_atual'] = 'login';
			$data['campos'] = $campos;
			$this->_carrega_pagina($data);
		}
		
	}

	public function _valida_login($senha)
	{	
		$logou = $this->_realiza_login($this->input->post('email',TRUE),$this->input->post('senha',TRUE));
		if( ! $logou['autorizado']) {
			$this->form_validation->set_message('_valida_login', $logou['mensagem']);
		}
		return $logou['autorizado'];
	}
	
	public function _realiza_login($email,$senha)
	{
		$usuario = $this->usuario_model->consulta_usuario_por_email($email);
		$this->lang->load('cp_mensagem_lang');

		if ($usuario && $usuario['senha'] == sha1($senha.$usuario['sal'])) 
		{
			if ($usuario['ativo'] != 'I') 
			{
				$this->_atualiza_sessao($usuario);

				$this->usuario_model->registra_login('id_usuario',$usuario['id_usuario']);

				// retorno com sucesso
				$retorno['mensagem'] = '';
				$retorno['autorizado'] = true;	
				
			} else {

				$retorno['mensagem'] =  $this->lang->line('login_conta_nao_ativada');
				$retorno['autorizado'] = false;
			}
		} else {
			$retorno['mensagem'] = $this->lang->line('login_usuario_senha_nao_conferem');
			$retorno['autorizado'] = false;
		}
	
		return $retorno;
	}

	public function logout()
	{
		$this->session->sess_destroy();
		redirect('/','refresh');
	}

	public function reset_senha() {

		$campos = $this->usuario_model->carrega_campos_cadastro('reset');

		$this->load->library('form_validation');

		foreach ($campos as $key => $campo) {
			$this->form_validation->set_rules($campo['nome_campo'], $campo['label'], $campo['form_validation']);
		}

		$this->form_validation->set_error_delimiters('<div class="status alert alert-danger">', '</div>');

		if ($this->form_validation->run()) {
			
			$usuario = $this->usuario_model->consulta_usuario_por_cpf($this->input->post('cpf',TRUE));

			if (isset($usuario['email'])) {
				if($this->_envia_email_reset_senha($usuario)) {
					$this->_exibe_mensagem('reset_sucesso');
				} else {
					$this->_exibe_mensagem('reset_erro_email');
				}
			} else {
				$this->_exibe_mensagem('reset_falha_cpf');
			}
		} else {
			$data['pagina_atual'] = 'reset_senha';
			$data['campos'] = $campos;
			$this->_carrega_pagina($data);
		}	
	}

	public function _envia_email_reset_senha($usuario) {
		//$usuario = $this->usuario_model->consulta_usuario_por_email($para_email);
		$para_email = $usuario['email'];
		$assunto = 'Reset de senha - Contaponto rede de fidelidade';
		$data['link'] = "usuario/altera_senha/" . $usuario['sal'] . "/" . str_replace('@', '~', $usuario['email']). "/";
		$data['nome'] = $usuario['nome'];
		$mensagem = $this->load->view('emails/v_email_reset_senha_html',$data,true);
		
		return $this->_envia_email($para_email,$assunto,$mensagem);
	}

	public function altera_senha($sal=0,$email=0)
	{
		$campos = $this->usuario_model->carrega_campos_cadastro('nova_senha');

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

			$data['email'] = str_replace('~', '@', $email);
			$data['sal'] = $sal; 
			$data['senha'] = $this->input->post('senha', TRUE);

			if ($this->usuario_model->altera_usuario($data)) {
				$this->_exibe_mensagem('nova_senha_sucesso');
			} else {
				//carrega pagina de erro.
				$this->_exibe_mensagem('nova_senha_erro');
			}
		}
	}
}

/* End of file Usuario.php */
/* Location: ./application/controllers/Usuario.php */