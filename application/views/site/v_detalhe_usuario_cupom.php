<section id="portfolio">
	<div class="container">
		<div class="row">		
			<div class="col-xs-12 cupom_impressao">
				<div class="well">
					<div class="row">
						<div class="col-xs-4 col-xs-offset-4 text-center">
							<h2><img src="<?= base_url('assets/images/png/logo_cp_300x53.png'); ?>"></h2>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12 text-center">
							<h2 class="black">Cupom: <?=$cupom['titulo_cupom']; ?></h2>
						</div>
					</div>

					<div class="row">
						<div class="col-xs-1 text-center">
							<p class="lead icon"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></p>
						</div>
						<div class="col-xs-11 space-10">
							<p><?=$cupom['breve_descricao']?></p>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-1 text-center">
							<p class="lead icon"><i class="fa fa-calendar" aria-hidden="true"></i></p>
						</div>
						<div class="col-xs-11 space-10">
							<p>Cupom válido até <b><?= formata_data($cupom['dt_validade'],'d/m/Y','Erro: veja no site a data de validade.') ?></b>.</p>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-1 text-center">
							<p class="lead icon"><i class="fa fa-user" aria-hidden="true"></i></p>
						</div>
						<div class="col-xs-11 space-10">
							<p> <b><?=$cupom['nome']?> <?=$cupom['sobrenome']?></b> (CPF: <?=$cupom['cpf']?>)</p>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-1 text-center">
							<p class="lead icon"><i class="fa fa-hashtag" aria-hidden="true"></i></p>
						</div>
						<div class="col-xs-11 space-10">
							<p> Número do cupom: <span class="codigo_cupom"><?=$cupom['id_usuario_cupom']?></span></p>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-1 text-center">
							<p class="lead icon"><i class="fa fa-unlock-alt" aria-hidden="true"></i></p>
						</div>
						<div class="col-xs-11 space-10">
							<p> Código do cupom: <span class="codigo_cupom"><?=$cupom['codigo_cupom']?></span></p>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-1 text-center">
							<p class="lead icon"><i class="fa fa-shopping-bag" aria-hidden="true"></i></p>
						</div>
						<div class="col-xs-11">
							<p> <b><?=$cupom['nome_fantasia']?></b> <br />
								<?=$cupom['endereco']?> <?=($cupom['complemento'])?'- '.$cupom['complemento']:''?> - <?=$cupom['bairro']?> - <?=$cupom['nome_cidade']?> - <?=$cupom['uf']?> <?=($cupom['telefone'])?' / Tel: '.$cupom['telefone'].'<br />':''?></p>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12">
							<p><?=$cupom['descricao_detalhada']?></p>
						</div>
					</div>	
				</div>
			</div>
		</div>
	</div>
</section><!--/#portfolio-item-->
<div class="text-center hidden-print">
	<?=(isset($rodape)?$rodape:'') ?>
</div>		