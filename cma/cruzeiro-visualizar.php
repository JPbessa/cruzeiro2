<? 
	require("../inc/engine/include.php");	

	seguranca();
	bloqueiaUsuarioCotacao();


	if($_GET){					
		$id = $_GET['id'];
		
		if(is_numeric($id)){					
			if(existeEsseRegistro($id, "cruzeiro", $conexao)){
				$id = $_GET['id'];
			}else{
				$_SESSION['strErroCB'] = "O cruzeiro n&atilde;o existe no banco de dados.";
				redirectTo(WEB_ROOT_CRUZEIRO);
			}						
		}else{
			$_SESSION['strErroCB'] = "O id passado n&atilde;o &eacute; num&eacute;rico.";
			redirectTo(WEB_ROOT_CRUZEIRO);			
		}
	}
	
	$sql 	= 	"SELECT nome, itinerario, portoChegada, portoSaida, DATE_FORMAT( dtSaida, '%d/%m/%Y' ) AS dtSaida, DATE_FORMAT( dtChegada, '%d/%m/%Y' ) AS dtChegada
				FROM cruzeiro
				WHERE idCruzeiro=".$id;	
	
	$resultado		= query_execute($sql, $conexao) or die ("Não foi possível executar a consulta");
	$resultRow		= mysql_fetch_array($resultado);
	
	$nome			= $resultRow['nome'];
	$itinerario		= $resultRow['itinerario'];
	$portoChegada	= $resultRow['portoChegada'];
	$portoSaida		= $resultRow['portoSaida'];
	$dtSaida		= $resultRow['dtSaida'];
	$dtChegada		= $resultRow['dtChegada'];
	
	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pt">
    <head>
        <title>Visualizar Cruzeiro | <?=PROJETO?></title>
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

	<?php include_once("topo.php") ?>
		
	<div class="control">				
		<ul>
			<li><a href="<?=WEB_ROOT_CRUZEIRO?>"><img src="../inc/img/button/back.png" alt="Voltar" title="Voltar" /></a></li>
		</ul>
		<br/>
		<h2 class="titulo1">Visualizar Cruzeiro</h2>
	</div>
		
	<div class="conteudo">
	
		<fieldset class='comInput'>
			<legend>Visualizar</legend>					
			
				<div class="boxTabela">	

					<div id="MsgErroValida" class='MsgErroValida'></div>	
					
					<form id="frmDados" name="frmDados" method="post" action="" >					
						<label for="nome">Nome:</label>
						<div class="visualiza"><?=utf8_encode($nome)?></div><br /><br />
						
						<label for="nome">Itinerário:</label>
						<div class="visualiza"><?=utf8_encode($itinerario)?></div><br /><br />
						
						<label for="nome">Porto chegada:</label>
						<div class="visualiza"><?=utf8_encode($portoChegada)?></div><br /><br />
						
						<label for="nome">Porto saída:</label>
						<div class="visualiza"><?=utf8_encode($portoSaida)?></div><br /><br />
						
						<label for="data">Data saída: </label>
						<div class="visualiza"><?=$dtSaida?></div><br /><br />
						
						<label for="data">Data chegada: </label>
						<div class="visualiza"><?=$dtChegada?></div><br /><br />					

					</form>
					
					<br/><br/>
					<a href="<?=WEB_ROOT_CRUZEIRO?>" id="bt-voltar" class="bt-inserir btnInterno comMargem">Voltar</a>
					<a href="<?=WEB_ROOT_CMA?>/cruzeiro-alterar/<?=$id?>" id="bt-limpar" class="btnInterno comMargem">Alterar</a>

				</div>			
			</fieldset>
		</div>
		
		<!--layout inferior-->
		<div class="rodape">
		  <p>2013 - <?=PROJETO?>. Todos os direitos reservados.</p>
		</div>
		<!--fim layout inferior-->
		
	</body>
</html>
