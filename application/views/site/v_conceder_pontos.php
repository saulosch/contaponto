<div class="row">
	<h2 class="text-center"><?=(isset($titulo))?$titulo:'Conceder Pontos'?></h2>
	<div class="col-xs-12 col-sm-offset-2 col-sm-8">
		<div class="panel panel-default" style="padding:0;">
			<div class="" role="tab" id="heading-instrucoes" style="background-color: #FFF;">
				<p class="text-right">
					<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion-old" href="#collapse-instrucoes" aria-expanded="false" aria-controls="collapse-instrucoes">
				        <u>Instruções</u>
					</a>
				</p>
			</div>
			<div id="collapse-instrucoes" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading-instrucoes" style="" aria-expanded="false">
		    	<div class="panel-body">
		      		<p>
						Você está pontuando em nome do estabelecimento: <?=$loja; ?> <br>
						É necessário informar 2 campos, um de indentificação e outro sobre a pontução. Assim, informe:
					
						<ol>
							<li><u>ou</u> o e-mail <u>ou</u> o CPF do seu cliente; </li>
							<li><u>ou</u> o desconto concedido <u>ou</u> a quantidade de pontos que deseja conceder.</li>
						</ol> 
						Lembrando que a regra de pontuação divulgada é de 1 ponto para cada <?php echo formata_moeda($preco_ponto); ?>.
					</p>
		      	</div>
			</div>
  		</div>
			  				

			
	</div>
	<script>
		function chPy(oSelect)
  		{
			preco_ponto = <?php echo $preco_ponto; ?>;
			valor = document.getElementById(oSelect.id).value;
			
			if (oSelect.id == 'qtd_pontos' )
			{
				outro_campo = 'valor_desconto';
				document.getElementById("aviso").innerHTML = "A quantidade de pontos corresponde ao cliente ter recebido um desconto de R$ "+ (Math.floor(valor*preco_ponto*100)/100).toFixed(2).replace(".", ",") ;
			}
			else if (oSelect.id == 'valor_desconto' )
			{
				outro_campo = 'qtd_pontos';
				document.getElementById("aviso").innerHTML = "O valor de desconto informado corresponde a "+ (Math.floor(valor/preco_ponto)) + " pontos." ;
			}
			else if (oSelect.id == 'cpf' )
				outro_campo = 'email';
			else if (oSelect.id == 'email' )
				outro_campo = 'cpf';
			
			document.getElementById(outro_campo).value = "";			
		}
	</script>

	<div class="col-xs-12 col-sm-offset-1 col-sm-10">
		<?php echo form_open(); ?>
			<p>&nbsp;</p>
			
			<div class="form-group row">
				<div class="col-xs-12 col-sm-3 col-sm-offset-1">
					<label for="email">E-mail</label>
				</div>
				<div class="col-xs-12 col-sm-7">
					<input OnChange="chPy(this)" id="email" name="email" value="<?php echo set_value('email'); ?>" placeholder="Ex.: jose.carlos@site.com.br" class="form-control" type="email">
				</div>
			</div>
			<div class="row">
				<div class="col-xs-12 col-sm-10 col-sm-offset-1">
					<?= form_error('email'); ?>
				</div>
			</div>
		
			<div class="form-group row">
				<div class="col-xs-12 col-sm-3 col-sm-offset-1">
					<label for="cpf">CPF</label>
				</div>
				<div class="col-xs-12 col-sm-7">
					<input OnChange="chPy(this)" id='cpf' name="cpf" value="<?php echo set_value('cpf'); ?>" placeholder="Apenas números. Ex.: 12345678901" class="form-control" type="number">
				</div>
					
			</div>
			<div class="row">
				<div class="col-xs-12 col-sm-10 col-sm-offset-1">
					<?= form_error('cpf'); ?>
				</div>
			</div>
			
			<hr>
			
			<div class="form-group row">
				<div class="col-xs-12 col-sm-3 col-sm-offset-1">
						<label for="valor_desconto">Desconto concedido</label>										
				</div>
				<div class="col-xs-12 col-sm-7">
					<input OnChange="chPy(this)" id="valor_desconto" name="valor_desconto" value="<?php echo set_value('valor_desconto'); ?>" placeholder="Valor economizado pelo cliente. Ex.: 1,23" step="0.01" class="form-control" type="number">
				</div>
					
			</div>
			<div class="row">
				<div class="col-xs-12 col-sm-10 col-sm-offset-1">
					<?= form_error('valor_desconto'); ?>
				</div>
			</div>
			
			<div class="form-group row">
				<div class="col-xs-12 col-sm-3 col-sm-offset-1">
					<label for="qtd_pontos">Quantidade de pontos</label>										
				</div>
				<div class="col-xs-12 col-sm-7">
					<input OnChange="chPy(this)" id="qtd_pontos" name="qtd_pontos" value="<?php echo set_value('qtd_pontos'); ?>" placeholder="Apenas números. Ex.: 200" class="form-control" type="number">
				</div>
					
			</div>
			
			<div class="row">
				<div class="col-xs-12 col-sm-10 col-sm-offset-1">
					<?= form_error('qtd_pontos'); ?>
				</div>
			</div>
			<div class="text-center">
				<p id="aviso"></p>
			</div>
			
			<div class="row">
				<div class="col-xs-12 col-sm-6 col-sm-offset-3">
					<?php echo form_submit('cadastro_submit', 'Cadastrar', array('value'=>'1','class'=>'btn btn-primary btn-lg col-xs-10 col-xs-offset-1 col-sm-10 col-sm-offset-1')); ?>
				</div>
			</div>
		<?php echo form_close(); ?>
	</div>
</div>