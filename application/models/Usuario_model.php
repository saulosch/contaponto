<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usuario_model extends CI_Model {

	public function carrega_campos_cadastro($tipo)
	{
		$campos_cadastro[] = array(	
			'tipo'				=> array('cadastro','cadastro_lojista','alteracao'),
			'nome_campo' 		=> 'nome',
			'tipo_campo_html' 	=> 'text',
			'label' 			=> 'Nome',
			'obrigatorio' 		=> TRUE,
			'propriedades_html' => '',
			'form_validation' 	=> 'trim|required|min_length[2]|max_length[50]',
			'placeholder' 		=> 'Ex.: José Carlos');
		
		$campos_cadastro[] = array(	
			'tipo'				=> array('cadastro','cadastro_lojista','alteracao'),
			'nome_campo' 		=> 'sobrenome',
			'tipo_campo_html' 	=> 'text',
			'label' 			=> 'Sobrenomes',
			'obrigatorio' 		=> TRUE,
			'propriedades_html' => '',
			'form_validation' 	=> 'trim|required|min_length[2]|max_length[50]',
			'placeholder' 		=> 'Ex.: Santos da Silva');
			
		$campos_cadastro[] = array(	
			'tipo'				=> array('cadastro'),
			'nome_campo' 		=> 'email',
			'tipo_campo_html' 	=> 'email',
			'label' 			=> 'E-mail',
			'obrigatorio' 		=> TRUE,
			'propriedades_html' => '',
			'form_validation' 	=> 'valid_email|trim|required|max_length[100]|is_unique[usuario.email]',
			'placeholder' 		=> 'Ex.: jose.carlos@site.com.br');

		$campos_cadastro[] = array(	
			'tipo'				=> array('cadastro_lojista'),
			'nome_campo' 		=> 'email',
			'tipo_campo_html' 	=> 'email',
			'label' 			=> 'E-mail',
			'obrigatorio' 		=> TRUE,
			'propriedades_html' => '',
			'form_validation' 	=> 'valid_email|trim|required|max_length[100]|is_unique[usuario.email]|callback__valida_email',
			'placeholder' 		=> 'Ex.: jose.carlos@site.com.br');

		$campos_cadastro[] = array(	
			'tipo'				=> array('cadastro','cadastro_lojista'),
			'nome_campo' 		=> 'cpf',
			'tipo_campo_html' 	=> 'number',
			'label' 			=> 'CPF',
			'obrigatorio' 		=> TRUE,
			'propriedades_html' => '',
			'form_validation' 	=> 'trim|exact_length[11]|required|is_natural_no_zero|callback__valida_cpf|is_unique[usuario.cpf]',
			'placeholder' 		=> 'Apenas números. Ex.: 12345678901');
			
		$campos_cadastro[] = array(	
			'tipo'				=> array('cadastro','nova_senha'),
			'nome_campo' 		=> 'senha',
			'tipo_campo_html' 	=> 'password',
			'label' 			=> 'Senha',
			'obrigatorio' 		=> TRUE,
			'propriedades_html' => '',
			'form_validation' 	=> 'required|min_length[6]',
			'placeholder' 		=> '********');

		$campos_cadastro[] = array(	
			'tipo'				=> array('cadastro','nova_senha'),
			'nome_campo' 		=> 'confirmar_senha',
			'tipo_campo_html' 	=> 'password',
			'label' 			=> 'Confirme a Senha',
			'obrigatorio' 		=> TRUE,
			'propriedades_html' => '',
			'form_validation' 	=> 'required|matches[senha]',
			'placeholder' 		=> '********');
				
		$campos_cadastro[] = array(	
			'tipo'				=> array('alteracao'),
			'nome_campo' 		=> 'email',
			'tipo_campo_html' 	=> 'email',
			'label' 			=> 'E-mail',
			'obrigatorio' 		=> TRUE,
			'propriedades_html' => 'readonly',
			'form_validation' 	=> 'trim',
			'placeholder' 		=> 'Ex.: jose.carlos@site.com.br');

		$campos_cadastro[] = array(	
			'tipo'				=> array('alteracao'),
			'nome_campo' 		=> 'cpf',
			'tipo_campo_html' 	=> 'number',
			'label' 			=> 'CPF',
			'obrigatorio' 		=> TRUE,
			'propriedades_html' => 'readonly',
			'form_validation' 	=> 'trim',
			'placeholder' 		=> 'Apenas números. Ex.: 12345678901');

		$campos_cadastro[] = array(	
			'tipo'				=> array('alteracao'),
			'nome_campo' 		=> 'senha',
			'tipo_campo_html' 	=> 'password',
			'label' 			=> 'Senha',
			'obrigatorio' 		=> FALSE,
			'propriedades_html' => '',
			'form_validation' 	=> 'min_length[6]',
			'placeholder' 		=> '********');

		$campos_cadastro[] = array(	
			'tipo'				=> array('alteracao'),
			'nome_campo' 		=> 'confirmar_senha',
			'tipo_campo_html' 	=> 'password',
			'label' 			=> 'Confirme a Senha',
			'obrigatorio' 		=> FALSE,
			'propriedades_html' => '',
			'form_validation' 	=> 'matches[senha]',
			'placeholder' 		=> '********');
			
		$campos_cadastro[] = array(
			'tipo'				=> array('cadastro','cadastro_lojista','alteracao'),
			'nome_campo' 		=> 'celular',
			'tipo_campo_html' 	=> 'text',
			'label' 			=> 'Celular',
			'obrigatorio' 		=> FALSE,
			'propriedades_html' => '',
			'form_validation' 	=> 'trim|min_length[8]|max_length[50]',
			'placeholder' 		=> 'Ex.: (14) 9 1234-5678');

		$campos_cadastro[] = array(
			'tipo'				=> array('cadastro','cadastro_lojista','alteracao'),
			'nome_campo' 		=> 'data_nascimento',
			'tipo_campo_html' 	=> 'text',
			'label' 			=> 'Data de nascimento',
			'obrigatorio' 		=> FALSE,
			'propriedades_html' => '',
			'form_validation' 	=> 'trim|min_length[8]|max_length[10]|callback__valida_data',
			'placeholder' 		=> 'Ex.: 01/01/1990');

		$campos_cadastro[] = array(	
			'tipo'				=> array('cadastro','cadastro_lojista','alteracao'),
			'nome_campo' 		=> 'cep',
			'tipo_campo_html' 	=> 'number',
			'label' 			=> 'CEP',
			'obrigatorio' 		=> TRUE,
			'propriedades_html' => '',
			'form_validation' 	=> 'trim|min_length[7]|max_length[8]|required|is_natural_no_zero',
			'placeholder' 		=> 'Apenas números. Ex.: 17300000');
			
		$campos_cadastro[] = array(	
			'tipo'				=> array('cadastro','cadastro_lojista','alteracao'),
			'nome_campo' 		=> 'genero',
			'tipo_campo_html' 	=> 'select',
			'label' 			=> 'Gênero',
			'obrigatorio' 		=> FALSE,
			'propriedades_html' => array('-' => '-',
									'M' => 'Masculino',
									'F' => 'Feminino'),
			'form_validation' 	=> 'trim|in_list[M,F,-]',
			'placeholder' 		=> 'Ex.: ');
		
		$campos_cadastro[] = array(	
			'tipo'				=> array('cadastro','cadastro_lojista','alteracao'),
			'nome_campo' 		=> 'fk_estado_civil',
			'tipo_campo_html' 	=> 'select',
			'label' 			=> 'Estado civil',
			'obrigatorio' 		=> FALSE,
			'propriedades_html' => array('0' => '-',
									'1' => 'Solteiro (a)',
									'2' => 'Casado (a)',
									'3' => 'Separado (a)',
									'4' => 'Divorciado (a)',
									'5' => 'Viúvo (a)'),
			'form_validation' 	=> 'trim|integer|less_than[6]',
			'placeholder' 		=> 'Ex.: ');

		if(isset($this->session->usuario['id_loja']) && $tipo == 'cadastro_lojista')
		{
			$this->load->model('loja_model');
			$nome_loja = $this->loja_model->consulta_loja_por_id($this->session->usuario['id_loja'])['nome_fantasia'];
			$campos_cadastro[] = array(	
				'tipo'				=> array('cadastro_lojista'),
				'nome_campo' 		=> 'fk_loja_cadastro',
				'tipo_campo_html' 	=> 'select',
				'label' 			=> 'Loja',
				'obrigatorio' 		=> TRUE,
				'propriedades_html' => array($this->session->usuario['id_loja'] => $nome_loja),
				'form_validation' 	=> 'trim|integer',
				'placeholder' 		=> '');
		}
				
		$campos_cadastro[] = array(
			'tipo'				=> array('login'),
			'nome_campo' 		=> 'email',
			'tipo_campo_html' 	=> 'email',
			'label' 			=> 'E-mail',
			'obrigatorio' 		=> TRUE,
			'propriedades_html' => 'autofocus',
			'form_validation' 	=> 'valid_email|trim|required|max_length[100]',
			'placeholder' 		=> 'E-mail - Ex.: jose.carlos@site.com.br');

		$campos_cadastro[] = array(	
			'tipo'				=> array('login'),
			'nome_campo' 		=> 'senha',
			'tipo_campo_html' 	=> 'password',
			'label' 			=> 'Senha',
			'obrigatorio' 		=> TRUE,
			'propriedades_html' => '',
			'form_validation' 	=> 'required|min_length[6]',
			'placeholder' 		=> 'Senha');

		$campos_cadastro[] = array(	
			'tipo'				=> array('reset'),
			'nome_campo' 		=> 'cpf',
			'tipo_campo_html' 	=> 'number',
			'label' 			=> 'CPF',
			'obrigatorio' 		=> TRUE,
			'propriedades_html' => '',
			'form_validation' 	=> 'trim|exact_length[11]|required|is_natural_no_zero|callback__valida_cpf',
			'placeholder' 		=> 'Apenas números. Ex.: 12345678901');

		// $campos_cadastro[] = array(	
		// 	'tipo'				=> array('nova_senha'),
		// 	'nome_campo' 		=> 'senha',
		// 	'tipo_campo_html' 	=> 'password',
		// 	'label' 			=> 'Senha',
		// 	'obrigatorio' 		=> FALSE,
		// 	'propriedades_html' => '',
		// 	'form_validation' 	=> 'min_length[6]',
		// 	'placeholder' 		=> '********');

		// $campos_cadastro[] = array(	
		// 	'tipo'				=> array('nova_senha'),
		// 	'nome_campo' 		=> 'confirmar_senha',
		// 	'tipo_campo_html' 	=> 'password',
		// 	'label' 			=> 'Confirme a Senha',
		// 	'obrigatorio' 		=> FALSE,
		// 	'propriedades_html' => '',
		// 	'form_validation' 	=> 'matches[senha]',
		// 	'placeholder' 		=> '********');
		
		$campos_cadastro[] = array(	
			'tipo'				=> array('cadastro','nova_senha'),
			'nome_campo' 		=> 'termos',
			'tipo_campo_html' 	=> 'checkbox',
			'label' 			=> 'Concordo com os termos e condições',
			'obrigatorio' 		=> TRUE,
			'propriedades_html' => '',
			'form_validation' 	=> 'required',
			'placeholder' 		=> '');
		
		$retorno = array();

		foreach ($campos_cadastro as $campo)
		{
			if (in_array($tipo, $campo['tipo']))
			{
				$retorno[] = $campo;
			}
		}
		return $retorno;
	}

	public function consulta_usuario_por_email($email)
	{
		$query = $this->db->get_where('usuario', array('email' => $email));
		if ($query->num_rows() > 0) 
		{
			return $query->row_array(0);
		}
		else
		{
			return false;
		}
	}
	
	public function consulta_usuario_por_cpf($cpf)
	{
		$query = $this->db->get_where('usuario', array('cpf' => $cpf));
		if ($query->num_rows() > 0) 
		{
			return $query->row_array(0);
		}
		else
		{
			return false;
		}
	}

	public function consulta_usuario_por_id($id_usuario)
	{
		$query = $this->db->get_where('usuario', array('id_usuario' => $id_usuario));
		if ($query->num_rows() > 0) 
		{
			return $query->row_array(0);
		}
		else
		{
			return false;
		}
	}
	
	public function insere_usuario($usuario)
	{
		$sal = sha1(uniqid(rand(),TRUE));
		if( ! isset($usuario['senha']))
		{
			$usuario['senha'] = substr(sha1(uniqid(rand(),TRUE)),mt_rand(0,10),5);
		}
		$data = array (
			'nome' => ucwords(strtolower($usuario['nome'])),
			'sobrenome' => ucwords(strtolower($usuario['sobrenome'])),
			'email' => strtolower($usuario['email']),
			'cpf' => $usuario['cpf'],
			'sal' => $sal,
			'senha' => sha1($usuario['senha'].$sal),
			'fk_tipo_acesso' => 3 , // Consumidor
			'celular' => $usuario['celular'],
			'cep' => $usuario['cep'],
			'data_nascimento' => formata_data($usuario['data_nascimento'],'Y-m-d',NULL),
			'genero' => $usuario['genero'], 
			'fk_estado_civil' => $usuario['fk_estado_civil'],
			'fk_loja_cadastro' => (isset($usuario['fk_loja_cadastro']))?$usuario['fk_loja_cadastro']:NULL ,
			'ts_criacao' => date('Y-m-d H:i:s'),
		);
		$this->db->insert('usuario', $data);
		return $this->db->insert_id();
	}

	public function ativa_usuario($email,$sal)
	{
		$data = array('ativo' => 'A');

		$this->db->where('email', $email);
		$this->db->where('sal', $sal);
		$this->db->where('ativo !=', 'A');

		$this->db->update('usuario', $data); 

		return $this->db->affected_rows();
	}

	public function altera_usuario($usuario)
	{	
		//se foi solicitado trocar a senha, o sal anterior deve ser informado obrigatoriamente
		
		if (isset($usuario['senha']) === isset($usuario['sal'])) 
		{

			foreach ($usuario as $key => $value) {
				
				if ( ! in_array($key, array('email','senha','sal'))) 
				{
					
					$data[$key] = $value;
				
				} elseif ($key == 'senha') 
				{
					$data['sal'] = sha1(uniqid(rand(),TRUE));
					$data['senha'] = sha1($value.$data['sal']);
					$data['ativo'] = 'A';
				}
			}

			$this->db->where('email', $usuario['email']);
			//garante que o sal anterior esteja correto para alterar a senha.
			if (isset($usuario['senha'])) 
			{
				$this->db->where('sal', $usuario['sal']);
			}
			if (!$this->db->update('usuario', $data)) 
			{
				return false;
			}
			else
			{
				return $this->db->affected_rows() ;
			} 
		}
		else
		{		
			return false;
		}

		/// adicionar validacao de insert 
	}

	public function registra_login($campo_where,$valor_where)
	{
		
		if (in_array($campo_where, array('id_usuario','email','cpf'))) 
		{
			
			$this->db->where($campo_where, $valor_where);
			
			if ( ! $this->db->update('usuario', array('ts_ult_acesso' => date('Y-m-d H:i:s')))) 
			{
				return false;
			}
			else
			{
				return $this->db->affected_rows();
			} 
		}
		else
		{		
			return false;
		}	
	}

	public function atualiza_saldo_usuario($id_usuario)
	{
		if ($id_usuario === 0)
		{
			$sql = 'UPDATE usuario u 
					SET u.saldo = (	SELECT sum(p.qtd_disponivel) 
					   				FROM pontuacao p
					   				WHERE p.fk_usuario = u.id_usuario
									AND p.validade_pontos >= CURDATE()
									AND p.ts_estorno IS NULL
									group by p.fk_usuario
									 )';
		}
		else
		{
			$sql = 'UPDATE usuario u 
					SET u.saldo = (	SELECT sum(p.qtd_disponivel) 
					   				FROM pontuacao p
					   				WHERE p.fk_usuario = u.id_usuario
									AND p.validade_pontos >= CURDATE()
									AND p.ts_estorno IS NULL
									group by p.fk_usuario
									 )
					WHERE u.id_usuario = ?';
		}

		if ($this->db->query($sql, array($id_usuario)))
		{
			return $this->db->affected_rows();
		}
		else
		{	
			return false;
		}
	}

	public function consulta_usuarios_por_loja($id_loja, $dias_atras = 60)
	{
	 	$retorno ['campos'] = array (
	  		'nome' => 'Nome',
	  		'sobrenome' => 'Sobrenome', 
	  		'email' => 'E-mail',
	  		'cpf' => 'CPF',
	  		'data_nascimento' => 'Data Nascimento',
	  		'genero' => 'Gênero',
	  		'estado_civil' => 'Estado Civil',
	  		'ts_criacao' => 'Data de Cadastro',
  		);

	  	$select = 'nome,sobrenome,email,cpf,data_nascimento,genero,estado_civil.estado_civil,ts_criacao';
	  	$this->db->join('estado_civil', 'usuario.fk_estado_civil = estado_civil.id_estado_civil','left');
	  	
		$this->db->select($select);

		$where = array('usuario.fk_loja_cadastro' => $id_loja,
						'usuario.ts_criacao >=' => date('Y-m-d',time()-$dias_atras*86400) ,// 60 dias (1 dia = 86.400 segundos)
						); 

		$this->db->order_by('usuario.id_usuario DESC');

		$query = $this->db->get_where('usuario', $where);

		if ($query->num_rows() > 0) 
		{ 
			$retorno['dados'] = $query->result_array();
			return $retorno;
		} else {
			return false;
		}
	}

	public function consulta_ultimos_usuarios_por_dias($dias_atras = 10)
	{
	 	$select = 'usuario.*, estado_civil.estado_civil, loja.nome_fantasia';
	  	$this->db->join('estado_civil', 'usuario.fk_estado_civil = estado_civil.id_estado_civil','left');
	  	$this->db->join('loja', 'usuario.fk_loja_cadastro = loja.id_loja','left');
	  	
		$this->db->select($select);

		$where = array(
			'usuario.ts_criacao >=' => date('Y-m-d',time()-$dias_atras*86400),// 60 dias (1 dia = 86.400 segundos)
		);

		$this->db->order_by('usuario.id_usuario DESC');

		$query = $this->db->get_where('usuario', $where);

		if ($query->num_rows() > 0) 
		{ 
			return $query->result_array();
		} else {
			return false;
		}
	} 

	//Extrai dados sinteticos de quantidade usuarios por CEP
	public function consulta_usuarios_por_cep()
	{
		$select = 'cep,ativo,count(1) as qtd';
		$this->db->select($select);
	  	
	  	$this->db->group_by('cep,ativo');
	  	
		$where = array(
			'fk_tipo_acesso' => 3,
		);

		$this->db->order_by('cep,ativo');

		$query = $this->db->get_where('usuario', $where);

		if ($query->num_rows() > 0) 
		{ 
			return $query->result_array();
		} else {
			return false;
		}
	} 

	public function consulta_usuarios_exportacao()
	{
	 	$select = 'nome, sobrenome, email, cpf, data_nascimento, genero, ativo, estado_civil.estado_civil, ts_criacao, fk_tipo_acesso, tipo_acesso.nome_tipo_acesso, ts_ult_acesso, saldo, sal, fk_loja_cadastro';
	  	$this->db->join('estado_civil', 'usuario.fk_estado_civil = estado_civil.id_estado_civil','left');
	  	$this->db->join('tipo_acesso', 'usuario.fk_tipo_acesso = tipo_acesso.id_tipo_acesso');
	  	
		$this->db->select($select);

		$this->db->order_by('usuario.id_usuario ASC');

		$query = $this->db->get('usuario');

		if ($query->num_rows() > 0) 
		{ 
			return $query->result_array();
		} else {
			return false;
		}
	} 

	public function atualiza_ultima_cidade_usuario($id_usuario, $id_cidade)
	{
		$this->db->where('id_usuario', $id_usuario);
			
		if (!$this->db->update('usuario', array('ultima_fk_cidade' => $id_cidade))) 
		{
			return false;
		}
		else
		{
			return $this->db->affected_rows();
		} 
	}

}
/* End of file Usuario_model.php */
/* Location: ./application/models/Usuario_model.php */