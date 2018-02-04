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

		.table {
		  margin: 0 auto 40px auto;
		  width: 95%;
		  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
		  display: table;

		}
		@media screen and (max-width: 580px) {
		  .table {
		    display: block;
		  }
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
	<?php if ( ( !isset($usuarios_por_dia['dados']) or !is_array($usuarios_por_dia['dados']) )
			&&  ( !isset($pontuacao_por_dia['dados']) or !is_array($pontuacao_por_dia['dados']) ) ): ?>
		<p>Não houve movimentação nos últimos <?=$qtd_dias?> dias.</p>
	<?php else: ?>
		<h2>Relatório executivo</h2>
		Dados dos últimos <?=$qtd_dias?> dias.

		<?php if (isset($usuarios_por_dia['dados']) && is_array($usuarios_por_dia['dados'])): ?>
			<hr>

			<h3>Cadastro de usuários</h3>
			<div>
				<p>Mostra a quantidade de usuários cadastrados no sistema por empresa.</p>
			</div>
			<table style="width: 100%;">
				<tr class="row header blue">
					<th class="cell">Empresa</th>
					<?php foreach ($datas as $data): ?>
						<th class="cell"><?php echo date('d/m/Y', strtotime($data)); ?></th>
					<?php endforeach;?>
					<th class="cell">Total</th>
				</tr>
				<?php foreach ($usuarios_por_dia['dados'] as $empresa => $quantidades_por_dia): ?>
					<tr class="row">
						<td class="cell">
							<?php echo $empresa; ?>
						</td>
						<?php foreach ($datas as $data): ?>
							<td class="cell"><?php echo (isset($quantidades_por_dia[$data])) ? $quantidades_por_dia[$data] : '-'; ?></td>
						<?php endforeach;?>
						<td class="cell">
							<b><?php echo $usuarios_por_dia['totais'][$empresa]; ?></b>
						</td>
					</tr>
				<?php endforeach?>
				<tr class="row">
					<td class="cell">Total</td>
					<?php foreach ($datas as $data): ?>
						<td class="cell"><b><?php echo (isset($usuarios_por_dia['totais'][$data])) ? $usuarios_por_dia['totais'][$data] : '-'; ?></b></td>
					<?php endforeach;?>
					<td class="cell" style="color:#2980b9;"><b><?php echo array_sum($usuarios_por_dia['totais']) / 2; ?></b></td>
				</tr>
			</table>
		<?php endif;?>

		<?php if (isset($pontuacao_por_dia['dados']) && is_array($pontuacao_por_dia['dados'])): ?>
			<hr>
			<h3>Pontuação</h3>
			<div>
				<p>Mostra a quantidade de pontos concedidos por empresa e, entre parenteses, a quantidade de consumidores que receberam esses pontos.</p>
			</div>
			<table style="width: 100%;">
				<tr class="row header green">
					<th class="cell">Empresa</th>
					<?php foreach ($datas as $data): ?>
						<th class="cell"><?php echo date('d/m/Y', strtotime($data)); ?></th>
					<?php endforeach;?>
					<th class="cell"><b>Total</b></th>
				</tr>
				<?php foreach ($pontuacao_por_dia['dados'] as $empresa => $quantidades_por_dia): ?>
					<tr class="row">
						<td class="cell">
							<?php echo $empresa; ?>
						</td>
						<?php foreach ($datas as $data): ?>
							<td class="cell">
								<?php echo (isset($quantidades_por_dia[$data]['qtd_usuarios'])) ? $quantidades_por_dia[$data]['qtd_pontos'] . ' (' . $quantidades_por_dia[$data]['qtd_usuarios'] . ')' : '-'; ?>
							</td>
						<?php endforeach;?>
						<td class="cell">
							<b><?php echo $pontuacao_por_dia['totais']['qtd_pontos'][$empresa] . ' (' . $pontuacao_por_dia['totais']['qtd_usuarios'][$empresa] . ')'; ?></b>
						</td>
					</tr>
				<?php endforeach?>
				<tr class="row">
					<td class="cell"><b>Total</b></td>
					<?php foreach ($datas as $data): ?>
						<td class="cell"><b><?php echo (isset($pontuacao_por_dia['totais']['qtd_usuarios'][$data])) ? $pontuacao_por_dia['totais']['qtd_pontos'][$data] . ' (' . $pontuacao_por_dia['totais']['qtd_usuarios'][$data] . ')' : '-'; ?></b></td>
					<?php endforeach;?>
					<td class="cell" style="color:#27ae60;">
						<b>
							<?php echo array_sum($pontuacao_por_dia['totais']['qtd_pontos']) / 2 . ' (' . array_sum($pontuacao_por_dia['totais']['qtd_usuarios']) / 2 . ')'; ?>
						</b>
					</td>
				</tr>
			</table>
		<?php endif;?>
	<?php endif;?>

</body>
</html>