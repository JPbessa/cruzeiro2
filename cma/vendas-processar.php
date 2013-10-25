<? 
	require("../inc/engine/include.php");	

	seguranca();
	bloqueiaUsuarioCotacao();
	
	$intErros			= 0;
	
	if ($_POST){

		$idCabine		= $_POST['IdCabine'];
		$idVendaCabine	= $_POST['idVendaCabine'];
		$observacao		= RetiraPlicas($_POST['observacao']);
	
		// troco o status do pagamento para processando
		$strSQL		= 	"UPDATE pagamento SET idStatusPagamento = ".STATUS_PROCESSANDO_PAGAMENTO."
						WHERE idVendaCabine = ".$idVendaCabine;
		$resultSet		= query_execute($strSQL);
		
		// Insere no controle do CMA
		$sql = "INSERT INTO controlecma (
					idVendaCabine,
					idUsuario, 
					dtLogControle,
					operacao,
					motivo
				)VALUES(
					".$idVendaCabine.",
					".$_SESSION['userId'].",
					'".date("Y-m-d H:i:s")."',
					'Processando pagamento da cabine (ID ".$idCabine.")',
					'".utf8_decode($observacao)."'
				)";					
		$resultSet	= query_execute($sql);
		
		$_SESSION['paginaAnterior'] = "processar";
		$_SESSION['strErro'] = "";					
		redirectTo(WEB_ROOT_VENDAS);
		exit;
		
	}else{
		
		if($_GET){					
			$id = $_GET['id'];
			
			if(is_numeric($id)){					
				if(existeEsseRegistro($id, "vendacabine", $conexao)){
					$id = $_GET['id'];
				}else{
					$_SESSION['strErroCB'] = "A Venda Cabine n&atilde;o existe no banco de dados.";
					redirectTo(WEB_ROOT_VENDAS);
				}						
			}else{
				$_SESSION['strErroCB'] = "O id passado n&atilde;o &eacute; num&eacute;rico.";
				redirectTo(WEB_ROOT_VENDAS);			
			}	
		}
	}
	
	$sql 	= 	"SELECT codVendaFinal, vc.idCabine, idVendaCabine, descricaoCabine, ocupacaoMaximaCabine, adultoHospede, criancaHospede, precoVendaCabineReal, precoVendaCabine, DATE_FORMAT(dtLogCabine, '%d/%m/%Y') AS dtLogCabine, observacaoReserva, c.numCabine 
				FROM vendacabine vc, cabine c
				WHERE vc.idCabine = c.idCabine
				AND blnAtivo = 1
				AND idVendaCabine = ".$id;	
	
	$resultado		= query_execute($sql, $conexao) or die ("Não foi possível executar a consulta");
	$resultRow		= mysql_fetch_array($resultado);
	
	$idCabine				= $resultRow['idCabine'];
	$codVendaFinal			= $resultRow['codVendaFinal'];
	$idVendaCabine			= $resultRow['idVendaCabine'];
	$descricaoCabine		= $resultRow['descricaoCabine'];
	$ocupacaoMaximaCabine	= $resultRow['ocupacaoMaximaCabine'];
	$quantHospede			= $resultRow['adultoHospede']+$resultRow['criancaHospede'];
	$precoVendaCabineReal	= $resultRow['precoVendaCabineReal'];
	$precoVendaCabine		= $resultRow['precoVendaCabine'];
	$dtLogCabine			= $resultRow['dtLogCabine'];
	$observacaoReserva		= $resultRow['observacaoReserva'];
	$numCabine				= $resultRow['numCabine'];
	
	
	$sqlPag 	= 	"SELECT tipoPagamento, idTelexfree, loginTelexfree, bonusConsumidoTelexfree, nome, sobrenome, telefone, celular, email, idStatusPagamento 
					FROM pagamento
					WHERE idVendaCabine=".$idVendaCabine;	
	
	$result		= query_execute($sqlPag, $conexao) or die ("Não foi possível executar a consulta");
	$linhaPag	= mysql_fetch_array($result);
	
	$tipoPagamento				= $linhaPag['tipoPagamento'];
	$idTelexfree				= $linhaPag['idTelexfree'];
	$loginTelexfree				= $linhaPag['loginTelexfree'];
	$bonusConsumidoTelexfree	= $linhaPag['bonusConsumidoTelexfree'];
	$nome				= $linhaPag['nome']." ".$linhaPag['sobrenome'];
	$telefone			= $linhaPag['telefone'];
	$celular			= $linhaPag['celular'];
	$email				= $linhaPag['email'];
	$idStatusPagamento	= $linhaPag['idStatusPagamento'];
	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pt">
    <head>
        <title>Processar venda de Cabine | <?=PROJETO?></title>
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
				
				$('#bt-inserir').click(function(){
				
					var intErros	= 0;
					var strErros	= "Os seguintes erros foram encontrados:<br \/>";
					
					var observacao 		= Trim($('#observacao').val());
				
					if (observacao == ""){
						intErros++;
						strErros	+= intErros + ". O campo 'Observação' não foi preenchido;<br/>";
					}
					
					if (!$("#aceiteProcesso").is(':checked')){
							intErros++;
							strErros	+= intErros + ". O campo 'Processo de pagamento' não foi selecionado;<br/>";
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
			<li><a href="<?=WEB_ROOT_VENDAS?>"><img src="../inc/img/button/back.png" alt="Voltar" title="Voltar" /></a></li>
		</ul>
		<br/>
		<h2 class="titulo1">Processar venda <?=$codVendaFinal?></h2>
	</div>
		
	<div class="conteudo">
	
		<fieldset class='comInput'>
			<legend>Estornar</legend>					
			
				<div class="boxTabela">		
				
					<div id="resumoReserva">
						<h3>Resumo da sua reserva:</h3>
						<ul>
							<li><strong class="opaco">Nº da cabine: </strong> <?=$numCabine?></li>
							<li><strong class="opaco">Descrição: </strong> <?=utf8_encode($descricaoCabine)?></li>
							<li><strong class="opaco">Quantidade de hóspedes: </strong> <?=$quantHospede?></li>
							<li><strong class="opaco">Data da reserva: </strong> <?=$dtLogCabine?></li>
							<li><strong class="opaco">Observação: </strong> <?=$observacaoReserva?></li>
						</ul>
						<br />
						<h3>Resumo do pagamento:</h3>
						<ul>
							<?
							if ($tipoPagamento == PAGAMENTO_BONUS){
								echo "<li><strong class='opaco'>Tipo: </strong> Bônus</li>";
								echo "<li><strong class='opaco'>ID TelexFREE: </strong> ".$idTelexfree."</li>";
								echo "<li><strong class='opaco'>Login TelexFREE: </strong> ".$loginTelexfree."</li>";
								echo "<li><strong class='opaco'>Bônus consumido: </strong> ".$bonusConsumidoTelexfree."</li>";
								echo "<li><strong class='opaco'>Venda Cabine em Dólar: </strong> ".formataParaDolar($precoVendaCabine)."</li>";
								echo "<li><strong class='opaco'>Venda Cabine em Real: </strong> ".formataParaReal($precoVendaCabineReal)."</li>";
							}else{
								echo "<li><strong class='opaco'>Tipo: </strong> Cartão</li>";
								echo "<li><strong class='opaco'>Venda Cabine em Dólar: </strong> ".formataParaDolar($precoVendaCabine)."</li>";
								echo "<li><strong class='opaco'>Venda Cabine em Real: </strong> ".formataParaReal($precoVendaCabineReal)."</li>";
								echo "<li><strong class='opaco'>Nome: </strong> ".utf8_encode($nome)."</li>";
								echo "<li><strong class='opaco'>Telefone: </strong> ".$telefone."</li>";
								echo "<li><strong class='opaco'>Celular: </strong> ".$celular."</li>";							
							}
							?>
							<li><strong class="opaco">Email: </strong> <?=$email?></li>
							<li><strong class="opaco">Status: </strong>
							<?
							if ($idStatusPagamento == STATUS_AGUARDANDO_PAGAMENTO){
								echo "<span class='amarelo'>Aguardando pagamento</span>";
							}
							if ($idStatusPagamento == STATUS_PROCESSANDO_PAGAMENTO){
								echo "<span class='verdeClaro'>Processando pagamento</span>";
							}
							if ($idStatusPagamento == STATUS_PAGAMENTO_CONCLUIDO){
								echo "<span class='verde'>Pagamento concluído</span>";
							}
							if ($idStatusPagamento == STATUS_PAGAMENTO_CANCELADO){
								echo "<span class='vermelho'>Pagamento cancelado</span>";
							}
							if ($idStatusPagamento == STATUS_PAGAMENTO_ESTORNO){
								echo "<span class='vermelho'>Pagamento estornado</span>";
							}
							?>						
							</li>
					</div>
					
			
					<div id="MsgErroValida" class='MsgErroValida'></div>	
					
					<form id="frmDados" name="frmDados" method="post" action="" >					
						<label for="observacao">Observação:</label>
						<textarea id="observacao" name="observacao"></textarea>
						<input type="hidden" id="IdCabine" name="IdCabine" value="<?=$idCabine?>" />
						<input type="hidden" id="idVendaCabine" name="idVendaCabine" value="<?=$idVendaCabine?>" />
						
						<br /><br />
						<input type="checkbox" class="termoAceite" id="aceiteProcesso" name="aceiteProcesso" value="1">
						<label for="aceiteProcesso" class="limpaFormatacao"><span id="txtAceitoCartao">Atesto que já iniciei o <strong>processo de pagamento</strong> dessa venda.</span></label>
					</form>
					
					<br/><br/>
					<a href="javascript:;" id="bt-inserir" class="bt-inserir btnInterno comMargem">Enviar</a>
					<a href="javascript:;" id="bt-limpar" class="btnInterno comMargem">Resetar</a>

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
