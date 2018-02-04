<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Verso Vale-pontos</title>
	<link rel="stylesheet" href="">
	<style>
	@media all
	{
		@page {
			size: A4;
			margin: 3mm 4mm 4mm 4mm;
		}
		body, html{
    		padding:0!important; 
    		margin:0 !important;
    	}
		html {
			font-family: arial, sans-serif;
			background-color: #FFF;
		}
		body {
			background-color: #E8E8E8;
			width: 100%;
		}
		
		table, th, td {
		    border: none;
		    vertical-align: bottom;
		}
		table {
			vertical-align: top;
		}

		td.valeponto {
			padding: 0.11cm 0.25cm;
			height: 5.45cm;
			width: 9.4cm;
			background-size: 9.4cm 5.45cm;
			background-color: #FFF;
		}

		table.valeponto-verso {
			display: inline-block;
		}
		p.texto-verso
		{
			font-size: 7.5pt;
			text-align: justify;
		}
		p.formulario
		{
			font-size: 8pt;
			line-height: 12.8pt;
			text-align: justify;	
		}
		p
		{
			margin: 0.5mm 0 !important;
		}
	}
	</style>
</head>
<body>

<?php for ($i=1; $i <= 10; $i++):?>
		<table class=valeponto-verso>
			<tr>
				<td class="valeponto">
					<p class="texto-verso">Este vale-pontos concede o direito de aquisição de pontos na rede de fidelidade Contaponto. Escolha uma das alternativas para acumular os pontos em sua conta: (a) acessar o site contaponto.com.br, realizar login, clicar em “vale pontos” e informar o número e código descritos neste vale-pontos (os pontos serão concedidos na hora), após confirmar o cadastro do vale-pontos, rasgue e descarte adequadamente este papel; (b) preencher os dados abaixo e entregar no estabelecimento que te concedeu este vale-pontos, os pontos serão concedidos em até 60 dias, caso já não tenha sido cadastrado no site conforme opção “a”. A participação na rede de fidelidade Contaponto está sujeita às condições descritas no site Contaponto.</p>
					<p class="formulario">
						Nome: _____________________________________________________
						E-mail: _____________________________________________________
						CPF: ___________________________ Dt. Nasc: _____ /_____ /_____
						Celular: (_____) ___________________ Gênero: (__) Masc. / (__) Fem.
					</p>

				</td>
			</tr>
		</table>
<?php endfor; ?>
 
</body>
</html>