<div class="container">
	<div class="center">
		<h2>Seja nosso parceiro</h2>

		<p class="lead">Quer aproveitar os benefícios que a Contaponto oferece para seus parceiros?</p>
		<p>Entre em contato conosco para saber como a Contaponto pode ajudar a melhorar a satisfação dos seus clientes e a aumentar a receita do seu comércio. Você pode enviar um e-mail para <?php echo safe_mailto('contato@contaponto.com.br'); ?> ou por meio do formulário abaixo.</p>
	</div> 
	<div class="row contact-wrap"> 
	<?php if ( isset($sucesso) && $sucesso === TRUE): ?>
		<div class="status alert alert-success">
			Mensagem enviada com sucesso.
		</div>
	<?php else: ?>
		<?php $sucesso = FALSE; ?>
		<?php if (isset($erro_envio)): ?>
			<div class="status alert alert-danger">
				<?= $erro_envio; ?>
			</div>
		<?php endif ?>	
	<?php endif ?>
				
		<form id="main-contact-form" class="contact-form" name="contact-form" method="post">
			<div class="col-sm-5 col-sm-offset-1">
				<div class="form-group">
					<label>Nome *</label>
					<input type="text" name="nome" class="form-control" required="required" value="<?= set_value('nome'); ?>" <?= ($sucesso)?'disabled':''; ?>>
				</div>
				<?= form_error('nome'); ?>
				
				<div class="form-group">
					<label>E-mail *</label>
					<input type="email" name="email" class="form-control" required="required" value="<?= set_value('email'); ?>" <?= ($sucesso)?'disabled':''; ?>>
				</div>
				<?= form_error('email'); ?>
				
				<div class="form-group">
					<label>Telefone *</label>
					<input type="text" name="telefone" class="form-control" required="required" value="<?= set_value('telefone'); ?>" <?= ($sucesso)?'disabled':''; ?>>

				</div>
				<?= form_error('telefone'); ?>
				
			</div>
			<div class="col-sm-5">
				<div class="form-group">
					<label>Nome da Empresa *</label>
					<input type="text" name="empresa" class="form-control" required="required" value="<?= set_value('empresa'); ?>" <?= ($sucesso)?'disabled':''; ?>>
				</div>
				<?= form_error('empresa'); ?>

				<div class="form-group">
					<label>Mensagem *</label>
					<textarea name="mensagem" id="mensagem" required="required" class="form-control" rows="8" <?= ($sucesso)?'disabled':''; ?>><?= set_value('mensagem'); ?></textarea>
				</div>
				<?= form_error('mensagem'); ?>
							
				<div class="form-group">
					<button type="submit" name="submit" class="btn btn-primary btn-lg"  <?= ($sucesso)?'disabled':''; ?>>Enviar Mensagem</button>
				</div>
				
			</div>
		</form> 
	</div><!--/.row-->
</div>