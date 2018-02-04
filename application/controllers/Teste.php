<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Teste extends CI_Controller {

	public function email($value='')
	{
		
		if ($value == '')
		{
			$value = 'saulos~gmail.com';
		}
	 	$email =  str_replace('~', '@', $value);
	    $key = "AMCLlIcPKMgFoA8QjJ39P";
	    $url = "https://app.emaillistverify.com/api/verifEmail?secret=".$key."&email=".$email;
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
	    $response = curl_exec($ch);
	    curl_close($ch);
	    echo "$email<br><pre>";

	     var_dump($response);
	     echo "</pre><br>";
	    if ($response == 'fail' OR $response== 'unknown')
	    {
	    	echo 'FALSE';
	    }
	    else
	    {
	    	echo 'TRUE';
	    }

	}

	public function banners_marcel($id = 0)
	{
		$this->load->model('loja_model');
		$lojas = $this->loja_model->carrega_lojas_acumulo_por_cidade(2);
		$link_imagem_fundo = base_url("assets/images/png");
		
		?>
			<!DOCTYPE html>
			<html>
			<head>
			  <meta charset="utf-8">
			  <meta http-equiv="X-UA-Compatible" content="IE=edge">
			  <title></title>
			  <link rel="stylesheet" href="">
			  <style>
			    body {
			      background-color: #777;
			    }
			  </style>
			</head>
			<body>
			<script>
			  function banner_loja($l,$d,$i)
			  {  
			    var img = new Image();
			    img.src = '<?= $link_imagem_fundo; ?>/banner_desconto_' + $d + '.png';
			    			    var img2 = new Image();
			    img2.src = $l;
			    img.onload = function() {
			      var canvas = document.getElementById('canvas'+$i);
			      var context = canvas.getContext('2d');
			      context.drawImage(img, 0, 0, canvas.width, canvas.height);

			      // Draw the Watermark
			      // context.font = '48px sans-serif';
			      // context.globalCompositeOperation = 'lighter';
			      //context.fillStyle = '#444';
			       context.align = 'center';
			       // context.textBaseline = 'middle';
			      // context.fillText('watermark', canvas.width / 2, canvas.height / 2);
			      var img2w = 330;
			      var img2h = img2.height * img2w / img2.width;
			      var y_ini =  (330 - img2h)/2 + 180;
			      context.drawImage(img2, 305, y_ini, img2w, img2h);
			    };
			  };
			</script>


		<?php foreach ($lojas as $key => $loja): if ( ($id == 0 && $loja["percentual_desconto"]) OR $id == $loja["id_loja"] ):  ?>
			
			 	<p><?=$loja["id_loja"]?> - <?=$loja["nome_fantasia"]?></p>
			 	<canvas id='canvas<?=$loja["id_loja"]?>' width="1200" height="628"></canvas>
			  	<script>
			  		banner_loja(
			  			'<?=base_url("assets/uploads/files/".$loja["logo"])?>',
			  			<?=$loja["percentual_desconto"]?>,
			  			<?=$loja["id_loja"]?>);
			  	</script>  	
		<?php endif;endforeach;?>
			</script>
			</body>
			</html>
		<?php
	}

	// Exibe uma imagem com todos os banners da cidade juntos
	public function banners_lojas($cor = '#FFFFFF')
	{

		$this->load->model('loja_model');
		$lojas = $this->loja_model->carrega_lojas_acumulo_por_cidade(2);
		$link_imagem_fundo = base_url("assets/images/png");

		$qtd = sizeof($lojas);
		$cols = ceil(sqrt($qtd));

		die;
		?>
			<!DOCTYPE html>
			<html>
			<head>
			  <meta charset="utf-8">
			  <meta http-equiv="X-UA-Compatible" content="IE=edge">
			  <title></title>
			  <link rel="stylesheet" href="">
			  <style>
			    body {
			      background-color: <?=$cor?>;
			    }
			  </style>
			</head>
			<body>
			<canvas id='canvas' width="1000" height="1000"></canvas>
			<script>
			  function banner_loja($l,$d,$i)
			  { 
			  	<?php foreach ($lojas as $key => $value) :?> 
			    	var img_<?=$key?> = new Image();
			    	img<?=$key?>.src = '<?=base_url("assets/uploads/files/".$loja["logo"])?>';
			    <?php endforeach; ?>

			    img.onload = function() {
			      var canvas = document.getElementById('canvas');
			      var context = canvas.getContext('2d');
			      var cw = canvas.width;
			      var ch = canvas.height;
			      
			      var x_ini =  (330 - img2h)/2 + 180;
			      var y_ini =  (330 - img2h)/2 + 180;
			      
			  	<?php foreach ($lojas as $key => $value) :?> 
			      var img<?=$key?>w = (cw - ( ( 1 + <?=$cols?> ) * 98/100 ))/<?=$cols?>;
			      var img<?=$key?>h = img2.height * img2w / img2.width;
			    <?php endforeach; ?>
			      // context.drawImage(img, 0, 0, canvas.width, canvas.height);

			      // Draw the Watermark
			      // context.font = '48px sans-serif';
			      // context.globalCompositeOperation = 'lighter';
			      //context.fillStyle = '#444';
			       context.align = 'center';
			       // context.textBaseline = 'middle';
			      // context.fillText('watermark', canvas.width / 2, canvas.height / 2);
			      context.drawImage(img2, 305, y_ini, img2w, img2h);
			    };
			  };
			</script>


		<?php foreach ($lojas as $key => $loja): if ( ($id == 0 && $loja["percentual_desconto"]) OR $id == $loja["id_loja"] ):  ?>
			
			 	<p><?=$loja["id_loja"]?> - <?=$loja["nome_fantasia"]?></p>
			  	<script>
			  		banner_loja(
			  			'<?=base_url("assets/uploads/files/".$loja["logo"])?>',
			  			<?=$loja["percentual_desconto"]?>,
			  			<?=$loja["id_loja"]?>);
			  	</script>  	
		<?php endif;endforeach;?>
			</script>
			</body>
			</html>
		<?php
	}

}
//fim