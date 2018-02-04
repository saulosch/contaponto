<?php 
	$faaaaq = 
		array('Dúvidas Gerais' => 
			array('Qualquer pessoa pode se registrar?'
					=>'Sim. Se você é o consumidor final (Pessoa Física), terá direito a registrar 01 usuário por CPF.',
					'Meus dados pessoais estão protegidos?'
					=>'Sim. A Conta Ponto respeita todos os direitos de privacidade e não irá divulgar seus dados pessoais sem sua expressa autorização. Os únicos dados que serão divulgados são os que você autorizar.'),
			'Resgate De Pontos' =>
			array('O que são "Pontos para troca" e como ganhá-los?' => 'Os Pontos para troca podem ser adquiridos de diversas maneiras e ao acumular estes Pontos você pode trocá-los por produtos, serviços ou descontos.'),


		);

 ?>
<div class="row">
	<div class="col-xs-12">
		<?php foreach ($faq as $titulo => $questoes) : ?>
			<h2><?=$titulo?></h2>
			<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
				<?php foreach ($questoes as $pergunta => $resposta) : ?>
					<div class="panel panel-default">
						<div class="panel-heading" role="tab" id="heading-<?php echo url_title($pergunta, 'underscore', TRUE); ?>">
							<p class="panel-title">
								<a role="button" data-toggle="collapse" data-parent="#accordion-old" href="#collapse-<?php echo url_title($pergunta, 'underscore', TRUE); ?>" aria-expanded="true" aria-controls="collapse-<?php echo url_title($pergunta, 'underscore', TRUE); ?>">
				        		<?php echo $pergunta; ?>
				        		</a>
			      			</p>
			    		</div>
			    		<div id="collapse-<?php echo url_title($pergunta, 'underscore', TRUE); ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading-<?php echo url_title($pergunta, 'underscore', TRUE); ?>">
		      				<div class="panel-body">
		      					<?php echo $resposta; ?>
		      				</div>
			    		</div>
			  		</div>
			  	<?php endforeach; ?>
			</div>
		<?php endforeach; ?>
	</div>
</div>