<section id="portfolio">
	<div class="container">
		<?php if ($cupons): ?>
			<div class="row">
				<div class="col-xs-12 col-sm-9">
					<div class="center">
					   <h2>Troque seus pontos</h2>
					   <p class="lead">Confira abaixo a lista dos cupons que você pode emitir e apresentar nas lojas parceiras da Contaponto Rede de Fidelidade.</p>
					</div>
				</div>
				<div class="col-xs-12 col-sm-3 line-left">
					<div class="center" style="margin-top:15px;">
						<h3>Localidade atual:</h3>
						<h3>
							<b>
								<?php echo $cidade_atual['nome_cidade'].' - '.$cidade_atual['uf'] ; ?>
							</b>
						</h3>
						<small>
							<a href="<?= base_url('troque_seus_pontos?cidade=0') ?>" alt="">Escolher outra</a>
						</small>
					</div>
				</div>
			</div>
			<ul class="portfolio-filter text-center" data-filter-group="color">
				<li><a class="btn btn-xs btn-default active" href="#" data-filter="">Todos os segmentos</a></li>
				<?php foreach ($segmentos as $key => $value) : ?>
					<li><a class="btn btn-xs btn-default" href="#" data-filter=".<?=$key?>"><?=$value?></a></li>
				<?php endforeach; ?>
			</ul><!--/#portfolio-filter-->

			<ul class="portfolio-filter text-center" data-filter-group="size"> 
				<li><a class="btn btn-xs btn-default active" href="#" data-filter="">Todos os bairros</a></li>
				<?php foreach ($bairros as $key => $value) : ?>
					<li><a class="btn btn-xs btn-default" href="#" data-filter=".<?=$key?>"><?=$value?></a></li>
				<?php endforeach; ?>
			</ul><!--/#portfolio-filter-->

			<ul class="portfolio-filter text-center" data-filter-group="qty"> 
				<li><a class="btn btn-xs btn-default active" href="#" data-filter="">Todos os pontos</a></li>
				<?php foreach ($pontos as $key => $value) : ?>
					<li><a class="btn btn-xs btn-default" href="#" data-filter=".<?=$key?>"><?=$value?></a></li>
				<?php endforeach; ?>
			</ul><!--/#portfolio-filter-->

			<div class="row">
				<div class="portfolio-items">
					<?php foreach ($cupons as $cupom) : ?>
						<div class="portfolio-item <?=$cupom['codigo_bairro'].' '.$cupom['codigo_segmento'].' p'.$cupom['preco_pontos']?> col-xs-12 col-sm-4 col-md-3">
							<div class="recent-work-wrap well well-sm">
								<img class="img-responsive" src="<?=base_url('assets/uploads/files/'.$cupom['imagem_principal'])?>" alt="">
								<div class="overlay1">
									<div class="recent-work-inner">
										<h4><a href="<?=base_url('usuario_logado/detalhe_cupom/'.$cupom['id_cupom'])?>"><?=$cupom['titulo_cupom']?></a></h4>
										<p><strong><?=$cupom['nome_fantasia']?></strong><br />
										<a href="https://www.google.com.br/maps/place/<?=$cupom['endereco_mapa']?> " target="_blank"><i class="fa fa-map-marker"></i> <?=$cupom['bairro']?></a><br />
										<?=$cupom['nome_segmento']?></p>
										<p><?=$cupom['breve_descricao']?></p>
										<h4 class="text-center"><a href="<?=base_url('usuario_logado/detalhe_cupom/'.$cupom['id_cupom'])?>" class="btn btn-<?php echo ( ! isset($this->session->usuario['saldo']) OR $this->session->usuario['saldo']>=$cupom['preco_pontos'])?'info':'danger'; ?> ">Adquira por <strong><?=$cupom['preco_pontos']?> pontos</strong></a></h4>
										<!-- <a class="preview" href="assets/images/portfolio/full/item1.png" rel="prettyPhoto"><i class="fa fa-eye"></i> Mais detalhes</a> -->
									</div> 
								</div>
							</div>
						</div><!--/.portfolio-item-->
					<?php endforeach; ?>				
				</div>
			</div>
			<div class="text-center">
				<p><small>Imagens meramente ilustrativas. Os valores dos cupons devem ser utilizados em uma única compra. Não será devolvido troco ou contra-vale caso a compra não atinja o valor do cupom.</small></p>
			</div>
		<?php else: ?>
			<div class="center">
			   <h2>Troque seus pontos - em breve</h2>
			   <p class="lead">Em breve, aqui você poderá conferir a lista dos cupons que você poderá emitir e apresentar nas lojas parceiras da Contaponto Rede de Fidelidade.</p>
			</div>
			<div class="col-xs-12">
						<div class="center" style="margin-top:15px;">
							<h3>Localidade atual:</h3>
							<h3>
								<b>
									<?php echo $cidade_atual['nome_cidade'].' - '.$cidade_atual['uf'] ; ?>
								</b>
							</h3>
							<small>
								<a href="<?= base_url('troque_seus_pontos?cidade=0') ?>" alt="">Escolher outra</a>
							</small>
						</div>
					</div>
		<?php endif; ?>
	</div>
</section><!--/#portfolio-item-->

		