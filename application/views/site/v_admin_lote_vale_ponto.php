<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Vale-pontos</title>
	<link rel="stylesheet" href="">
	<style>
	@media all
	{
		@page {
			size: A4;
			margin: 3mm 4mm 4mm 4mm;
		}
		body, html{
    		padding:0!important; 
    		margin:0 !important;
    	}
		html {
			font-family: arial, sans-serif;
			background-color: #FFF;
		}
		body {
			background-color: #F8F8F8;
			width: 100%;
		}
		
		table, th, td {
		    border: none;
		    vertical-align: bottom;
		}
		table {
			vertical-align: top;
		}
		table.dados{
			margin-top: 26.4mm;
			margin-left: 8mm;
		}

		td.numero {
			font-size: 0.75cm;
			width: 3.55cm;
			/*background-color: #333;*/
			text-align: center;
			padding-bottom: 3mm;
		}

		td.codigo {
			font-size: 0.75cm;
			width: 3.55cm;
			/*background-color: #333;*/
			padding-bottom: 1mm;
			text-align: center;
		}

		td.pontos {
			font-size: 36pt;
			text-align: center;
			vertical-align: bottom;
			padding-left: 18mm;
			width: 27mm;

		}

		td.validade {
			font-size: 10pt;
			padding-bottom: 0.7mm;
			width: 21.5mm;
			padding-left: 14mm;
		}

		td.valeponto {
			padding: 0.11cm 0.25cm;
			background-image:url("vale_pontos_v2.png");
			background-image:url("<?= base_url('assets/images/png/vale_pontos_v2.png'); ?>");
			height: 5.45cm;
			width: 9.4cm;
			background-size: 9.4cm 5.45cm;
			background-position: center;
			background-repeat: no-repeat;
			background-color: #FFF;
		}

		table.valeponto {
			display: inline-block;
		}
	}
	</style>
</head>
<body>
	<?php for ($i=$vale_ponto['nr_inicial']; $i <= $vale_ponto['nr_final']; $i++):?>
		<table class=valeponto>
			<tr>
				<td class="valeponto">
					<table class="dados">
						<tr>
							<td class="numero"><?=$i;?></td>
							<td rowspan="3" class="pontos"><?=$vale_ponto['qtd_pontos'];?></td>
						</tr>
						<tr>
							<td class="codigo"><?=consulta_codigo_vale_ponto($i);?></td>
						</tr>
						<tr>
							<td class="validade"><?=formata_data($vale_ponto['dt_fim_validade_original'],'d/m/Y');?></td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	<?php endfor; ?>
</body>
</html>