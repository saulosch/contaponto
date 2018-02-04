<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

	public function __construct(){
		parent::__construct();
		//date_default_timezone_set('America/Sao_Paulo');
		$this->_valida_acesso();
	}

	public function index()
	{
		redirect('/','refresh');	
	}
	
	public function _carrega_pagina($data=array())
	{	
		//define a pagina atual, caso ainda nao esteja.
		$data['pagina_atual'] = (isset($data['pagina_atual']))? $data['pagina_atual']:'home';
		
		$titulo_padrao = 'Contaponto - '.ucwords(str_replace(array('-','_'), ' ', $data['pagina_atual']));

		$this->load->model('menu_model');

		//verifica se está logado para barra superior personalizada
		if (isset($this->session->userdata['usuario'])) 
		{
			$data['logado'] = TRUE;
			$data['sessao_usuario'] = $this->session->usuario;
			$data['menu_barra'] = $this->menu_model->carrega_menu_barra($data['sessao_usuario']['acesso']);
		}
		else
		{
			 $data['logado'] = FALSE;
		}

		//carrega os itens dos menus
		$data['menu_superior'] = $this->menu_model->carrega_menu_superior();
		$data['menu_inferior'] = $this->menu_model->carrega_menu_inferior();
		
		//Define o título da pagina
		if (! isset($data['titulo'])) {
			$data['titulo'] = $titulo_padrao;
			foreach ($data['menu_superior'] as $key => $value) {
				$data['titulo'] = ($key==$data['pagina_atual'])? $value.' - Contaponto' : $data['titulo'];
			}
			if ($data['titulo'] == $titulo_padrao) {
				foreach ($data['menu_inferior'] as $key => $value) {
					$data['titulo'] = ($key==$data['pagina_atual'])? $value.' - Contaponto' : $data['titulo'];
				} 
			}
		}

		//exibe pagina
		$buffer = $this->load->view('includes/v_header',$data,TRUE);
		$buffer .= $this->load->view('site/v_'.$data['pagina_atual'],$data,TRUE);
		$buffer .= $this->load->view('includes/v_footer',array($data['menu_inferior'],$data['pagina_atual']),TRUE);
		$buffer = compacta_html($buffer);
		$this->load->view('buffer',compact('buffer'));
	}

	public function _exibe_mensagem($codigo_mensagem = 'default')
	{  
		$this->lang->load('cp_mensagem_lang');

		$data['pagina_atual'] = 'mensagem';
		$data['tipo'] = $this->lang->line($codigo_mensagem.'_tipo');
		$data['titulo_mensagem'] = $this->lang->line($codigo_mensagem.'_titulo');
		$data['mensagem'] = $this->lang->line($codigo_mensagem.'_mensagem');
		
		$this->_carrega_pagina($data);
	}

	public function _envia_email($para_email,
									$assunto,
									$mensagem,
									$de_nome = null,
									$de_email = null,
									$reply_to_email = '',
									$reply_to_nome = '',
									$mailtype = 'text')
	{
		if ($mailtype == 'text' && strpos($mensagem, '<html') && strpos($mensagem, '</html'))
		{
			$mailtype = 'html';
		}
		return envia_email($para_email,$assunto,$mensagem,$de_nome,$de_email,$reply_to_email,$reply_to_nome,$mailtype);

	}

	public function _logado()
	{
		return isset($this->session->usuario);
	}

	public function _valida_acesso()

	{	
		$this->load->model('acesso_model');

		$classe = $this->uri->rsegment(1, 0);
		$metodo = $this->uri->rsegment(2, 0);
		
		$permissoes = $this->acesso_model->carrega_permissoes();

		if (in_array($classe,$permissoes[0])) {
			$acesso = TRUE;
		} else {
			$acesso = FALSE;

			if (isset($this->session->usuario['acesso'])){
				//$menu = $this->menu_model->carrega_menu_admin($this->session->usuario['acesso']);
				if(in_array($classe, $permissoes[$this->session->usuario['acesso']])){
					switch ($classe) {
						case 'lojista':
							if ($this->session->usuario['id_loja']) {
								$acesso = TRUE;
							}
							break;
						
						default:
							$acesso = TRUE;
							break;
					}
				}
			}
		}
		if ( ! $acesso) {
			if ($this->_logado()) {
				redirect('/','refresh');
			} else {
				redirect('/login/'.$this->uri->uri_string(),'refresh');
			}
		}
	}

	public function _valida_email($email)
	{
		
		$this->form_validation->set_message('_valida_email', 'O %s informado está retornando mensagens e não pode ser cadastrado por um parceiro. Se estiver correto, solicite ao cliente que acesse o menu "Cadastre-se" para poder utilizar o site.');

		
		return $this->_verifica_email_online($email);
		
	}

	public function _verifica_email_online($email)
	{
		
		$key = "AMCLlIcPKMgFoA8QjJ39P";
	    $url = "https://app.emaillistverify.com/api/verifEmail?secret=".$key."&email=".$email;
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
	    $response = curl_exec($ch);
	    curl_close($ch);
	    // var_dump($response);
	    if ($response == 'fail' OR $response == 'unknown')
	    {
	    	return FALSE;
	    }
	    else
	    {
	    	return TRUE;
	    }
	}
	
	public function _valida_cpf($cpf = FALSE) 
	{
		// Verifica se um número foi informado
	    if($cpf) {

	    	$this->form_validation->set_message('_valida_cpf', 'O %s informado não é válido.');
			$cpf = str_pad($cpf, 11, '0', STR_PAD_LEFT);
	     
	     	$prova = str_split($cpf);

		    // Verifica se o numero de digitos informados é igual a 11 
		    if (strlen($cpf) != 11) {
		        return false;
		    }
		    // Verifica se nenhuma das sequências invalidas abaixo 
		    // foi digitada. Caso afirmativo, retorna falso
		    elseif ($prova[0]==$prova[1] && $prova[0]==$prova[2] && $prova[0]==$prova[3] && $prova[0]==$prova[4] && $prova[0]==$prova[5] && $prova[0]==$prova[6] && $prova[0]==$prova[7] && $prova[8]==$prova[9] && $prova[0]==$prova[10]) {
		        return false;
		     // Calcula os digitos verificadores para verificar se o
		     // CPF é válido
		     } else {   

				for ($t = 9; $t < 11; $t++) {
					for ($d = 0, $c = 0; $c < $t; $c++) {
						$d += $prova[$c] * ($t + 1 - $c);
					}
					$d = ((10 * $d) % 11) % 10;
					if ($prova[$c] != $d) {
						return false;
					}
				}
			}
		}	
		return true;
	}

		public function _valida_data($data)
	{	
		
		$data_formatada = formata_data($data);

		//valida data no formato aaaa-mm-dd
		if (strpos($data_formatada.'  ', '-',strpos($data_formatada, '-')+1)) {
			list($a, $m, $d) = explode('-', $data_formatada);
			
			if ($a > 1900) {
				$this->form_validation->set_message('_valida_data', 'A %s deve ser informada no formato dd/mm/aaaa');
				return checkdate($m, $d, $a);
			} else {
				$this->form_validation->set_message('_valida_data', 'O ano deve ser posterior a 1900');
				return false;
			}
		} elseif ($data == '' OR is_null($data)) {
			return true;
		} else {
			$this->form_validation->set_message('_valida_data', 'A %s deve ser informada no formato dd/mm/aaaa');
			return false;	
		}
	}

	public function _calcula_data_validade_pontos($date_string = null)
	{	
		if ($date_string === null) {
			$date_string = date('Y-m-d');
		}

		$date = new DateTime($date_string);
		$date->add(new DateInterval('P2Y')); //adiciona-se 2 anos à data informada
		return $date->format('Y-m-d') ;
	}

	public function _carrega_saldo_usuario($id_usuario)
	{
		$this->load->model('usuario_model');
		$this->usuario_model->atualiza_saldo_usuario($id_usuario);
		$usuario = $this->usuario_model->consulta_usuario_por_id($id_usuario);
		if ($usuario) 
		{
			return $usuario['saldo'];
		} else {
			return FALSE;
		}
	}

	/**
	* Atualiza os dados da sessao, pode ser passado um array da tabela usuario como parametro
	* caso nao seja informado, os dados sao obtidos a partir do id_usuario atual da sessao
	*/
	public function _atualiza_sessao($usuario = false)
	{
		
		if ( ! $usuario) {
			$usuario = $this->usuario_model->consulta_usuario_por_id($this->session->usuario['id_usuario']);
		}

		if ($usuario)
		{	//Cria/atualiza dados na sessão
			$newdata = array(
				'id_usuario'=> $usuario['id_usuario'],
	          	'nome'  	=> $usuario['nome'],
	        	 //'email' 	=> $email,
	        	'genero'	=> $usuario['genero'],
	        	'acesso'	=> $usuario['fk_tipo_acesso'],
	        	'saldo'		=> $this->_carrega_saldo_usuario($usuario['id_usuario']),
	       	);
			if (in_array($usuario['fk_tipo_acesso'], array(4,5,6))) //se usuario é lojista
			{	
				$this->load->model('loja_model');
				$id_loja = $this->loja_model->consulta_loja_relacionada_por_id_usuario($usuario['id_usuario']);
				//Se a loja está inativa, então considera como usuario comum.
				if ($this->loja_model->consulta_loja_por_id($id_loja)['status'] == 'A')				
				{
					$newdata['id_loja'] = $id_loja;
				}
				else
				{
					$newdata['acesso'] = 3; // Consumidor
				}
			}

			$this->session->set_userdata('usuario',$newdata);

			return true;
		}
		else
		{
			return false;
		}
	}

	public function mostrar_fatura($id_fatura,$redirecionar_falha,$id_loja)
	{
		if ($id_fatura)
		{
			$this->load->model('faturamento_model');
			$data['fatura'] = $this->faturamento_model->consulta_fatura_por_id($id_fatura, $id_loja);
			if ($data['fatura'])
			{	
				$data['fatura']['totais'] = array (
					'qtd' => 0, 
					'valor' => 0,
					'itens' => array(
						'pontos' => array('qtd' => 0, 'valor' => 0, 'soma' => 0),
						'cupons' => array('qtd' => 0, 'valor' => 0),
						'cobrancas' => array('qtd' => 0, 'valor' => 0),
						'ajustes' => array('qtd' => 0, 'valor' => 0),
					)
				);

				$data['item_fatura'] = $this->faturamento_model->consulta_item_fatura_por_fatura($id_fatura);
				if ($data['item_fatura'])
				{
					foreach ($data['item_fatura']  as $key => $value)
					{
						//pontos
						if($value['fk_pontuacao'])
						{
							$data['fatura']['totais']['itens']['pontos']['soma'] += $value['qtd_pontos'];
							$data['fatura']['totais']['itens']['pontos']['qtd']++;
							$data['fatura']['totais']['itens']['pontos']['valor'] += $value['valor'];
						}
						//cupons
						elseif ($value['fk_usuario_cupom'])
						{
							$data['fatura']['totais']['itens']['cupons']['qtd']++;
							$data['fatura']['totais']['itens']['cupons']['valor'] += $value['valor'];
						}
						//cobrancas
						elseif ($value['fk_mensalidade'])
						{
							$data['fatura']['totais']['itens']['cobrancas']['qtd']++;
							$data['fatura']['totais']['itens']['cobrancas']['valor'] += $value['valor'];
						}
						//total geral
						$data['fatura']['totais']['qtd']++;
						$data['fatura']['totais']['valor'] += $value['valor'];
					}
				}
				else
				{
					$data['item_fatura'] = array();
				}

				$data['ajuste_fatura'] = $this->faturamento_model->consulta_ajuste_fatura_por_fatura($id_fatura);
				
				if ($data['ajuste_fatura'])
				{
					foreach ($data['ajuste_fatura']  as $key => $value)
					{
						//ajustes
						$data['fatura']['totais']['itens']['ajustes']['qtd']++;
						$data['fatura']['totais']['itens']['ajustes']['valor'] += $value['valor'];
						//total geral
						$data['fatura']['totais']['qtd']++;
						$data['fatura']['totais']['valor'] += $value['valor'];
					}
				}
				else
				{
					$data['ajuste_fatura'] = array();
				}
				
				$data['descontos'] = $this->faturamento_model->consulta_descontos_ponto_por_periodo();


				$data['pagina_atual'] = 'detalhe_fatura';
				//show($data);
				$this->_carrega_pagina($data);
			}
			else
			{
				$this->_exibe_mensagem('fatura_nao_existe');
			}
		}
		else
		{
			redirect($redirecionar_falha,'refresh');
		}
	}
	
	public function _valida_cliente($pontos)
	{	
		$this->load->model('usuario_model');
		if ( $this->input->post('email',TRUE) == "" ) {
			if ($this->input->post('cpf',TRUE) == "") {
				$this->form_validation->set_message('_valida_cliente', 'É necessário informar <u>OU</u> o e-mail <u>OU</u> o CPF do cliente para poder conceder os pontos..');
				return false;	
			} else {
				//pesquisa pelo CPF
				if ($this->usuario_model->consulta_usuario_por_cpf($this->input->post('cpf',TRUE))){
					return true;
				} else {
					$this->form_validation->set_message('_valida_cliente', 'CPF não cadastrado.');
					return false;
				}
			}
		} else {
			if ( $this->input->post('cpf',TRUE) != "") {
				$this->form_validation->set_message('_valida_cliente', 'É necessário informar APENAS UM: OU o <u>e-mail</u> OU o <u>CPF</u> do cliente para poder conceder os pontos. Nunca os dois.');
				return false;
			}else{
				//pesquisa pelo email
				if ($this->usuario_model->consulta_usuario_por_email($this->input->post('email',TRUE))){
					return true;
				} else {
					$this->form_validation->set_message('_valida_cliente', 'E-mail não cadastrado.');
					return false;
				}	
			}
		}	
	}

	public function _converte_valor_em_ponto($valor)
	{
		$this->load->model('faturamento_model');
		//obtem o valor unitário do ponto para a data atual
		$preco_ponto = $this->faturamento_model->consulta_preco_ponto_por_periodo(date('Y-m-d'),date('Y-m-d'))[date('Y-m-d')];
		return floor($valor/$preco_ponto);
	}

}

/* End of file MY_Site.php */
/* Location: ./application/core/MY_Site.php */