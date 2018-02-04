<section id="portfolio">
	<div class="container">
		<div class="row">
			<div class="col-xs-12 text-center">
				<h1 class="black"><?=$cupom['titulo_cupom']?></h1>
			</div>
		</div>
		<div class="space-10 row ">		
			<div class="col-xs-12 col-sm-9">
				<div class="row">
					<div class="col-xs-10 col-xs-offset-1 col-sm-12 col-sm-offset-0 space-10">
						<img class="block-center thumbnail" src="<?=base_url('assets/uploads/files/'.$cupom['imagem_principal'])?>" alt="">
					</div>
					
					<div class="col-xs-12">
						<div class="row space-10">
							<div class="col-xs-2 text-center ">
								<p class="lead icon"><i class="fa fa-tags" aria-hidden="true"></i></p>
							</div>
							<div class="col-xs-10">
								<p><?=$cupom['nome_segmento']?></p>
							</div>
						</div>
						<div class="row space-10">
							<div class="col-xs-2 text-center ">
								<p class="lead icon"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></p>
							</div>
							<div class="col-xs-10">
								<p><?=$cupom['breve_descricao']?></p>
							</div>
						</div>
						<div class="row space-10">
							<div class="col-xs-2 text-center">
								<p class="lead icon"><i class="fa fa-calendar" aria-hidden="true"></i></p>
							</div>
							<div class="col-xs-10">
								<p>Cupom válido por <?=$cupom['validade_dias']?> dias após emitido.</p>
							</div>
						</div>
						<div class="row space-10">
							<div class="col-xs-2 text-center">
								<p class="lead icon"><i class="fa fa-money" aria-hidden="true"></i></p>
							</div>
							<div class="col-xs-10">
								<p>Adquira por <?=$cupom['preco_pontos']?> pontos</p>
							</div>
						</div>
						<div class="row space-10">
							<div class="space-10 col-xs-12 ">
								<p><?=$cupom['descricao_detalhada']?></p>
							</div>
						</div>	
						<div class="row space-10">
							<div class="space-10 col-xs-12 ">
							<?php if(($cupom['preco_pontos']<=$saldo)): ?>
								<p>Você possui os pontos necessários para emitir este cupom. Clique no botão abaixo para emiti-lo por <?=$cupom['preco_pontos']?> pontos.</p>
								<h4 class="text-center"><a href="<?=base_url('usuario_logado/emitir_cupom/'.$cupom['id_cupom'])?>" class="btn btn-info btn-lg col-xs-12 col-xs-offset-0 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4">Emitir Cupom</a></h4>
							<?php else: ?>
								<p class="alert alert-warning">Você ainda não possui os pontos necessários para emitir este cupom. Acumule mais pontos comprando na rede de lojas parceiras. <a href="<?=base_url('ganhe_pontos')?>">Clique aqui</a> para ver a lista das lojas parceiras ou escolha outro cupom.</p>
								<h4 class="text-center"><a href="#" class="btn btn-danger btn-lg disabled col-xs-12 col-xs-offset-0 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4">Emitir Cupom</a></h4>
							<?php endif; ?>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-xs-12 col-sm-3">
				<div class="well well-sm">
					<img class="img-responsiv" src="<?=base_url('assets/uploads/files/'.$cupom['logo'])?>" alt="">
					<h2><?=$cupom['nome_fantasia']?></h2>
					<p><strong><?=$cupom['nome_segmento']?></strong></p>
					<p><?=$cupom['endereco']?> <?=($cupom['complemento'])?'- '.$cupom['complemento']:''?> - <?=$cupom['bairro']?> 
					<a href="https://www.google.com.br/maps/place/<?=str_replace(' ', '+', $cupom['endereco'].' - '.$cupom['bairro'].', '.$cupom['nome_cidade'].' - '.$cupom['uf'].', '.$cupom['cep']);?> " target="_blank"><span style="font-size: smaller;" >(Mapa)</span></a>

					<br /> 
					<?=$cupom['nome_cidade']?> - <?=$cupom['uf']?> <br />
					<?=($cupom['telefone'])?$cupom['telefone'].'<br />':''?>
					<a href="<?=$cupom['site']?>" target="_blank"><?=$cupom['site']?> </a></p>
					<p class="text-small"><strong>Horário de funcionamento:</strong><br /> <?=$cupom['horario_funcionamento']?> </p>
				</div>
			</div>	
		</div>
		<div class="text-center">
			<div class="row">
				<div class="col-xs-12 col-sm-6">
					<h4 class="text-center"><a onclick="window.history.back();" class="btn btn-default col-xs-8 col-xs-offset-2 col-sm-6 col-sm-offset-0"><< Voltar</a></h4>
				</div>
				<div class="space-10 col-xs-8 col-xs-offset-2 col-sm-6 col-sm-offset-0">
					<p class="text-right"><small>Imagens meramente ilustrativas</small></p>
				</div>
			</div>
			

			
		</div>
	</div>
</section><!--/#portfolio-item-->

		