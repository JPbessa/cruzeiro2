<? 
	require("../inc/engine/include.php");	
	require("email.php");	
	

	seguranca();
	bloqueiaUsuarioCotacao();
	
	$intErros			= 0;
	
	if ($_POST){

		$idCabine		= $_POST['IdCabine'];
		$idVendaCabine	= $_POST['idVendaCabine'];
		$status			= $_POST['status'];
		$idTelex		= $_POST['idTelex'];
		$dtProcessamento	= formataDataHora($_POST['dtProcessamento']);
		$descFalha		= utf8_decode(RetiraPlicas($_POST['descFalha']));	
		$observacao		= RetiraPlicas($_POST['observacao']);
		
		if ($status == 1){
		
			iniciaTransacao();
	
			// troco o status do pagamento para concluido
			$strSQL		= 	"UPDATE pagamento SET 
							idStatusPagamento = ".STATUS_PAGAMENTO_CONCLUIDO.",
							idTelexfree = ".$idTelex.",
							dtProcessamento = '".$dtProcessamento."'
							WHERE idVendaCabine = ".$idVendaCabine;
			$resultSet		= query_execute($strSQL);
			// echo $strSQL;
			
			// coloco a cabine como ocupada
			$strSQL		= 	"UPDATE cabine SET idStatus = ".STATUS_OCUPADO."
							WHERE idCabine = ".$idCabine;
			$resultSet	= query_execute($strSQL);
			// echo $strSQL;
			
			
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
						'Conclusão do pagamento da cabine (ID ".$idCabine.")',
						'".utf8_decode($observacao)."'
					)";					
			$resultSet	= query_execute($sql);
			
			// envia email para o cliente
			$email = getEmail($idVendaCabine);		
			
			if ($email <> ""){
				$codVendaFinal = getCodVendaFinal($idVendaCabine);
				$mensagem = criaEmailSucesso($codVendaFinal);
				enviaEmail(EMAIL_DE, utf8_decode(NOME_EMAIL_DE), $email.";".EMAIL_EVENTO.";".USER_EMAIL, "Confirmação do pagamento", $mensagem);			
			}			
			
			finalizaTransacao();
			
			$_SESSION['paginaAnterior'] = "concluir";
			$_SESSION['strErro'] = "";					
			redirectTo(WEB_ROOT_VENDAS);
			exit;
		
		}else{
		
			iniciaTransacao();
			
			// desativo a vendacabine
			$strSQL		= 	"UPDATE vendacabine SET blnAtivo = 0
							WHERE idVendaCabine = ".$idVendaCabine;
			$resultSet		= query_execute($strSQL);
			
			// estorno o pagamento	
			$strSQL		= 	"UPDATE pagamento SET 
							idStatusPagamento = ".STATUS_PAGAMENTO_CANCELADO.",
							idTelexfree = ".$idTelex.",
							dtProcessamento = '".$dtProcessamento."',
							motivoFalha = '".$descFalha."'							
							WHERE idVendaCabine = ".$idVendaCabine;
			$resultSet		= query_execute($strSQL);
			
			// libero a cabine
			$strSQL		= 	"UPDATE cabine SET idStatus = ".STATUS_LIVRE."
							WHERE idCabine = ".$idCabine;
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
						'Liberação da cabine (ID ".$idCabine.") e cancelamento da pagamento',
						'".utf8_decode($observacao)."'
					)";					
			$resultSet	= query_execute($sql);
			
			// envia email para o cliente
			$email = getEmail($idVendaCabine);		
			
			if ($email <> ""){
				$codVendaFinal = getCodVendaFinal($idVendaCabine);
				$mensagem = criaEmailFalhaBonus($codVendaFinal, $descFalha);
				enviaEmail(EMAIL_DE, utf8_decode(NOME_EMAIL_DE), $email.";".EMAIL_EVENTO.";".USER_EMAIL, "Falha no pagamento", $mensagem);			
			}	
		
			finalizaTransacao();
			
			$_SESSION['paginaAnterior'] = "falha";
			$_SESSION['strErro'] = "";					
			redirectTo(WEB_ROOT_VENDAS);
			exit;
		
		}
		
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
        <title>Concluir venda de Cabine | <?=PROJETO?></title>
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
				
				$("#falhaProcessamento").hide();
				$("#dtProcessamento").mask("99/99/9999 99:99");
				$('#idTelex').numeric({nocaps:true,ichars:'~´`^çáàãâéèêíìóòôõúùûüäëïöü_!@#$%¨&*+={}[]?/:;<>.,'});
				
				
				function Trim(str){
					return str.replace(/^\s+|\s+$/g,"");
				}
				
				$('#bt-limpar').click(function(){
					limpar();
				});
				
				$('#status').change(function(){
					if ($("#status option:selected").val() == 0){
						$("#falhaProcessamento").show();
					}else{
						$("#falhaProcessamento").hide();
					}
				});			
				
				$('#bt-inserir').click(function(){
				
					var intErros	= 0;
					var strErros	= "Os seguintes erros foram encontrados:<br \/>";
					
					var status			= Trim($('#status').val());
					var idTelex			= Trim($('#idTelex').val());
					var dtProcessamento	= Trim($('#dtProcessamento').val());
					var descFalha		= Trim($('#descFalha').val());
					var observacao 		= Trim($('#observacao').val());

					if (status == "selecione"){
						intErros++;
						strErros	+= intErros + ". O campo 'Status' não foi selecionado;<br/>";
					}
					
					if (idTelex == ""){
						intErros++;
						strErros	+= intErros + ". O campo 'Id TelexFREE' não foi preenchido;<br/>";
					}
					
					if (dtProcessamento == ""){
						intErros++;
						strErros	+= intErros + ". O campo 'data de Processamento' não foi preenchido;<br/>";
					}
					
					if (status == "0"){
						if (descFalha == ""){
							intErros++;
							strErros	+= intErros + ". O campo 'Motivo da falha' não foi preenchido;<br/>";
						}
					}
				
					if (observacao == ""){
						intErros++;
						strErros	+= intErros + ". O campo 'Observação' não foi preenchido;<br/>";
					}
					
					if (!$("#aceiteProcesso").is(':checked')){
							intErros++;
							strErros	+= intErros + ". O campo 'Conclusão do pagamento' não foi marcado;<br/>";
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
		<h2 class="titulo1">Concluir venda <?=$codVendaFinal?></h2>
	</div>
		
	<div class="conteudo">
	
		<fieldset class='comInput'>
			<legend>Concluir</legend>					
			
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
					
						<label for="status">Status:</label>
						<select name="status" id="status">
							<option value="selecione">Selecione</option>
							<option value="0">Falha</option>
							<option value="1">Sucesso</option>
						</select><br />					
						
						<label for="idTelex">Id TelexFREE:</label>
						<input type="text" class="itemForm4" name="idTelex" id="idTelex" maxlength="70" /><br />
						
						<label for="dtProcessamento">Data do processamento:</label>
						<input type="text" class="itemForm4" name="dtProcessamento" id="dtProcessamento" maxlength="70" /><br />
						
						<div id="falhaProcessamento">
							<label for="descFalha">Motivo da falha:</label>
							<textarea id="descFalha" name="descFalha"></textarea><br />												
						</div>
						
						<label for="observacao">Observação:</label>
						<textarea id="observacao" id="observacao" name="observacao"></textarea>
						<input type="hidden" id="IdCabine" name="IdCabine" value="<?=$idCabine?>" />
						<input type="hidden" id="idVendaCabine" name="idVendaCabine" value="<?=$idVendaCabine?>" />
						
						<br /><br />
						<input type="checkbox" class="termoAceite" id="aceiteProcesso" name="aceiteProcesso" value="1">
						<label for="aceiteProcesso" class="limpaFormatacao"><span id="txtAceitoCartao">Atesto que <strong>conclui o pagamento</strong> dessa venda.</span></label>
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
