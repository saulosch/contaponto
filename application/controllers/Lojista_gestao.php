<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lojista_gestao extends MY_Controller {

	public function __construct(){
		parent::__construct();
	}


	public function lista_faturas()
	{	
		//carrega dados de pontos concedidos
		$this->load->model('faturamento_model');
		$data ['model']['fatura'] = $this->faturamento_model->consulta_faturas_por_loja($this->session->usuario['id_loja']);
		if ($data['model']['fatura'])
		{	
			foreach ($data['model']['fatura']['dados'] as $key => $value)
			{
				if ($value['data_estorno_fatura'] != NULL)
				{
					$data['model']['fatura']['dados'][$key]['tipo_linha'] = 'danger';
				}
				elseif ($value['data_pgto_boleto'] == NULL) 
				{
					$data['model']['fatura']['dados'][$key]['tipo_linha'] = 'warning';
				}
				else
				{
					$data['model']['fatura']['dados'][$key]['tipo_linha'] = '';
				}
				$data['model']['fatura']['dados'][$key]['link'] = 'lojista_gestao/fatura/'.$value['id_fatura'];
			}

			$data['campos_data']['fatura'] = array('dt_vencimento','ts_criacao','data_pgto_boleto','data_estorno_fatura');
			$data['abas']['fatura'] = 'Faturas';
		}

		//exibe dados carregados na tela
		// if ($data['model']['fatura'] OR $data['model']['usuario'])
		if ($data['model']['fatura'])
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

	public function fatura($id_fatura)
	{	
		$redirecionar_falha = '/lojista_gestao/lista_faturas';
		$this->mostrar_fatura($id_fatura,$redirecionar_falha,$this->session->usuario['id_loja']);
	}
}

/* End of file Lojista_gestao.php */
/* Location: ./application/controllers/Lojista_gestao.php */