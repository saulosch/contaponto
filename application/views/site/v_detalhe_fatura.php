<section id="portfolio">
	<div class="container">
		<div class="row">		
			<div class="col-xs-12 cupom_impressao">
				<div class="well">
					<div class="row">
						<div class="col-xs-4 col-xs-offset-1 text-center space-10">
							<img src="<?= base_url('assets/images/png/logo_cp_300x53.png'); ?>">
						</div>
						<div class="col-xs-6 col-xs-offset-1">
							<div class="row">
								<div class="col-xs-7 text-right">Fatura: </div>
								<div class="col-xs-5"><?=$fatura['id_fatura'];?></div>
							</div>
							<div class="row">
								<div class="col-xs-7 text-right">Data vencimento:</div>
								<div class="col-xs-5"><?=formata_data($fatura['dt_vencimento'],'d/m/Y');?></div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12 text-center">
							<p>Período de faturamento: <?= formata_data($fatura['dt_inicio_faturamento'],'d/m/Y');?> a <?=formata_data($fatura['dt_fim_faturamento'],'d/m/Y');?></p>
						</div>
					</div>
					<div class="row thumbnail">
						<div class="col-xs-10 col-xs-offset-1 space-10">
							<div class="row">
								<div class="col-xs-6"><b>Resumo</b></div>
								<div class="col-xs-3 text-center"><b>Quantidade</b></div>
								<div class="col-xs-3 text-right"><b>Valor</b></div>
							</div>
							<div class="row">
								<div class="col-xs-6">Pontos concedidos</div>
								<div class="col-xs-3 text-center">
									<?=$fatura['totais']['itens']['pontos']['soma']?>
								</div>
								<div class="col-xs-3 text-right">
									<?=formata_moeda($fatura['totais']['itens']['pontos']['valor'])?>
								</div>
							</div>
							<div class="row">
								<div class="col-xs-6">Cupons recebidos</div>
								<div class="col-xs-3 text-center">
									<?=$fatura['totais']['itens']['cupons']['qtd']?>
								</div>
								<div class="col-xs-3 text-right">
									<?=formata_moeda($fatura['totais']['itens']['cupons']['valor'])?>
								</div>
							</div>
							<div class="row">
								<div class="col-xs-6">Cobranças</div>
								<div class="col-xs-3 text-center">
									<?=$fatura['totais']['itens']['cobrancas']['qtd']?>
								</div>
								<div class="col-xs-3 text-right">
									<?=formata_moeda($fatura['totais']['itens']['cobrancas']['valor'])?>
								</div>
							</div>
							<div class="row">
								<div class="col-xs-6">Ajustes</div>
								<div class="col-xs-3 text-center">
									<?=$fatura['totais']['itens']['ajustes']['qtd']?>
								</div>
								<div class="col-xs-3 text-right">
									<?=formata_moeda($fatura['totais']['itens']['ajustes']['valor'])?>
								</div>
							</div>
							<div class="row">
								<div class="col-xs-6"><b>VALOR TOTAL</b></div>
								<div class="col-xs-3 text-center">
									<b><?=$fatura['totais']['qtd']?></b>
								</div>
								<div class="col-xs-3 text-right">
									<b><?=formata_moeda($fatura['totais']['valor'])?></b>
								</div>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="space-10"></div>
						<div class="col-xs-12 text-center">
							<p><b>Pontos concedidos</b></p>
						</div>
					</div>
					<div class="row small space-10 thumbnail" >
						<?php $i = 0; ?>
						<?php $metade = ceil($fatura['totais']['itens']['pontos']['qtd']/2); ?>
						<div class="col-xs-6" style="border-right: 1px solid #e3e3e3;"> <!-- abre -->
							<?php foreach ($item_fatura as $key => $value): ?>
								<?php if($value['fk_pontuacao']): ?>
									<?php if($i%$metade == 0): ?>
										<?php if ($i!=0): ?>
											</div><!-- fecha -->
											<div class="col-xs-6" > <!-- abre -->
										<?php endif; ?>
										<div class="row">
											<div class="col-xs-3"><b>Data</b></div>
											<div class="col-xs-3 text-center"><b>Código</b></div>
											<div class="col-xs-3 text-center"><b>Quantidade</b></div>
											<div class="col-xs-3 text-right"><b>Valor</b></div>
										</div>
									<?php endif; ?>
								
									<?php $i++; ?>
									<div class="row">
										<div class="col-xs-3"><?=formata_data($value['ts_pontuacao'],'d/m/Y')?></div>
										<div class="col-xs-3 text-center"><?=$value['fk_pontuacao']?></div>
										<div class="col-xs-3 text-center"><?=$value['qtd_pontos']?></div>
										<div class="col-xs-3 text-right"><?=formata_moeda($value['valor'])?></div>
									</div>
								<?php endif; ?>
							<?php endforeach; ?>
						</div><!-- fecha -->
					</div>

					<div class="row">
						<div class="space-10"></div>
						<div class="col-xs-12 text-center">
							<p><b>Cupons recebidos</b></p>
						</div>
					</div>
					<div class="row small space-10 thumbnail" >
						<?php $i = 0; ?>
						<?php $metade = ceil($fatura['totais']['itens']['cupons']['qtd']/2); ?>
						<div class="col-xs-6" style="border-right: 1px solid #e3e3e3;"> 
							<?php foreach ($item_fatura as $key => $value): ?>
								<?php if($value['fk_usuario_cupom']): ?>
									<?php if($i%$metade == 0): ?>
										<?php if ($i!=0): ?>
											</div><!-- fecha -->
											<div class="col-xs-6" > <!-- abre -->
										<?php endif; ?>
											<div class="row">
												<div class="col-xs-4"><b>Data</b></div>
												<div class="col-xs-4 text-center"><b>Código</b></div>
												<div class="col-xs-4 text-right"><b>Valor</b></div>
											</div>
									<?php endif; ?>
								
									<?php $i++; ?>
									<div class="row">
										<div class="col-xs-4"><?=formata_data($value['ts_utilizacao'],'d/m/Y')?></div>
										<div class="col-xs-4 text-center"><?=$value['fk_usuario_cupom']?></div>
										<div class="col-xs-4 text-right"><?=formata_moeda($value['valor'])?></div>
									</div>
								<?php endif; ?>
							<?php endforeach; ?>
						</div><!-- fecha -->
					</div>



					<div class="row">
						<div class="space-10"></div>
						<div class="col-xs-12 text-center">
							<p><b>Cobranças</b></p>
						</div>
					</div>
					<div class="row small space-10 thumbnail" >
						<?php $i = 0; ?>
						<?php $metade = ceil($fatura['totais']['itens']['cobrancas']['qtd']/2); ?>
						<div class="col-xs-6" style="border-right: 1px solid #e3e3e3;"> 
							<?php foreach ($item_fatura as $key => $value): ?>
								<?php if($value['fk_mensalidade']): ?>
									<?php if($i%$metade == 0): ?>
										<?php if ($i!=0): ?>
											</div><!-- fecha -->
											<div class="col-xs-6" > <!-- abre -->
										<?php endif; ?>
											<div class="row">
												<div class="col-xs-2"><b>Dia</b></div>
												<div class="col-xs-7 text-center"><b>Descrição</b></div>
												<div class="col-xs-3 text-right"><b>Valor</b></div>
											</div>
									<?php endif; ?>
								
									<?php $i++; ?>
									<div class="row">
										<div class="col-xs-2"><?=$value['dia_cobrar_mensalidade']?></div>
										<div class="col-xs-7 text-center"><?=$value['descricao_mensalidade']?></div>
										<div class="col-xs-3 text-right"><?=formata_moeda($value['valor'])?></div>
									</div>
								<?php endif; ?>
							<?php endforeach; ?>
						</div><!-- fecha -->
					</div>



					<div class="row">
						<div class="space-10"></div>
						<div class="col-xs-12 text-center">
							<p><b>Ajustes</b></p>
						</div>
					</div>
					<div class="row small space-10 thumbnail" >
						<div class="col-xs-12" >
							<?php foreach ($ajuste_fatura as $key => $value): ?>
								<?php if ($key==0): ?>
									<div class="row">
										<div class="col-xs-2"><b>Data</b></div>
										<div class="col-xs-8 text-"><b>Descrição</b></div>
										<div class="col-xs-2 text-right"><b>Valor</b></div>
									</div>
								<?php endif; ?>
								<div class="row">
									<div class="col-xs-2"><?=formata_data($value['ts_criacao'],'d/m/Y')?></div>
									<div class="col-xs-8 text-"><?=$value['descricao']?></div>
									<div class="col-xs-2 text-right"><?=formata_moeda($value['valor'])?></div>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
					
					
					<hr>
					<div class="row small" >
						<div class="col-xs-12 text-justify">
							<p>Fatura para simples conferência, caso não tenha recebido um e-mail com o boleto para pagamento em até cinco dias antes do vencimento desta fatura, entre em contato com a equipe da Contaponto. Obrigado.</p>
						</div>
					</div>

					<?php if (sizeof($descontos) > 0): ?>
						<p></p>
						<div class="row small" >
							<div class="col-xs-12 text-center">
								<b>Descontos</b>
							</div>
							<div class="col-xs-12 text-justify">
								<p>São aplicáveis os seguintes descontos ao valor dos pontos de acordo com a quantidade de novos usuários cadastrados pelo estabelecimento comercial no mês anterior à data de concessão dos pontos ao cliente:</p>
								<ul>
									<?php foreach ($descontos as $key => $value): ?>
										<li>
											<?= ($value['qtd_usuarios_ate'] < 999999)? 'entre ' . $value['qtd_usuarios_de'].' e '.$value['qtd_usuarios_ate'] : 'acima de '. $value['qtd_usuarios_de'] ?> usuários cadastrados, <?= ($value['percentual_desconto'] - floor($value['percentual_desconto'] == 0) ? number_format($value['percentual_desconto'] , 0 , ',' , '.') : number_format($value['percentual_desconto'] , 1 , ',' , '.')) ?>% de desconto no preço dos pontos;
										</li>				
									<?php endforeach; ?>
								</ul>
							</div>
						</div>
					<?php endif ?>

				</div>
			</div>
		</div>
	</div>
</section><!--/#portfolio-item-->