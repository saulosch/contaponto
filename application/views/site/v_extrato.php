<?php// show($model[$key],false); ?>
<?php if (isset($mensagem_economia)): ?>
	<div class="alert alert-info text-center">
		<p><?= $mensagem_economia ?></p>
	</div>
<?php endif; ?>
<div class="raow">
	<ul class="nav nav-tabs">
		<?php $aba_padrao = ($this->input->get('aba') && array_key_exists($this->input->get('aba'), $abas))? $this->input->get('aba') : key($abas); ?>

		<?php foreach ($abas as $codigo_aba => $titulo_aba) : ?>
  			<li class="<?= ($codigo_aba==$aba_padrao)?'active':'' ?> "><a data-toggle="tab" href="#<?=$codigo_aba?>"><?=$titulo_aba?></a></li>
		<?php endforeach; ?>
	</ul>
	<div class="tab-content">
		<?php foreach ($abas as $codigo_aba => $titulo_aba) : ?>
	  		<div id="<?=$codigo_aba?>" class="tab-pane fade in <?= ($codigo_aba==$aba_padrao)?'active':'' ?>">
				
				<div class="hidden-xs hidden-sm">
					<table class="table table-striped table-condensed table-responsive" style="@-moz-document url-prefix() { fieldset { display: table-cell;}}">
						<thead>
							<tr>
								<?php foreach ($model[$codigo_aba]['campos'] as $campo): ?>
									<th><?=$campo?></th>	
								<?php endforeach; ?>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($model[$codigo_aba]['dados'] as $linha): ?>
								<tr class="<?= (isset($linha['tipo_linha']))?$linha['tipo_linha']:'' ?>">
									
									<?php foreach ($model[$codigo_aba]['campos'] as $key => $campo): ?>
										<td>
										<?php if ($linha[$key] != '' && in_array($key, $campos_data[$codigo_aba])): ?>
											<?= (isset($linha['link']))
												?anchor($linha['link'], date('d/m/Y', strtotime($linha[$key])))
												:date('d/m/Y', strtotime($linha[$key])); ?>
										<?php else: ?>
											
											<?= (isset($linha['link']) && $linha[$key])
												?anchor($linha['link'],  $linha[$key])
												: $linha[$key]; ?>
										<?php endif; ?>
										</td>
									<?php endforeach; ?>
								</tr>
							<?php endforeach; ?>

						</tbody>
					</table>
				</div>

				<div class="visible-xs visible-sm hidden-print">
					<?php foreach ($model[$codigo_aba]['dados'] as $linha): ?>
						<div class="col-xs-12 alert <?= (isset($linha['tipo_linha']) && $linha['tipo_linha'])?'alert-'.$linha['tipo_linha']:'alert-success' ?>">
							<?php foreach ($model[$codigo_aba]['campos'] as $key => $campo): ?>
								<div class="">
									<strong><?=$campo;?></strong>: 
									<?php if ($linha[$key] != '' && in_array($key, $campos_data[$codigo_aba])): ?>
										<?= date('d/m/Y', strtotime($linha[$key])); ?>
									<?php else: ?>
										<?=  $linha[$key]; ?>
									<?php endif; ?>
								</div>
								
							<?php endforeach; ?>
							<?php if (isset($linha['link'])): ?>
								<div class="text-right"><?= anchor($linha['link'], 'Ver detalhe' );?> </div>
							<?php endif; ?>
						</div>
					<?php endforeach; ?>
				</div>
	  		</div>
	  	<?php endforeach; ?>	
  		<div id="usuarios_cadastrados" class="tab-pane fade">
			<h3>Menu 1</h3>
			<p>Some content in menu 1.</p>
  		</div>
	</div>
</div>
