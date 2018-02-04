<?php //! defined('BASEPATH') OR exit('Only direct script access allowed');

class Jobs extends CI_Controller {

	public function processa_faturamento()
	{	
		echo "Inicio<br />";
		$this->load->model('faturamento_model');
		$this->faturamento_model->processa_faturamento();
		echo "Fim<br />";
	}

	public function gera_relatorio_executivo($qtd_dias = 5)
	{
		$this->gera_relatorio_executivo_de_pontuacao($qtd_dias);
		$this->gera_relatorio_executivo_de_cadastros(1);
		$this->gera_relatorio_executivo_de_cadastros_por_cep();
	}
	
	public function gera_relatorio_executivo_de_pontuacao($qtd_dias = 5)
	{	
		echo "Inicio<br />";
		
		$this->load->model('usuario_model');
		$this->load->model('pontuacao_model');

		//Quantidade de dias para exibição do relatório
		$data['qtd_dias'] = $qtd_dias;
		for ($i=1; $i <= $data['qtd_dias'] ; $i++) { 
			$data['datas'][] = date('Y-m-d',time()-$i*86400);
		}

		// Usuarios cadastrados nos ultimos dias por empresa
		$usuarios = $this->usuario_model->consulta_ultimos_usuarios_por_dias($data['qtd_dias']); 
		//$data['usuarios_por_dia'] = array();
		
		if  (is_array($usuarios)) foreach ($usuarios as $usuario)
		{
			$loja = ($usuario['nome_fantasia'])?$usuario['nome_fantasia']:'-';
			$dia = date('Y-m-d',strtotime($usuario['ts_criacao']));
			if (isset($data['usuarios_por_dia']['dados'][$loja][$dia]))
			{
				$data['usuarios_por_dia']['dados'][$loja][$dia] += 1;
			}
			else
			{
				$data['usuarios_por_dia']['dados'][$loja][$dia] = 1;
			}
			$data['usuarios_por_dia']['totais'][$loja] = (isset($data['usuarios_por_dia']['totais'][$loja]))?$data['usuarios_por_dia']['totais'][$loja] + 1 : 1;
			$data['usuarios_por_dia']['totais'][$dia] = (isset($data['usuarios_por_dia']['totais'][$dia]))?$data['usuarios_por_dia']['totais'][$dia] + 1 : 1;

		}
		
		if (isset($data['usuarios_por_dia']) && is_array($data['usuarios_por_dia']))
			arsort($data['usuarios_por_dia']);
		
		// Pontos concedidos nos últimos dias
		$pontos = $this->pontuacao_model->consulta_pontuacao_nos_ultimos_dias($data['qtd_dias']);

		if (is_array($pontos)) foreach ($pontos as $ponto)
		{
			if ($ponto['nome_fantasia'])
			{
				$loja = $ponto['nome_fantasia'];
			}
			elseif ($ponto['fk_lote_vale_ponto'])
			{
				$loja = '0 - Vale Pontos';
			}
			elseif ($ponto['fk_usuario_cupom'])
			{
				$loja = '0 - Estorno de Cupom';
			}
			else	
			{
				$loja = '0 - Erro';
			}
			$dia = date('Y-m-d',strtotime($ponto['ts_pontuacao']));
			if (isset($data['pontuacao_por_dia']['dados'][$loja][$dia]))
			{
				$data['pontuacao_por_dia']['dados'][$loja][$dia]['qtd_usuarios'] += 1;	
				$data['pontuacao_por_dia']['dados'][$loja][$dia]['qtd_pontos'] += $ponto['qtd_pontos'];
			}
			else
			{
				$data['pontuacao_por_dia']['dados'][$loja][$dia]['qtd_usuarios'] = 1;	
				$data['pontuacao_por_dia']['dados'][$loja][$dia]['qtd_pontos'] = $ponto['qtd_pontos'];
			}
		
			//calcula o total de usuarios que receberam a pontuacao por empresa e por dia
			$data['pontuacao_por_dia']['totais']['qtd_usuarios'][$loja] = (isset($data['pontuacao_por_dia']['totais']['qtd_usuarios'][$loja]))?$data['pontuacao_por_dia']['totais']['qtd_usuarios'][$loja] + 1 : 1;
			$data['pontuacao_por_dia']['totais']['qtd_usuarios'][$dia] = (isset($data['pontuacao_por_dia']['totais']['qtd_usuarios'][$dia]))?$data['pontuacao_por_dia']['totais']['qtd_usuarios'][$dia] + 1 : 1;

			//calcula o total de pontos concedidos por empresa e por dia
			$data['pontuacao_por_dia']['totais']['qtd_pontos'][$loja] = 
			(isset($data['pontuacao_por_dia']['totais']['qtd_pontos'][$loja]))
			?$data['pontuacao_por_dia']['totais']['qtd_pontos'][$loja] + $ponto['qtd_pontos'] : $ponto['qtd_pontos'];
			$data['pontuacao_por_dia']['totais']['qtd_pontos'][$dia] = (isset($data['pontuacao_por_dia']['totais']['qtd_pontos'][$dia]))?$data['pontuacao_por_dia']['totais']['qtd_pontos'][$dia] + $ponto['qtd_pontos'] : $ponto['qtd_pontos'];
		}
		if (isset($data['pontuacao_por_dia']) && is_array($data['pontuacao_por_dia']))
			arsort($data['pontuacao_por_dia']);

		$mensagem = $this->load->view('emails/v_email_relatorio_executivo',$data, TRUE);

		/*echo "<pre>";
		print_r($data);
		echo "</pre>";*/

		$para_email = 'saulo@contaponto.com.br,rampazzo_calcados@hotmail.com';
		$assunto = 'Relatório executivo - '.date('d/m/Y');
		$de_nome = 'Relatorios Contaponto';
		$de_email = null;
		$reply_to_email = '';
		$reply_to_nome = '';
		$mailtype = 'html';
		
		envia_email($para_email,$assunto,$mensagem,$de_nome,$de_email,$reply_to_email,$reply_to_nome,$mailtype);

		echo 'fim';

	}

