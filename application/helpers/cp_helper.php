<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Conta Ponto Helpers
 *
 * @package		ContaPonto
 * @subpackage	Helpers
 * @category	Helpers
 * @author		Saulo
 */

// ------------------------------------------------------------------------

if ( ! function_exists('show'))
{
	/**
	 * Show 
	 *
	 * Print and die
	 *
	 * @param	mixed	what will be shown with print_r
	 * @param	boolean	indicates if dies or not after showing
	 */
	function show($variable = NULL, $die = FALSE)
	{	
		$CI =& get_instance();
			$CI->load->library('session');

		if ($variable == NULL) {
			$variable = array (
				'POST' => $CI->input->post(NULL, TRUE),
				'SESSION' => $CI->session->all_userdata());
		}

		if (isset($CI->session->userdata['usuario']))
		{
		  	if ($CI->session->userdata['usuario']['acesso'] == '1')
		  	{// admin
				echo "<pre>";
				print_r($variable);
				echo "</pre>";
			}
		}
		
		if ($die) {
			die;
		}

	}
}

if ( ! function_exists('formata_moeda'))
{
	/**
	 * formata_moeda 
	 *
	 * Recebe numero e retorna no formato R$ 1.234,56 ou -R$ 1.234,56
	 *
	 * @param	float	numero a ser formatado
	 * @return	string	numero formatado
	 */
	function formata_moeda($valor)
	{	
		return ($valor>=0)
			?'R$ '.number_format ( $valor , 2 , ',' , '.') 
			:'-R$ '.number_format ( $valor*(-1) , 2 , ',' , '.');
	}
}


if ( ! function_exists('formata_data'))
{
	/**
	 * formata_data 
	 *
	 * Recebe data nos formatos dd/mm/yyyy ou aaaa-mm-dd 
	 * Caso esteja em outro formato, retorna FALSE.
	 * Retorna no formato informado. 
	 *
	 * @param	string	Data
	 * @param	string	fomato de retorno da data, padrão 'Y-m-d'
	 * @param	mixed	Informar o que quer que seja retornado em caso de falha
	 * @return	mixed	boolean ou string
	 */
	function formata_data($data,$formato = 'Y-m-d', $retorno_falso = FALSE)
	{	
		if (strpos($data.'  ', '/',strpos($data, '/')+1) OR strpos($data.'  ', '-',strpos($data, '-')+1)) {
			return date($formato, strtotime(str_replace('/', '-', $data)));
		}	else {
			return $retorno_falso;
		}
	}
}


if ( ! function_exists('envia_email'))
{
	/**
	 * envia_email 
	 *
	 * Recebe data nos formatos dd/mm/yyyy ou aaaa-mm-dd 
	 * Caso esteja em outro formato, retorna FALSE.
	 * Retorna no formato informado. 
	 *
	 * @param	string	Data
	 * @param	string	fomato de retorno da data, padrão 'Y-m-d'
	 * @param	mixed	Informar o que quer que seja retornado em caso de falha
	 * @return	mixed	boolean ou string
	 */
	function envia_email($para_email,
						$assunto,
						$mensagem,
						$de_nome = null,
						$de_email = null,
						$reply_to_email = '',
						$reply_to_nome = '',
						$mailtype = 'text')
	{
		if (strpos(base_url(), 'localhost')) {
			echo "envia_email";
			show($mensagem,false);
			echo $mensagem;
			return true;

		} else {

			if ($de_nome == null)
				$de_nome = 'Site Contaponto';
			if ($de_email == null)
				$de_email = 'site@contaponto.com.br';
				

			$CI =& get_instance();
			$CI->load->library('email');

			// // REDEHOST - Demora 10 minutos pro email chegar!!!!
			// $config['protocol'] = 'smtp';
			// $config['smtp_host'] = 'mail37.redehost.com.br';
			// $config['smtp_user'] = 'saulo@contaponto.com.br';
			// $config['smtp_pass'] = '??????';
			// $config['smtp_port'] = '587';
			// $config['smtp_timeout'] = '20';
			

			// // AMAZON - conta saulos@gmail.com
			// $config['protocol'] = 'smtp';
			// $config['smtp_host'] = 'email-smtp.us-west-2.amazonaws.com';
			// $config['smtp_user'] = 'AKIAJBMLUBI2SPQ6CGXA';
			// $config['smtp_pass'] = 'ArxhKDgS9LL4/jamYoZnK9NkzeyhKuc358xOvxUrr7A2';
			// $config['smtp_port'] = '25';
			// $config['smtp_timeout'] = '10';
			// $config['smtp_crypto'] = 'tls';
			
			// AMAZON - conta nati.jordao@gmail.com
			// $config['protocol'] = 'smtp';
			// $config['smtp_host'] = 'email-smtp.us-east-1.amazonaws.com';
			// $config['smtp_user'] = 'AKIAJHSM75HDFD24R55A';
			// $config['smtp_pass'] = 'AoPu5bBjevwGTsowdYRJ3EPbqevct3Att7S0asCKWV2N';
			// $config['smtp_port'] = '25';
			// $config['smtp_timeout'] = '10';
			// $config['smtp_crypto'] = 'tls';
			
			// mailjet.com - site@contaponto.com.br
			$config['protocol'] = 'smtp';
			$config['smtp_host'] = 'in-v3.mailjet.com';
			$config['smtp_user'] = 'dde1e82d599f0f773417d873572239db';
			$config['smtp_pass'] = '95147108d20d8a597cb244d27d9b3efb';
			$config['smtp_port'] = '25';
			$config['smtp_timeout'] = '10';
			$config['smtp_crypto'] = 'tls';
			
			// $config['charset'] = 'iso-8859-1';
			$config['charset'] = 'utf-8';
			$config['wordwrap'] = TRUE;
			$config['mailtype'] = $mailtype; //pode ser html ou text

			$CI->email->initialize($config);
			$CI->email->set_newline("\r\n");

			$CI->email->set_header('List-Unsubscribe', 'mailto:site@contaponto.com.br?subject=UNSUBSCRIBE');

			$CI->email->from($de_email,$de_nome);
			$CI->email->to( $para_email ); 
			//$CI->email->cc('arcanjo1983@yahoo.com.br'); 
			//$CI->email->bcc('saulos@gmail.com');
			if ($reply_to_email != '')
				$CI->email->reply_to($reply_to_email, $reply_to_nome);

			$CI->email->subject($assunto);
			$CI->email->message($mensagem);

			$retorno = $CI->email->send();

		  	//echo $CI->email->print_debugger();
		  	show($retorno,false);
		  	
		  	// return false;
			return $retorno;
		}
	}
}


if ( ! function_exists('consulta_codigo_vale_ponto'))
{
	/**
	 * consulta_codigo_vale_ponto 
	 *
	 * Recebe numero e retorna o codigo do vale ponto correspondente ao numero
	 *
	 * @param	int		numero a ser calculado o codigo
	 * @param	int		tamanho do codigo a ser retornado (Default = 6 caracteres)
	 * @return	string	codigo do vale pontos
	 */
	function consulta_codigo_vale_ponto($numero,$tamanho_codigo = 6)
	{
		return strtoupper(substr(sha1('5e057af5-8612T709bcd1a]26a42fe8b4a2'.$numero),0,$tamanho_codigo));
	}
}

if ( ! function_exists('compacta_html'))
{
	/**
	 * compacta_html 
	 *
	 * Recebe conteudo html e remove espacos desnecessarios
	 */
	function compacta_html($content)
	{
		//return trim(preg_replace('/\n|\r|\t|/','',$content));
		return $content;
	}
}

//fim - cp_helper.php