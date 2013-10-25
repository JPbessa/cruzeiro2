<?
	session_start();

function pre($array){
	echo "<pre>";
	print_r($array);
	echo "</pre>";
}

function formataParaDolar($valor){
	return "US$ " . number_format($valor, 2, '.', ',');
}

function formataParaReal($valor){
	return "R$ " . number_format($valor, 2, ',', '.');
}

function ocupacao($quantAdulto, $quantCrianca){
	$intOcupacao = 0;
	$ocupacao = "";
	if ($quantAdulto > 0){
		$intOcupacao ++;
		if ($quantAdulto > 1){
			$ocupacao .= $quantAdulto." adultos";
		}else{
			$ocupacao .= $quantAdulto." adulto";
		}		
	}
	if ($quantCrianca > 0){
		if ($intOcupacao > 0){
			$ocupacao .= " e ";
		}
		if ($quantCrianca > 1){
			$ocupacao .= $quantCrianca." crianças";
		}else{
			$ocupacao .= $quantCrianca." criança";
		}		
	}
	return $ocupacao;
}

function calculaPreco($valorAdulto, $valorCrianca, $quantAdulto, $quantCrianca){

	$print = "<span class='precoFinal'>".formataParaDolar(($valorAdulto * $quantAdulto) + ($valorCrianca * $quantCrianca))."</span>";
	$intDetalhe = 0;
	$detalhe = "";
	if ($quantAdulto > 0){
		$intDetalhe ++;
		$detalhe = "Adulto: ".formataParaDolar($valorAdulto);
	}
	if ($quantCrianca > 0){
		if ($intDetalhe > 0){
			$detalhe .= " e Criança: ".formataParaDolar($valorCrianca);
		}else{
			$detalhe .= "Criança: ".formataParaDolar($valorCrianca);
		}
	}
	return $print."<br /><span class='precoDetalhe'>".$detalhe."</span>";
}


function redirectTo($destino){
	?>
	<script type="text/javascript">
	<!--
		document.location = "<?=$destino?>";
	//-->
	</script>
	<p>Java Script desabilitado! Clique <a href="<?=$destino?>">AQUI</a> para continuar.</p>
	<?
	exit;
}

function RetiraPlicas($string){
	return str_replace("'","''",$string);
}

function TresPontinhos($string, $quant){
	if (strlen($string) > $quant) {	
		$stringTrat = substr($string, 0, $quant); 
		$stringFinal = $stringTrat."...";
		return $stringFinal;
	}else{
		return $string;
	}
}

function formataData($dtData){
	
	if(empty($dtData)) return "";

	$arrayData = explode("/", $dtData);
	
	$dia = $arrayData[0];
	$mes = $arrayData[1];
	$ano = $arrayData[2];
	
	$data = "$ano-$mes-$dia";
	
	return $data;
}

function formataDataHora($dtData){
	
	if(empty($dtData)) return "";

	$arrayData = explode("/", $dtData);
	
	$arrayDataHora = explode(" ", $arrayData[2]);
	
	$dia = $arrayData[0];
	$mes = $arrayData[1];
	$ano = $arrayDataHora[0];
	
	$data = "$ano-$mes-$dia $arrayDataHora[1]";
	
	return $data;
}

function formataDataBR($dtData){

	if(empty($dtData)) return "";

	$arrayData = explode("-", $dtData);
		$dia = $arrayData[2];
		$mes = $arrayData[1];
		$ano = $arrayData[0];
	$data = "$dia/$mes/$ano";
	
	return $data;
}

function enviaEmail($de, $deNome, $para, $assunto, $mensagem){
	
	$mail = new PHPMailer();
	$mail->IsSMTP();
	$mail->SMTPDebug = 1;
	$mail->SMTPAuth = true;
	
	$mail->SMTPSecure = 'ssl';	
	$mail->Host = 'smtp.gmail.com';
	$mail->Port = 465; 
	$mail->Username = USER_EMAIL;  
	$mail->Password = PASSWORD_EMAIL; 
	
	$mail->IsHTML(true);
	
	$arrEmails = explode(";", $para);
	
	$mail->From = $de;
	$mail->FromName = $deNome;
	$mail->Subject = utf8_decode($assunto);
	$mail->Body = $mensagem;
	
	foreach($arrEmails as $index => $value){
	
		$mail->AddAddress($arrEmails[$index]);
	
		if(!$mail->Send())
			echo $mail->ErrorInfo;

		$mail->ClearAddresses();
	}
}

