<?
	require("inc/engine/include.php");	
	
	pre($_POST);
	
	$tokenGenerator = new tokenGenerator();
	$string = implode('|', array(
		$_POST['login'],
		$_POST['descricao'],
		$_POST['precoVendaCabine'],
		$_POST['precoVendaCabineReal'],
		$_POST['idVendaCabine'],
		$_POST['dtLogPagamento']
	));
	
	$tokenConfirma = $tokenGenerator->get($string);
	echo $tokenConfirma;
	
	if ($tokenConfirma == $_POST["token"]){
		echo "<br /><br />Token comparado com sucesso";
	}else{
		echo "<br /><br />Falha na comparação do token";
	}

?>