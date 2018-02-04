<section id="portfolio">
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<h2>Escolha uma localidade: </h2>
			</div>
			<?php $proxima_pagina.='?cidade='; ?>
			<?php foreach ($cidades as $key => $row): ?>
				<div class="col-xs-12 col-sm-4 text-center">
					<p>
						<a class="btn btn-primary btn-lg" href="<?php echo base_url($proxima_pagina.$row['id_cidade']) ?>"><?php echo $row['nome_cidade'] ?></a>
					</p>
				</div>
			<?php endforeach ?>
		</div>
	</div>
</section><!--/#portfolio-item-->

		