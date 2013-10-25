<? 
require("../inc/engine/include.php");	

seguranca();
bloqueiaUsuarioCotacao();

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pt">
    <head>
        <title>Gerenciar Cruzeiro | <?=PROJETO?></title>
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
			
			function openConfirm(num) {
				confirm("Deseja realmente excluir?", function () {
					window.location.href = 'cruzeiro-excluir.php?id='+num ;
				});
			}					
			
			$().ready(function() {
				$("#gerenciar").tablesorter({ 					
					headers: {						 
						5: { 
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
			<li><a href="cruzeiro-inserir" class="light-box"><img src="inc/img/button/add.png" alt="Adicionar Cruzeiro"  title="Adicionar Cruzeiro"/></a></li>
			<li><a href="javascript:window.print();"><img src="inc/img/button/printer.png" alt="imprimir" title="imprimir" /></a></li>
		</ul>
		<br/>
		<h2 class="titulo1">Gerenciar Cruzeiro</h2>
	</div>
		
	<div class="conteudo">

		<fieldset>
			<legend>Gerenciar Cruzeiro</legend>					
			
				<?
				if ($_POST){	
					
					$sqlParse = "";
					$busca	= RetiraPlicas(trim($_POST['buscaPor']));
					
					if ($busca != ""){
						$sqlParse .= " AND ((nome like '%$busca%') OR (de like '%$busca%') OR (para like '%$busca%'))";
					}
				}
				
				$sql		= 	"SELECT idCruzeiro, nome, itinerario, DATE_FORMAT( dtSaida, '%d/%m/%Y' ) AS dtSaida, DATE_FORMAT( dtChegada, '%d/%m/%Y' ) AS dtChegada
								FROM cruzeiro
								WHERE 1=1
								".$sqlParse."
								ORDER BY idCruzeiro";
				
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
					

				<!--Confirmar exclusão-->
				<div id='confirm'>
					<a href='#' title='Close' class='modalCloseX simplemodal-close'>
						<img src="inc/img/layout/img_closeAlert.gif" alt="Fechar" />
					</a>
					<div class='header'><span>Confirmação</span></div>
					<p class='message'></p>
					<div class='buttons'>
						<div class='no simplemodal-close'>Não</div>
						<div class='yes'>Sim</div>
					</div>
				</div>
				
				<div class="boxTabelaGerenciar">	

					<?
					if (isset($_SESSION['paginaAnterior'])){
						
						if ($_SESSION['paginaAnterior'] == "alterar.php"){
							$_SESSION['paginaAnterior'] = "";
							echo "<div id='divMsg' class='MsgOk'>";
							echo "Cruzeiro alterado com sucesso.";
							echo "</div>";				
						}
						
						if ($_SESSION['paginaAnterior'] == "excluir.php"){
							$_SESSION['paginaAnterior'] = "";
							echo "<div id='divMsg' class='MsgOk'>";
							echo "Cruzeiro excluído com sucesso.";
							echo "</div>";				
						}
						
						if ($_SESSION['paginaAnterior'] == "excluirErro.php"){
							$_SESSION['paginaAnterior'] = "";
							echo "<div id='divMsg' class='MsgError'>";
							echo "Não foi possível excluir o Cruzeiro pois existem Cabines associadas.";
							echo "</div>";				
						}
						
						if ($_SESSION['paginaAnterior'] == "inserir.php"){
							$_SESSION['paginaAnterior'] = "";
							echo "<div id='divMsg' class='MsgOk'>";
							echo "Cruzeiro inserido com sucesso.";
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
								<th>Id&nbsp;&nbsp;&nbsp;</th>
								<th>Nome</th>
								<th>Itinerário</th>							
								<th>Data saída</th>
								<th>Data de chegada</th>
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
									<td class="trCenter"><?=$linha["idCruzeiro"]?></a></td>
									<td><a href="cruzeiro-visualizar/<?=$linha["idCruzeiro"]?>" class="linkVisualizar"><?=utf8_encode($linha["nome"])?></a></td>
									<td><?=utf8_encode($linha["itinerario"])?></td>
									<td class="trCenter"><?=$linha["dtSaida"]?></td>
									<td class="trCenter"><?=$linha["dtChegada"]?></td>
									<td class='trOpcoes'>
										<a href='cruzeiro-alterar/<?=$linha["idCruzeiro"]?>' class='btnOpcoes'><img src='inc/img/button/window_edit2.png' alt='Alterar' title='Alterar' /></a>
										<a href="#" class="confirm" onclick="openConfirm(<?=$linha["idCruzeiro"]?>)"><img src="inc/img/button/delete.png" alt="Excluir" title="Excluir" /></a>
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
