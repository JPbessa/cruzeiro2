<?
function criaEmail($pedido){

	$mensagem = "";
	$mensagem .= "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.1//EN' 'http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd'>";
	$mensagem .= "<html xmlns='http://www.w3.org/1999/xhtml' xml:lang='pt'>";
		$mensagem .= "<head>";
			$mensagem .= "<title>Recebemos seu pedido</title>";
			$mensagem .= "<meta http-equiv='X-UA-Compatible' content='IE=8' />";
			$mensagem .= "<meta http-equiv='content-type' content='text/html; charset=UTF-8' />";
			$mensagem .= "<meta http-equiv='content-language' content='pt-br' />";
		$mensagem .= "</head>";
		
		$mensagem .= "<body style='margin: 0 auto;font-size:13px;color: #666666;background-color:#F3F3F3;font-family:Arial;line-height: 200%;'>";
			$mensagem .= "<table style='margin: 0 auto;background-color:#FFF;width:600px;'>";
				$mensagem .= "<tr>";
					$mensagem .= "<td>";
						$mensagem .= "<img src='".WEB_ROOT."/inc/img/email/topoemail.jpg' alt='1ª Convenção Internacional em alto mar' title='1ª Convenção Internacional em alto mar' />";
					$mensagem .= "</td>";
				$mensagem .= "</tr>";
				$mensagem .= "<tr>";
					$mensagem .= "<td style='padding: 0 10px;'>";
						$mensagem .= "<span style='font-size:20px;color: #666666'>Seu pedido foi recebido com sucesso!</span><br />";
						$mensagem .= "<span style='font-size:14px;color: #666666'>Número: <strong>".$pedido."</strong></span>";
						$mensagem .= "<p>Preparamos um resumo do seu pedido.<br /> Caso tenha alguma dúvida entre em contatos com a equipe da '1ª Convenção Internacional em alto mar', através do telefone (27) 2121-4354 ou email contato@converenciatelexfree.com.br</p>";
						$mensagem .= "<p>Fique atento:</p>";
						$mensagem .= "<ul style='padding:0px 15px;margin:0px;'>";
							$mensagem .= "<li><a href='".WEB_ROOT."/inc/documento/Autorizacoes-CNJ-131-2013.doc' target='_blank'>Preencha a declaração para menor acompanhado</a></li>";
							$mensagem .= "<li><a href='".WEB_ROOT."/inc/documento/Procedimento_de_Embarque_Desembarque_atualizado.pdf' target='_blank'>Consulte as instruções de embarque e desembarque</a></li>";
						$mensagem .= "</ul>";
					$mensagem .= "</td>";
				$mensagem .= "</tr>";
				$mensagem .= "<tr>";
					$mensagem .= "<td style='height: 70px;'>";
						$mensagem .= "<img src='".WEB_ROOT."/inc/img/email/cruzeiro.jpg' alt='Cruzeiro' title='Cruzeiro' />";
					$mensagem .= "</td>";
				$mensagem .= "</tr>";		
						
				$sql	= 	"SELECT vc.idVendaCabine, vc.precoVendaCabine, vc.precoVendaCabineReal, vc.observacao, ce.idCabine, ce.descricaoBR, ce.numCabine, ce.ocupacaoMaxima,
									co.nome as cruzeiro, co.itinerario, co.portoSaida, co.portoChegada, 
									DATE_FORMAT( co.dtSaida, '%d/%m/%Y' ) AS dtSaida, DATE_FORMAT( co.dtChegada, '%d/%m/%Y' ) AS dtChegada, 
									pg.tipoPagamento, pg.loginTelexfree, pg.bonusConsumidoTelexfree, pg.nome, pg.sobrenome, pg.cpf, pg.rg
							FROM vendacabine vc, cabine ce, cruzeiro co, pagamento pg
							WHERE vc.codvendafinal = '$pedido'
							AND vc.idCabine = ce.idCabine
							AND ce.idCruzeiro = co.idCruzeiro
							AND vc.idVendaCabine = pg.idVendaCabine";
				
				$result			= query_execute($sql, $conexao) or die ("Não foi possível executar a consulta");
				$resultDados	= mysql_fetch_array($result);			
				
				$mensagem .= "<tr>";		
					$mensagem .= "<td style='padding: 0 10px;'>";
						$mensagem .= "<strong>Nome: </strong> ".utf8_encode($resultDados['cruzeiro'])."<br />";
						$mensagem .= "<strong>Itinerário: </strong> ".utf8_encode($resultDados['itinerario'])."<br />";
						$mensagem .= "<strong>Porto de saída: </strong> ".utf8_encode($resultDados['portoSaida'])."<br />";
						$mensagem .= "<strong>Porto de chegada: </strong> ".utf8_encode($resultDados['portoChegada'])."<br />";
						$mensagem .= "<strong>Período: </strong>".$resultDados['dtSaida']." até ".$resultDados['dtChegada']."";
					$mensagem .= "</td>";
				$mensagem .= "</tr>";
				$mensagem .= "<tr>";
					$mensagem .= "<td style='height: 70px;'>";
						$mensagem .= "<img src='".WEB_ROOT."/inc/img/email/cabine.jpg' alt='Cabine' title='Cabine' />";
					$mensagem .= "</td>";
				$mensagem .= "</tr>";
				$mensagem .= "<tr>";	
					$mensagem .= "<td style='padding: 0 10px;'>";
						$mensagem .= "<strong>Cabine: </strong>".utf8_encode($resultDados['descricaoBR'])."<br />";
						$mensagem .= "<strong>Número: </strong>".$resultDados['numCabine']."<br />";
						$mensagem .= "<strong>Ocupação máxima: </strong>".$resultDados['ocupacaoMaxima']." pessoas";
					$mensagem .= "</td>";
				$mensagem .= "</tr>";
				$mensagem .= "<tr>";
					$mensagem .= "<td style='height: 70px;'>";
						$mensagem .= "<img src='".WEB_ROOT."/inc/img/email/passageiro.jpg' alt='Passageiros' title='Passageiros' />";
					$mensagem .= "</td>";
				$mensagem .= "</tr>";
				$mensagem .= "<tr>";		
					$mensagem .= "<td style='padding: 0 10px;'>";
						
						$sqlPassageiro	= 	"select tipoPassageiro, nome, sobrenome, cpf, passaporte, nacionalidade
											from passageiro
											where 
											idVendaCabine =".$resultDados['idVendaCabine'];
				
						$resultPassageiro	= query_execute($sqlPassageiro, $conexao) or die ("Não foi possível executar a consulta");
						
						$mensagem .= "<ul style='padding:0px 15px;margin:0px;'>";
						while ($linha = mysql_fetch_array($resultPassageiro)) {
							$mensagem .= "<li>".utf8_encode($linha['nome'])." ".utf8_encode($linha['sobrenome']);
							if($linha['tipoPassageiro'] == "a"){
								$mensagem .= " (adulto - ";
								if ($linha['nacionalidade'] == "Brasil"){
									$mensagem .= "CPF: ".$linha['cpf'].")</li>";
								}else{
									$mensagem .= "Passaporte: ".$linha['passaporte'].")</li>";
								}						
							}else{
								$mensagem .= " (criança)</li>";
							}
						}
						$mensagem .= "</ul>";	
						if ($resultDados['observacao'] <> ""){
							$mensagem .= "<strong>Observação: </strong>".utf8_encode($resultDados['observacao'])."<br />";
						}
					$mensagem .= "</td>";
				$mensagem .= "</tr>";
				$mensagem .= "<tr>";
					$mensagem .= "<td style='height: 70px;'>";
						$mensagem .= "<img src='".WEB_ROOT."/inc/img/email/formapagamento.jpg' alt='Forma de pagamento' title='Forma de pagamento' />";
					$mensagem .= "</td>";
				$mensagem .= "</tr>";
				$mensagem .= "<tr>";		
					$mensagem .= "<td style='padding: 0 10px;'>";					
						if ($resultDados['tipoPagamento'] == PAGAMENTO_CARTAO){
							$mensagem .= "<strong>Tipo: </strong>Cartão<br />";
							$mensagem .= "<strong>Nome: </strong>".utf8_encode($resultDados['nome'])." ".utf8_encode($resultDados['sobrenome'])." (CPF ".$resultDados['cpf'].")<br />";
							$mensagem .= "<strong>Investimento: </strong>".formataParaReal($resultDados['precoVendaCabineReal'])." (".formataParaDolar($resultDados['precoVendaCabine']).")";				
						}else{
							$mensagem .= "<strong>Tipo: </strong>Bônus<br />";
							$mensagem .= "<strong>Login TelexFree: </strong>".$resultDados['loginTelexfree']."<br />";		
							$mensagem .= "<strong>Bônus consumido: </strong>".$resultDados['bonusConsumidoTelexfree'];				
						}				
						$mensagem .= "</td>";
				$mensagem .= "</tr>";		
			$mensagem .= "</table>";
		$mensagem .= "</body>";
	$mensagem .= "</html>";

	return $mensagem;
}