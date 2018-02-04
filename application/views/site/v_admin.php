<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="shortcut icon" href="<?php echo base_url('assets/images/ico/favicon.ico'); ?>">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

    <link href="<?php echo base_url('assets/css/admin.css'); ?>" rel="stylesheet">
<?php 
foreach($css_files as $file): ?>
	<link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
<?php endforeach; ?>
<?php foreach($js_files as $file): ?>
	<script src="<?php echo $file; ?>"></script>
<?php endforeach; ?>

</head>
<body>
	<div class="top-bar header">
		<div class="container">
			<div class="row">
				<div class="col-xs-12 col-sm-3 col-md-2">
					<a class="navbar-bran text-center" href="<?=base_url()?>"><img class="block-center" src="<?=base_url('assets\images\png\logo_cp_300x53.png')?>" alt="logo"></a>
				</div>
				<div class="col-xs-12 col-sm-9 col-md-10">
					<ul class="nav navbar-nav navbar-right navbar-collapse">
						<?php foreach ($menu_admin as $key => $texto) : ?>
							<li><a href="<?=base_url($key)?>"><?=$texto['label']?></a></li>	
						<?php endforeach; ?>
					</ul>
				</div>
			</div>
		</div>
	</div>
	<div>
		<main class='main container'>
			<?php echo $output; ?>
	    </main>
	    <div class='container'>
	    	<div class="text-right">
	    		Hora do servidor: <?=date('d/m/Y H:i')?>
	    	</div>
	    </div>
    </div>
</body>
</html>
