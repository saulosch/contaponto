<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vale_ponto_model extends CI_Model {

	public function consulta_vale_pontos_por_id($id_lote_vale_ponto)
	{
		$where = array('id_lote_vale_ponto' => $id_lote_vale_ponto);
		$this->db->join('loja', 'lote_vale_ponto.fk_loja = loja.id_loja');
		$this->db->select('loja.nome_fantasia,lote_vale_ponto.*');
		$query = $this->db->get_where('lote_vale_ponto', $where);
		if ($query->num_rows() > 0)
		{ 
			return $query->row_array(0);
		} else {
			return false;
		}
	}

	public function consulta_vale_pontos_por_numero_vale($numero_vale)
	{
		$where = array(
			'nr_inicial <=' => $numero_vale,
			'nr_final >=' => $numero_vale,
			'dt_ini_validade <=' => date('y-m-d'),
			'dt_fim_validade >=' => date('y-m-d'),
			'status' => 'A' );
		$query = $this->db->get_where('lote_vale_ponto', $where);

		if ($query->num_rows() > 0)
		{ 
			return $query->row_array(0);
		} else {
			return false;
		}
	}
}
/* End of file Vale_ponto_model.php */
/* Location: ./application/models/Vale_ponto_model.php */