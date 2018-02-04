<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Faq_model extends CI_Model {

	public function carrega_faq($tipo)
	{
		
		$restricao = ',';
		$restricao .= (is_numeric($tipo))?$tipo:'0';
		$restricao .= ',';

		$this->db->from('faq');
		$this->db->join('categoria_faq', 'faq.fk_categoria_faq = categoria_faq.id_categoria_faq');
		$this->db->like("concat(',','restricao_tipo_acesso',',')", $restricao);
		$this->db->or_where('restricao_tipo_acesso','0');
		$this->db->order_by('id_categoria_faq , ordem');
		$query = $this->db->get();

		return $query->result_array();
	}

	
}

/* End of file Faq_model.php */
/* Location: ./application/models/Faq_model.php */