<p> </p>
<div class=" col-xs-10 col-xs-offset-1 alert alert-warning">
	<p>Verifique se o cliente que está apresentando o cupom é a pessoa indicada acima. Caso queira consumir o cupom e entregar o produto/serviço/desconto descrito no cupom, clique no botão abaixo. Essa ação não pode ser desfeita.</p>
</div>

<div class=" col-xs-12 col-xs-offset-0 col-sm-4 col-sm-offset-4">
	<?php 
		echo form_open('lojista/consome_cupom');
		echo form_hidden('numero_cupom', $numero_cupom);
		echo form_hidden('codigo_cupom', $codigo_cupom);
		echo "<h2>";
		echo form_submit('consulta_cupom_submit', 'Receber Cupom', array('value'=>'1','class'=>'btn btn-success  btn-lg col-xs-12'));
		echo "</h2>";
		echo form_close();
	?>
</div>