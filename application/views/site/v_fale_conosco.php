<div class="container">
	<div class="center">
		<h2>Entre em contato</h2>
		<p class="lead">Você pode entrar em contato conosco enviando um e-mail para <?php echo safe_mailto('contato@contaponto.com.br'); ?> ou por meio do formulário abaixo. <br />Lembre-se que muitas dúvidas já estão respondidas na seção <?= anchor('duvidas','Dúvidas Frequentes'); ?>.</p>
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
					<label>Telefone</label>
					<input type="text" name="telefone" class="form-control" value="<?= set_value('telefone'); ?>" <?= ($sucesso)?'disabled':''; ?>>

				</div>
				<?= form_error('telefone'); ?>
				
			</div>
			<div class="col-sm-5">
				<div class="form-group">
					<label>Assunto *</label>
					<input type="text" name="assunto" class="form-control" required="required" value="<?= set_value('assunto'); ?>" <?= ($sucesso)?'disabled':''; ?>>
				</div>
				<?= form_error('assunto'); ?>

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