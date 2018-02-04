<?php// show($pontuacao,false); ?>
<div class="raow">
	<div class="hidden-xs hidden-sm">
		<table class="table table-striped table-responsive" style="@-moz-document url-prefix() { fieldset { display: table-cell;}}">
		  	<thead>
		  		<tr>
		  			<?php foreach ($pontuacao['campos'] as $campo): ?>
		  				<th><?=$campo?></th>	
		  			<?php endforeach; ?>
		  		</tr>
		  	</thead>
		  	<tbody>
				<?php foreach ($pontuacao['dados'] as $linha): ?>
		  	  		<tr class="<?= (isset($linha['qtd_disponivel']) && $linha['qtd_disponivel'] == 0)?'success':''?> <?= ($linha['data_pgto_boleto'] == NULL)?'warning':''?> <?= ($linha['ts_estorno'] == NULL)?'':'danger'?>">
		  				<?php foreach ($pontuacao['campos'] as $key => $campo): ?>
		  					<?php if ($linha[$key] != '' && in_array($key, $campos_data)): ?>
								<td><?= date('d/m/Y', strtotime($linha[$key])) ?></td>	
		  					<?php else: ?>
		  						<td><?= $linha[$key]?></td>	
		  					<?php endif; ?>
		  				<?php endforeach; ?>
		  			</tr>
		  		<?php endforeach; ?>

		  	</tbody>
		</table>
	</div>
	<div class="visible-xs visible-sm hidden-print">
		<?php foreach ($pontuacao['dados'] as $linha): ?>
  	  		<div class="col-xs-12 alert <?= (isset($linha['qtd_disponivel']) && $linha['qtd_disponivel'] == 0)?'alert-success':''?> <?= ($linha['data_pgto_boleto'] == NULL)?'alert-warning':''?> <?= ($linha['ts_estorno'] == NULL)?'':'alert-danger'?>">
  				<?php foreach ($pontuacao['campos'] as $key => $campo): ?>
  					<div class="">
  						<strong><?=$campo;?></strong>: 
  						<?php if ($linha[$key] != '' && in_array($key, $campos_data)): ?>
						<?= date('d/m/Y', strtotime($linha[$key])) ?>	
  					<?php else: ?>
  						<?= $linha[$key]?>	
  					<?php endif; ?>
  					</div>
  					
  				<?php endforeach; ?>
  			</div>
  		<?php endforeach; ?>

	</div>
</div>


<?php  if (1==2): ?>
<!-- On rows -->
<tr class="active">...</tr>
<tr class="success">...</tr>
<tr class="warning">...</tr>
<tr class="danger">...</tr>
<tr class="info">...</tr>

.table-condensed

<?php endif; ?>