function secure() {

 	$_GET = array_map('strip_tags', $_GET);
    $_POST = array_map('strip_tags', $_POST);
    $_COOKIE = array_map('strip_tags', $_COOKIE);
    $_REQUEST = array_map('strip_tags', $_REQUEST);
	
	$_GET = array_map('trim', $_GET);
    $_POST = array_map('trim', $_POST);
    $_COOKIE = array_map('trim', $_COOKIE);
    $_REQUEST = array_map('trim', $_REQUEST);

    if(get_magic_quotes_gpc()) {
        $_GET = array_map('stripslashes', $_GET);
        $_POST = array_map('stripslashes', $_POST);
        $_COOKIE = array_map('stripslashes', $_COOKIE);
        $_REQUEST = array_map('stripslashes', $_REQUEST);
    }

}


function query_execute($SQL){
	
	$result = mysql_query($SQL);
	
	if(!$result){
	
		$erro = mysql_error();
		$mensagemErro = str_replace("'", "", $erro);
		$SQL = str_replace("'", "/", $SQL);
		
		$sqlErro = "INSERT INTO logerro (dtErro, mensagem, consulta) 
					VALUES ('".date("Y-m-d H:i:s")."', '".$mensagemErro."', '".$SQL."')";
		$resultErro = mysql_query($sqlErro);
	
		$mensagem = "";
		$mensagem .= "<strong>Data</strong> " . date("d/m/y H:i:s") . "<br /><br/ >";
		$mensagem .= "<strong>Erro</strong> " . $mensagemErro . "<br /><br />";
		$mensagem .= "<strong>SQL</strong> " . $SQL;
				
		enviaEmail(EMAIL_DE, utf8_decode(NOME_EMAIL_DE), EMAIL_PARA.";".USER_EMAIL, "Erro no site", utf8_encode($mensagem));
		redirectTo(WEB_ROOT_ERRO);
	}
	
	return $result;
}

function get_rnd_iv($iv_len){
   $iv = '';
   while ($iv_len-- > 0) {
      $iv .= chr(mt_rand() & 0xff);
   }
   return $iv;
}

function md5_decrypt($enc_text, $password, $iv_len = 16){
   $enc_text = base64_decode($enc_text);
   $n = strlen($enc_text);
   $i = $iv_len;
   $plain_text = '';
   $iv = substr($password ^ substr($enc_text, 0, $iv_len), 0, 512);
   while ($i < $n) {
      $block = substr($enc_text, $i, 16);
      $plain_text .= $block ^ pack('H*', md5($iv));
      $iv = substr($block . $iv, 0, 512) ^ $password;
      $i += 16;
   }
   return preg_replace('/\x13\x00*$/', '', $plain_text);
}
 
function md5_encrypt($plain_text, $password, $iv_len = 16){
   $plain_text .= "x13";
   $n = strlen($plain_text);
   if ($n % 16) $plain_text .= str_repeat("{TEXTO}", 16 - ($n % 16));
   $i = 0;
   $enc_text = get_rnd_iv($iv_len);
   $iv = substr($password ^ $enc_text, 0, 512);
   while ($i < $n) {
      $block = substr($plain_text, $i, 16) ^ pack('H*', md5($iv));
      $enc_text .= $block;
      $iv = substr($block . $iv, 0, 512) ^ $password;
      $i += 16;
   }
   return base64_encode($enc_text);
}

function existeEsseRegistro($id, $tabela, $conn)
{
	$strSQL			= "SELECT COUNT(*) FROM $tabela WHERE id$tabela = $id";
	$resultSet		= query_execute($strSQL);
	$numRegistro	= mysql_result($resultSet, 0,0);

	if($numRegistro > 0)return true;
	else return false;
}

function verificaDisponibilidade($descricaoCabine, $capacidade, $statusLivre){
	$strSQL			= 	"SELECT COUNT(*) 
						FROM cabine
						WHERE descricaoBr = '$descricaoCabine' AND ocupacaoMaxima = $capacidade AND idStatus = $statusLivre";
	$resultSet		= query_execute($strSQL);
	$numRegistro	= mysql_result($resultSet, 0,0);

	if($numRegistro > 0)return true;
	else return false;
}

function quantidadeDisponibilidade($descricaoCabine, $capacidade, $statusLivre){
	$strSQL			= 	"SELECT COUNT(*) 
						FROM cabine
						WHERE descricaoBr = '$descricaoCabine' AND ocupacaoMaxima = $capacidade AND idStatus = $statusLivre";
	$resultSet		= query_execute($strSQL);
	$numRegistro	= mysql_result($resultSet, 0,0);

	return $numRegistro;
}

