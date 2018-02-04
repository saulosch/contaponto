<section id="portfolio">
	<div class="container">
		<?php if ($lojas): ?>
			<div class="row">
				<div class="col-xs-12 col-sm-9">
					<div class="center">
						<h2>Rede de lojas</h2>
						<p class="lead">
							Conheça as lojas parceiras da Contaponto Rede de Fidelidade. <br>
							Lembre-se que você ganha 20 pontos para cada R$ 1 de desconto que receber!
						</p>
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
							<a href="<?= base_url('ganhe_pontos?cidade=0') ?>" alt="">Escolher outra</a>
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

			<div class="row">
				<div class="portfolio-items">
					<?php foreach ($lojas as $loja) : ?>
						<div class="portfolio-item <?=$loja['codigo_bairro'].' '.$loja['codigo_segmento']?> col-xs-12 col-sm-4 col-md-3">
							<div class="recent-work-wrap well well-sm">
								<img class="img-responsive" src="<?=base_url('assets/uploads/files/'.$loja['logo'])?>" alt="">
								<div class="overlay1">
									<div class="recent-work-inner">
										<h2 class="nome_empresa"><?=$loja['nome_fantasia']?></h2>
										<?php if (isset($loja['slogan'])) : ?>
											<p><?=$loja['slogan']?></p>
										<?php endif; ?>
										<hr class="small">
										<p>
											<small><?=$loja['nome_segmento']?></small><br>
											<?=$loja['endereco']?> <?=($loja['complemento'])?'- '.$loja['complemento']:''?> - <?=$loja['bairro']?> 
											<a href="<?=$loja['endereco_mapa']?> " target="_blank"><span style="font-size: smaller;" >(Mapa)</span></a>

											<br /> 
											<?=$loja['nome_cidade']?> - <?=$loja['uf']?> <br />
											<?=($loja['telefone'])?$loja['telefone'].'<br />':''?>
											<?php if ($loja['site']): ?>
												<a href="<?=$loja['site']?>" target="_blank">Acesse o site</a>
											<?php endif; ?>
										</p>
										<p class=""><strong>Horário de funcionamento:</strong><br /> <?=str_replace('/', '<br>', $loja['horario_funcionamento'])?> </p>
										
										<?php if ( $loja['percentual_desconto'] > 0 ): ?>
											<h2 class="text-center" style='color:#000;'><strong><?=$loja['percentual_desconto']?>% </strong> de desconto <br /></h2>
											
											<p class="text-center" style="font-size: smaller;"> <?= $loja['restricao'] ?></p>
										<?php else: ?>
												<h4 class="text-center">Em breve</h4>
											<?php endif; ?>
										
										<?php // Removido da lógica devido a implantação do novo modelo de descontos ?>
										<?php if ( false ): ?>
											<?php if ( $loja['qtd_pontos'] > 0 ): ?>
												<?php if(strlen($loja['item_em_compras']) == 0): ?>
													<h4 class="text-center">Acumule <strong><?=$loja['qtd_pontos']?> pontos</strong> <br /> a cada <strong>R$ <?= str_replace('.',',',$loja['valor_em_compras'])?></strong></h4>
												<?php else: ?>
													<h4 class="text-center">Acumule <strong><?=$loja['qtd_pontos']?> pontos</strong> <br /> <?=$loja['item_em_compras']?></h4>
												<?php endif; ?>

												<p class="text-center" style="font-size: smaller;"> <?= $loja['restricao'] ?></p>
											<?php else: ?>
												<h4 class="text-center">Acumule pontos em breve.</h4>
											<?php endif; ?>
										<?php endif; ?>
										<?php // FIM - Removido da lógica devido a implantação do novo modelo de desscontos ?>
										

										<?php /* 
										<!-- <a class="preview" href="" rel="prettyPhoto"><i class="fa fa-eye"></i> Mais detalhes</a> -->
										*/ ?>
									</div> 
								</div>
							</div>
						</div><!--/.portfolio-item-->
					<?php endforeach; ?>				
				</div>
			</div>
			<div class="text-center">
				<p><small>Imagens meramente ilustrativas. Descontos não cumulativos com outras promoções/descontos das lojas. Descontos e pontuação devem ser concedidos no momento do pagamento. Descontos não aplicáveis para pagamentos de parcela (fiado/prazo/crediário) em atraso. Confira a aplicabilidade do desconto diretamente nos estabelecimentos antes de efetuar a compra.</small></p>
			</div>
		<?php else: ?>
			<div class="row">
				<div class="col-xs-12">
					<div class="center">
			   			<h2>Rede de lojas</h2>
			   			<p class="lead">Em breve, aqui você poderá conferir a lista dos comércios onde, ao comprar, você receberá descontos e acumulará pontos.</p>
			   
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
								<a href="<?= base_url('ganhe_pontos?cidade=0') ?>" alt="">Escolher outra</a>
							</small>
						</div>
					</div>
				</div>
			</div>
		<?php endif; ?>
	</div>
</section><!--/#portfolio-item-->

		