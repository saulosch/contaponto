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
		if ($variable == NULL) {
			$CI =& get_instance();
			$CI->load->library('session');
			$variable = array (
				'POST' => $CI->input->post(NULL, TRUE),
				'SESSION' => $CI->session->all_userdata());
		}

		echo "<pre>";
		print_r($variable);
		echo "</pre>";
		if ($die) {
			die;
		}

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