function formataNomeCabine($nome){
	return str_replace(" ", "_", $nome);
}

function limpaFormataNomeCabine($nome){
	return str_replace("_", " ", $nome);
}

function moneyToBD($value){
	return str_replace("US$ ", "", str_replace(",", "", $value));
}

function iniciaTransacao(){
	// $SQL = "START TRANSACTION;";
	// query_execute($SQL);
}

function finalizaTransacao(){
	// $SQL = "IF mysql_error() > 0
				// ROLLBACK
			// ELSE
				// COMMIT";
	// query_execute($SQL);
}

function reservaCabine($descricaoCabine, $capacidade, $statusLivre, $statusReserva){

	$strSQL			= 	"SELECT COUNT(*) as quantidade
						FROM cabine
						WHERE descricaoBr = '$descricaoCabine' AND ocupacaoMaxima = $capacidade AND idStatus = $statusLivre";
	
	$resultSet	= query_execute($strSQL);
	$quantLivre	= mysql_result($resultSet, 0,0);
	
	if ($quantLivre > 0){
		$strSQL			= 	"SELECT idCabine
							FROM cabine
							WHERE descricaoBr = '$descricaoCabine' AND ocupacaoMaxima = $capacidade AND idStatus = $statusLivre
							ORDER BY idCabine DESC
							LIMIT 1"; 
		$resultSet	= query_execute($strSQL);		
		$idCabine	= mysql_result($resultSet, 0,0);
		
		$strSQL		= 	"UPDATE cabine SET idStatus = ".$statusReserva."
						WHERE idCabine = ".$idCabine;
		$resultSet		= query_execute($strSQL);
		
		return $idCabine;	
		
	}else{
		return 0;		
	}
}

function numeroCruzeiro($numCruzeiro){	
	return sprintf("%04s", $numCruzeiro);
}

function numeroVenda($numVenda){	
	return sprintf("%06s", $numVenda);
}

function cotacaoDolar(){
	$strSQL			= 	"SELECT valor 
						FROM cotacaodolar
						ORDER BY idCotacao DESC";
	$resultSet	= query_execute($strSQL);
	$cotacao	= mysql_result($resultSet, 0,0);

	return $cotacao;
}

function seguranca(){

	if (!isset($_SESSION['userId'])){
		$_SESSION['permissionCmaDenied'] = true;
		header("Location: ".WEB_ROOT."/cma/");
		die();
	}
}

function bloqueiaUsuarioCotacao(){
	if (strtolower($_SESSION['userNome']) == 'telexfree'){
		$_SESSION['permissionCotacaoDenied'] = true;
		header("Location: ".WEB_ROOT_COTACAO);
		die();
	}
}

function redirectWithPost($url, $post) {
	$html = "<html><body><form id='form' action='$url' method='post'>";
	foreach ($post as $key => $value) {
		$html .= "<input type='hidden' name='$key' value='$value'>";
	}
	$html .= "</form><script>document.getElementById('form').submit();</script>";
	$html .= "</body></html>";
	print($html);
}

class tokenGenerator{
	const HASH_ALGO = 'sha1';
	const HASH_SALT = 'AmakakeruRyuNoHirameki';
	const ITERATIONS_COUNT = 40000;

	public function get($value)
	{
		$token = '';
		for ($iteration = 0; $iteration < self::ITERATIONS_COUNT; $iteration++) {
			$token = hash(self::HASH_ALGO, $iteration . $token . self::HASH_SALT . $value);
		}

		return $token;
	}
}	

function getCodVendaFinal($idVendaCabine){
	$strSQL			= 	"SELECT codVendaFinal
						FROM vendacabine
						WHERE idVendaCabine =".$idVendaCabine;
	$resultSet		= query_execute($strSQL);
	$codVendaFinal	= mysql_result($resultSet, 0,0);

	return $codVendaFinal;
}

function getEmail($idVendaCabine){
	$strSQL			= 	"SELECT email
						FROM pagamento p
						WHERE idVendaCabine =".$idVendaCabine;
	$resultSet		= query_execute($strSQL);
	$intContagem	= (int) mysql_num_rows($resultSet);
	
	if ($intContagem > 0){
		$email	= mysql_result($resultSet, 0,0);
	}else{
		$email	= "";
	}
	return $email;
}
?>