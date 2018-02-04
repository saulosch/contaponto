<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?=$titulo?></title>

		<link rel="shortcut icon" href="<?php echo base_url('assets/images/ico/favicon.ico'); ?>">
		<link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo base_url('assets/images/ico/apple-touch-icon-144-precomposed.png'); ?>">
		<link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo base_url('assets/images/ico/apple-touch-icon-114-precomposed.png'); ?>">
		<link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo base_url('assets/images/ico/apple-touch-icon-72-precomposed.png'); ?>">
		<link rel="apple-touch-icon-precomposed" href="<?php echo base_url('assets/images/ico/apple-touch-icon-57-precomposed.png'); ?>">


	<style>
		div#loader{width: 100vw; height: 100vh; text-align: center;padding-bottom:50px; }
	</style>

	<meta property="og:url" content="https://www.contaponto.com.br" />
	<meta property="og:type" content="website" />
	<meta property="og:title" content="Contaponto - Agora em Dois Córregos" />
	<meta property="og:description" content="Ganhe descontos exclusivos nas lojas da rede e pontos para trocar por vales-compras. Cadastre-se e aproveite." />
	<meta property="og:image" content="<?= site_url('assets/images/png/modelo_face.png') ?>" />

</head>
<body>
<div id="loader">
	<p>
		<img src="<?php echo base_url('assets/images/ico/apple-touch-icon-72-precomposed.png'); ?>" alt="Carregando..." title="Carregando..." style="margin-top: 40vh ; ">
	</p>
	<p id="loading" style="font-size: large;">
		<img src="<?php echo base_url('assets/images/ico/30.gif'); ?>" alt="Carregando..." title="Carregando..." style="">
	</p>
</div>
<div id="wrapper">
	<header id="header" class="hidden-print">
		<div class="top-bar">
			<div class="container">
				<div class="row">
					<div class="col-xs-12 col-sm-4 text-center">
					<?php if ($logado): ?>
							Olá <?=$sessao_usuario['nome'] ?>, 
						<?php if($sessao_usuario['saldo']==0): ?>
							 você ainda não possui pontos.
						<?php else: ?>
							 você possui <?php echo anchor('usuario_logado/extrato', $sessao_usuario['saldo'].' pontos');?> disponíveis.
						<?php endif ?>
					<?php endif ?>
						
					</div>
					<div class="col-xs-12 col-sm-8">
					   <div class="text-right">
							<ul>
								<?php if ($logado): ?>
									<?php foreach ($menu_barra as $key => $item) : ?>
										<li><a class="bn bn-default" href="<?=base_url($key)?>"><i class="<?=$item['classe']?>"></i>&nbsp;<?=$item['label']?></a></li>
									<?php endforeach; ?>
								<?php else: ?>
									<li><a class="bn bn-default" href="<?=base_url('cadastro');?>"><i class="fa fa-pencil-square-o"></i>&nbsp;Cadastre-se</a></li>
									<li><a class="bn bn-default important" href="<?=base_url('login');?>"><i class="fa fa-sign-in"></i>&nbsp;Acessar</a></li>
								<?php endif ?>
							</ul>
					   </div>
					</div>
				</div>
			</div>
		</div>

		<nav class="navbar navbar-inverse" role="banner">
			<div class="container">
				<div class="row">
					<div class="navbar-headr col-xs-12 col-lg-4 text-center">
						<div class="row">
							<div class='col-xs-12'>
								<a class="navbar-bran text-center" href="<?=base_url()?>"><img class="block-center" src="<?=base_url('assets/images/png/logo_cp_300x53.png')?>" alt="logo"></a>
								<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
									<span class="sr-only">Toggle navigation</span>
									<span class="icon-br" style="color: #FFF;">Menu</span>
								</button>
							</div>
						</div>
					</div>
					
					<div class="collapse navbar-collapse navbar-right col-xs-12 col-lg-8">
						<ul class="nav navbar-nav">
							<?php foreach ($menu_superior as $key => $texto) : ?>
								<li class="<?=($key==$pagina_atual)?'active':''?>"><a href="<?=base_url($key)?>"><?=$texto?></a></li>	
							<?php endforeach; ?>
						</ul>
					</div>
				</div>
			</div>
		</nav>
	</header>
	<?= (isset($carousel))?$carousel:''; ?>
	<main class="container main">
