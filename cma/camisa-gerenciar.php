<? 
require("../inc/engine/include.php");	

seguranca();
bloqueiaUsuarioCotacao();

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pt">
    <head>
        <title>Gerenciar Camisa | <?=PROJETO?></title>
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
			<li><a href="javascript:window.print();"><img src="inc/img/button/printer.png" alt="imprimir" title="imprimir" /></a></li>
		</ul>
		<br/>
		<h2 class="titulo1">Gerenciar Camisa</h2>
	</div>
		
	<div class="conteudo">

		<fieldset>
			<legend>Gerenciar Camisa</legend>					
			
				<?
				$sql		= 	"SELECT count(a.tamanhoCamisa) as quantidade,a.tamanhoCamisa, a.sexo
								FROM (
									SELECT idPassageiro, tamanhoCamisa, sexo  
									FROM passageiro
									WHERE idVendaCabine in (SELECT idVendaCabine FROM vendacabine WHERE blnAtivo = 1)
									ORDER BY sexo, tamanhoCamisa DESC
									) AS a
								GROUP BY a.tamanhoCamisa, a.sexo
								ORDER BY sexo, tamanhoCamisa DESC";
				
				$resultado		= query_execute($sql, $conexao) or die ("Não foi possível executar a consulta");
				$intContagem	= (int) mysql_num_rows($resultado);
				
				?>
			
				<div class="status">
					<label>Status:</label>
					<strong><?=$intContagem?></strong> registros cadastrados
				</div>				
		
				<div class="boxTabelaGerenciar">	

					<table id="gerenciar" class="tablesorter listaValores">
						<thead>
							<tr>
								<th>Tamanho</th>
								<th>Sexo</th>
								<th>Quantidade</th>											
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
									<td class="trCenter"><?=utf8_encode($linha["tamanhoCamisa"])?></td>
									<td class="trCenter">
										<?
										if ($linha["sexo"] == "f"){
											echo "Feminino";
										}else{
											echo "Masculino";
										}
										?>
									<td class="trCenter"><?=$linha["quantidade"]?></td>
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
