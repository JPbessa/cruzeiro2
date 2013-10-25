<? 
require("../inc/engine/include.php");	

seguranca();

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pt">
    <head>
        <title>Gerenciar Cotação | <?=PROJETO?></title>
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
				$("#gerenciar").tablesorter();
			});

		</script>
		 
		
</head>
<body>	

	<?php include_once("topo.php") ?>	
		
	<div class="control">				
		<ul>
			<li><a href="cotacao-inserir" class="light-box"><img src="inc/img/button/add.png" alt="Adicionar Cotação"  title="Adicionar Cotação"/></a></li>
			<li><a href="javascript:window.print();"><img src="inc/img/button/printer.png" alt="imprimir" title="imprimir" /></a></li>
		</ul>
		<br/>
		<h2 class="titulo1">Gerenciar Cotação do Dólar</h2>
	</div>
		
	<div class="conteudo">

		<fieldset>
			<legend>Gerenciar Cotação do Dólar</legend>					
			
				<?
				$sql		= 	"SELECT idCotacao, valor, DATE_FORMAT( dtLogMudanca, '%d/%m/%Y %H:%i:%s') AS dtMudanca
								FROM cotacaodolar
								ORDER BY idCotacao DESC";
				
				$resultado		= query_execute($sql, $conexao) or die ("Não foi possível executar a consulta");
				$intContagem	= (int) mysql_num_rows($resultado);
				
				?>
			
				<div class="status">
					<label>Status:</label>
					<strong><?=$intContagem?></strong> registros cadastrados
				</div>				
		
				<div class="boxTabelaGerenciar">	

					<?
					if (isset($_SESSION['paginaAnterior'])){
						
						if ($_SESSION['paginaAnterior'] == "inserir.php"){
							$_SESSION['paginaAnterior'] = "";
							echo "<div id='divMsg' class='MsgOk'>";
							echo "Cotação inserida com sucesso.";
							echo "</div>";				
						}
					}

					if (isset($_SESSION['strErroCB'])){					
						echo "<div id='divMsg' class='MsgError'>";
						echo $_SESSION['strErroCB'];
						echo "</div>";	
						unset($_SESSION['strErroCB']);					
					}
					
					if ($_SESSION['permissionCotacaoDenied']){
						echo "<div id='divMsg' class='MsgError'>";
						echo "Você executou uma operação não permitida.<br /> Seu usuário só possui permissão para acessar a área de 'cotação'.";
						echo "</div>";	
						unset($_SESSION['permissionCotacaoDenied']);
					}
					
					?>
						
					<table id="gerenciar" class="tablesorter listaValores">
						<thead>
							<tr>
								<th>Id</th>
								<th>Cotação</th>
								<th>Data de alteração</th>											
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
									<td class="trCenter"><?=$linha["idCotacao"]?></a></td>
									<td class="trCenter"><?="R$ ".str_replace(".", ",", $linha["valor"])?></a></td>
									<td class="trCenter"><?=$linha["dtMudanca"]?></a></td>									
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
