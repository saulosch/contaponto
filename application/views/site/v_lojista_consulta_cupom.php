<div class="row">
	<h2 class="text-center">Consulta Cupom</h2>
	<div class="col-xs-12 col-sm-offset-1 col-sm-10">
		<?php echo form_open(); ?>
		<p>&nbsp;</p>
		<?php $a = 0; ?>
		<?php foreach ($campos as $key => $campo) : ?>
			<div class="form-group row">
				<div class="col-xs-12 col-sm-3 col-sm-offset-1">
					<?php echo form_label(($campo['obrigatorio'])? $campo['label'] . ' *' : $campo['label'], $campo['nome_campo']); ?>
				</div>
				<div class="col-xs-12 col-sm-7">
					<?php if ($campo['tipo_campo_html'] == 'select') :
						$attributes = array(
							'required' => ($campo['obrigatorio'])?'required':'',
							'class' => 'form-control');
						echo form_dropdown($campo['nome_campo'], $campo['propriedades_html'], set_value($campo['nome_campo']),$attributes);
					else : ?>
						<input type="<?= $campo['tipo_campo_html'];?>" name="<?= $campo['nome_campo'];?>" value="<?= set_value($campo['nome_campo']) ?>" placeholder="<?= $campo['placeholder'];?>" <?= ($campo['obrigatorio'])?'required':'';?> class ="form-control" <?= ($campo['propriedades_html']=='readonly')?'readonly':'';?>/>
					<?php endif;  ?>
				</div>
				
			</div>
			<div class="row">
				
			<div class="col-xs-12 col-sm-10 col-sm-offset-1">
				<?= form_error($campo['nome_campo']); ?>
			</div>
			</div>
		<?php endforeach; ?>
		<div class="row">
			<div class="col-xs-12 col-sm-6 col-sm-offset-3">
				<?php echo form_submit('consulta_cupom_submit', 'Consutar Cupom', array('value'=>'1','class'=>'btn btn-primary btn-lg col-xs-10 col-xs-offset-1 col-sm-10 col-sm-offset-1')); ?>
			</div>
		</div>
		<?php echo form_close(); ?>
	</div>
</div>