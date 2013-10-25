<?
function criaEmailEstorno($pedido, $motivo){

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
						$mensagem .= "<span style='font-size:20px;color: #666666'>Estorno de pagamento realizado com sucesso.</span><br />";
						$mensagem .= "<span style='font-size:14px;color: #666666'>Número: <strong>".$pedido."</strong></span><br />";
						$mensagem .= "<strong>Motivo: </strong>".$motivo."<br />";
						$mensagem .= "<p>Caso tenha alguma dúvida entre em contatos com a equipe da '1ª Convenção Internacional em alto mar', através do telefone (27) 2121-4354 ou email contato@converenciatelexfree.com.br</p>";
						
					$mensagem .= "</td>";
				$mensagem .= "</tr>";				
			$mensagem .= "</table>";
		$mensagem .= "</body>";
	$mensagem .= "</html>";

	return $mensagem;
}

function criaEmailLiberar($pedido, $motivo){

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
						$mensagem .= "<span style='font-size:20px;color: #666666'>Reserva cancelada com sucesso.</span><br />";
						$mensagem .= "<span style='font-size:14px;color: #666666'>Número: <strong>".$pedido."</strong></span><br />";
						$mensagem .= "<strong>Motivo: </strong>".$motivo."<br />";
						$mensagem .= "<p>Caso tenha alguma dúvida entre em contatos com a equipe da '1ª Convenção Internacional em alto mar', através do telefone (27) 2121-4354 ou email contato@converenciatelexfree.com.br</p>";
						
					$mensagem .= "</td>";
				$mensagem .= "</tr>";				
			$mensagem .= "</table>";
		$mensagem .= "</body>";
	$mensagem .= "</html>";

	return $mensagem;
}

function criaEmailSucesso($pedido){

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
						$mensagem .= "<span style='font-size:20px;color: #666666'>Pagamento efetuado com sucesso.</span><br />";
						$mensagem .= "<span style='font-size:14px;color: #666666'>Número: <strong>".$pedido."</strong></span>";
						$mensagem .= "<p>Caso tenha alguma dúvida entre em contatos com a equipe da '1ª Convenção Internacional em alto mar', através do telefone (27) 2121-4354 ou email contato@converenciatelexfree.com.br</p>";
						
					$mensagem .= "</td>";
				$mensagem .= "</tr>";				
			$mensagem .= "</table>";
		$mensagem .= "</body>";
	$mensagem .= "</html>";

	return $mensagem;
}

function criaEmailFalhaBonus($pedido, $motivo){

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
						$mensagem .= "<span style='font-size:20px;color: #666666'>Seu pagamento via bônus não foi aprovado.</span><br />";
						$mensagem .= "<span style='font-size:14px;color: #666666'>Número: <strong>".$pedido."</strong></span><br />";
						$mensagem .= "<strong>Motivo: </strong>".$motivo."<br />";
						$mensagem .= "<p>Caso tenha alguma dúvida entre em contatos com a equipe da '1ª Convenção Internacional em alto mar', através do telefone (27) 2121-4354 ou email contato@converenciatelexfree.com.br</p>";
						
					$mensagem .= "</td>";
				$mensagem .= "</tr>";				
			$mensagem .= "</table>";
		$mensagem .= "</body>";
	$mensagem .= "</html>";

	return $mensagem;
}