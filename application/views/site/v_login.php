<div id="loginbox" style="margin-top:20px;" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
	<div class="panel panel-cp" >
		<div class="panel-heading">
			<div class="panel-title">Acessar</div>
			<?php /* <div style="float:right; font-size: 80%; position: relative; top:-10px"><a href="#">Forgot password?</a></div> */ ?>
		</div> 
		<div style="padding-top:20px" class="panel-body" >

			<div style="display:none" id="login-alert" class="alert alert-danger col-sm-12"></div>
				
			<form id="loginform" class="form-horizontal" role="form" method="post" >
						
				<?php foreach ($campos as $key => $campo) : ?>
					<div style="margin-bottom: 20px" class="input-group">
						<span class="input-group-addon"><i class="glyphicon glyphicon-<?=($campo['nome_campo']=='email')?'envelope':'lock' ?>"></i></span>
						<input type="<?= $campo['tipo_campo_html'];?>" name="<?= $campo['nome_campo'];?>" value="<?= set_value($campo['nome_campo']) ?>" placeholder="<?= $campo['placeholder'];?>" <?= ($campo['obrigatorio'])?'required':'';?> class ="form-control" <?= ($campo['propriedades_html']=='autofocus')?'autofocus':'';?> />
					</div>
				<?= form_error($campo['nome_campo']); ?>
				<?php endforeach; ?>

				<!-- <div style="margin-bottom: 20px" class="input-group">
					<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
					<input id="login-username" type="text" class="form-control" name="email" value="" placeholder="E-mail">
				</div>
				<?= form_error('email'); ?>
					
				<div style="margin-bottom: 20px" class="input-group">
					<span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
					<input id="login-password" type="password" class="form-control" name="password" placeholder="Senha">
				</div>
				<?= form_error('senha'); ?> -->
				<?php /*
					<div class="input-group">
						<div class="checkbox">
							<label>
								<input id="login-remember" type="checkbox" name="remember" value="1"> Remember me
							</label>
						</div>
					</div>
 				*/ ?>	

				<div style="margin-top:10px" class="form-group">
					<!-- Button -->
					<div class="col-sm-12 controls">
						<?php echo form_submit('login_submit', 'Acessar', array('value'=>'1','class'=>'btn btn-success btn-lg col-xs-10 col-xs-offset-1 col-sm-4 col-sm-offset-4')); ?>
						<!-- <a id="btn-login" href="#" class="btn btn-success btn-lg col-xs-10 col-xs-offset-1 col-sm-4 col-sm-offset-4">Acessar</a> -->
						<!-- <a id="btn-fblogin" href="#" class="btn btn-primary">Login with Facebook</a> -->
					</div>
				</div>
				<div class="form-group row" style="border-top: 1px solid#888; padding-top:15px;">
					<div class="col-xs-12 col-md-7 control">
						<div>
							Não possui cadastro? 
							<a href="<?=base_url('cadastro')?>">Cadastre-se</a>
						</div>
					</div>
					<div class="col-xs-12 col-md-5 control text-right">
						<div>
							<a href="<?=base_url('reset_senha')?>">Esqueci minha senha!</a>
						</div>
					</div>
				</div>
			</form> 
		</div> 
	</div>
</div>

