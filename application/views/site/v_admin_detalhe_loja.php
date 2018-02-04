<div class="row">
	<div class="col-xs-12 text-center">
		<h2><?= $loja['nome_fantasia']?></h2>
		<p>Acumule <strong><?=$loja['qtd_pontos']?> pontos</strong> a cada <strong>R$ <?= str_replace('.',',',$loja['valor_em_compras'])?></strong>.</p>
		<?php if($loja['qtd_pontos'] > 0): ?>
			<p>1.250 pontos concedidos com R$ <?= str_replace('.',',',$loja['valor_em_compras']*1250/$loja['qtd_pontos'])?> em compras. </p>
		<?php endif; ?>
		<?php if($loja_limite): ?>
			<p><strong>Limite:</strong> <?=$loja_limite['saldo_atual']?>/<?=$loja_limite['limite_max']?> - <strong>Utilizado:</strong> <?=$loja_limite['limite_max']-$loja_limite['saldo_atual']?>.</p>
		<?php endif ?>
	</div>
	<div class="col-xs-12">
		<h3>Lojistas</h3>
		<table class="table table-striped table-condensed">
			<thead>
				<tr>
					<th>Nome</th>
					<th>E-mail</th>
					<th>Status</th>
					<th>Último acesso</th>
					<th>Criado em</th>
				</tr>
			</thead>
			<tbody>
			<?php
				$algum_usuario_ativo = FALSE;
				$todos_usuarios_ativos = TRUE;
			?>
			<?php foreach ($lojistas as $key => $lojista): ?>
				<?php  
				if ($lojista['ativo'] == 'A')
				{
					$algum_usuario_ativo = TRUE;
				}
				else
				{
					$todos_usuarios_ativos = FALSE;
				}
				?>
				<tr>
					<td><?=$lojista['nome']?></td>
					<td><?=$lojista['email']?></td>
					<td><?=$lojista['ativo']?></td>
					<td><?=$lojista['ts_ult_acesso']?></td>
					<td><?=$lojista['ts_criacao']?></td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
	<?php if ( ! $algum_usuario_ativo): ?>
		<div class="alert alert-danger" role="alert">Nenhum usuário da loja está ativo.</div>
	<?php elseif (! $todos_usuarios_ativos) : ?>
		<div class="alert alert-warning" role="alert">Alguns usuários da loja ainda não foram ativados.</div>
	<?php endif ?>

	</div>
	<div class="col-xs-12">
		<h3>Clientes cadastrados</h3>
		<p>Nos últimos 60 dias: <?=sizeof($loja_clientes['dados']); ?></p>
	</div>
	<div class="col-xs-12">
		<h3>Pontuação</h3>
		<p>Pontuações realizadas nos últimos 60 dias: <?=sizeof($loja_pontuacao['dados']); ?></p>
		<?php
			$total = 0;
			if ( ! isset ($loja_pontuacao['dados']) )
			{
				$loja_pontuacao['dados'] = array();
				$pontos = array();
			}
			foreach ($loja_pontuacao['dados'] as $key => $pontuacao) {
				$data = substr($pontuacao['ts_pontuacao'],0,10);
				if (isset($pontos[$data]))
				{
					$pontos[$data] += $pontuacao['qtd_pontos'];
				}
				else
				{
					$pontos[$data] = $pontuacao['qtd_pontos'];
				}
				$total += $pontuacao['qtd_pontos'];
			}
		?>
		
		<table class="table table-striped table-condensed">
			<thead>
				<tr>
					<th>Data</th>
					<th>Qtd</th>
					
				</tr>
			</thead>
			<tbody>
				<?php foreach ($pontos as $data => $qtd): ?>
					<tr>
						<td><?=$data?></td>
						<td><?=$qtd?></td>
					</tr>
				<?php endforeach; ?>
				<tr class="info">
					<td><b>Total</b></td>
					<td><b><?=$total?></b></td>
				</tr>
			</tbody>
		</table>
		<?php if ($total < 1250): ?>
			<p>Ainda restam <?= 1250-$total ?> pontos dos 1250 pontos isentos de pagamento.</p>
		<?php endif ?>
	</div>
</div>