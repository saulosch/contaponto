<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Site extends MY_Controller {

	public function __construct() {
		parent::__construct();
		//echo $this->uri->rsegment(2, 0);;
		//$this->_valida_Acesso
	}

	public function index()
	{
		$this->_home();
	}

	public function treinamento()
	{
		for ($i=1; $i < 11; $i++) { 
			echo '<p><img src="http://contaponto.com.br/assets/treinamento/Treinamento_site_v1.0/Slide'.$i.'.JPG" ></p><hr />';
		}
	}

	public function _home()
	{
		$data['pagina_atual'] = 'home';
		$data['titulo'] = 'Contaponto fidelidade - Agora em Dois Córregos';
		$data['carousel'] =  $this->load->view('includes/v_carousel',null,true);
		$this->_carrega_pagina($data);
	}

	public function logout()
	{
		$this->session->sess_destroy();
		redirect('/','refresh');
	}

	public function como_funciona()
	{
		$this->_carrega_pagina(array('pagina_atual' => 'como_funciona'));
	}

	public function duvidas()
	{	
		$this->load->model('faq_model');

		$faq = $this->faq_model->carrega_faq((isset($this->session->usuario['tipo']))?$this->session->usuario['tipo']:0);
		foreach ($faq as $linha) {
			$data['faq'][$linha['categoria']][$linha['pergunta']]= $linha['resposta'];
		}
		$data['pagina_atual'] = 'duvidas';

		$this->_carrega_pagina($data);
	}

	public function fale_conosco()
	{	
		$this->load->library('form_validation');

		$this->form_validation->set_rules('nome', 'Nome', 'trim|required|min_length[2]|max_length[50]');
		$this->form_validation->set_rules('email', 'E-mail', 'trim|required|valid_email');
		$this->form_validation->set_rules('telefone', 'Telefone', 'trim|max_length[50]');
		$this->form_validation->set_rules('assunto', 'Assunto', 'trim|required|max_length[50]');
		$this->form_validation->set_rules('mensagem', 'Mensagem', 'trim|required');

		$this->form_validation->set_error_delimiters('<div class="status alert alert-danger">', '</div>');

		if ($this->form_validation->run() == FALSE)
				{
					$this->_carrega_pagina(array('pagina_atual' => 'fale_conosco'));
				}
				else
				{
					$message = "Contato via site contaponto.com.br\r\n\r\nDe: ".$this->input->post('nome',TRUE).' - '.$this->input->post('email',TRUE)."\r\n".'Assunto: '.$this->input->post('assunto',TRUE)."\r\nMensagem:\r\n".$this->input->post('mensagem',TRUE);
									
					$enviou = $this->_envia_email('saulos@gmail.com', //$para_email,
						'Contato via site - '.$this->input->post('assunto',TRUE), //$assunto
						$message, //$mensagem
						null, //$de_nome = 'Cuidaê',
						null, //$de_email = 'cuidae@cuidae.com.br',
						$this->input->post('email',TRUE), //$reply_to_email = '',
						$this->input->post('nome',TRUE) //$reply_to_nome = ''
						);

					if ($enviou) {
						$this->_carrega_pagina(array('pagina_atual' => 'fale_conosco', 'sucesso' => TRUE));
					} else {
						$data['pagina_atual'] = 'fale_conosco';
						$data['erro_envio'] = 'Desculpe pelo transtorno, houve um erro ao encaminhar sua mensagem, por favor envie sua mensagem diretamente para o e-mail informado acima.';
						$this->_carrega_pagina($data);
					}

				}
	}

	public function _set_cidade_default($id_cidade)
	{

		$this->load->helper('cookie');
		$cookie = array(
		    'name'   => 'cidade',
		    'value'  => $id_cidade,
		    'expire' => '3456000' //40 dias
		);
		set_cookie($cookie);

		// se o usuario estiver logado, altera a cidade padrao
		if ($this->_logado() && $id_cidade > 0)
		{
			$this->load->model('usuario_model');
			$this->usuario_model->atualiza_ultima_cidade_usuario($this->session->usuario['id_usuario'], $id_cidade);
		}
	}

	public function _get_cidade_default()
	{
		$this->load->helper('cookie');
		$cookie_cidade = get_cookie('cidade');

		if ($this->_logado())
		{
			// se estiver logado, busca cidade no login do usuario
			$this->load->model('usuario_model');
			$usuario = $this->usuario_model->consulta_usuario_por_id($this->session->usuario['id_usuario']);
			$id_cidade = $usuario['ultima_fk_cidade'];
			if ($id_cidade)	
			{
				//se encontar uma cidade, retorna
				return $id_cidade;
			}
			elseif ($cookie_cidade)
			{	
				//se nao encontrar e tiver uma cidade definida no cookie local, atualiza perfil com cidade do cookie
				$this->usuario_model->atualiza_ultima_cidade_usuario($this->session->usuario['id_usuario'], $cookie_cidade);
			}
		}
		return $cookie_cidade;
	}

	public function _delete_cidade_default()
	{
		$this->load->helper('cookie');
		delete_cookie('cidade');
	}

	public function _define_cidade($proxima_pagina)
	{
		$this->load->model('cidade_model');
		$cidade = $this->input->get('cidade', TRUE);
		
		if ($cidade && $this->cidade_model->consulta_cidade_por_id($cidade) )
		{
			$this->_set_cidade_default($cidade);
		}
		elseif ($cidade === "0")
		{
			$this->_delete_cidade_default();
		}
		else
		{
			$cidade = $this->_get_cidade_default();
		}
		
		if ( ! $cidade )
		{
			$data['cidades'] = $this->cidade_model->consulta_cidades();
			
			if ( sizeof($data['cidades']) > 1 )
			{

				$data['proxima_pagina'] = $proxima_pagina;
				$data['pagina_atual'] = 'escolha_cidade';

				$this->_carrega_pagina($data);
				return false;
			}
			else
			{
				$cidade = $data['cidades'][0]['id_cidade'];
			}
		}
		return $cidade;
	}

	public function ganhe_pontos()
	{	
		
		$cidade = $this->_define_cidade(__FUNCTION__);

		if ($cidade)
		{	
			$this->load->model('cidade_model');
			$data['cidade_atual'] = $this->cidade_model->consulta_cidade_por_id($cidade);

			$this->load->model('loja_model');

			$data['lojas'] = $this->loja_model->carrega_lojas_acumulo_por_cidade($cidade);
			$data['segmentos'] = array();
			$data['bairros'] = array();

			show($data);

			shuffle($data['lojas']); //embaralha a ordem da lista de lojas.
			
			$this->load->helper('text');
			$this->load->helper('inflector');

			foreach ($data['lojas'] as $key => $loja) {
				$data['lojas'][$key]['codigo_segmento'] = str_replace(',','_',underscore(convert_accented_characters($loja['nome_segmento'])));
				$data['lojas'][$key]['codigo_bairro'] = underscore(convert_accented_characters($loja['bairro']));
				$data['lojas'][$key]['cep'] = substr(str_pad($loja['cep'], 8, "0", STR_PAD_LEFT), 0, 5).'-'.substr(str_pad($loja['cep'], 8, "0", STR_PAD_LEFT), -3, 3);
				
				if( filter_var($loja['link_mapa'], FILTER_VALIDATE_URL) ) {
					$data['lojas'][$key]['endereco_mapa'] = $loja['link_mapa'];
				} else {
					$data['lojas'][$key]['endereco_mapa'] = 'https://www.google.com.br/maps/place/'.str_replace(' ', '+', $loja['endereco'].' - '.$loja['bairro'].', '.$loja['nome_cidade'].' - '.$loja['uf'].', '.$data['lojas'][$key]['cep']);
				}
				
				if ( ! in_array($loja['nome_segmento'], $data['segmentos'])) {
					$data['segmentos'][$data['lojas'][$key]['codigo_segmento']] = $loja['nome_segmento'];
				}

				if ( ! in_array($loja['bairro'], $data['bairros'])) {
					$data['bairros'][$data['lojas'][$key]['codigo_bairro']] = $loja['bairro'];
				}	
			}

			ksort($data['bairros']);
			ksort($data['segmentos']);
			
			$data['pagina_atual'] = 'ganhe_pontos';

			//show($data,false);

			$this->_carrega_pagina($data);
		}
	}

	public function parceiro()
	{
		$this->load->library('form_validation');

		$this->form_validation->set_rules('nome', 'Nome', 'trim|required|min_length[2]|max_length[70]');
		$this->form_validation->set_rules('email', 'E-mail', 'trim|required|valid_email');
		$this->form_validation->set_rules('telefone', 'Telefone', 'trim|required|max_length[50]');
		$this->form_validation->set_rules('empresa', 'Empresa', 'trim|required|max_length[50]');
		$this->form_validation->set_rules('mensagem', 'Mensagem', 'trim|required');

		$this->form_validation->set_error_delimiters('<div class="status alert alert-danger">', '</div>');

		if ($this->form_validation->run() == FALSE)
		{
			$this->_carrega_pagina(array('pagina_atual' => 'parceiro'));
		}
		else
		{		
			$message = "Contato de parceria via site contaponto.com.br\r\n\r\nDe: ".$this->input->post('nome',TRUE).' - '.$this->input->post('email',TRUE)."\r\n".'empresa: '.$this->input->post('empresa',TRUE)."\r\nMensagem:\r\n".$this->input->post('mensagem',TRUE);
							
			$enviou = $this->_envia_email('contato@contaponto.com.br', //$para_email,
				'Contato de parceria via site - '.$this->input->post('empresa',TRUE), //$assunto
				$message, //$mensagem
				null, //$de_nome = 'Cuidaê',
				null, //$de_email = 'cuidae@cuidae.com.br',
				$this->input->post('email',TRUE), //$reply_to_email = '',
				$this->input->post('nome',TRUE) //$reply_to_nome = ''
				);

			if ($enviou) {
				$this->_carrega_pagina(array('pagina_atual' => 'parceiro', 'sucesso' => TRUE));
			} else {
				$data['pagina_atual'] = 'parceiro';
				$data['erro_envio'] = 'Desculpe pelo transtorno, houve um erro ao encaminhar sua mensagem, por favor envie sua mensagem diretamente para o e-mail informado acima.';
				$this->_carrega_pagina($data);
			}
		}
	}

	public function termos($versao = FALSE)
	{
		if ($versao != 'politica') {
			$versao = 'termos';
		}

		$this->_carrega_pagina(array('pagina_atual' => $versao));
	}

	public function troque_seus_pontos()
	{
		$cidade = $this->_define_cidade(__FUNCTION__);

		if ($cidade)
		{	
			$this->load->model('cidade_model');
			$data['cidade_atual'] = $this->cidade_model->consulta_cidade_por_id($cidade);

			$this->load->model('cupom_model');

			$data['cupons'] = $this->cupom_model->carrega_cupons_por_cidade($cidade);
			$data['segmentos'] = array();
			$data['bairros'] = array();
			$data['pontos'] = array();
			
			$this->load->helper('text');
			$this->load->helper('inflector');

			foreach ($data['cupons'] as $key => $cupom) {
				$data['cupons'][$key]['codigo_segmento'] = str_replace(',','_',underscore(convert_accented_characters($cupom['nome_segmento'])));
				$data['cupons'][$key]['codigo_bairro'] = underscore(convert_accented_characters($cupom['bairro']));
				$data['cupons'][$key]['cep'] = substr(str_pad($cupom['cep'], 8, "0", STR_PAD_LEFT), 0, 5).'-'.substr(str_pad($cupom['cep'], 8, "0", STR_PAD_LEFT), -3, 3);
				$data['cupons'][$key]['endereco_mapa'] = str_replace(' ', '+', $cupom['endereco'].' - '.$cupom['bairro'].', '.$cupom['nome_cidade'].' - '.$cupom['uf'].', '.$data['cupons'][$key]['cep']);
				
				if ( ! in_array($cupom['nome_segmento'], $data['segmentos'])) {
					$data['segmentos'][$data['cupons'][$key]['codigo_segmento']] = $cupom['nome_segmento'];
				}

				if ( ! in_array($cupom['bairro'], $data['bairros'])) {
					$data['bairros'][$data['cupons'][$key]['codigo_bairro']] = $cupom['bairro'];
				}
				if ( ! in_array($cupom['preco_pontos'], $data['pontos'])) {
					$data['pontos']['p'.$cupom['preco_pontos']] = $cupom['preco_pontos'];
				}
				//caso o cupom não tenha imagem, utiliza imagem da empresa
				if ( ! $data['cupons'][$key]['imagem_principal'])
				{
					$data['cupons'][$key]['imagem_principal'] = $data['cupons'][$key]['logo'];
				}
			}

			shuffle($data['cupons']);

			ksort($data['bairros']);
			ksort($data['segmentos']);
			asort($data['pontos']);
			
			$data['pagina_atual'] = 'troque_seus_pontos';

			show($data);

			$this->_carrega_pagina($data);
		}
	}


	public function l($pagina = false)
	{
		switch ($pagina) {
			case 'vale_compra_rampazzo_100':	
				$data['titulo'] = 'Sorteio de vale-compra de R$ 100 - Rampazzo Calçados';
				$this->load->view('landing/v_'.$pagina,$data);
				break;
			
			default:
				redirect('/','refresh');
				break;
		}

	}

	public function teste($value='')
	{
		
// 		echo ceil(5/2);
// 		echo "<br>";
// 		echo 2%ceil(5/2);
// 		echo "<br>";
// 		//echo date('Y-m-d', time()-60*60*60*24);
// 		//echo date('Y-m-d',time()-60*86400)
// 		echo date('Y-m-d H:i:s',time());

		
// 		// tudo isso retorna falso: Null, 0, array() e ''
// 		if (NULL) {
// 			echo 'NULL';
// 		}
// 		if (0) {
// 			echo '0';
// 		}
// 		$array = array();
// 		if ($array) 
// 		{
// 			echo 'array()';
// 		}
// 		if ('')
// 		{
// 			echo "''";
// 		}


// 		echo "new table";
// 		echo "<pre>";
// 		var_dump($this->db->query("CREATE TABLE IF NOT EXISTS `conta_ponto_db`.`categoria_faq` (
//   `id_categoria_faq` INT NOT NULL AUTO_INCREMENT COMMENT '',
//   `categoria` VARCHAR(60) NOT NULL COMMENT '',
//   `restricao_tipo_acesso` VARCHAR(50) NULL COMMENT '',
//   PRIMARY KEY (`id_categoria_faq`)  COMMENT '')
// ENGINE = InnoDB;"));
// 		echo "</pre>";

// 		echo $this->db->affected_rows();


// echo "new table";
// 		echo "<pre>";
// 		var_dump($this->db->query("CREATE TABLE IF NOT EXISTS `conta_ponto_db`.`faq` (
//   `id_faq` INT NOT NULL AUTO_INCREMENT COMMENT '',
//   `fk_categoria_faq` INT NOT NULL COMMENT '',
//   `pergunta` VARCHAR(200) NOT NULL COMMENT '',
//   `resposta` VARCHAR(1000) NOT NULL COMMENT '',
//   `ordem` INT NULL COMMENT '',
//   PRIMARY KEY (`id_faq`)  COMMENT '',
//   INDEX `fk_faq_categoria_faq_idx` (`fk_categoria_faq` ASC)  COMMENT '',
//   CONSTRAINT `fk_faq_categoria_faq`
//     FOREIGN KEY (`fk_categoria_faq`)
//     REFERENCES `conta_ponto_db`.`categoria_faq` (`id_categoria_faq`)
//     ON DELETE NO ACTION
//     ON UPDATE NO ACTION)
// ENGINE = InnoDB;"));
// 		echo "</pre>";

// 		echo $this->db->affected_rows();
// 		

	if ($value == '') {
		$value = 'saulos~gmail.com';
	}

    list($user, $domain) = explode('~', $value);
    $arr= dns_get_record($domain,DNS_MX);
    echo "<pre>";
    print_r($arr);
    echo "</pre>";
    echo "<br>Retorno:<br>";
    if($arr[0]['host']==$domain&&!empty($arr[0]['target'])){
            echo $arr[0]['target'];
            echo('This MX records exists; I will accept this email as valid.');
	}
	else {
	        echo('No MX record exists;  Invalid email.');
	}
}
}

/* End of file Site.php */
/* Location: ./application/controllers/Site.php */