<? 
require("../inc/engine/include.php");	

seguranca();
bloqueiaUsuarioCotacao();

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pt">
    <head>
        <title>Gerenciar Cabine | <?=PROJETO?></title>
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
		<h2 class="titulo1">Gerenciar Cabine</h2>
	</div>
		
	<div class="conteudo">

		<fieldset>
			<legend>Gerenciar Cabine</legend>					
			
				<?
				if ($_POST){	
					
					$sqlParse = "";
					$busca	= RetiraPlicas(trim($_POST['buscaPor']));
					
					if ($busca != ""){
						if (is_numeric($busca)){
							$sqlParse .= " AND ((ce.numCabine = $busca) OR (ce.ocupacaoMaxima = $busca))";							
						}else{				
							$sqlParse .= " AND ( ce.descricaoBr like '%$busca%')";
						}
					}
				}
				
				$sql		= 	"SELECT ce.idCabine, vc.observacaoReserva, ce.descricaoBr, ce.numCabine, ce.deck, ce.categoria, ce.ocupacaoMaxima, ce.idStatus, vc.idVendaCabine
								FROM cabine ce
								LEFT JOIN vendacabine vc ON ce.idCabine = vc.idCabine
								AND vc.blnAtivo =1
								WHERE 1=1
								".$sqlParse."
								ORDER BY ce.numCabine ASC";
				
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
						
						if ($_SESSION['paginaAnterior'] == "reservar"){
							$_SESSION['paginaAnterior'] = "";
							echo "<div id='divMsg' class='MsgOk'>";
							echo "Cabine reservada com sucesso.";
							echo "</div>";				
						}
						
						if ($_SESSION['paginaAnterior'] == "erroReservar"){
							$_SESSION['paginaAnterior'] = "";
							echo "<div id='divMsg' class='MsgError'>";
							echo "Não foi possível reservar a Cabine pois ela já foi comercializada.";
							echo "</div>";				
						}
						
						if ($_SESSION['paginaAnterior'] == "liberar"){
							$_SESSION['paginaAnterior'] = "";
							echo "<div id='divMsg' class='MsgOk'>";
							echo "Cabine liberada com sucesso.";
							echo "</div>";				
						}
						
						if ($_SESSION['paginaAnterior'] == "estornar"){
							$_SESSION['paginaAnterior'] = "";
							echo "<div id='divMsg' class='MsgOk'>";
							echo "Cabine estornada com sucesso.";
							echo "</div>";				
						}
						
						if ($_SESSION['paginaAnterior'] == "pagamento-CMA"){
							$_SESSION['paginaAnterior'] = "";
							echo "<div id='divMsg' class='MsgOk'>";
							echo "Pagamento cadastrado com sucesso. Enviamos um email para o cliente com os dados da reserva.<br />Lembre-se de processar o pagamento e finalizar a venda da cabine.";
							echo "</div>";				
						}						
						
					}

					if (isset($_SESSION['strErroCB'])){					
						echo "<div id='divMsg' class='MsgError'>";
						echo $_SESSION['strErroCB'];
						echo "</div>";	
						unset($_SESSION['strErroCB']);					
					}
					
					if (isset($_SESSION['permissionDenied'])){
						?>
						<div id="MsgPermissionDenied" class='MsgError2'>
							Você executou uma operação ilegal!
						</div>	
						<?
						unset($_SESSION['permissionDenied']);					
					}					
					?>

					<table id="gerenciar" class="tablesorter listaValores">
						<thead>
							<tr>
								<th>Nº cabine</th>
								<th>Descrição</th>
								<th>Deck&nbsp;&nbsp;</th>							
								<th>Categoria</th>
								<th>Ocupação máxima</th>
								<th>Status</th>								
								<th>Observação</th>								
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
									<td class="trCenter"><?=$linha["numCabine"]?></td>
									<td><?=utf8_encode($linha["descricaoBr"])?></td>
									<td class="trCenter"><?=$linha["deck"]?></td>
									<td class="trCenter"><?=$linha["categoria"]?></td>
									<td class="trCenter"><?=$linha["ocupacaoMaxima"]?></td>
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
									<td class='trOpcoes'>
									<?
										if ($linha["idStatus"] == STATUS_LIVRE){
											echo "<a href='".WEB_ROOT_CMA."/cabine-reservar/".$linha["idCabine"]."' class='linkVisualizar' alt='Reservar' title='Reservar' >Reservar</a>";		
											echo "<a href='".WEB_ROOT_CMA."/cabine-vender/".$linha["idCabine"]."' class='linkVisualizar' alt='Vender' title='Vender'>Vender</a>";		
										}
										if ($linha["idStatus"] == STATUS_RESERVADO){
											echo "<a href='".WEB_ROOT_CMA."/cabine-vender/".$linha["idCabine"]."' class='linkVisualizar' alt='Vender' title='Vender'>Vender</a>";	
											echo "<a href='".WEB_ROOT_CMA."/cabine-liberar/".$linha["idCabine"]."' class='linkVisualizar' alt='Liberar' title='Liberar' >Liberar</a>";
										}
										if ($linha["idStatus"] == STATUS_OCUPADO){
											echo "<a href='".WEB_ROOT_CMA."/cabine-estornar/".$linha["idCabine"]."' class='linkVisualizar' alt='Estornar' title='Estornar'>Estornar</a>";		
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
