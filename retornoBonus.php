<?
	require("inc/engine/include.php");	
	
	if ($_POST){
	
		pre ($_POST);
		
		$token				= $_POST['token'];	
		$idVendaCabine		= $_POST['idVendaCabine'];	
		$loginTelexFree		= $_POST['loginTelexFree'];
		$idTelexFree		= $_POST['idTelexFree'];
		$nome				= $_POST['nome'];
		$sobrenome			= $_POST['sobrenome'];
		$email				= $_POST['email'];
		$status				= $_POST['status'];
		$motivoFalha		= $_POST['motivoFalha'];
		$dtProcessamento	= $_POST['dtProcessamento'];		
		
		$tokenGenerator = new tokenGenerator();
		$string = implode('|', array(
			$idVendaCabine,
			$loginTelexFree,
			$idTelexFree,
			$nome,
			$sobrenome,
			$email,
			$status,
			$motivoFalha,
			$dtProcessamento,
		));
		$tokenConfirma = $tokenGenerator->get($string);
		
		// echo $tokenConfirma;

		if ($token == $tokenConfirma) {
			
			if ($status	== 1){
			
				echo "Sucesso!<br />";			
				echo "<strong>Procedimento:</strong><br />";
				echo "Update dos dados de pagamento<br />";
				echo "Coloco a cabine como ocupada<br />";	
				echo "Insiro o c&oacute;digo da venda<br />";
				echo "Insiro no controle do CMA<br /><br />";			
				echo "<strong>Informar o cliente!</strong><br />";
			
			
			}else{
			
				echo "Falha!<br />";			
				echo "<strong>Procedimento:</strong><br />";
				echo "Desativo a vendacabine<br />";
				echo "Coloco o pagamento como cancelado<br />";	
				echo "Libero a cabine<br />";
				echo "Insiro no controle do CMA<br /><br />";			
				echo "<strong>Informar o cliente!</strong><br />";
			}
			
		}else{
		
			echo "Token não corresponde";
			
		}		
		
	}else{
	
		echo "POST n&atilde;o informado";
		
	}		
?>
<!--
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pt">
    <head>
        <title>Retorno do bônus</title>
		<meta http-equiv="X-UA-Compatible" content="IE=8" />
        <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
        <meta http-equiv="content-language" content="pt-br" />
        <meta name="description" content="A TelexFREE escolheu o Rio de Janeiro para sua 1ª Convenção Internacional. Serão dias de treinamentos e muita festa em um luxuoso navio fretado exclusivamente para a TelexFREE." />
        <meta name="keywords" content="TelexFREE, convenção internacional, navio, Orchestra, Bruno & Marrone, festas, cruzeiro" />
        <meta name="robots" content="index,follow" />
        <link rel="shortcut icon" href="favicon.ico" />
		<meta http-equiv='cache-control' content='no-cache' />
		<meta http-equiv='expires' content='0' />
		<meta http-equiv='pragma' content='no-cache' />	
		
		<script type="text/javascript" src="inc/js/jquery.js"></script>
	
	
	</head>
    <body class="home">
		
		<div class="conteudo">
		
			<form id="frmDados" name="frmDados" method="post" action="" >	

				<fieldset class='comInput'>
					<legend>Cadastro</legend>
					
					<label for="token">token: </label>						
					<input type="text" class="itemForm4" name="token" id="token" /><br />
					
					<label for="status">status: </label>						
					<input type="text" class="itemForm4" name="status" id="status" /><br />

					<label for="codVenda">idVendaCabine: </label>						
					<input type="text" class="itemForm4" name="idVendaCabine" id="idVendaCabine" /><br />
					
					<label for="loginTelexFree">loginTelexFree: </label>						
					<input type="text" class="itemForm4" name="loginTelexFree" id="loginTelexFree" /><br />
					
					<label for="idTelexFree">idTelexFree: </label>						
					<input type="text" class="itemForm4" name="idTelexFree" id="idTelexFree" /><br />
					
					<label for="dtProcessamento">dtProcessamento: </label>						
					<input type="text" class="itemForm4" name="dtProcessamento" id="dtProcessamento" /><br />
					
					<label for="motivoFalha">motivoFalha: </label>						
					<input type="text" class="itemForm4" name="motivoFalha" id="motivoFalha" /><br />
					
					<label for="nome">nome: </label>						
					<input type="text" class="itemForm4" name="nome" id="nome" /><br />
					
					<label for="sobrenome">sobrenome: </label>						
					<input type="text" class="itemForm4" name="sobrenome" id="sobrenome" /><br />
					
					<label for="email">email: </label>						
					<input type="text" class="itemForm4" name="email" id="email" /><br />
				
				</fieldset>
				
				<div>
					<a href="javascript:;" id="bt-inserir" class="bt-inserir btnInterno comMargem">Continuar</a>
				</div>
			</form>
			
		</div>
			
		<script type="text/javascript">		
		
			$(document).ready(function(){	
			
				$('#bt-inserir').click(function(){							
					
					$("#frmDados").submit();	
		
				});
				
			});
		
		</script>		
		
	</body>
</html>
-->