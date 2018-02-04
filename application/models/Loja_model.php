<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Loja_model extends CI_Model {

	/**
	* Retorna as lojas de uma determinada cidade em que haja acúmulo de pontos,
	* ou seja, valor_em_compras e qtd_pontos devem ser maior que 0.
	 */
	public function carrega_lojas_acumulo_por_cidade($cidade)
	{
		$this->db->select('*');
		$this->db->from('loja');
		$this->db->join('segmento', 'loja.fk_segmento = segmento.id_segmento');
		$this->db->join('cidade', 'loja.fk_cidade = cidade.id_cidade');
		$this->db->join('banco', 'loja.fk_banco = banco.id_banco');
		$this->db->where(array(
			'loja.valor_em_compras >' => 0,
			// 'loja.qtd_pontos >' => 0,
			'loja.status' => 'A',
			'loja.fk_cidade' => $cidade,
		));
		$query = $this->db->get();

		return $query->result_array();
	}

	public function consulta_loja_por_id($id_loja)
	{
		$query = $this->db->get_where('loja', array('id_loja' => $id_loja));
		if ($query->num_rows() > 0) 
		{
			return $query->row_array(0);
		} else {
			return false;
		}
	}

	public function consulta_loja_relacionada_por_id_usuario($id_usuario)
	{	
		$where = array(	'fk_id_usuario' => $id_usuario,
						'ts_ini_validade <=' => date("Y-m-d H:i:s"),
						'ts_fim_validade >=' => date("Y-m-d H:i:s"));
		$query = $this->db->get_where('usuario_loja', $where);
		if ($query->num_rows() > 0) 
		{
			return $query->row_array(0)['fk_id_loja'];
		} else {
			return false;
		}
	}

	public function consulta_lojistas_por_loja($id_loja)
	{
		$this->db->select('*');
		$this->db->from('usuario_loja');
		$this->db->join('usuario', 'usuario.id_usuario = usuario_loja.fk_id_usuario');

		$this->db->where(array(
			'usuario_loja.fk_id_loja' => $id_loja,
		));
		$query = $this->db->get();

		return $query->result_array();

	}
}

/* End of file Loja_model.php */
/* Location: ./application/models/Loja_model.php */