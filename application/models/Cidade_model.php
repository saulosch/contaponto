<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cidade_model extends CI_Model {

	public function consulta_cidade_por_id($id_cidade)
	{
		$query = $this->db->get_where('cidade', array('id_cidade' => $id_cidade, 'eh_ativa' => 'S'));
		if ($query->num_rows() > 0) 
		{
			return $query->row_array(0);
		} else {
			return false;
		}
	}

	public function consulta_cidades()
	{
		$this->db->order_by('nome_cidade ASC');
		$query = $this->db->get_where('cidade', array('eh_ativa' => 'S'));
		if ($query->num_rows() > 0) 
		{
			return $query->result_array(0);
		} else {
			return false;
		}
	}
}

/* End of file Cidade_model.php */
/* Location: ./application/models/Cidade_model.php */