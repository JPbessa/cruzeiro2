<? 
	require("../inc/engine/include.php");	

	seguranca();
	bloqueiaUsuarioCotacao();
		
	$intErros			= 0;
	
	if ($_POST){

		$idCabine		= $_POST['IdCabine'];
		$adultos		= RetiraPlicas($_POST['adultos']);
		$criancas		= RetiraPlicas($_POST['criancas']);
		$vendaDolar		= $_POST['vendaFinalDolar'];
		$vendaReal		= $_POST['vendaFinalReal'];	
		$observacao		= RetiraPlicas($_POST['observacao']);
		$ocupacaoMaxima	= RetiraPlicas($_POST['ocupacaoMaxima']);
		$descricao		= RetiraPlicas($_POST['descricao']);

		if ($adultos == ""){
			$adultos = 0;
		}
		
		if ($criancas == ""){
			$criancas = 0;
		}
		
		$strSQL		= 	"SELECT COUNT(*) as livre
						FROM cabine
						WHERE idStatus = ".STATUS_LIVRE." AND idCabine = ".$idCabine;
	
		$resultSet	= query_execute($strSQL);
		$livre	= mysql_result($resultSet, 0,0);
		
		if ($livre > 0){		
			
			iniciaTransacao();
			
			// reserva a cabine
			$strSQL		= 	"UPDATE cabine SET idStatus = ".STATUS_RESERVADO."
							WHERE idCabine = ".$idCabine;
			$resultSet		= query_execute($strSQL);
			
			// Insere na vendaCabine
			$sql = "INSERT INTO vendacabine (
						idCabine,
						adultoHospede,
						criancaHospede,
						dtLogHospede,
						descricaoCabine,
						ocupacaoMaximaCabine,
						precoVendaCabine,
						precoVendaCabineReal,
						dtLogCabine,
						blnAtivo,
						observacaoReserva
					)VALUES(
						".$idCabine.",
						".$adultos.",
						".$criancas.",
						'".date("Y-m-d H:i:s")."',
						'".$descricao."',
						".$ocupacaoMaxima.",
						".moneyToBD($vendaDolar).",
						".moneyToBD($vendaReal).",
						'".date("Y-m-d H:i:s")."',
						1,
						'".utf8_decode($observacao)."'
					)";					
			$resultSet	= query_execute($sql);
			
			// Pega o ID da vendaCabine
			$strSQL		= 	"SELECT idVendaCabine
							FROM vendacabine
							WHERE blnAtivo = 1 AND idCabine = ".$idCabine." AND dtLogCabine = '".date("Y-m-d H:i:s")."'";	
			$resultSet	= query_execute($strSQL);
			$idVendaCabine	= mysql_result($resultSet, 0,0);

			// Insere no controle do CMA
			$sql = "INSERT INTO controlecma (
						idVendaCabine,
						idUsuario, 
						dtLogControle,
						operacao
					)VALUES(
						".$idVendaCabine.",
						".$_SESSION['userId'].",
						'".date("Y-m-d H:i:s")."',
						'Reserva da cabine (ID ".$idCabine.")'
					)";					
			$resultSet	= query_execute($sql);
		
			finalizaTransacao();
		
			redirectTo(WEB_ROOT_CMA."/vendas-dados/".$idVendaCabine);
			exit;
			
		}else{
			$_SESSION['paginaAnterior'] = "erroReservar";
			$_SESSION['strErro'] = "";					
			redirectTo(WEB_ROOT_CABINE);
			exit;
		}
		
	}else{
		
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
	}
	
	$sql 	= 	"SELECT descricaoBr, numCabine, ocupacaoMaxima, precoAdulto, precoCrianca
				FROM cabine
				WHERE idCabine=".$id;	
	
	$resultado		= query_execute($sql, $conexao) or die ("Não foi possível executar a consulta");
	$resultRow		= mysql_fetch_array($resultado);
	
	$descricaoBr		= $resultRow['descricaoBr'];
	$numCabine			= $resultRow['numCabine'];
	$ocupacaoMaxima		= $resultRow['ocupacaoMaxima'];
	$precoAdulto		= $resultRow['precoAdulto'];
	$precoCrianca		= $resultRow['precoCrianca'];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pt">
    <head>
        <title>Vender: Passageiros | <?=PROJETO?></title>
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
		<script type="text/javascript" src="../inc/js/alphanumeric.js"></script>	
				
		<link rel="stylesheet" type="text/css" href="../inc/css/screen.css" />
		<link rel="stylesheet" type="text/css" href="../inc/css/calendario.css" />
		<link rel="stylesheet" type="text/css" href="../inc/css/screen_imprimir.css" media="print" />

		<script type="text/javascript">
		
			$().ready(function() {
				
				$('#adultos').numeric({nocaps:true,ichars:'~´`^çáàãâéèêíìóòôõúùûüäëïöü_!@#$%¨&*+={}[]?/:;<>.,'});
				$('#criancas').numeric({nocaps:true,ichars:'~´`^çáàãâéèêíìóòôõúùûüäëïöü_!@#$%¨&*+={}[]?/:;<>.,'});

				function Trim(str){
					return str.replace(/^\s+|\s+$/g,"");
				}
				
				$('#bt-limpar').click(function(){
					limpar();
				});
				
				$(".calculaPreco").keyup(function(){
					var quantAdultos = Trim($("#adultos").val());
					var quantCriancas = Trim($("#criancas").val());
					
					if (quantAdultos == ""){
						quantAdultos = 0;
					}					
					if (quantCriancas == ""){
						quantCriancas = 0;
					}
					
					var valorAdulto = $("#valorAdulto").val();
					var valorCrianca = $("#valorCrianca").val();
					var cotacaoDolar = $("#cotacaoDolar").val();
					
					var vendaDolarAdulto = parseFloat(quantAdultos)*parseFloat(valorAdulto);
					var vendaRealAdulto = parseFloat(vendaDolarAdulto)*parseFloat(cotacaoDolar);
					
					var vendaDolarCrianca = parseFloat(quantCriancas)*parseFloat(valorCrianca);
					var vendaRealCrianca = parseFloat(vendaDolarCrianca)*parseFloat(cotacaoDolar);

					$("#vendaDolar").val((parseFloat(vendaDolarAdulto)+parseFloat(vendaDolarCrianca)).toFixed(2));
					$("#vendaReal").val((parseFloat(vendaRealAdulto)+parseFloat(vendaRealCrianca)).toFixed(2));
					
					$("#vendaFinalDolar").val((parseFloat(vendaDolarAdulto)+parseFloat(vendaDolarCrianca)).toFixed(2));
					$("#vendaFinalReal").val((parseFloat(vendaRealAdulto)+parseFloat(vendaRealCrianca)).toFixed(2));
					
				});
				
				$('#bt-inserir').click(function(){
				
					var intErros	= 0;
					var strErros	= "Os seguintes erros foram encontrados:<br \/>";
					var reDate4 = /^((0?[1-9]|[12]\d)\/(0?[1-9]|1[0-2])|30\/(0?[13-9]|1[0-2])|31\/(0?[13578]|1[02]))\/(19|20)?\d{2}$/;
					
					var adultos 		= Trim($('#adultos').val());
					var criancas 		= Trim($('#criancas').val());
					var observacao 		= Trim($('#observacao').val());
					var ocupacaoMaxima 	= Trim($('#ocupacaoMaxima').val());
					
					if (adultos == ""){
						adultos = 0;
					}
					
					if (criancas == ""){
						criancas = 0;
					}
					
					var quantHospedes = parseInt(adultos) + parseInt(criancas);
					
					if (quantHospedes == 0){
						intErros++;
						strErros	+= intErros + ". A quantidade de hóspedes deve ser maior que zero;<br/>";					
					}else{					
						if (parseInt(quantHospedes) > parseInt(ocupacaoMaxima)){
							intErros++;
							strErros	+= intErros + ". A quantidade de hóspedes excede a capacidade máxima da cabine;<br/>";	
						}
					}
					
					if (observacao == ""){
						intErros++;
						strErros	+= intErros + ". O campo 'Observação' não foi preenchido;<br/>";	
					}
					
					if(intErros != 0) {
						$("#MsgErroValida").empty();
						$("#MsgErroValida").append(strErros);
						$("#MsgErroValida").attr("style", "display:block;");	
						$('html, body').animate({ scrollTop: $("#MsgErroValida").offset().top }, 500);
					}else{
						$("#frmDados").submit();	
					}			
				
				});
			});

		</script>
		
