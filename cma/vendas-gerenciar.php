<? 
require("../inc/engine/include.php");	

seguranca();
bloqueiaUsuarioCotacao();

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pt">
    <head>
        <title>Registro de Vendas | <?=PROJETO?></title>
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
						8: { 
							sorter: false 
						} 
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
		<h2 class="titulo1">Registro de Vendas</h2>
	</div>
		
	<div class="conteudo">

		<fieldset>
			<legend>Registro de Vendas</legend>					
			
				<?
				if ($_POST){	
					
					$sqlParse = "";
					$busca	= RetiraPlicas(trim($_POST['buscaPor']));
					
					if ($busca != ""){
						$sqlParse .= " AND ((vc.codVendaFinal like '%$busca%') OR (ce.descricaoBR like '%$busca%'))";
					}
				}
				
				$sql		= 	"SELECT vc.idVendaCabine, vc.codVendaFinal, vc.precoVendaCabineReal, vc.adultoHospede+vc.criancaHospede as hospedes, ce.descricaoBR, ce.numCabine, ce.ocupacaoMaxima, ce.idStatus as statusCabine,
								pg.tipoPagamento, DATE_FORMAT( pg.dtLogPagamento, '%d/%m/%Y' ) AS dtPagamento, pg.idStatusPagamento
								FROM vendacabine vc, cabine ce, cruzeiro co, pagamento pg
								WHERE vc.idCabine = ce.idCabine
								AND ce.idCruzeiro = co.idCruzeiro
								AND vc.idVendaCabine = pg.idVendaCabine
								".$sqlParse."
								ORDER BY codVendaFinal DESC";
						
				$resultado		= query_execute($sql, $conexao) or die ("Não foi possível executar a consulta");
				$intContagem	= (int) mysql_num_rows($resultado);
				
				?>
			
				<div class="status">
					<label>Status:</label>
					<strong><?=$intContagem?></strong> registros cadastrados
				</div>				

				<!--Form de busca-->
				<form id="frmBusca" name="frmBusca" method="post" action="" >
					<input type="text" id="buscaPor" name="buscaPor" value="" class="sizeCamposBox campoBusca" />
					<a href="#" onclick="javascript: $('#frmBusca').submit();" class="btnInternoBusca">Buscar</a>
				</form>
					
				<div class="boxTabelaGerenciar">	

					<?
					if (isset($_SESSION['paginaAnterior'])){
						
						if ($_SESSION['paginaAnterior'] == "processar"){
							$_SESSION['paginaAnterior'] = "";
							echo "<div id='divMsg' class='MsgOk'>";
							echo "Processo de pagamento alterado com sucesso.";
							echo "</div>";				
						}	
						
						if ($_SESSION['paginaAnterior'] == "concluir"){
							$_SESSION['paginaAnterior'] = "";
							echo "<div id='divMsg' class='MsgOk'>";
							echo "Processo de pagamento concluído com sucesso.";
							echo "</div>";				
						}	
						
						if ($_SESSION['paginaAnterior'] == "falha"){
							$_SESSION['paginaAnterior'] = "";
							echo "<div id='divMsg' class='MsgOk'>";
							echo "Processo de pagamento cancelado com sucesso.";
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
				
					<table id="gerenciar" class="tablesorter listaValores">
						<thead>
							<tr>
								<th>Nº pedido</th>
								<th>Cabine</th>							
								<th>Nº cabine&nbsp;&nbsp;</th>
								<th>Status cabine</th>
								<th>Pagamento&nbsp;&nbsp;</th>
								<th>Data pagamento</th>
								<th>Valor</th>
								<th>Status pagamento</th>
								<th class="trOpcoes">Opções</th>							
							</tr>
						</thead>
						<?
						if ($intContagem > 0){	
						
							while ($linha = mysql_fetch_array($resultado)) {
						
								if($contador%2 == 0)
									$class = "";
								else
									$class = "par";
							?>		

								<tr class='linhaTabela'>
									<td class="trCenter"><a href="vendas-visualizar/<?=$linha["idVendaCabine"]?>" class="linkVisualizar"><?=$linha["codVendaFinal"]?></a></td>
									<td><?=utf8_encode($linha["descricaoBR"])?></td>
									<td class="trCenter"><?=utf8_encode($linha["numCabine"])?></td>
									<td class="trCenter"><?									
									if ($linha["statusCabine"] == STATUS_LIVRE){
										echo "<span class='verde'>Livre</span>";
									}
									if ($linha["statusCabine"] == STATUS_RESERVADO){
										echo "<span class='amarelo'>Reservado</span>";
									}
									if ($linha["statusCabine"] == STATUS_OCUPADO){
										echo "<span class='cinza'>Ocupado</span>";
									}
									?></td>
									<td class="trCenter"><?
									if ($linha["tipoPagamento"] == PAGAMENTO_CARTAO){
										echo "Cartão";
									}
									if ($linha["tipoPagamento"] == PAGAMENTO_BONUS){
										echo "Bônus";
									}								
									?></td>
									<td class="trCenter"><?=$linha["dtPagamento"]?></td>
									<td class="trCenter"><?=formataParaReal($linha["precoVendaCabineReal"])?></td>
									<td class="trCenter"><?
									if ($linha["idStatusPagamento"] == STATUS_AGUARDANDO_PAGAMENTO){
										echo "<span class='amarelo'>Aguardando pagamento</span>";
									}
									if ($linha["idStatusPagamento"] == STATUS_PROCESSANDO_PAGAMENTO){
										echo "<span class='verdeClaro'>Processando pagamento</span>";
									}
									if ($linha["idStatusPagamento"] == STATUS_PAGAMENTO_CONCLUIDO){
										echo "<span class='verde'>Pagamento concluído</span>";
									}
									if ($linha["idStatusPagamento"] == STATUS_PAGAMENTO_CANCELADO){
										echo "<span class='vermelho'>Pagamento cancelado</span>";
									}
									if ($linha["idStatusPagamento"] == STATUS_PAGAMENTO_ESTORNO){
										echo "<span class='vermelho'>Pagamento estornado</span>";
									}
									?></td>
									<td class='trOpcoes'>
										<?
										if ($linha["idStatusPagamento"] == STATUS_AGUARDANDO_PAGAMENTO){
											echo "<a href='vendas-processar/".$linha["idVendaCabine"]."' class='btnOpcoes'><img src='inc/img/button/window_edit2.png' alt='Processar pagamento' title='Processar pagamento' /></a>";
										}else{
											
											if ($linha["idStatusPagamento"] == STATUS_PROCESSANDO_PAGAMENTO){
												
												if ($linha["tipoPagamento"] == PAGAMENTO_BONUS){
													echo "<a href='vendas-concluir-bonus/".$linha["idVendaCabine"]."' class='btnOpcoes'><img src='inc/img/button/window_edit2.png' alt='Concluir pagamento' title='Concluir pagamento' /></a>";
												}else{
													echo "<a href='vendas-concluir/".$linha["idVendaCabine"]."' class='btnOpcoes'><img src='inc/img/button/window_edit2.png' alt='Concluir pagamento' title='Concluir pagamento' /></a>";
												}
											
											}else{
												echo "<img class='desabilitado' src='inc/img/button/window_edit2-off.png' alt='' title='' />";
											}								
										}										
										?>								
									</td>
								</tr>								
								
						<?
							}
						}
						?>
						
					</table>
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
