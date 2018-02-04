<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu_model extends CI_Model {

	public function carrega_menu_superior()
	{
		return array(
			'como_funciona' => 'Como funciona',
			'ganhe_pontos' => 'Lojas parceiras',
			'troque_seus_pontos' => 'Troque seus pontos',
			'fale_conosco' => 'Fale conosco',
			);
	}

	public function carrega_menu_inferior()
	{
		return array(
			'duvidas' => 'Dúvidas Frequentes',
			'termos' => 'Termos e Condições',
			'parceiro' => 'Seja nosso parceiro',
			'fale_conosco' => 'Fale Conosco',
			);
	}

	public function carrega_menu_admin($tipo_acesso)
	{
		$opcoes =  array(
			//admin
			
			'admin/tipo_acesso' => array ( 
				'acesso' => array(1),
				'label'  => 'Tipos de acesso'),
			
			'admin/banco' => array ( 
				'acesso' => array(1),
				'label'  => 'Bancos'),
			
			'admin/preco' => array ( 
				'acesso' => array(1),
				'label'  => 'Preços'),
			
			'admin/desconto' => array ( 
				'acesso' => array(1),
				'label'  => 'Descontos'),
			
			'admin/segmento' => array ( 
				'acesso' => array(1),
				'label'  => 'Segmentos'),
			
			'admin/loja' => array ( 
				'acesso' => array(1,2),
				'label'  => 'Lojas'),
			
			'admin/usuario' => array ( 
				'acesso' => array(1,2),
				'label'  => 'Usuário'),
			
			'admin/usuario_loja' => array ( 
				'acesso' => array(1,2),
				'label'  => 'Usuários por loja'),
			
			'admin/cupom' => array ( 
				'acesso' => array(1),
				'label'  => 'Cupons'),
			
			'admin/usuario_cupom' => array ( 
				'acesso' => array(1),
				'label'  => 'Cupons por usuário'),
			
			'admin/pontuacao' => array ( 
				'acesso' => array(1),
				'label'  => 'Pontuação'),
			
			'admin/consumo_pontuacao' => array ( 
				'acesso' => array(1),
				'label'  => 'Consumo de pontuação'),
			
			'admin/limite_credito' => array ( 
				'acesso' => array(1,2),
				'label'  => 'Limite de Crédito'),
			
			'admin/fatura' => array ( 
				'acesso' => array(1,2),
				'label'  => 'Faturas'),
			
			'admin/faturamento' => array ( 
				'acesso' => array(1,2),
				'label'  => 'Faturamentos'),
			
			'admin/mensalidade' => array ( 
				'acesso' => array(1,2),
				'label'  => 'Cobranças'),
			
			'admin/ajuste_fatura' => array ( 
				'acesso' => array(1,2),
				'label'  => 'Ajuste nas Faturas'),
			
			'admin/item_fatura' => array ( 
				'acesso' => array(1),
				'label'  => 'Itens das Faturas'),
			
			'admin/categoria_faq' => array ( 
				'acesso' => array(1),
				'label'  => 'Categorias'),
			
			'admin/faq' => array ( 
				'acesso' => array(1),
				'label'  => 'FAQ'),
			
			'admin/lote_vale_ponto' => array ( 
				'acesso' => array(1),
				'label'  => 'Vale Pontos'),
			
			'admin/parametro' => array ( 
				'acesso' => array(1),
				'label'  => 'Parâmetros'),
			
			//
			'/' => array (
				'label' =>'Voltar para o site'),
			'usuario/logout' => array (
				'label' =>'Sair'),
			);

		foreach ($opcoes as $key => $item) {
			if ( ! isset($item['acesso']) OR in_array($tipo_acesso, $item['acesso'])) {
				$retorno[$key]['label'] = $item['label'];	
			}
		}

		return $retorno;
	}

	public function carrega_menu_barra($tipo_acesso)
	{	// echo "tipo_acesso $tipo_acesso;";
		$opcoes =  array(
			'lojista/extrato_loja' => array ('classe'=>'fa fa-file-text-o', 
							   'label' =>'Extrato da Loja'),
			'lojista/cadastra_cliente' => array ('classe'=>'fa fa-user-plus', 
							   'label' =>'Cadastrar Cliente'),
			'lojista/conceder_pontos' => array ('classe'=>'fa fa-money', 
							   'label' =>'Conceder Pontos'),
			'lojista/recebe_cupom' => array ('classe'=>'fa fa-sticky-note-o', 
							   'label' =>'Receber Cupom'),
			'lojista_gestao/lista_faturas' => array ('classe'=>'fa fa-dollar', 
							   'label' =>'Faturas'),
			'admin' => array ('classe'=>'fa fa-user-secret', 
							   'label' =>'Administração'),
			'usuario_logado/cadastrar_vale_pontos' => array ('classe'=>'fa fa-ticket', 
							   'label' =>'Vale-pontos'),
			'usuario_logado/extrato' => array ('classe'=>'fa fa-file-text-o', 
							   'label' =>'Pontos/Cupons'),
			'usuario_logado/alterar_cadastro' => array ('classe'=>'fa fa-user', 
							   'label' =>'Meus dados'),
			'usuario/logout' => array ('classe'=>'fa fa-sign-out', 
							   'label' =>'Sair'),
		);

		$this->load->model('acesso_model');
		$permissoes = array_merge(	$this->acesso_model->carrega_permissoes('0'),
									$this->acesso_model->carrega_permissoes($tipo_acesso));


		foreach ($opcoes as $key => $value) {
			$classe = substr($key, 0, strpos($key.'/', '/')); 
			// echo "$classe;";
			if (in_array($classe, $permissoes)) {
				$retorno[$key] = $value;
			}
		}

		return $retorno;
	}
}

/* End of file Menu_model.php */
/* Location: ./application/models/Menu_model.php */