</head>
<body>	

	<?php include_once("topo.php") ?>
		
	<div class="control">				
		<ul>
			<li><a href="<?=WEB_ROOT_CABINE?>"><img src="../inc/img/button/back.png" alt="Voltar" title="Voltar" /></a></li>
		</ul>
		<br/>
		<h2 class="titulo1">Quantidade de passageiros</h2>
	</div>
		
	<div class="conteudo">
	
		<fieldset class='comInput'>
			<legend>Reservar</legend>					
			
				<div class="boxTabela">		
				
					<div id="resumoReserva">
						<h3>Resumo da sua reserva:</h3>
						<ul>
							<li><strong class="opaco">Nº da cabine:</strong> <?=$numCabine?></li>
							<li><strong class="opaco">Descrição:</strong> <?=utf8_encode($descricaoBr)?></li>
							<li><strong class="opaco">Ocupação máxima:</strong> <?=$ocupacaoMaxima?></li>
							<li><strong class="opaco">Preço adulto:</strong> <?=formataParaDolar($precoAdulto)?></li>
							<li><strong class="opaco">Preço criança:</strong> <?=formataParaDolar($precoCrianca)?></li>
							<li><strong class="opaco">Cotação do dólar:</strong> <?="R$ ".str_replace(".", ",", cotacaoDolar())?></li>
						</ul>
					</div>
					
					<div id="MsgErroValida" class='MsgErroValida'></div>	
					
					<form id="frmDados" name="frmDados" method="post" action="" >					
						<label for="adultos">Adultos:</label>
						<input type="text" id="adultos" name="adultos" class="calculaPreco sizeCamposBox"/><br />
						
						<label for="criancas">Crianças:</label>
						<input type="text" id="criancas" name="criancas" class="calculaPreco sizeCamposBox"/><br />
						
						<label for="vendaDolar">Preço US$:</label>
						<input type="text" id="vendaDolar" name="vendaDolar" value="0" disabled="disabled" class="sizeCamposBox"/><br />
						
						<label for="vendaReal">Preço R$:</label>
						<input type="text" id="vendaReal" name="vendaReal" value="0" disabled="disabled" class="sizeCamposBox"/><br />
						
						<label for="observacao">Observação:</label>
						<textarea id="observacao" name="observacao"></textarea>

						<input type="hidden" id="IdCabine" name="IdCabine" value="<?=$id?>" />						
						<input type="hidden" id="valorCrianca" name="valorCrianca" value="<?=$precoCrianca?>" />						
						<input type="hidden" id="valorAdulto" name="valorAdulto" value="<?=$precoAdulto?>" />						
						<input type="hidden" id="cotacaoDolar" name="cotacaoDolar" value="<?=cotacaoDolar()?>" />						
						<input type="hidden" id="ocupacaoMaxima" name="ocupacaoMaxima" value="<?=$ocupacaoMaxima?>" />
						<input type="hidden" id="descricao" name="descricao" value="<?=$descricaoBr?>" />
						<input type="hidden" id="vendaFinalDolar" name="vendaFinalDolar" value="0" />
						<input type="hidden" id="vendaFinalReal" name="vendaFinalReal" value="0" />
						
					</form>
					
					<br/><br/>
					<a href="javascript:;" id="bt-inserir" class="bt-inserir btnInterno comMargem">Continuar</a>
					
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
