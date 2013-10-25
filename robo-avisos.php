<?
	require("inc/engine/include.php");		
	
	$strSQL			= 	"SELECT dtLogMudanca 
						FROM cotacaodolar
						ORDER BY idCotacao DESC";
	$resultSet	= query_execute($strSQL);
	$ultimaCotacao	= mysql_result($resultSet, 0,0);	
	
	$agora = strtotime(date("Y-m-d H:i:s"));
	$ultimaCotacaoTime = strtotime($ultimaCotacao);	

	$quantHoras =  round(abs($agora - $ultimaCotacaoTime) /3600, 2);

	// echo "agora:".date("Y-m-d H:i:s")."<br />";
	// echo "banco:".$ultimaCotacao."<br /><br />";
	
	// echo "agora:".$agora."<br />";
	// echo "banco:".$ultimaCotacaoTime."<br />";
	
	$mensagem = "";	
	
	if ($quantHoras > 24) {			
		$mensagem .=  "<span style='background-color: #FF0000;color: #FFFFFF;font-weight: bold;padding: 5px;line-height: 50px;'>Registre uma nova cota&ccedil;&atilde;o de d&oacute;lar.</span><br />A &uacute;ltima cota&ccedil;&atilde;o foi inserida h&aacute; mais mais de 24h.<br /><br /><br />";			
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
	
	if ($intContagem > 0) {			
		$mensagem .=  "<span style='background-color: #FF0000;color: #FFFFFF;font-weight: bold;padding: 5px;line-height: 50px;'>Revise a reserva de Cabines.</span><br />Voc&ecirc; possui ".$intContagem." cabine(s) reservada(s) h&aacute; mais de 24h. Acesse o CMA e, se for necess&aacute;rio, libere-as.";			
	}
	
	if ($mensagem != ""){
		enviaEmail(EMAIL_DE, utf8_decode(NOME_EMAIL_DE), USER_EMAIL, "Alerta de cotação e reserva", $mensagem);
	}
	
	
		
	
?>