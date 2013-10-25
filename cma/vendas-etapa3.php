<?
	require("../inc/engine/include.php");
	require("../email.php");		

	seguranca();
	bloqueiaUsuarioCotacao();
	
	if($_GET){					
		
		$id = $_GET['id'];
		
		if(is_numeric($id)){					
			if(existeEsseRegistro($id, "vendacabine", $conexao)){
				
				$id = $_GET['id'];				
				
				// busco se já possui pagamento cadastros nessa venda de cabine
				$strSQL		= 	"SELECT COUNT(*) 
								FROM pagamento
								WHERE idVendaCabine = ".$id;
				$resultSet		= query_execute($strSQL);
				$numRegistro	= mysql_result($resultSet, 0,0);
				
				if ($numRegistro > 0){
					$_SESSION['permissionDenied'] = true;
					header("Location: ".WEB_ROOT_CABINE);
					die();
				}	
				
			}else{
				$_SESSION['strErroCB'] = "A cabine n&atilde;o existe no banco de dados.";
				redirectTo(WEB_ROOT_CABINE);
			}						
		}else{
			$_SESSION['strErroCB'] = "O id passado n&atilde;o &eacute; num&eacute;rico.";
			redirectTo(WEB_ROOT_CABINE);			
		}	
	}
	
	if ($_POST){
	
		secure();	
		$intErros		= 0;
		$strErros		= "Os seguintes erros foram encontrados:<br \/>";	
		
		$idVendaCabine 		= $_POST['idVendaCabine'];
		$idCabine 			= $_POST['idCabine'];
		
		//monta código da venda
		$codVendaFinal = date("Y")."-".numeroCruzeiro(NUMERO_CRUZEIRO)."-".numeroVenda($idVendaCabine);
		
		$txtnome			= utf8_decode(RetiraPlicas($_POST['txtnome']));
		$txtsobrenome		= utf8_decode(RetiraPlicas($_POST['txtsobrenome']));
		$txttelefone		= utf8_decode(RetiraPlicas($_POST['txttelefone']));
		$txtcelular			= utf8_decode(RetiraPlicas($_POST['txtcelular']));
		$txtemail			= utf8_decode(RetiraPlicas($_POST['txtemail']));
		$txtcpf				= utf8_decode(RetiraPlicas($_POST['txtcpf']));
		$txtrg				= utf8_decode(RetiraPlicas($_POST['txtrg']));
		$txtExpedidor		= utf8_decode(RetiraPlicas($_POST['txtExpedidor']));						
		$tipo				= $_POST['tipo'];
		$txtlogradouro		= utf8_decode(RetiraPlicas($_POST['txtlogradouro']));	
		$txtcomplemento		= utf8_decode(RetiraPlicas($_POST['txtcomplemento']));	
		$txtcep				= utf8_decode(RetiraPlicas($_POST['txtcep']));	
		$txtcidade			= utf8_decode(RetiraPlicas($_POST['txtcidade']));	
		$txtestado			= utf8_decode(RetiraPlicas($_POST['txtestado']));	
		$pais				= $_POST['pais'];
		
		if($txtnome == "" or $txtnome == "Nome") {
			$intErros++;
			$strErros	.= "- O campo 'Nome' n&atilde;o foi preenchido;<br/>";	
		}
		
		if($txtsobrenome == "" or $txtsobrenome == "Sobrenome") {
			$intErros++;
			$strErros	.= "- O campo 'Sobrenome' n&atilde;o foi preenchido;<br/>";	
		}
		
		if($txttelefone == "" or $txttelefone == "Telefone") {
			$intErros++;
			$strErros	.= "- O campo 'Telefone' n&atilde;o foi preenchido;<br/>";	
		}
		
		if($txtcelular == "" or $txtcelular == "Celular") {
			$intErros++;
			$strErros	.= "- O campo 'Celular' n&atilde;o foi preenchido;<br/>";	
		}
		
		if($txtemail == "" or $txtemail == "Email") {
			$intErros++;
			$strErros	.= "- O campo 'Email' n&atilde;o foi preenchido;<br/>";	
		}
		
		if($txtcpf == "" or $txtcpf == "CPF") {
			$intErros++;
			$strErros	.= "- O campo 'CPF' n&atilde;o foi preenchido;<br/>";	
		}
		
		if($txtrg == "" or $txtrg == "RG") {
			$intErros++;
			$strErros	.= "- O campo 'RG' n&atilde;o foi preenchido;<br/>";	
		}
		
		if($txtExpedidor == "" or $txtExpedidor == "Órgão Expedidor") {
			$intErros++;
			$strErros	.= "- O campo 'Órgão Expedidor' n&atilde;o foi preenchido;<br/>";	
		}
	
		if($txtlogradouro == "" or $txtlogradouro == "Logradouro") {
			$intErros++;
			$strErros	.= "- O campo 'Logradouro' n&atilde;o foi preenchido;<br/>";	
		}
		
		if($txtcomplemento == "" or $txtcomplemento == "Complemento") {
			$intErros++;
			$strErros	.= "- O campo 'Complemento' n&atilde;o foi preenchido;<br/>";	
		}
		
		if($txtcep == "" or $txtcep == "CEP") {
			$intErros++;
			$strErros	.= "- O campo 'CEP' n&atilde;o foi preenchido;<br/>";	
		}
		
		if($txtcidade == "" or $txtcidade == "Cidade") {
			$intErros++;
			$strErros	.= "- O campo 'Cidade' n&atilde;o foi preenchido;<br/>";	
		}
		
		if($txtestado == "" or $txtestado == "Estado") {
			$intErros++;
			$strErros	.= "- O campo 'Estado' n&atilde;o foi preenchido;<br/>";	
		}
		
		if ($intErros > 0){
			echo $strErros;			
		}else{

			iniciaTransacao();
			
			$sql = 	"INSERT INTO pagamento(
						tipoPagamento,
						nome,
						sobrenome,
						telefone,
						celular,
						email,
						cpf,
						rg,
						expedidor,
						endereco,
						complemento,
						cep,
						cidade,
						estado,
						pais,
						idStatusPagamento,
						idVendaCabine,
						dtLogPagamento
					)VALUES(
						".PAGAMENTO_CARTAO.",
						'".$txtnome."',
						'".$txtsobrenome."',
						'".$txttelefone."',
						'".$txtcelular."',
						'".$txtemail."',
						'".$txtcpf."',
						'".$txtrg."',
						'".$txtExpedidor."',
						'".$tipo." ".$txtlogradouro."',
						'".$txtcomplemento."',
						'".$txtcep."',
						'".$txtcidade."',
						'".$txtestado."',
						'".$pais."',
						".STATUS_AGUARDANDO_PAGAMENTO.",
						".$idVendaCabine.",
						'".date("Y-m-d H:i:s")."'
					)";
			
			$resultado = query_execute($sql, $conexao) or die ("Não foi possivel inserir no banco de dados!");	
			
			//insiro o código da venda
			$sql = 	"UPDATE vendacabine SET codVendaFinal = '".$codVendaFinal."'
					WHERE idVendaCabine = ".$idVendaCabine." AND blnAtivo = 1";
			$resultado = query_execute($sql, $conexao) or die ("Não foi possivel inserir no banco de dados!");	
			
			// Insere no controle do CMA
			$sql = "INSERT INTO controlecma (
						idVendaCabine,
						idUsuario, 
						dtLogControle,
						operacao
					)VALUES(
						".$idVendaCabine.",
						".$_SESSION['userId'].",
						'".date("Y-m-d H:i:s")."',
						'Cadastro de pagamento da cabine (ID ".$idCabine.")'
					)";					
			$resultSet	= query_execute($sql);
			
			// envia email para o cliente
			$mensagem = criaEmail($codVendaFinal);
			enviaEmail(EMAIL_DE, utf8_decode(NOME_EMAIL_DE), $txtemail.";".EMAIL_EVENTO.";".USER_EMAIL, "Registramos seu pedido", $mensagem);	
			
			finalizaTransacao();
			
			$_SESSION['paginaAnterior'] = "pagamento-CMA";
			$_SESSION['strErro'] = "";					
			redirectTo(WEB_ROOT_CABINE);
			exit;				
			
		}	
		
	}	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pt">
    <head>
         <title>Vender: Forma de pagamento | <?=PROJETO?></title>
		<meta http-equiv="X-UA-Compatible" content="IE=8" /><!-- Enable IE8 Standards mode -->
        <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
        <meta http-equiv="content-language" content="pt-br" />
        <meta name="description" content="A TelexFREE escolheu o Rio de Janeiro para sua 1ª Convenção Internacional. Serão dias de treinamentos e muita festa em um luxuoso navio fretado exclusivamente para a TelexFREE." />
        <meta name="keywords" content="TelexFREE, convenção internacional, navio, Orchestra, Bruno & Marrone, festas, cruzeiro" />
        <meta name="robots" content="index,follow" />
        <link rel="shortcut icon" href="favicon.ico" />
		<meta http-equiv='cache-control' content='no-cache' />
		<meta http-equiv='expires' content='0' />
		<meta http-equiv='pragma' content='no-cache' />	
		
		<script type="text/javascript" src="../inc/js/jquery.js"></script>
		<script type="text/javascript" src="../inc/js/jquery.simplemodal.js"></script>
		<script type="text/javascript" src="../inc/js/confirm.js"></script>
		<script type="text/javascript" src="../inc/js/funcoes.js"></script>
		<script type="text/javascript" src="../inc/js/jquery.maskedinput.js"></script>
		<script type="text/javascript" src="../inc/js/ui.datepicker.js"></script>
		<script type="text/javascript" src="../inc/js/passwordStrengthMeter.js"></script>	
		<script type="text/javascript" src="../inc/js/alphanumeric.js"></script>	
				
		<link rel="stylesheet" type="text/css" href="../inc/css/screen.css" />
		<link rel="stylesheet" type="text/css" href="../inc/css/calendario.css" />
		<link rel="stylesheet" type="text/css" href="../inc/css/screen_imprimir.css" media="print" />
		
	
	</head>
    <body class="home">
		
		<?php include_once("topo.php") ?>
		
		<div class="control">				
			<ul>
				<li><a href="<?=WEB_ROOT_CABINE?>"><img src="../inc/img/button/back.png" alt="Voltar" title="Voltar" /></a></li>
			</ul>
			<br/>
			<h2 class="titulo1">Pagamento via cartão</h2>
		</div>
			
		<div class="conteudo">
		
			<fieldset class='comInput2'>
				<legend>Reservar</legend>					
		
				<div class="boxTabela">	
			
			<div id="resumoReserva">
				<h3>Resumo da sua reserva:</h3>
				<?
				$int = 0;
				
				$sql 	= 	"SELECT idCabine, adultoHospede, criancaHospede, descricaoCabine, ocupacaoMaximaCabine, precoVendaCabine, precoVendaCabineReal
							FROM vendacabine
							WHERE idVendaCabine = ".$id;	
			
				$result			= query_execute($sql, $conexao) or die ("Não foi possível executar a consulta");
				$resultCabine	= mysql_fetch_array($result);

				$idCabine = $resultCabine['idCabine'];
				$adultoHospede = $resultCabine['adultoHospede'];
				$criancaHospede = $resultCabine['criancaHospede'];
				$criancaHospede = $resultCabine['criancaHospede'];
				$precoVendaCabine = $resultCabine['ocupacaoMaximaCabine'];
				$precoVendaCabineReal = $resultCabine['precoVendaCabineReal'];
				
				if ($resultCabine['adultoHospede'] > 0){	
					$int++;
					if ($resultCabine['adultoHospede'] > 1){									
						$detalhe = $resultCabine['adultoHospede']." adultos";
					}else{
						$detalhe = $resultCabine['adultoHospede']." adulto";
					}
				}						
				if ($resultCabine['criancaHospede'] > 0){	
					if ($int > 0){
						$detalhe .= " e ";
					}
					if ($resultCabine['criancaHospede'] > 1){									
						$detalhe .= $resultCabine['criancaHospede']." crianças";
					}else{
						$detalhe .= $resultCabine['criancaHospede']." criança";
					}
				}
				
				$sql 	= 	"SELECT nome, itinerario, portoSaida, portoChegada, DATE_FORMAT( dtSaida, '%d/%m/%Y' ) AS dtSaida, DATE_FORMAT( dtChegada, '%d/%m/%Y' ) AS dtChegada
							FROM cruzeiro
							WHERE idCruzeiro =".NUMERO_CRUZEIRO;	
			
				$resultado		= query_execute($sql, $conexao) or die ("Não foi possível executar a consulta");
				$resultRow		= mysql_fetch_array($resultado);
				?>		
				<ul>
					<li><strong>Passageiros:</strong> <?=$detalhe?></li>
					<?
					$sqlHospede 	= 	"SELECT nome, sobrenome, tipoPassageiro, sexo
										FROM passageiro
										WHERE idVendaCabine =".$id;	
			
					$resultadoHospede		= query_execute($sqlHospede, $conexao) or die ("Não foi possível executar a consulta");
					echo "<li><ul class='listaPassageiro'>";
					while ($resultRowHospede = mysql_fetch_array($resultadoHospede)) {
					?>
						<li><?=utf8_encode($resultRowHospede["nome"])." ".utf8_encode($resultRowHospede["sobrenome"])?></li>
					<?
					}
					echo "</ul></li>";
					?>					
					<li><strong>Itinerário:</strong> <?=utf8_encode($resultRow['itinerario'])?></li>
					<li><strong>Porto de saída:</strong> <?=utf8_encode($resultRow['portoSaida'])?></li>
					<li><strong>Porto de chegada:</strong> <?=utf8_encode($resultRow['portoChegada'])?></li>
					<li><strong>Período:</strong> <?=$resultRow['dtSaida']?> até <?=$resultRow['dtChegada']?></li>
					<li><strong>Cabine:</strong> <?=$resultCabine['descricaoCabine']." - ocupação máxima de ".$resultCabine['ocupacaoMaximaCabine']." pessoas"?></li>
					<li><strong>Preço:</strong> <?=formataParaReal($precoVendaCabineReal)?> (<?=formataParaDolar($resultCabine['precoVendaCabine'])?>)</li>
				</ul>
			</div>
			
			<div id="contCartao">				
			<div id="contCartao">				
				<form id="frmDadosCartao" name="frmDadosCartao" method="post" action="" >	
					
					<fieldset class='cadastro'>
						<legend>Dados do titular do cartão de crédito</legend>
						
						<div id="MsgErroValida" class='MsgErroValida'>
							Os seguintes erros foram encontrados:<br />
							- Os campos em vermelho são de preenchimento obrigatório ou contém um valor inválido.
						</div>
						
						<input type="hidden" name="tipoPagamentoCartao" id="tipoPagamentoCartao" value="cartao" />
						<input type="text" class="itemForm4" name="txtnome" id="txtnome" placeholder="Nome" maxlength="70" />
						<input type="text" class="itemForm4" name="txtsobrenome" id="txtsobrenome" placeholder="Sobrenome" maxlength="200" /><br />
						<input type="text" class="itemForm2" name="txttelefone" id="txttelefone" placeholder="Telefone" maxlength="20" />
						<input type="text" class="itemForm2" name="txtcelular" id="txtcelular" placeholder="Celular" maxlength="20" />
						<input type="text" class="itemForm10" name="txtemail" id="txtemail" placeholder="Email" maxlength="70" />
						<input type="text" class="itemForm2" name="txtcpf" id="txtcpf" placeholder="CPF" maxlength="14" />
						<input type="text" class="itemForm2" name="txtrg" id="txtrg" placeholder="RG" maxlength="14" />
						<input type="text" class="itemForm10" name="txtExpedidor" id="txtExpedidor" placeholder="Órgão expedidor" maxlength="50" />
						
						<fieldset class='cadastro'>
							<legend>Endereço</legend>
							
							<select name="tipo" id="tipo" style="width:120px;">
								<option value = "Aeroporto">Aeroporto</option>
								<option value = "Alameda">Alameda</option>
								<option value = "Área">Área</option>
								<option value = "Avenida">Avenida</option>														
								<option value = "Campo">Campo</option>
								<option value = "Chácara">Chácara</option>
								<option value = "Colônia">Colônia</option>
								<option value = "Condomínio">Condomínio</option>
								<option value = "Conjunto">Conjunto</option>
								<option value = "Distrito">Distrito</option>
								<option value = "Esplanada">Esplanada</option>
								<option value = "Estação">Estação</option>
								<option value = "Estrada">Estrada</option>
								<option value = "Favela">Favela</option>
								<option value = "Fazenda">Fazenda</option>
								<option value = "Feira">Feira</option>
								<option value = "Jardim">Jardim</option>
								<option value = "Ladeira">Ladeira</option>
								<option value = "Lago">Lago</option>
								<option value = "Lagoa">Lagoa</option>
								<option value = "Largo">Largo</option>
								<option value = "Loteamento">Loteamento</option>
								<option value = "Morro">Morro</option>
								<option value = "Núcleo">Núcleo</option>
								<option value = "Parque">Parque</option>
								<option value = "Passarela">Passarela</option>
								<option value = "Pátio">Pátio</option>
								<option value = "Praça">Praça</option>
								<option value = "Quadra">Quadra</option>
								<option value = "Recanto">Recanto</option>
								<option value = "Residencial">Residencial</option>
								<option value = "Rodovia">Rodovia</option>
								<option value = "Rua" selected="selected">Rua</option>
								<option value = "Setor">Setor</option>
								<option value = "Sítio">Sítio</option>
								<option value = "Travessa">Travessa</option>
								<option value = "Trecho">Trecho</option>
								<option value = "Trevo">Trevo</option>
								<option value = "Vale">Vale</option>
								<option value = "Vereda">Vereda</option>
								<option value = "Via">Via</option>
								<option value = "Viaduto">Viaduto</option>
								<option value = "Viela">Viela</option>
								<option value = "Vila">Vila</option>
								<option value="">Outros</option>										
							</select>
							
							<input type="text" class="itemForm3" name="txtlogradouro" id="txtlogradouro" placeholder="Logradouro" maxlength="300" /><br />
							<input type="text" class="itemForm5" name="txtcomplemento" id="txtcomplemento" placeholder="Complemento" maxlength="100" />
							<input type="text" class="itemForm2" name="txtcep" id="txtcep" placeholder="CEP" maxlength="10" /><br />
							
							<input type="text" class="itemForm2" name="txtcidade" id="txtcidade" placeholder="Cidade" maxlength="100" />
							<input type="text" class="itemForm2" name="txtestado" id="txtestado" placeholder="Estado" maxlength="100" />
							<select name="pais" id="pais">
								<option value="Brasil" selected="selected">Brasil</option>
								<option value="Afeganistão">Afeganistão</option>
								<option value="África do Sul">África do Sul</option>
								<option value="Albânia">Albânia</option>
								<option value="Alemanha">Alemanha</option>
								<option value="Andorra">Andorra</option>
								<option value="Angola">Angola</option>
								<option value="Anguilla">Anguilla</option>
								<option value="Antilhas Holandesas">Antilhas Holandesas</option>
								<option value="Antárctida">Antárctida</option>
								<option value="Antígua e Barbuda">Antígua e Barbuda</option>
								<option value="Argentina">Argentina</option>
								<option value="Argélia">Argélia</option>
								<option value="Armênia">Armênia</option>
								<option value="Aruba">Aruba</option>
								<option value="Arábia Saudita">Arábia Saudita</option>
								<option value="Austrália">Austrália</option>
								<option value="Áustria">Áustria</option>
								<option value="Azerbaijão">Azerbaijão</option>
								<option value="Bahamas">Bahamas</option>
								<option value="Bahrein">Bahrein</option>
								<option value="Bangladesh">Bangladesh</option>
								<option value="Barbados">Barbados</option>
								<option value="Belize">Belize</option>
								<option value="Benim">Benim</option>
								<option value="Bermudas">Bermudas</option>
								<option value="Bielorrússia">Bielorrússia</option>
								<option value="Bolívia">Bolívia</option>
								<option value="Botswana">Botswana</option>
								<option value="Brunei">Brunei</option>
								<option value="Bulgária">Bulgária</option>
								<option value="Burkina Faso">Burkina Faso</option>
								<option value="Burundi">Burundi</option>
								<option value="Butão">Butão</option>
								<option value="Bélgica">Bélgica</option>
								<option value="Bósnia e Herzegovina">Bósnia e Herzegovina</option>
								<option value="Cabo Verde">Cabo Verde</option>
								<option value="Camarões">Camarões</option>
								<option value="Camboja">Camboja</option>
								<option value="Canadá">Canadá</option>
								<option value="Catar">Catar</option>
								<option value="Cazaquistão">Cazaquistão</option>
								<option value="Chade">Chade</option>
								<option value="Chile">Chile</option>
								<option value="China">China</option>
								<option value="Chipre">Chipre</option>
								<option value="Colômbia">Colômbia</option>
								<option value="Comores">Comores</option>
								<option value="Coreia do Norte">Coreia do Norte</option>
								<option value="Coreia do Sul">Coreia do Sul</option>
								<option value="Costa do Marfim">Costa do Marfim</option>
								<option value="Costa Rica">Costa Rica</option>
								<option value="Croácia">Croácia</option>
								<option value="Cuba">Cuba</option>
								<option value="Dinamarca">Dinamarca</option>
								<option value="Djibouti">Djibouti</option>
								<option value="Dominica">Dominica</option>
								<option value="Egito">Egito</option>
								<option value="El Salvador">El Salvador</option>
								<option value="Emirados Árabes Unidos">Emirados Árabes Unidos</option>
								<option value="Equador">Equador</option>
								<option value="Eritreia">Eritreia</option>
								<option value="Escócia">Escócia</option>
								<option value="Eslováquia">Eslováquia</option>
								<option value="Eslovênia">Eslovênia</option>
								<option value="Espanha">Espanha</option>
								<option value="Estados Federados da Micronésia">Estados Federados da Micronésia</option>
								<option value="Estados Unidos">Estados Unidos</option>
								<option value="Estônia">Estônia</option>
								<option value="Etiópia">Etiópia</option>
								<option value="Fiji">Fiji</option>
								<option value="Filipinas">Filipinas</option>
								<option value="Finlândia">Finlândia</option>
								<option value="França">França</option>
								<option value="Gabão">Gabão</option>
								<option value="Gana">Gana</option>
								<option value="Geórgia">Geórgia</option>
								<option value="Gibraltar">Gibraltar</option>
								<option value="Granada">Granada</option>
								<option value="Gronelândia">Gronelândia</option>
								<option value="Grécia">Grécia</option>
								<option value="Guadalupe">Guadalupe</option>
								<option value="Guam">Guam</option>
								<option value="Guatemala">Guatemala</option>
								<option value="Guernesei">Guernesei</option>
								<option value="Guiana">Guiana</option>
								<option value="Guiana Francesa">Guiana Francesa</option>
								<option value="Guiné">Guiné</option>
								<option value="Guiné Equatorial">Guiné Equatorial</option>
								<option value="Guiné-Bissau">Guiné-Bissau</option>
								<option value="Gâmbia">Gâmbia</option>
								<option value="Haiti">Haiti</option>
								<option value="Honduras">Honduras</option>
								<option value="Hong Kong">Hong Kong</option>
								<option value="Hungria">Hungria</option>
								<option value="Ilha Bouvet">Ilha Bouvet</option>
								<option value="Ilha de Man">Ilha de Man</option>
								<option value="Ilha do Natal">Ilha do Natal</option>
								<option value="Ilha Heard e Ilhas McDonald">Ilha Heard e Ilhas McDonald</option>
								<option value="Ilha Norfolk">Ilha Norfolk</option>
								<option value="Ilhas Cayman">Ilhas Cayman</option>
								<option value="Ilhas Cocos (Keeling)">Ilhas Cocos (Keeling)</option>
								<option value="Ilhas Cook">Ilhas Cook</option>
								<option value="Ilhas Feroé">Ilhas Feroé</option>
								<option value="Ilhas Geórgia do Sul e Sandwich do Sul">Ilhas Geórgia do Sul e Sandwich do Sul</option>
								<option value="Ilhas Malvinas">Ilhas Malvinas</option>
								<option value="Ilhas Marshall">Ilhas Marshall</option>
								<option value="Ilhas Menores Distantes dos Estados Unidos">Ilhas Menores Distantes dos Estados Unidos</option>
								<option value="Ilhas Salomão">Ilhas Salomão</option>
								<option value="Ilhas Virgens Americanas">Ilhas Virgens Americanas</option>
								<option value="Ilhas Virgens Britânicas">Ilhas Virgens Britânicas</option>
								<option value="Ilhas Åland">Ilhas Åland</option>
								<option value="Indonésia">Indonésia</option>
								<option value="Inglaterra">Inglaterra</option>
								<option value="Índia">Índia</option>
								<option value="Iraque">Iraque</option>
								<option value="Irlanda do Norte">Irlanda do Norte</option>
								<option value="Irlanda">Irlanda</option>
								<option value="Irã">Irã</option>
								<option value="Islândia">Islândia</option>
								<option value="Israel">Israel</option>
								<option value="Itália">Itália</option>
								<option value="Iêmen">Iêmen</option>
								<option value="Jamaica">Jamaica</option>
								<option value="Japão">Japão</option>
								<option value="Jersey">Jersey</option>
								<option value="Jordânia">Jordânia</option>
								<option value="Kiribati">Kiribati</option>
								<option value="Kuwait">Kuwait</option>
								<option value="Laos">Laos</option>
								<option value="Lesoto">Lesoto</option>
								<option value="Letônia">Letônia</option>
								<option value="Libéria">Libéria</option>
								<option value="Liechtenstein">Liechtenstein</option>
								<option value="Lituânia">Lituânia</option>
								<option value="Luxemburgo">Luxemburgo</option>
								<option value="Líbano">Líbano</option>
								<option value="Líbia">Líbia</option>
								<option value="Macau">Macau</option>
								<option value="Macedônia">Macedônia</option>
								<option value="Madagáscar">Madagáscar</option>
								<option value="Malawi">Malawi</option>
								<option value="Maldivas">Maldivas</option>
								<option value="Mali">Mali</option>
								<option value="Malta">Malta</option>
								<option value="Malásia">Malásia</option>
								<option value="Marianas Setentrionais">Marianas Setentrionais</option>
								<option value="Marrocos">Marrocos</option>
								<option value="Martinica">Martinica</option>
								<option value="Mauritânia">Mauritânia</option>
								<option value="Maurícia">Maurícia</option>
								<option value="Mayotte">Mayotte</option>
								<option value="Moldávia">Moldávia</option>
								<option value="Mongólia">Mongólia</option>
								<option value="Montenegro">Montenegro</option>
								<option value="Montserrat">Montserrat</option>
								<option value="Moçambique">Moçambique</option>
								<option value="Myanmar">Myanmar</option>
								<option value="México">México</option>
								<option value="Mônaco">Mônaco</option>
								<option value="Namíbia">Namíbia</option>
								<option value="Nauru">Nauru</option>
								<option value="Nepal">Nepal</option>
								<option value="Nicarágua">Nicarágua</option>
								<option value="Nigéria">Nigéria</option>
								<option value="Niue">Niue</option>
								<option value="Noruega">Noruega</option>
								<option value="Nova Caledônia">Nova Caledônia</option>
								<option value="Nova Zelândia">Nova Zelândia</option>
								<option value="Níger">Níger</option>
								<option value="Omã">Omã</option>
								<option value="Palau">Palau</option>
								<option value="Palestina">Palestina</option>
								<option value="Panamá">Panamá</option>
								<option value="Papua-Nova Guiné">Papua-Nova Guiné</option>
								<option value="Paquistão">Paquistão</option>
								<option value="Paraguai">Paraguai</option>
								<option value="País de Gales">País de Gales</option>
								<option value="Países Baixos">Países Baixos</option>
								<option value="Peru">Peru</option>
								<option value="Pitcairn">Pitcairn</option>
								<option value="Polinésia Francesa">Polinésia Francesa</option>
								<option value="Polônia">Polônia</option>
								<option value="Porto Rico">Porto Rico</option>
								<option value="Portugal">Portugal</option>
								<option value="Quirguistão">Quirguistão</option>
								<option value="Quênia">Quênia</option>
								<option value="Reino Unido">Reino Unido</option>
								<option value="República Centro-Africana">República Centro-Africana</option>
								<option value="República Checa">República Checa</option>
								<option value="República Democrática do Congo">República Democrática do Congo</option>
								<option value="República do Congo">República do Congo</option>
								<option value="República Dominicana">República Dominicana</option>
								<option value="Reunião">Reunião</option>
								<option value="Romênia">Romênia</option>
								<option value="Ruanda">Ruanda</option>
								<option value="Rússia">Rússia</option>
								<option value="Saara Ocidental">Saara Ocidental</option>
								<option value="Saint Martin">Saint Martin</option>
								<option value="Saint-Barthélemy">Saint-Barthélemy</option>
								<option value="Saint-Pierre e Miquelon">Saint-Pierre e Miquelon</option>
								<option value="Samoa Americana">Samoa Americana</option>
								<option value="Samoa">Samoa</option>
								<option value="Santa Helena, Ascensão e Tristão da Cunha">Santa Helena, Ascensão e Tristão da Cunha</option>
								<option value="Santa Lúcia">Santa Lúcia</option>
								<option value="Senegal">Senegal</option>
								<option value="Serra Leoa">Serra Leoa</option>
								<option value="Seychelles">Seychelles</option>
								<option value="Singapura">Singapura</option>
								<option value="Somália">Somália</option>
								<option value="Sri Lanka">Sri Lanka</option>
								<option value="Suazilândia">Suazilândia</option>
								<option value="Sudão">Sudão</option>
								<option value="Suriname">Suriname</option>
								<option value="Suécia">Suécia</option>
								<option value="Suíça">Suíça</option>
								<option value="Svalbard e Jan Mayen">Svalbard e Jan Mayen</option>
								<option value="São Cristóvão e Nevis">São Cristóvão e Nevis</option>
								<option value="São Marino">São Marino</option>
								<option value="São Tomé e Príncipe">São Tomé e Príncipe</option>
								<option value="São Vicente e Granadinas">São Vicente e Granadinas</option>
								<option value="Sérvia">Sérvia</option>
								<option value="Síria">Síria</option>
								<option value="Tadjiquistão">Tadjiquistão</option>
								<option value="Tailândia">Tailândia</option>
								<option value="Taiwan">Taiwan</option>
								<option value="Tanzânia">Tanzânia</option>
								<option value="Terras Austrais e Antárticas Francesas">Terras Austrais e Antárticas Francesas</option>
								<option value="Território Britânico do Oceano Índico">Território Britânico do Oceano Índico</option>
								<option value="Timor-Leste">Timor-Leste</option>
								<option value="Togo">Togo</option>
								<option value="Tonga">Tonga</option>
								<option value="Toquelau">Toquelau</option>
								<option value="Trinidad e Tobago">Trinidad e Tobago</option>
								<option value="Tunísia">Tunísia</option>
								<option value="Turcas e Caicos">Turcas e Caicos</option>
								<option value="Turquemenistão">Turquemenistão</option>
								<option value="Turquia">Turquia</option>
								<option value="Tuvalu">Tuvalu</option>
								<option value="Ucrânia">Ucrânia</option>
								<option value="Uganda">Uganda</option>
								<option value="Uruguai">Uruguai</option>
								<option value="Uzbequistão">Uzbequistão</option>
								<option value="Vanuatu">Vanuatu</option>
								<option value="Vaticano">Vaticano</option>
								<option value="Venezuela">Venezuela</option>
								<option value="Vietname">Vietname</option>
								<option value="Wallis e Futuna">Wallis e Futuna</option>
								<option value="Zimbabwe">Zimbabwe</option>
								<option value="Zâmbia">Zâmbia</option>
							</select>

						</fieldset>	
						
						<input type="hidden" id="idVendaCabine" name="idVendaCabine" value="<?=$id?>" />
						<input type="hidden" id="idCabine" name="idCabine" value="<?=$idCabine?>" />						
						<a href="javascript:;" id="bt-compra" class="bt-login btnInterno comMargem">Finalizar</a>
						
					</fieldset>					
				</form>
				
			</div>
		
		</div>
			
		<?php include_once("rodape.php") ?>	
			
		<script type="text/javascript">		
		
			$(document).ready(function(){	
			
				$('#txttelefone').numeric({nocaps:true,ichars:'~´`^çáàãâéèêíìóòôõúùûüäëïöü_!@#$%¨&*+={}[]?/:;<>.,'});
				$('#txtcelular').numeric({nocaps:true,ichars:'~´`^çáàãâéèêíìóòôõúùûüäëïöü_!@#$%¨&*+={}[]?/:;<>.,'});
				$("#txtcpf").mask("999.999.999-99");
				$("#txtcep").mask("99.999-999");
				
				var placeholder = 'placeholder' in document.createElement('input');  
				if (!placeholder) {      
				  $.getScript("inc/js/jquery.html5form-1.5-min.js", function() {   
					  $(":input").each(function(){   // this will work for all input fields
						$(this).placeHolder();
					  });
				  });
				}
				
				function Trim(str){
					return str.replace(/^\s+|\s+$/g,"");
				}					
				
				function validaCPF(cpf){
					  cpf = cpf.replace(/\./gi, '');
					  cpf = cpf.replace(/\-/gi, '');

					  var numeros, digitos, soma, i, resultado, digitos_iguais;
					  digitos_iguais = 1;
					  if (cpf.length < 11)
							return false;
					  for (i = 0; i < cpf.length - 1; i++)
							if (cpf.charAt(i) != cpf.charAt(i + 1))
								  {
								  digitos_iguais = 0;
								  break;
								  }
					  if (!digitos_iguais)
							{
							numeros = cpf.substring(0,9);
							digitos = cpf.substring(9);
							soma = 0;
							for (i = 10; i > 1; i--)
								  soma += numeros.charAt(10 - i) * i;
							resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
							if (resultado != digitos.charAt(0))
								  return false;
							numeros = cpf.substring(0,10);
							soma = 0;
							for (i = 11; i > 1; i--)
								  soma += numeros.charAt(11 - i) * i;
							resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
							if (resultado != digitos.charAt(1))
								  return false;
							return true;
							}
					  else
							return false;
				}
				
				$('#bt-compra').click(function(){							
					
					$("#frmDadosCartao input:text").css("border-color","#CCC");
					$("#frmDadosCartao select").css("border-color","#CCC");
					$('#txtAceitoCartao').css("color","#666666");
					
					
					var reEmail 	= /^[\w-]+(\.[\w-]+)*@(([A-Za-z\d][A-Za-z\d-]{0,61}[A-Za-z\d]\.)+[A-Za-z]{2,6}|\[\d{1,3}(\.\d{1,3}){3}\])$/;
					
					var intErros = 0;
					
					var txtnome				= Trim($('#txtnome').val());
					var txtsobrenome		= Trim($('#txtsobrenome').val());
					var txttelefone			= Trim($('#txttelefone').val());
					var txtcelular			= Trim($('#txtcelular').val());
					var txtemail			= Trim($('#txtemail').val());
					var txtcpf				= Trim($('#txtcpf').val());
					var txtrg				= Trim($('#txtrg').val());
					var txtExpedidor		= Trim($('#txtExpedidor').val());						
					var tipo				= $('#tipo').val();
					var txtlogradouro		= Trim($('#txtlogradouro').val());
					var txtcomplemento		= Trim($('#txtcomplemento').val());
					var txtcep				= $('#txtcep').val();
					var txtcidade			= Trim($('#txtcidade').val());
					var txtestado			= Trim($('#txtestado').val());
					var pais				= $('#pais').val();
					
					if (txtnome == "" || txtnome == "Nome"){
						$('#txtnome').css("border-color","red");
						intErros++;
					}
					
					if (txtsobrenome == "" || txtsobrenome == "Sobrenome"){
						$('#txtsobrenome').css("border-color","red");
						intErros++;
					}
					
					if (txttelefone == "" || txttelefone == "Telefone"){
						$('#txttelefone').css("border-color","red");
						intErros++;
					}
					
					if (txtcelular == "" || txtcelular == "Celular"){
						$('#txtcelular').css("border-color","red");
						intErros++;
					}
					
					if (txtemail == "" || txtemail == "Email"){
						$('#txtemail').css("border-color","red");
						intErros++;
					}else{
						if(!txtemail.match(reEmail)){
							$('#txtemail').css("border-color","red");
							intErros++;
						}						
					}
					 
					if (txtcpf == "" || txtcpf == "CPF"){
						$('#txtcpf').css("border-color","red");
						intErros++;
					}else{
						if(!validaCPF(txtcpf)){
							$('#txtcpf').css("border-color","red");
							intErros++;
						}
					}
					
					if (txtrg == "" || txtrg == "RG"){
						$('#txtrg').css("border-color","red");
						intErros++;
					}
					
					if (txtExpedidor == "" || txtExpedidor == "Órgão expedidor"){
						$('#txtExpedidor').css("border-color","red");
						intErros++;
					}
					
					if (txtlogradouro == "" || txtlogradouro == "Logradouro"){
						$('#txtlogradouro').css("border-color","red");
						intErros++;
					}
					
					if (txtcomplemento == "" || txtcomplemento == "Complemento"){
						$('#txtcomplemento').css("border-color","red");
						intErros++;
					}
					
					if (txtcep == "" || txtcep == "CEP"){
						$('#txtcep').css("border-color","red");
						intErros++;
					}
					
					if (txtcidade == "" || txtcidade == "Cidade"){
						$('#txtcidade').css("border-color","red");
						intErros++;
					}
					
					if (txtestado == "" || txtestado == "Estado"){
						$('#txtestado').css("border-color","red");
						intErros++;
					}
					
					if(intErros != 0) {						
						$("#MsgErroValida").attr("style", "display:block;");	
						$('html, body').animate({ scrollTop: $("#cartao").offset().top }, 800);						
					}else{
						$("#frmDadosCartao").submit();	
					}	
					
				});				
			});
		
		</script>
		
	</body>
</html>