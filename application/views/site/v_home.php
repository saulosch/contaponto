<div class="get-started center wow fadeInDown animated" style="visibility: visible; animation-name: fadeInDown;">
	<h2>Benefício em dobro</h2>
	<p class="lead">Comprando nas lojas parceiras da Contaponto Rede de Fidelidade, você ajuda a melhorar sua comunidade, fortalece o comércio local e ainda é recompensado por isso: receba descontos exclusivos e para cada real que economizar, ganhe 20 pontos. Troque pontos por diversos produtos e serviços. Comece a ganhar.</p>
	<div class="request">
    	<h4><a href="<?=base_url('cadastro') ?>" style="padding-right: 36px;padding-left: 36px;">Cadastre-se já!</a></h4>
	</div>
</div>

<?php  if (time() <= strtotime('2017-07-01 00:00:00')): ?>	
	<div class="clients-area center wow fadeInDown animated" style="visibility: visible; animation-name: fadeInDown;">
		<h2>Contaponto em Dois Córregos</h2>
		<div class="row">
			<div class="col-xs-12 text-center" style="color:black;"><p class="lead">Agora Dois Córregos já conta com os descontos e promoções da Contaponto Rede de Fidelidade. <a href="<?= site_url('ganhe_pontos?cidade=2') ?>">Clique aqui</a> para conhecer as lojas participantes.</p></div>
		</div>
	</div>
<?php endif ?>

<?php if (true): ?>	
	<div class="clients-area center wow fadeInDown animated" style="visibility: visible; animation-name: fadeInDown;">
		<h2>O que dizem por aí</h2>
		<!-- <p class="lead">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut <br> et dolore magna aliqua. Ut enim ad minim veniam</p> -->
	</div>

	<div class="row">
		<div class="col-md-4 wow fadeInDown animated" style="visibility: visible; animation-name: fadeInDown;">
			<div class="clients-comments text-center">
				<img src="assets/images/png/client1.png" class="img-circle" alt="">
				<h3>"Amei os descontos nas minhas compras! Logo junto os pontos para trocar por uma sapatilha. Muito boa ideia! Vou usar sempre!"</h3>
				<h4><span>-Luciana Moreira </span><br />Terapeuta Ocupacional - S. J. dos Campos</h4>
			</div>
		</div>
		<div class="col-md-4 wow fadeInDown animated" style="visibility: visible; animation-name: fadeInDown;">
			<div class="clients-comments text-center">
				<img src="assets/images/png/client2.png" class="img-circle" alt="">
				<h3>"Demais esse lance de reconhecerem a fidelidade dos clientes, já economizei bastante! Eu nunca esqueço de pedir meus pontos!"</h3>
				<h4><span>-Marlon Santos </span><br />Engenheiro - S. J. dos Campos</h4>
			</div>
		</div>
		<div class="col-md-4 wow fadeInDown animated" style="visibility: visible; animation-name: fadeInDown;">
			<div class="clients-comments text-center">
				<img src="assets/images/png/client3.png" class="img-circle" alt="">
				<h3>"Demais esse lance de reconhecerem a fidelidade dos clientes, já economizei bastante! Eu nunca esqueço de pedir meus pontos!"</h3>
				<h4><span>-Marlon Santos </span><br />Engenheiro - S. J. dos Campos</h4>
			</div>
		</div>
	</div>
<?php endif ?>