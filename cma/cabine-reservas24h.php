<? 
require("../inc/engine/include.php");	

seguranca();
bloqueiaUsuarioCotacao();

	if ($_POST){
	
		$liberarCabine	= $_POST['liberarCabine'];		
		
		foreach ($liberarCabine as $k => $value){
		
			list ($idCabine, $idVendaCabine) = split ('-', $liberarCabine[$k]);
			
			iniciaTransacao();
			
			// desativo a vendacabine
			$strSQL		= 	"UPDATE vendacabine SET blnAtivo = 0
							WHERE idVendaCabine = ".$idVendaCabine;
			$resultSet		= query_execute($strSQL);
			
			// se tiver pagamento tenho que colocar como cancelado		
			$strSQL		= 	"UPDATE pagamento SET idStatusPagamento = ".STATUS_PAGAMENTO_CANCELADO."
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
						'Libera&ccedil;&atilde;o da cabine (ID ".$idCabine.") e cancelamento da inten&ccedil;&atilde;o de pagamento',
						'Reserva da cabine com mais de 24h'
					)";					
			$resultSet	= query_execute($sql);
		
			finalizaTransacao();			
		
		}
		
		$_SESSION['paginaAnterior'] = "alterar";
		$_SESSION['strErro'] = "";					
		redirectTo(WEB_ROOT_RESERVA);
		exit;
		
	}
	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pt">
    <head>
        <title>Gerenciar reservas de cabine | <?=PROJETO?></title>
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

		<script type="text/javascript" src="inc/js/jquery.js"></script>
		<script type="text/javascript" src="inc/js/jquery.simplemodal.js"></script>
		<script type="text/javascript" src="inc/js/confirm.js"></script>
		<script type="text/javascript" src="inc/js/funcoes.js"></script>
		<script type="text/javascript" src="inc/js/jquery.tablesorter.min.js"></script>	

		<link rel="stylesheet" type="text/css" href="inc/css/screen.css" />
		<link rel="stylesheet" type="text/css" href="inc/css/screen_imprimir.css" media="print" />

		<script type="text/javascript">	
			
			$().ready(function() {
				
				$("#gerenciar").tablesorter({ 					
					headers: {						 
						7: { 
							sorter: false 
						} 
					} 
				});
				
				$("#marcarTodos").click(function(){
					
					if ($(this).attr("checked")){
						$(":checkbox[name*=liberarCabine]").attr("checked",true);				
					}else{
						$(":checkbox[name*=liberarCabine]").removeAttr("checked")				
					}		
				
				});
				
				$("#bt-inserir").click(function(){
				
					if($(":checkbox[name*=liberarCabine]:checked").length == 0){
						$("#MsgErroValida").empty();
						$("#MsgErroValida").append("Os seguintes erros foram encontrados:<br />1. Selecione pelo menos uma cabine para liberar.");
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
			<li><a href="javascript:window.print();"><img src="inc/img/button/printer.png" alt="imprimir" title="imprimir" /></a></li>
		</ul>
		<br/>
		<h2 class="titulo1">Gerenciar reservas de Cabine</h2>
	</div>
		
	<div class="conteudo">

		<fieldset>
			<legend>Gerenciar reservas de Cabine</legend>					
			
				<?
				if ($_POST){	
					
					$sqlParse = "";
					$busca	= RetiraPlicas(trim($_POST['buscaPor']));
					
					if ($busca != ""){
						if (is_numeric($busca)){
							$sqlParse .= " AND (ce.numCabine = $busca)";							
						}else{				
							$sqlParse .= " AND ( ce.descricaoBr like '%$busca%')";
						}
					}
				}
				
				$sql		= 	"SELECT DATEDIFF(now(),vc.dtLogCabine) as diasReserva, DATE_FORMAT(vc.dtLogCabine, '%d/%m/%Y') AS dtLogCabine, ce.idCabine, vc.observacaoReserva, ce.descricaoBr, ce.numCabine, ce.deck, ce.categoria, ce.ocupacaoMaxima, ce.idStatus, vc.idVendaCabine
								FROM cabine ce, vendacabine vc
								WHERE ce.idCabine = vc.idCabine
								AND vc.blnAtivo =1
								AND ce.idStatus = ".STATUS_RESERVADO."
								AND DATEDIFF(now(),dtLogCabine) >= 1
								".$sqlParse."
								ORDER BY diasReserva DESC, ce.numCabine ASC";
				
				$resultado		= query_execute($sql, $conexao) or die ("Não foi possível executar a consulta");
				$intContagem	= (int) mysql_num_rows($resultado);
				
				?>
			
				<div class="status">
					<label>Status:</label>
					<strong><?=$intContagem?></strong> registros cadastrados
				</div>				

				<div class="boxTabelaGerenciar">
				
					<div id="MsgErroValida" class='MsgErroValida'></div>
					
					<?
					if (isset($_SESSION['paginaAnterior'])){
						
						if ($_SESSION['paginaAnterior'] == "alterar"){
							$_SESSION['paginaAnterior'] = "";
							echo "<div id='divMsg' class='MsgOk'>";
							echo "Operação realizada com sucesso. Cabine liberada e cancelamento da intenção de pagamento";
							echo "</div>";				
						}
					}

					if (isset($_SESSION['strErroCB'])){					
						echo "<div id='divMsg' class='MsgError'>";
						echo $_SESSION['strErroCB'];
						echo "</div>";	
						unset($_SESSION['strErroCB']);					
					}
					
					?>

					<form id="frmDados" name="frmDados" method="post" action="" >	
						<table id="gerenciar" class="tablesorter listaValores">
							<thead>
								<tr>
									<th>Nº cabine&nbsp;&nbsp;&nbsp;</th>
									<th>Descrição</th>
									<th>Status cabine</th>								
									<th>Observação</th>								
									<th>Dias de reserva</th>								
									<th>Status pagamento</th>
									<th>Pagamento</th>
									<th class="trOpcoes"><input type="checkbox" id="marcarTodos" name="marcarTodos" /></th>							
								</tr>
							</thead>
							<?
							if ($intContagem > 0){	
							
								while ($linha = mysql_fetch_array($resultado)) {
									
									
									
									$sqlPagamento	= 	"SELECT tipoPagamento, DATE_FORMAT(dtLogPagamento, '%d/%m/%Y') AS dtLogPagamento, idStatusPagamento 
														FROM pagamento
														WHERE idVendaCabine=".$linha['idVendaCabine'];
													
									$resultPag		= query_execute($sqlPagamento, $conexao) or die ("Não foi possível executar a consulta");
									$resultRowPag	= mysql_fetch_array($resultPag);								
									
									$tipoPagamento			= $resultRowPag['tipoPagamento'];								
									$dtLogPagamento			= $resultRowPag['dtLogPagamento'];								
									$idStatusPagamento		= $resultRowPag['idStatusPagamento'];								
									
									
									?>		
									<tr class='linhaTabela'>
										<td class="trCenter"><?=$linha["numCabine"]?></td>
										<td><?=utf8_encode($linha["descricaoBr"])?></td>
										<td class="trCenter">
										<?
											if ($linha["idStatus"] == STATUS_LIVRE){
												echo "<span class='verde'>Livre</span>";
											}
											if ($linha["idStatus"] == STATUS_RESERVADO){
												echo "<span class='amarelo'>Reservado</span>";
											}
											if ($linha["idStatus"] == STATUS_OCUPADO){
												echo "<span class='cinza'>Ocupado</span>";
											}
										?>									
										</td>
										<td><?=utf8_encode($linha["observacaoReserva"])?></td>	
										<td class="trCenter">
											<?
											if ($linha["diasReserva"] > 1){
												echo "<span class='diasReservaCabine'>".$linha["diasReserva"]." dias</span><br /><span class='detalheCabine'>Desde de ".$linha["dtLogCabine"]."</span></td>";
											}else{
												echo "<span class='diasReservaCabine'>".$linha["diasReserva"]." dia</span><br /><span class='detalheCabine'>Desde de ".$linha["dtLogCabine"]."</span></td>";
											}
											?>
										<td class="trCenter">
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
											if ($idStatusPagamento == ""){
												echo "<span class='vermelho'>Sem Pagamento</span>";
											}
											?>
										</td>
										<td class="trCenter">
										<?
										if ($tipoPagamento == PAGAMENTO_CARTAO){
											echo "Cartão<span class='detalheCabine'>Registrado em ".$dtLogPagamento	."</span>";
										}
										if ($tipoPagamento == PAGAMENTO_BONUS){
											echo "Bônus<span class='detalheCabine'>Registrado em ".$dtLogPagamento	."</span>";
										}
										if ($tipoPagamento == ""){
											echo "Sem pagamento";
										}									
										?>
										</td>
										<td class='trOpcoes'>
											<input type="checkbox" name="liberarCabine[]" value="<?=$linha['idCabine']."-".$linha['idVendaCabine']?>">
										</td>
									</tr>
								<?
								}
							}
							?>
							
						</table>					
					</form>	
					
					<?
					if ($intContagem > 0){	
						echo "<a href='javascript:;' id='bt-inserir' class='bt-inserir btnInterno comMargem'>Liberar</a>";					
					}					
					?>
					
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
