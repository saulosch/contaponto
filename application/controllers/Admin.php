<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends MY_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->model('menu_model');
		$this->_valida_acesso();
		$this->load->library('grocery_CRUD');
	}

	public function index()
	{
		$data['menu_admin'] = $this->menu_model->carrega_menu_admin($this->session->usuario['acesso']);
		$data['js_files'] = array();
		$data['css_files'] = array();
		$data['output'] = "";
		$this->load->view('site/v_admin.php',$data);
	}

	public function export_mc()
	{
		$this->load->model('usuario_model');
		$this->usuario_model->atualiza_saldo_usuario(0);//atualiza saldo de todos os usuarios
		$usuarios = $this->usuario_model->consulta_usuarios_exportacao();
		echo '"#"';
		foreach ($usuarios[0] as $nome_campo => $valor)
		{
			{
				echo ',"'.$nome_campo.'"';
			}
		}
		echo ',"link_confirmacao"';
		foreach ($usuarios as $key => $usuario)
		{
			echo '<br />"'.$key.'"'; 
			foreach ($usuario as $nome_campo => $valor)
			{
				echo ',"'.$valor.'"';
			}
			echo ',"'.base_url("usuario/altera_senha/" . $usuario['sal'] . "/" . str_replace('@', '~', $usuario['email']). "/").'"';
		}
	}

	public function tipo_acesso()
	{	
		$menu = $this->menu_model->carrega_menu_admin($this->session->usuario['acesso']);

		$crud = new grocery_CRUD();
		//$crud->set_theme('datatables');

		$crud->set_table(__FUNCTION__);
		if (isset($menu['admin/'.__FUNCTION__]['label'])) {
			$crud->set_subject($menu['admin/'.__FUNCTION__]['label']);
		}
		//$crud->columns();
		//$crud->set_relation('fk_estado_civil','estado_civil','estado_civil');
		
		$output = $crud->render();
		$output->menu_admin = $menu;
		$this->load->view('site/v_admin.php',$output);
	}

	public function banco()
	{	
		$menu = $this->menu_model->carrega_menu_admin($this->session->usuario['acesso']);

		$crud = new grocery_CRUD();
		//$crud->set_theme('datatables');

		$crud->set_table(__FUNCTION__);
		if (isset($menu['admin/'.__FUNCTION__]['label'])) {
			$crud->set_subject($menu['admin/'.__FUNCTION__]['label']);
		}
		//$crud->columns();
		//$crud->set_relation('fk_estado_civil','estado_civil','estado_civil');
		
		$output = $crud->render();
		$output->menu_admin = $menu;
		$this->load->view('site/v_admin.php',$output);
	}

	public function segmento()
	{	
		$menu = $this->menu_model->carrega_menu_admin($this->session->usuario['acesso']);

		$crud = new grocery_CRUD();
		//$crud->set_theme('datatables');

		$crud->set_table(__FUNCTION__);
		if (isset($menu['admin/'.__FUNCTION__]['label'])) {
			$crud->set_subject($menu['admin/'.__FUNCTION__]['label']);
		}
		//$crud->columns();
		//$crud->set_relation('fk_estado_civil','estado_civil','estado_civil');
		
		$output = $crud->render();
		$output->menu_admin = $menu;
		$this->load->view('site/v_admin.php',$output);
	}

	public function preco()
	{	
		$menu = $this->menu_model->carrega_menu_admin($this->session->usuario['acesso']);

		$crud = new grocery_CRUD();
		//$crud->set_theme('datatables');

		$crud->set_table(__FUNCTION__);
		if (isset($menu['admin/'.__FUNCTION__]['label'])) {
			$crud->set_subject($menu['admin/'.__FUNCTION__]['label']);
		}
		//$crud->columns();
		//$crud->set_relation('fk_estado_civil','estado_civil','estado_civil');
		$crud->set_relation('fk_usuario_criacao','usuario','email');
		
		$output = $crud->render();
		$output->menu_admin = $menu;
		$this->load->view('site/v_admin.php',$output);
	}

	public function desconto()
	{	
		$menu = $this->menu_model->carrega_menu_admin($this->session->usuario['acesso']);

		$crud = new grocery_CRUD();
		//$crud->set_theme('datatables');

		$crud->set_table(__FUNCTION__);
		if (isset($menu['admin/'.__FUNCTION__]['label'])) {
			$crud->set_subject($menu['admin/'.__FUNCTION__]['label']);
		}
		//$crud->columns();
		//$crud->set_relation('fk_estado_civil','estado_civil','estado_civil');
		$crud->set_relation('fk_usuario_criacao','usuario','email');
		
		$output = $crud->render();
		$output->menu_admin = $menu;
		$this->load->view('site/v_admin.php',$output);
	}

	public function loja()
	{	
		$menu = $this->menu_model->carrega_menu_admin($this->session->usuario['acesso']);

		$crud = new grocery_CRUD();
		//$crud->set_theme('datatables');

		$crud->set_table(__FUNCTION__);
		if (isset($menu['admin/'.__FUNCTION__]['label'])) {
			$crud->set_subject($menu['admin/'.__FUNCTION__]['label']);
		}
		$crud->columns('id_loja','nome_fantasia','nome_proprietario','telefone_proprietario','logo','endereco','status','valor_em_compras','qtd_pontos');
		$crud->set_relation('fk_banco','banco','nome_banco');
		$crud->set_relation('fk_segmento','segmento','nome_segmento');
		$crud->set_relation('fk_cidade','cidade','nome_cidade');

		$crud->set_field_upload('logo','assets/uploads/files');

		$crud->order_by('id_loja','desc');

		$crud->add_action('Detalhes', 'http://www.grocerycrud.com/assets/uploads/general/smiley.png', 'admin/detalhe_loja');
		
		$output = $crud->render();
		$output->menu_admin = $menu;
		$this->load->view('site/v_admin.php',$output);
	}

	public function detalhe_loja($id_loja=null) 
	{
		if ($id_loja)
		{	
			$this->load->model('loja_model');
			$return['loja'] = $this->loja_model->consulta_loja_por_id($id_loja);
			$return['lojistas'] =  $this->loja_model->consulta_lojistas_por_loja($id_loja);

			$this->load->model('pontuacao_model');
			$return['loja_pontuacao'] = $this->pontuacao_model->consulta_pontuacao_por_loja($id_loja);
			$return['loja_limite'] = $this->pontuacao_model->verifica_limite_credito($id_loja);

			$this->load->model('usuario_model');
			$return['loja_clientes'] = $this->usuario_model->consulta_usuarios_por_loja($id_loja);

			// show($return);

			$data['output'] = $this->load->view('site/v_admin_detalhe_loja.php',$return,TRUE);
		}
		else
		{
			$data['output'] = "Loja não encontrada.";
		}

		$data['menu_admin'] = $this->menu_model->carrega_menu_admin($this->session->usuario['acesso']);
		$data['js_files'] = array();
		$data['css_files'] = array();
		
		$this->load->view('site/v_admin.php',$data);

	}

	public function usuario()
	{	
		$menu = $this->menu_model->carrega_menu_admin($this->session->usuario['acesso']);

		$crud = new grocery_CRUD();
		$crud->set_theme('datatables');

		$crud->set_table(__FUNCTION__);
		if (isset($menu['admin/'.__FUNCTION__]['label'])) {
			$crud->set_subject($menu['admin/'.__FUNCTION__]['label']);
		}
		$crud->columns(	'id_usuario',
						'nome',
						'sobrenome',
						'email',
						'cpf',
						'ativo',
						'fk_tipo_acesso',
						'cep',
						'ultima_fk_cidade',
						//'data_nascimento',
						//'genero',
						//'fk_estado_civil',
						//'ts_ult_acesso',
						'saldo',
						'ts_criacao',
						'fk_loja_cadastro');
		
		$crud->set_relation('fk_estado_civil','estado_civil','estado_civil');
		$crud->set_relation('fk_tipo_acesso','tipo_acesso','nome_tipo_acesso');
		$crud->set_relation('fk_loja_cadastro','loja','nome_fantasia');
		$crud->set_relation('ultima_fk_cidade','cidade','nome_cidade');

		$crud->order_by('id_usuario','desc');

		$output = $crud->render();
		$output->menu_admin = $menu;
		$this->load->view('site/v_admin.php',$output);
	}

	public function usuario_loja()
	{	
		$menu = $this->menu_model->carrega_menu_admin($this->session->usuario['acesso']);

		$crud = new grocery_CRUD();
		//$crud->set_theme('datatables');

		$crud->set_table(__FUNCTION__);
		if (isset($menu['admin/'.__FUNCTION__]['label'])) {
			$crud->set_subject($menu['admin/'.__FUNCTION__]['label']);
		}
		$crud->columns('id_usuario_loja','fk_id_usuario','fk_id_loja','ts_ini_validade','ts_fim_validade');
		$crud->order_by('id_usuario_loja','desc');
				
		$crud->set_relation('fk_id_usuario','usuario','email');
		$crud->set_relation('fk_id_loja','loja','nome_fantasia');
		
		$output = $crud->render();
		$output->menu_admin = $menu;
		$this->load->view('site/v_admin.php',$output);
	}

	public function cupom()
	{	
		$menu = $this->menu_model->carrega_menu_admin($this->session->usuario['acesso']);

		$crud = new grocery_CRUD();
		//$crud->set_theme('datatables');

		$crud->set_table(__FUNCTION__);
		if (isset($menu['admin/'.__FUNCTION__]['label'])) {
			$crud->set_subject($menu['admin/'.__FUNCTION__]['label']);
		}
		//$crud->columns();
		$crud->set_relation('fk_loja','loja','nome_fantasia');
		$crud->set_field_upload('imagem_principal','assets/uploads/files');
		
		$output = $crud->render();
		$output->menu_admin = $menu;
		$this->load->view('site/v_admin.php',$output);
	}

	public function usuario_cupom()
	{	
		$menu = $this->menu_model->carrega_menu_admin($this->session->usuario['acesso']);

		$crud = new grocery_CRUD();
		//$crud->set_theme('datatables');

		$crud->set_table(__FUNCTION__);
		if (isset($menu['admin/'.__FUNCTION__]['label'])) {
			$crud->set_subject($menu['admin/'.__FUNCTION__]['label']);
		}
		$crud->columns('id_usuario_cupom','fk_usuario','fk_cupom','ts_emissao','codigo_cupom','dt_validade','ts_estorno','ts_utilizacao','fk_usuario_utilizacao');
		$crud->set_relation('fk_usuario','usuario','email');
		$crud->set_relation('fk_usuario_utilizacao','usuario','email');
		$crud->set_relation('fk_cupom','cupom','titulo_cupom');

		$output = $crud->render();
		$output->menu_admin = $menu;
		$this->load->view('site/v_admin.php',$output);
	}

	public function pontuacao()
	{	
		$menu = $this->menu_model->carrega_menu_admin($this->session->usuario['acesso']);

		$crud = new grocery_CRUD();
		//$crud->set_theme('datatables');

		$crud->set_table(__FUNCTION__);
		if (isset($menu['admin/'.__FUNCTION__]['label'])) {
			$crud->set_subject($menu['admin/'.__FUNCTION__]['label']);
		}
		$crud->columns('id_pontuacao','ts_pontuacao','fk_loja','fk_usuario_cupom','fk_usuario','qtd_pontos','validade_pontos','fk_usuario_criacao','qtd_disponivel','fk_fatura','ts_estorno','fk_usuario_estorno');
		
		$crud->set_relation('fk_loja','loja','nome_fantasia');
		$crud->set_relation('fk_usuario_cupom','usuario','email');
		$crud->set_relation('fk_usuario','usuario','email');
		$crud->set_relation('fk_usuario_criacao','usuario','email');
		$crud->set_relation('fk_usuario_estorno','usuario','email');

		$crud->order_by('id_pontuacao','desc');

		$output = $crud->render();
		$output->menu_admin = $menu;
		$this->load->view('site/v_admin.php',$output);
	}

	public function consumo_pontuacao()
	{	
		$menu = $this->menu_model->carrega_menu_admin($this->session->usuario['acesso']);

		$crud = new grocery_CRUD();
		//$crud->set_theme('datatables');

		$crud->set_table(__FUNCTION__);
		if (isset($menu['admin/'.__FUNCTION__]['label'])) {
			$crud->set_subject($menu['admin/'.__FUNCTION__]['label']);
		}
		//$crud->columns();
		//$crud->set_relation('fk_estado_civil','estado_civil','estado_civil');
		
		$output = $crud->render();
		$output->menu_admin = $menu;
		$this->load->view('site/v_admin.php',$output);
	}

	public function limite_credito()
	{	
		$menu = $this->menu_model->carrega_menu_admin($this->session->usuario['acesso']);

		$crud = new grocery_CRUD();
		//$crud->set_theme('datatables');

		$crud->set_table(__FUNCTION__);
		if (isset($menu['admin/'.__FUNCTION__]['label'])) {
			$crud->set_subject($menu['admin/'.__FUNCTION__]['label']);
		}
		//$crud->columns();
		$crud->set_relation('fk_loja','loja','nome_fantasia');
		
		$output = $crud->render();
		$output->menu_admin = $menu;
		$this->load->view('site/v_admin.php',$output);
	}

	public function faturamento()
	{	
		$menu = $this->menu_model->carrega_menu_admin($this->session->usuario['acesso']);

		$crud = new grocery_CRUD();
		//$crud->set_theme('datatables');

		$crud->set_table(__FUNCTION__);
		if (isset($menu['admin/'.__FUNCTION__]['label'])) {
			$crud->set_subject($menu['admin/'.__FUNCTION__]['label']);
		}
		//$crud->columns('id_fatura','fk_loja','dt_vencimento','ts_criacao','valor_total_ref');
		$crud->set_relation('fk_usuario_criacao','usuario','email');
		
		$output = $crud->render();
		$output->menu_admin = $menu;
		$this->load->view('site/v_admin.php',$output);
	}

	public function fatura()
	{	
		$menu = $this->menu_model->carrega_menu_admin($this->session->usuario['acesso']);

		$crud = new grocery_CRUD();
		//$crud->set_theme('datatables');

		$crud->set_table(__FUNCTION__);
		if (isset($menu['admin/'.__FUNCTION__]['label'])) {
			$crud->set_subject($menu['admin/'.__FUNCTION__]['label']);
		}
		$crud->columns('fk_faturamento','id_fatura','fk_loja','dt_vencimento','ts_criacao','valor_total_ref');
		$crud->set_relation('fk_loja','loja','nome_fantasia');

		$crud->add_action( 'ver fatura', 'http://www.grocerycrud.com/assets/uploads/general/smiley.png' , '' , '' ,  array($this,'_ver_fatura_callback'));
		
		$output = $crud->render();
		$output->menu_admin = $menu;
		$this->load->view('site/v_admin.php',$output);
	}

	public function _ver_fatura_callback($primary_key , $row)
	{	
		return base_url('admin/ver_fatura/'.$primary_key);
	}

	public function ver_fatura($id_fatura)
	{	
		$redirecionar_falha = '/admin/fatura';
		$this->mostrar_fatura($id_fatura,$redirecionar_falha,null);
	}

	public function mensalidade()
	{	
		$menu = $this->menu_model->carrega_menu_admin($this->session->usuario['acesso']);

		$crud = new grocery_CRUD();
		//$crud->set_theme('datatables');

		$crud->set_table(__FUNCTION__);
		if (isset($menu['admin/'.__FUNCTION__]['label'])) {
			$crud->set_subject($menu['admin/'.__FUNCTION__]['label']);
		}
		$crud->columns('id_mensalidade','fk_loja','dia_faturamento','valor','descricao','ts_ini_validade','ts_fim_validade');
		$crud->set_relation('fk_loja','loja','nome_fantasia');
		
		$output = $crud->render();
		$output->menu_admin = $menu;
		$this->load->view('site/v_admin.php',$output);
	}

	public function ajuste_fatura()
	{	
		$menu = $this->menu_model->carrega_menu_admin($this->session->usuario['acesso']);

		$crud = new grocery_CRUD();
		//$crud->set_theme('datatables');

		$crud->set_table(__FUNCTION__);
		if (isset($menu['admin/'.__FUNCTION__]['label'])) {
			$crud->set_subject($menu['admin/'.__FUNCTION__]['label']);
		}
		//$crud->columns();
		$crud->set_relation('fk_usuario_criacao','usuario','email');
		$crud->set_relation('fk_usuario_cancelamento','usuario','email');

		// Triggers
		$crud->callback_after_insert(array($this, '_ajusta_valor_referencia_fatura_callback'));
		$crud->callback_after_update(array($this, '_ajusta_valor_referencia_fatura_callback'));
		
		$output = $crud->render();
		$output->menu_admin = $menu;
		$this->load->view('site/v_admin.php',$output);
	}

	public function _ajusta_valor_referencia_fatura_callback($post_array,$primary_key)
	{
		$this->load->model('faturamento_model');
		$this->faturamento_model->atualiza_valor_referencia_fatura($post_array['fk_fatura']);
		return true;
	}

	public function item_fatura()
	{	
		$menu = $this->menu_model->carrega_menu_admin($this->session->usuario['acesso']);

		$crud = new grocery_CRUD();
		//$crud->set_theme('datatables');

		$crud->set_table(__FUNCTION__);
		if (isset($menu['admin/'.__FUNCTION__]['label'])) {
			$crud->set_subject($menu['admin/'.__FUNCTION__]['label']);
		}
		//$crud->columns();
		//$crud->set_relation('fk_estado_civil','estado_civil','estado_civil');
		
		//// Triggers
		$crud->callback_after_insert(array($this, '_ajusta_valor_referencia_fatura_callback'));
		$crud->callback_after_update(array($this, '_ajusta_valor_referencia_fatura_callback'));
		
		
		$output = $crud->render();
		$output->menu_admin = $menu;
		$this->load->view('site/v_admin.php',$output);
	}

	public function categoria_faq()
	{	
		$menu = $this->menu_model->carrega_menu_admin($this->session->usuario['acesso']);

		$crud = new grocery_CRUD();
		//$crud->set_theme('datatables');

		$crud->set_table(__FUNCTION__);
		if (isset($menu['admin/'.__FUNCTION__]['label'])) {
			$crud->set_subject($menu['admin/'.__FUNCTION__]['label']);
		}
		//$crud->columns();
		//$crud->set_relation('fk_estado_civil','estado_civil','estado_civil');
		
		$output = $crud->render();
		$output->menu_admin = $menu;
		$this->load->view('site/v_admin.php',$output);
	}

	public function faq()
	{	
		$menu = $this->menu_model->carrega_menu_admin($this->session->usuario['acesso']);

		$crud = new grocery_CRUD();
		//$crud->set_theme('datatables');

		$crud->set_table(__FUNCTION__);
		if (isset($menu['admin/'.__FUNCTION__]['label'])) {
			$crud->set_subject($menu['admin/'.__FUNCTION__]['label']);
		}
		//$crud->columns();
		$crud->set_relation('fk_categoria_faq','categoria_faq','categoria');
		$crud->change_field_type('resposta', 'text');
				
		$output = $crud->render();
		$output->menu_admin = $menu;
		$this->load->view('site/v_admin.php',$output);
	}

	public function lote_vale_ponto()
	{	
		$menu = $this->menu_model->carrega_menu_admin($this->session->usuario['acesso']);

		$crud = new grocery_CRUD();
		//$crud->set_theme('datatables');

		$crud->set_relation('fk_loja','loja','nome_fantasia');

		$crud->add_action( 'exportar', base_url('assets/images/ico/pdf.gif') , '' , '' ,  array($this,'_exporta_lote_vale_ponto'));

		$crud->add_action( 'imprimir', base_url('assets/images/ico/print_icon.gif') , '' , '' ,  array($this,'_imprime_lote_vale_ponto'));

		$crud->set_table(__FUNCTION__);
		if (isset($menu['admin/'.__FUNCTION__]['label'])) {
			$crud->set_subject($menu['admin/'.__FUNCTION__]['label']);
		}
		//$crud->columns();
		//$crud->set_relation('fk_estado_civil','estado_civil','estado_civil');
		
		$output = $crud->render();
		$output->menu_admin = $menu;
		$this->load->view('site/v_admin.php',$output);
	}

	public function _exporta_lote_vale_ponto($primary_key , $row)
	{	
		return base_url('admin/exporta_lote/'.$primary_key);
	}

	public function _imprime_lote_vale_ponto($primary_key , $row)
	{	
		return base_url('admin/imprime_lote/'.$primary_key);
	}

	public function imprime_lote($id_lote_vale_ponto = 0)
	{	
		if ($id_lote_vale_ponto == 0)
		{
			$this->load->view('site/v_admin_lote_vale_ponto_verso');
		}
		else
		{
			//carrega dados do lote de vale pontos
			$this->load->model('vale_ponto_model');
			$data['vale_ponto'] = $this->vale_ponto_model->consulta_vale_pontos_por_id($id_lote_vale_ponto);
			$this->load->view('site/v_admin_lote_vale_ponto',$data);
		}
	}

	public function parametro()
	{	
		$menu = $this->menu_model->carrega_menu_admin($this->session->usuario['acesso']);

		$crud = new grocery_CRUD();
		//$crud->set_theme('datatables');

		$crud->set_table(__FUNCTION__);
		if (isset($menu['admin/'.__FUNCTION__]['label'])) {
			$crud->set_subject($menu['admin/'.__FUNCTION__]['label']);
		}
		//$crud->columns();
		//$crud->set_relation('fk_estado_civil','estado_civil','estado_civil');
		
		$output = $crud->render();
		$output->menu_admin = $menu;
		$this->load->view('site/v_admin.php',$output);
	}
	
}

/* End of file Admin.php */
/* Location: ./application/controllers/Admin.php */