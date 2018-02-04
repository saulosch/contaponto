<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Acesso_model extends CI_Model {

	public function carrega_permissoes($tipo_acesso="") {
		// lista os controllers que cada tipo_acesso tem permissao de executar
		$permissoes =  array (

			// acesso livre ( todos acessam )
			'0' => array('0','site','usuario'), 
			
			//1,'Administrador'
			'1' => array('usuario_logado','admin'),

			//2,'Comercial Conta Ponto'
			'2' => array('usuario_logado','admin'),

			//3,'Consumidor'
			'3' => array('usuario_logado'),

			//4,'gerente de loja'
			'4' => array('usuario_logado','lojista','lojista_gestao'),

			//5,'Vendedor de loja'
			'5' => array('usuario_logado','lojista'),

			//6, 'Associação'
			'6' => array('usuario_logado','lojista','associacao'),

			);

		if ($tipo_acesso=="") {
			return $permissoes;
		} else {
			return $permissoes[$tipo_acesso];
		}


		
	}

}
/* End of file Acesso_model.php */
/* Location: ./application/models/Acesso_model.php */