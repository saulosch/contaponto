<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Relatorio executivo</title>
	<style>
		html {
			background: #2b2b2b;
		}
		body {
		  font-family: "Helvetica Neue", Helvetica, Arial;
		  font-size: 14px;
		  line-height: 20px;
		  font-weight: 400;
		  /*color: #EEE;*/
		  -webkit-font-smoothing: antialiased;
		  font-smoothing: antialiased;
		  background: #f0f0f0;
		  max-width: 1000px;
		  margin: 20px auto;
		  padding: 15px;
		}

		.wrapper {
		  margin: 0 auto;
		  padding: 40px;
		  max-width: 800px;
		}

		table {
		  margin: 0 auto 40px auto;
		  width: 95%;
		  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
		  display: table;

		}
		@media screen and (max-width: 580px) {
		  table {
		    display: block;
		  }
		}
		
		hr {
			width: 60%;
			margin:10px auto;
		}
		.left {
			text-align: left;
		}

		.center {
			text-align: center;
		}

		.row {
		  display: table-row;
		  background: #f6f6f6;
		  color: 111;
		}
		.row:nth-of-type(odd) {
		  background: #e9e9e9;
		}
		.row.header {
		  font-weight: 900;
		  color: #ffffff;
		  background: #ea6153;
		}
		.row.green {
		  background: #27ae60;
		}
		.row.blue {
		  background: #2980b9;
		}
		.bold
		{
			font-weight: 700;
		}
		@media screen and (max-width: 580px) {
		  .row {
		    padding: 8px 0;
		    display: block;
		  }
		}

		.cell {
		  padding: 6px 12px;
		  display: table-cell;
		}
		@media screen and (max-width: 580px) {
		  .cell {
		    padding: 2px 12px;
		    display: block;
		  }
		}

	</style>
</head>
<body>
	<p class="center">
		<img alt="" title="" src="http://www.contaponto.com.br/assets/images/png/logo_cp_300x53.png" style="border:none;border-radius:;display:block;outline:none;text-decoration:none;width:100%;height:auto;max-width: 300px;">
	</p>
	<?php if (!isset($linhas) or !is_array($linhas) ): ?>
		<p>Não há usuarios cadastrados no banco de dados.</p>
	<?php else: ?>
		<h3>Relatório de cadastros por CEP</h3>
		
		<hr>

		<?php 
			$campos = array(
				//'cep' => 'CEP',
				'A' => 'Ativo',
				'I' => 'Inativo',
				'total' => 'Total',
			);

		?>
		<table style="">
		 		<tr class="row header blue">
						<th class="cell">CEP</th>
					<?php foreach ($campos as $chave_campo => $nome_campo): ?>
						<th class="cell"><?= $nome_campo ?></th>
					<?php endforeach;?>
				</tr>
			<?php foreach ($linhas as $cep => $dados): ?>
			 	<tr class="row center <?= ( $cep == 'Total' ) ? 'bold' : '' ?>">
					<td class="cell "><?= $cep ?></td>
			 		<?php foreach ($campos as $chave_campo => $nome_campo): ?>
						<td class="cell">
								<?= ( isset( $dados[$chave_campo] ) ) ? $dados[$chave_campo] : '-' ?>
						</td>
					<?php endforeach;?>
				</tr>
			<?php endforeach?>
		</table>
	<?php endif;?>
</body>
</html>