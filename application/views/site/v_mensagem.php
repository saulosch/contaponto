<div class="row">
	<div class="col-xs-12 col-sm-offset-1 col-sm-10 alert alert-<?= (isset($tipo) && in_array($tipo,array('warning','success','danger')))?$tipo:'info'; ?>">
		<div class="text-center">
			<h2 class="alert-<?= (isset($tipo) && in_array($tipo,array('warning','success','danger')))?$tipo:'info'; ?>"><?= (isset($titulo_mensagem))?$titulo_mensagem:''; ?></h2>
		</div>
		<p><?= (isset($mensagem)?$mensagem:''); ?></p>
	</div>
</div>