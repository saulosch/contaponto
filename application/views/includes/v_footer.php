	</main>
	
	<div id="push" class="hidden-print"></div>	
</div><!-- /wrapper -->

<footer id="footer" class="midnight-blue hidden-print" <?= (strpos(base_url(), 'localhost'))?'style="background-color: #ffc300"' :' ' ?> >
		<div class="container">
			<div class="row">
				<div class="col-sm-5">
				   <a target="_blank" href="http://contaponto.com.br/" >Contaponto</a>&nbsp;&reg;&nbsp;<?php echo (date('Y')==2016)?'2016':'2016 - '.date('Y'); ?> Todos&nbsp;os&nbsp;direitos&nbsp;reservados.
				</div>
				<div class="col-sm-7">
					<ul class="pull-right">
						<?php foreach ($menu_inferior as $key => $texto) : ?>
							<li class="<?=($key==$pagina_atual)?'active':''?>"><a href="<?=base_url('site/'.$key)?>"><?=$texto?></a></li>   
						<?php endforeach; ?>
					</ul>
				</div>
			</div>
		</div>
	</footer>
</body>
</html>
		
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
		<link href="<?php echo base_url('assets/css/animate.min.css?v1'); ?>" rel="stylesheet">
		<link href="<?php echo base_url('assets/css/main.min.css?v1.01'); ?>" rel="stylesheet">
		<link href="<?php echo base_url('assets/css/print.css?v1'); ?>" media="print" rel="stylesheet">
		<?php if (isset($admin_page)) : ?>
			<?php foreach($admin_page->css_files as $file): ?>
				<link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
			<?php endforeach; ?>
			<!-- JSs -->
			<?php foreach($admin_page->js_files as $file): ?>
				<script src="<?php echo $file; ?>"></script>
			<?php endforeach; ?>
		<?php endif; ?>

	<script src="<?=base_url('assets/js/jquery-2.2.3.min.js?v1');?>"></script>
	<?php if (true OR $pagina_atual == 'ganhe_pontos' OR  $pagina_atual == 'troque_seus_pontos'):  ?>
		<?php // Script que filtra a grade de cards  ?>
		<script src="<?=base_url('assets/js/isotope.pkgd.min.js?v1');?>"></script>
	<?php endif; ?>
	<script src="<?=base_url('assets/js/wow.min.js?v1');?>"></script>
	<script src="<?=base_url('assets/js/jquery.prettyPhoto.js?v1');?>"></script>
	<script src="<?=base_url('assets/js/main.min.js?v1');?>"></script>
	<script type="text/javascript">
	 // $('.carousel').carousel()
	</script>
	<script src="<?=base_url('assets/js/bootstrap.min.js?v1');?>"></script>
	<script async>
		//Google Analytics
		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

		ga('create', 'UA-80391333-1', 'auto');
		ga('send', 'pageview');
	</script>
	<script>
		$('#loader').css('display', 'none');
	</script>
