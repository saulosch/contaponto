<?php //! defined('BASEPATH') OR exit('Only direct script access allowed');

class Faturamento extends CI_Controller {

	public function processa_faturamento()
	{	
		echo "Inicio<br />";
		$this->load->model('faturamento_model');
		$this->faturamento_model->processa_faturamento();
		echo "Fim<br />";
	}

}

/* End of file Faturamento.php */
/* Location: ./application/Faturamento.php */