	public function gera_relatorio_executivo_de_cadastros($qtd_dias = 2)
	{	
		echo "Inicio<br />";

		$this->load->model('usuario_model');

		//Quantidade de dias para exibição do relatório
		$data['qtd_dias'] = $qtd_dias;
		for ($i=1; $i <= $data['qtd_dias'] ; $i++) { 
			$data['datas'][] = date('Y-m-d',time()-$i*86400);
		}

		// Usuarios cadastrados nos ultimos dias por empresa
		$data['usuarios'] = $this->usuario_model->consulta_ultimos_usuarios_por_dias($data['qtd_dias']); 
		
		$mensagem = $this->load->view('emails/v_email_relatorio_cadastros',$data, TRUE);

		/*echo "<pre>";
		print_r($data);
		echo "</pre>";
		print_r($mensagem);*/

		$para_email = 'saulo@contaponto.com.br,rampazzo_calcados@hotmail.com,suzana_schmidt@hotmail.com
';
		$assunto = 'Relatório de cadastros - '.date('d/m/Y');
		$de_nome = 'Relatórios Contaponto';
		$de_email = null;
		$reply_to_email = '';
		$reply_to_nome = '';
		$mailtype = 'html';
		
		envia_email($para_email,$assunto,$mensagem,$de_nome,$de_email,$reply_to_email,$reply_to_nome,$mailtype);

		echo 'fim';
	}

	public function gera_relatorio_executivo_de_cadastros_por_cep()
	{
		$this->load->model('usuario_model');
		$linhas = $this->usuario_model->consulta_usuarios_por_cep();


		$data['linhas']['Total']['A'] = 0;
		$data['linhas']['Total']['I'] = 0;
		$data['linhas']['Total']['total'] = 0;

		foreach ($linhas as $linha) {
			$cep = ( $linha['cep'] )? str_pad($linha['cep'], 8, '0', STR_PAD_LEFT) :'00000000';
			$cep = substr ( $cep , 0 , 5).'-'.substr ( $cep , 5 , 3);

			$data['linhas'][$cep][$linha['ativo']] = $linha['qtd'];
			$data['linhas'][$cep]['total'] = ( isset($data['linhas'][$cep]['total']) ) ? $data['linhas'][$cep]['total'] + $linha['qtd'] : $linha['qtd'];
			//soma à linha de total
			$data['linhas']['Total'][$linha['ativo']] += $linha['qtd'];		
			$data['linhas']['Total']['total'] += $linha['qtd'];		
		}

		ksort($data['linhas']);
		echo "<pre>";
		print_r($data);
		echo "</pre>";

		$mensagem = $this->load->view('emails/v_email_relatorio_cadastros_cep',$data, TRUE);

		$para_email = 'saulo@contaponto.com.br';
		$assunto = 'Relatório de cadastros CEP - '.date('d/m/Y');
		$de_nome = 'Relatórios Contaponto';
		$de_email = null;
		$reply_to_email = '';
		$reply_to_nome = '';
		$mailtype = 'html';
		
		envia_email($para_email,$assunto,$mensagem,$de_nome,$de_email,$reply_to_email,$reply_to_nome,$mailtype);
	}
}
/* End of file Jobs.php */
/* Location: ./application/Jobs.php */