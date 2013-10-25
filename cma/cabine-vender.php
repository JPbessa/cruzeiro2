<? 
	require("../inc/engine/include.php");	

	seguranca();
	bloqueiaUsuarioCotacao();

	if($_GET){					
		$id = $_GET['id'];
		
		if(is_numeric($id)){					
			if(existeEsseRegistro($id, "cabine", $conexao)){
				$id = $_GET['id'];
			}else{
				$_SESSION['strErroCB'] = "A cabine n&atilde;o existe no banco de dados.";
				redirectTo(WEB_ROOT_CABINE);
			}						
		}else{
			$_SESSION['strErroCB'] = "O id passado n&atilde;o &eacute; num&eacute;rico.";
			redirectTo(WEB_ROOT_CABINE);			
		}
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pt">
    <head>
        <title>Vender Cabine | <?=PROJETO?></title>
		<meta http-equiv="X-UA-Compatible" content="IE=8" /><!-- Enable IE8 Standards mode -->
        <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
        <meta http-equiv="content-language" content="pt-br" />
        <meta name="description" content="A TelexFREE escolheu o Rio de Janeiro para sua 1ª Convenção Internacional. Serão dias de treinamentos e muita festa em um luxuoso navio fretado exclusivamente para a TelexFREE." />
		<meta name="keywords" content="TelexFREE, convenção internacional, navio, Orchestra, Bruno & Marrone, festas, cruzeiro" />
        <meta name="robots" content="index,follow" />
        <link rel="shortcut icon" href="favicon.ico" />
		<meta http-equiv='cache-control' content='no-cache'>
		<meta http-equiv='expires' content='0'>
		<meta http-equiv='pragma' content='no-cache'>	
	
		<script type="text/javascript" src="../inc/js/jquery.js"></script>
		<script type="text/javascript" src="../inc/js/jquery.simplemodal.js"></script>
		<script type="text/javascript" src="../inc/js/confirm.js"></script>
		<script type="text/javascript" src="../inc/js/funcoes.js"></script>
		<script type="text/javascript" src="../inc/js/jquery.maskedinput.js"></script>
		<script type="text/javascript" src="../inc/js/ui.datepicker.js"></script>
		<script type="text/javascript" src="../inc/js/passwordStrengthMeter.js"></script>	
	
		<link rel="stylesheet" type="text/css" href="../inc/css/screen.css" />
		<link rel="stylesheet" type="text/css" href="../inc/css/calendario.css" />
		<link rel="stylesheet" type="text/css" href="../inc/css/screen_imprimir.css" media="print" />

</head>
<body>	

	<?
	include_once("topo.php"); 
	
	// verifica se tem reserva
	$strSQL 	= 	"SELECT	idVendaCabine
					FROM vendacabine
					WHERE blnAtivo = 1
					AND idCabine = ".$id;	

	$resultSet		= query_execute($strSQL);
	$idVendaCabine	= mysql_result($resultSet, 0,0);
	
	if ($idVendaCabine == ""){
		
		// envia para primeira etapa: quantidade de passageiros
		redirectTo(WEB_ROOT_CMA."/vendas-passageiro/".$id);
	
	}else{
	
		$strSQL 	= 	"SELECT	count(idPassageiro)
						FROM passageiro
						WHERE idVendaCabine = ".$idVendaCabine;	

		$resultSet		= query_execute($strSQL);
		$quantPassageiro	= mysql_result($resultSet, 0,0);
	
		if ($quantPassageiro == 0){
		
			// envia para terceira etapa: dados do passageiro
			redirectTo(WEB_ROOT_CMA."/vendas-dados/".$idVendaCabine);
		
		}else{
		
			$sql 	= 	"SELECT idPagamento, idStatusPagamento 
						FROM pagamento
						WHERE idVendaCabine = ".$idVendaCabine;	

			$resultado		= query_execute($sql, $conexao) or die ("Não foi possível executar a consulta");
			$resultRow		= mysql_fetch_array($resultado);
			
			$idPagamento			= $resultRow['idPagamento'];
			$idStatusPagamento		= $resultRow['idStatusPagamento'];
		
			if ($idPagamento == ""){
			
				// envia para quarta etapa: dados do pagamento
				redirectTo(WEB_ROOT_CMA."/vendas-pagamento/".$idVendaCabine);
			
			}else{
			
				if ($idStatusPagamento== STATUS_AGUARDANDO_PAGAMENTO){
					
					// envio para dar inicio ao processo de pagamento
					redirectTo(WEB_ROOT_CMA."/vendas-processar/".$idVendaCabine);
				
				}else{
					if ($idStatusPagamento == STATUS_PROCESSANDO_PAGAMENTO){
						
						// envio para concluir o processo de pagamento
						redirectTo(WEB_ROOT_CMA."/vendas-concluir/".$idVendaCabine);
					
					}else{
						
						// envio para visualizar os dados da venda
						redirectTo(WEB_ROOT_CMA."/vendas-visualizar/".$idVendaCabine);
					
					}								
				}	
			}				
		}
	}
	?>
	</body>
</html>
