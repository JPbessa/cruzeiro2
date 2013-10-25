<?
	require("inc/engine/include.php");	
	
	//seguranca
	if (!isset($_SESSION['idVendaCabine'])){
		$_SESSION['permissionDenied'] = true;
		header("Location: ".WEB_ROOT."/quantidade-de-passageiro");
		die();
	}else{
		
		// busco se já possui passageiros cadastros nessa venda de cabine
		$strSQL		= 	"SELECT COUNT(*) 
						FROM passageiro
						WHERE idVendaCabine = ".$_SESSION['idVendaCabine'];
		$resultSet		= query_execute($strSQL);
		$numRegistro	= mysql_result($resultSet, 0,0);
		
		if ($numRegistro > 0){
			unset($_SESSION['idVendaCabine']);
			$_SESSION['permissionDenied'] = true;
			header("Location: ".WEB_ROOT."/quantidade-de-passageiro");
			die();
		}	
	}
	
	if ($_POST){
		
		iniciaTransacao();
		
		$observacaoGeral 	= utf8_decode(RetiraPlicas($_POST['observacaoGeral']));
		
		// CADASTRO ADULTO
		if (isset($_POST['adulto-Nome'])) {		
		
			$adultoNome 			= $_POST['adulto-Nome'];		
			$adultoSobrenome		= $_POST['adulto-Sobrenome'];
			$adultoNascimentoDia	= $_POST['adulto-Nascimento-Dia'];
			$adultoNascimentoMes	= $_POST['adulto-Nascimento-Mes'];
			$adultoNascimentoAno	= $_POST['adulto-Nascimento-Ano'];
			$adultoSexo				= $_POST['adulto-Sexo'];
			$adultoNacionalidade	= $_POST['adulto-Nacionalidade'];
			$adultoIdioma			= $_POST['adulto-Idioma'];
			$adultoCidadenatal		= $_POST['adulto-Cidadenatal'];
			$adultoCPF				= $_POST['adulto-CPF'];
			$adultoRG				= $_POST['adulto-RG'];
			$adultoExpedidor		= $_POST['adulto-Expedidor'];
			$adultoPassaporte		= $_POST['adulto-Passaporte'];
			$adultoEmissaoDia		= $_POST['adulto-Emissao-Dia'];
			$adultoEmissaoMes		= $_POST['adulto-Emissao-Mes'];
			$adultoEmissaoAno		= $_POST['adulto-Emissao-Ano'];
			$adultoVencimentoDia	= $_POST['adulto-Vencimento-Dia'];
			$adultoVencimentoMes	= $_POST['adulto-Vencimento-Mes'];
			$adultoVencimentoAno	= $_POST['adulto-Vencimento-Ano'];
			$adultoCamisa			= $_POST['adulto-Camisa'];
		
			foreach ($adultoNome as $k => $value){		
			
				if (checkdate($adultoNascimentoMes[$k], $adultoNascimentoDia[$k], $adultoNascimentoAno[$k])){
					$dataNascimento = formataData($adultoNascimentoDia[$k]."/".$adultoNascimentoMes[$k]."/".$adultoNascimentoAno[$k]);
				}else{
					$dataNascimento = "";
				}
				
				if (checkdate($adultoEmissaoMes[$k], $adultoEmissaoDia[$k], $adultoEmissaoAno[$k])){
					$dataEmissao = formataData($adultoEmissaoDia[$k]."/".$adultoEmissaoMes[$k]."/".$adultoEmissaoAno[$k]);
				}else{
					$dataEmissao = "";
				}
				
				if (checkdate($adultoVencimentoMes[$k], $adultoVencimentoDia[$k], $adultoVencimentoAno[$k])){
					$dataVencimento = formataData($adultoVencimentoDia[$k]."/".$adultoVencimentoMes[$k]."/".$adultoVencimentoAno[$k]);
				}else{
					$dataVencimento = "";
				}
				
				$sql = "INSERT INTO passageiro (
							tipoPassageiro,
							nome,
							sobrenome,
							dtNascimento,
							sexo, 
							nacionalidade,
							idioma, 
							cidadeNatal,
							cpf,
							rg,
							orgaoExpedidor,
							passaporte,
							dtEmissao,
							dtVencimento,
							dtLogCadastro,
							idVendaCabine,
							tamanhoCamisa
						)VALUES(
							'a',
							'".utf8_decode(RetiraPlicas($adultoNome[$k]))."',
							'".utf8_decode(RetiraPlicas($adultoSobrenome[$k]))."',
							'".$dataNascimento."',
							'".utf8_decode(RetiraPlicas($adultoSexo[$k]))."',
							'".utf8_decode(RetiraPlicas($adultoNacionalidade[$k]))."',
							'".utf8_decode(RetiraPlicas($adultoIdioma[$k]))."',
							'".utf8_decode(RetiraPlicas($adultoCidadenatal[$k]))."',
							'".utf8_decode(RetiraPlicas($adultoCPF[$k]))."',
							'".utf8_decode(RetiraPlicas($adultoRG[$k]))."',
							'".utf8_decode(RetiraPlicas($adultoExpedidor[$k]))."',
							'".utf8_decode(RetiraPlicas($adultoPassaporte[$k]))."',
							'".$dataEmissao."',
							'".$dataVencimento."',
							'".date("Y-m-d H:i:s")."',
							".$_SESSION['idVendaCabine'].",
							'".$adultoCamisa[$k]."'
						)";
				
				$resultado = query_execute($sql, $conexao) or die ("Não foi possivel inserir no banco de dados!");					
			}	
		}

		// CADASTRO CRIANÇA
		if (isset($_POST['crianca-Nome'])) {		
		
			$criancaNome 			= $_POST['crianca-Nome'];		
			$criancaSobrenome		= $_POST['crianca-Sobrenome'];
			$criancaNascimentoDia	= $_POST['crianca-Nascimento-Dia'];
			$criancaNascimentoMes	= $_POST['crianca-Nascimento-Mes'];
			$criancaNascimentoAno	= $_POST['crianca-Nascimento-Ano'];
			$criancaSexo			= $_POST['crianca-Sexo'];
			$criancaNacionalidade	= $_POST['crianca-Nacionalidade'];
			$criancaIdioma			= $_POST['crianca-Idioma'];
			$criancaCidadenatal		= $_POST['crianca-CidadeNatal'];
			$criancaCPF				= $_POST['crianca-CPF'];
			$criancaRG				= $_POST['crianca-RG'];
			$criancaExpedidor		= $_POST['crianca-Expedidor'];
			$criancaPassaporte		= $_POST['crianca-Passaporte'];
			$criancaEmissaoDia		= $_POST['crianca-Emissao-Dia'];
			$criancaEmissaoMes		= $_POST['crianca-Emissao-Mes'];
			$criancaEmissaoAno		= $_POST['crianca-Emissao-Ano'];
			$criancaVencimentoDia	= $_POST['crianca-Vencimento-Dia'];
			$criancaVencimentoMes	= $_POST['crianca-Vencimento-Mes'];
			$criancaVencimentoAno	= $_POST['crianca-Vencimento-Ano'];
			$criancaCamisa			= $_POST['crianca-Camisa'];
			
			foreach ($criancaNome as $k => $value){		
			
				if (checkdate($criancaNascimentoMes[$k], $criancaNascimentoDia[$k], $criancaNascimentoAno[$k])){
					$dataNascimento = formataData($criancaNascimentoDia[$k]."/".$criancaNascimentoMes[$k]."/".$criancaNascimentoAno[$k]);
				}else{
					$dataNascimento = "";
				}
				
				if (checkdate($criancaEmissaoMes[$k], $criancaEmissaoDia[$k], $criancaEmissaoAno[$k])){
					$dataEmissao = formataData($criancaEmissaoDia[$k]."/".$criancaEmissaoMes[$k]."/".$criancaEmissaoAno[$k]);
				}else{
					$dataEmissao = "";
				}
				
				if (checkdate($criancaVencimentoMes[$k], $criancaVencimentoDia[$k], $criancaVencimentoAno[$k])){
					$dataVencimento = formataData($criancaVencimentoDia[$k]."/".$criancaVencimentoMes[$k]."/".$criancaVencimentoAno[$k]);
				}else{
					$dataVencimento = "";
				}
				
				$sql = "INSERT INTO passageiro (
							tipoPassageiro,
							nome,
							sobrenome,
							dtNascimento,
							sexo, 
							nacionalidade,
							idioma, 
							cidadeNatal,
							cpf,
							rg,
							orgaoExpedidor,
							passaporte,
							dtEmissao,
							dtVencimento,
							dtLogCadastro,
							idVendaCabine,
							tamanhoCamisa
						)VALUES(
							'c',
							'".utf8_decode(RetiraPlicas($criancaNome[$k]))."',
							'".utf8_decode(RetiraPlicas($criancaSobrenome[$k]))."',
							'".$dataNascimento."',
							'".utf8_decode(RetiraPlicas($criancaSexo[$k]))."',
							'".utf8_decode(RetiraPlicas($criancaNacionalidade[$k]))."',
							'".utf8_decode(RetiraPlicas($criancaIdioma[$k]))."',
							'".utf8_decode(RetiraPlicas($criancaCidadenatal[$k]))."',
							'".utf8_decode(RetiraPlicas($criancaCPF[$k]))."',
							'".utf8_decode(RetiraPlicas($criancaRG[$k]))."',
							'".utf8_decode(RetiraPlicas($criancaExpedidor[$k]))."',
							'".utf8_decode(RetiraPlicas($criancaPassaporte[$k]))."',
							'".$dataEmissao."',
							'".$dataVencimento."',
							'".date("Y-m-d H:i:s")."',
							".$_SESSION['idVendaCabine'].",
							'".$criancaCamisa[$k]."'
						)";
				
				$resultado = query_execute($sql, $conexao) or die ("Não foi possivel inserir no banco de dados!");					
			}	
		}
		
		if ($observacaoGeral <> ""){
		
				$sql = "UPDATE vendacabine SET observacao = '".$observacaoGeral ."'
						WHERE idVendaCabine = ".$_SESSION['idVendaCabine'];
				
				$resultado = query_execute($sql, $conexao) or die ("Não foi possivel inserir no banco de dados!");	
		}
		
		finalizaTransacao();
		
		redirectTo(WEB_ROOT."/forma-de-pagamento");
		
	}	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pt">
    <head>
         <title>Dados dos passageiros | <?=PROJETO?></title>
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
		
		<script type="text/javascript" src="inc/js/jquery.js"></script>
		<script type="text/javascript" src="inc/js/jquery.jcarousel.min.js"></script>
		<script type="text/javascript" src="inc/js/scrollTo.js"></script>
		<script type="text/javascript" src="inc/js/password_strength.js"></script>
		<script type="text/javascript" src="inc/js/alphanumeric.js"></script>
		<script type="text/javascript" src="inc/js/thickbox.js"></script>
		<script type="text/javascript" src="inc/js/jquery.html5form-1.5-min.js"></script>	
		<script type="text/javascript" src="inc/js/jquery.maskedinput-1.1.3.js"></script>	
	
		<link href="inc/css/reset.css" rel="stylesheet" type="text/css" media="screen" />
		<link href="inc/css/cadastro.css" rel="stylesheet" type="text/css" media="screen" />
		
	
	</head>
    <body class="home">
		
		<?php include_once("topo.php") ?>
			
			<div class="conteudo">
			
				<h2 id="titReserva">Reserva de Cabines</h2>
			
				<h3 id="titDados">Forneça os dados dos passageiros</h3>
			
				<div id="resumoReserva">
					<h4>Resumo da sua reserva:</h4>
					<?
					$int = 0;
					
					$sql 	= 	"SELECT adultoHospede, criancaHospede, descricaoCabine, ocupacaoMaximaCabine, precoVendaCabine, precoVendaCabineReal
								FROM vendacabine
								WHERE idVendaCabine = ".$_SESSION['idVendaCabine'];	
				
					$result			= query_execute($sql, $conexao) or die ("Não foi possível executar a consulta");
					$resultCabine	= mysql_fetch_array($result);

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
						<li><strong>Itinerário:</strong> <?=utf8_encode($resultRow['itinerario'])?></li>
						<li><strong>Porto de saída:</strong> <?=utf8_encode($resultRow['portoSaida'])?></li>
						<li><strong>Porto de chegada:</strong> <?=utf8_encode($resultRow['portoChegada'])?></li>
						<li><strong>Período:</strong> <?=$resultRow['dtSaida']?> até <?=$resultRow['dtChegada']?></li>
						<li><strong>Cabine:</strong> <?=$resultCabine['descricaoCabine']." - ocupação máxima de ".$resultCabine['ocupacaoMaximaCabine']." pessoas"?></li>
						<li><strong>Preço:</strong> <?=formataParaReal($precoVendaCabineReal)?> (<?=formataParaDolar($resultCabine['precoVendaCabine'])?>)</li>
					</ul>
				</div>
				
				<div id="MsgErroValida" class='MsgError'>
					Os seguintes erros foram encontrados:<br />
					- Os campos em vermelho são de preenchimento obrigatório ou contém um valor inválido.
				</div>	
				
				<p>Preencha os dados de cada passageiro. Ao finalizar, clique no botão continuar.</p>				
				
				<form id="frmDados" name="frmDados" method="post" action="" >	

					<fieldset class='comInput'>
						<legend>Cadastro</legend>
						
						<?
						if ($resultCabine['adultoHospede'] > 0){
							echo "<h3 class='tipoPassageiro'>Adultos:</h3>";
						}
						
						for ($adulto = 1; $adulto <= $resultCabine['adultoHospede']; $adulto++) {
						?>
						
							<fieldset class='cadastro'>
							<legend>Adulto <?=$adulto?></legend>
							
								<input type="text" class="itemForm4 obrigatorio" name="adulto-Nome[]" placeholder="Nome" maxlength="70" />
								<input type="text" class="itemForm4 obrigatorio" name="adulto-Sobrenome[]" placeholder="Sobrenome" maxlength="200" /><br />
								
								<label for="adulto-Nascimento-Dia">Nascimento:</label>						
								<select name="adulto-Nascimento-Dia[]" class="obrigatorio" style="width:80px; margin-left: 26px;">
									<option value="Dia">Dia</option>
									<?php
										for($i=1; $i<=31; $i++){
											$d = $i;
											if ($d < 10)
												$d = "0".$d;
											echo "<option value='$d'>$d</option>";
										}
									?>
								</select>					
								<select name="adulto-Nascimento-Mes[]" class="obrigatorio" style="width:150px;">
									<option value="Mês">Mês</option>
									<option value="01">Janeiro</option>
									<option value="02">Fevereiro</option>
									<option value="03">Março</option>
									<option value="04">Abril</option>
									<option value="05">Maio</option>
									<option value="06">Junho</option>
									<option value="07">Julho</option>
									<option value="08">Agosto</option>
									<option value="09">Setembro</option>
									<option value="10">Outubro</option>
									<option value="11">Novembro</option>
									<option value="12">Dezembro</option>
								</select>
								<select name="adulto-Nascimento-Ano[]" class="obrigatorio" style="width:80px;">
									<option value="Ano">Ano</option>
									<?php
										$year = date("Y");
										while ($year > 1899) {  
											echo '<option value="'.$year.'">'.$year.'</option>';
											$year = $year - '1';
										}
									?>
								</select>		

								<label for="adulto-Sexo" class="itemForm7">Sexo:</label>
								<select name="adulto-Sexo[]" class="obrigatorio" style="width:200px;">
									<option value="selecione">Selecione</option>
									<option value="m">Masculino</option>
									<option value="f">Feminino</option>								
								</select>
								<br />
								
								<select name="adulto-Nacionalidade[]" class="nacionalidade">
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
									<option value="Brasil" selected="selected">Brasil</option>
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
								<input type="text" class="itemForm2 obrigatorio" name="adulto-Idioma[]" placeholder="Idioma" maxlength="70" />
								<input type="text" class="itemForm2 obrigatorio" name="adulto-Cidadenatal[]" placeholder="Cidade natal" maxlength="70" /><br />
								
								<input type="text" class="itemForm6 cpf obrigatorio" name="adulto-CPF[]" placeholder="CPF" maxlength="14" />
								<input type="text" class="itemForm2 obrigatorio" name="adulto-RG[]" placeholder="RG" maxlength="14" />
								<input type="text" class="itemForm2 obrigatorio" name="adulto-Expedidor[]" placeholder="Orgão expedidor" maxlength="100" /><br />
								
								<div class="dados-passaporte">
									<input type="text" class="itemForm9" name="adulto-Passaporte[]" placeholder="Passaporte" maxlength="50" />
									<label for="adulto-Emissao-Dia" class="itemForm8">Emissão: </label>						
									<select name="adulto-Emissao-Dia[]" style="width:80px;">
										<option>Dia</option>
										<?php
											for($i=1; $i<=31; $i++){
												$d = $i;
												if ($d < 10)
													$d = "0".$d;
												echo "<option value='$d'>$d</option>";
											}
										?>
									</select>					
									<select name="adulto-Emissao-Mes[]" class="" style="width:150px;">
										<option>Mês</option>
										<option value="01">Janeiro</option>
										<option value="02">Fevereiro</option>
										<option value="03">Março</option>
										<option value="04">Abril</option>
										<option value="05">Maio</option>
										<option value="06">Junho</option>
										<option value="07">Julho</option>
										<option value="08">Agosto</option>
										<option value="09">Setembro</option>
										<option value="10">Outubro</option>
										<option value="11">Novembro</option>
										<option value="12">Dezembro</option>
									</select>
									<select name="adulto-Emissao-Ano[]" class="" style="width:80px;">
										<option>Ano</option>
										<?php
											$year = date("Y");
											while ($year > 1899) {  
												echo '<option value="'.$year.'">'.$year.'</option>';
												$year = $year - '1';
											}
										?>
									</select>							
									
									<label for="adulto-Vencimento-Dia">Vencimento: </label>						
									<select name="adulto-Vencimento-Dia[]" class="" style="width:80px; margin-left: 10px;">
										<option>Dia</option>
										<?php
											for($i=1; $i<=31; $i++){
												$d = $i;
												if ($d < 10)
													$d = "0".$d;
												echo "<option value='$d'>$d</option>";
											}
										?>
									</select>					
									<select name="adulto-Vencimento-Mes[]" class="" style="width:150px;">
										<option>Mês</option>
										<option value="01">Janeiro</option>
										<option value="02">Fevereiro</option>
										<option value="03">Março</option>
										<option value="04">Abril</option>
										<option value="05">Maio</option>
										<option value="06">Junho</option>
										<option value="07">Julho</option>
										<option value="08">Agosto</option>
										<option value="09">Setembro</option>
										<option value="10">Outubro</option>
										<option value="11">Novembro</option>
										<option value="12">Dezembro</option>
									</select>
									<select name="adulto-Vencimento-Ano[]" class="" style="width:80px;">
										<option>Ano</option>
										<?php
											$year = date("Y")+10;
											while ($year > 1899) {  
												echo '<option value="'.$year.'">'.$year.'</option>';
												$year = $year - '1';
											}
										?>
									</select>
								</div>
								
								<label for="adulto-Camisa">Tamanho da camisa:</label>						
								<select name="adulto-Camisa[]" class="obrigatorio" style="width:112px; margin-left: 12px;">
									<option value="Selecione">Selecione</option>
									<option value="P">P</option>
									<option value="M">M</option>
									<option value="G">G</option>
									<option value="EXG">EXG</option>
								</select>
								
							</fieldset>				
						
						<?
						}
						
						if ($resultCabine['criancaHospede'] > 0){
							echo "<h3 class='tipoPassageiro'>Crianças:</h3>";
						}
						
						for ($crianca = 1; $crianca <= $resultCabine['criancaHospede']; $crianca++) {
						?>
							
							<fieldset class='cadastro'>
							<legend>Criança <?=$crianca?></legend>
							
								<input type="text" class="itemForm4 obrigatorio" name="crianca-Nome[]" placeholder="Nome" maxlength="70" />
								<input type="text" class="itemForm4 obrigatorio" name="crianca-Sobrenome[]" placeholder="Sobrenome" maxlength="200" /><br />
								
								<label for="crianca-Nascimento-Dia">Nascimento:</label>						
								<select name="crianca-Nascimento-Dia[]" class="obrigatorio" style="width:80px; margin-left: 26px;">
									<option>Dia</option>
									<?php
										for($i=1; $i<=31; $i++){
											$d = $i;
											if ($d < 10)
												$d = "0".$d;
											echo "<option value='$d'>$d</option>";
										}
									?>
								</select>					
								<select name="crianca-Nascimento-Mes[]" class="obrigatorio" style="width:150px;">
									<option>Mês</option>
									<option value="01">Janeiro</option>
									<option value="02">Fevereiro</option>
									<option value="03">Março</option>
									<option value="04">Abril</option>
									<option value="05">Maio</option>
									<option value="06">Junho</option>
									<option value="07">Julho</option>
									<option value="08">Agosto</option>
									<option value="09">Setembro</option>
									<option value="10">Outubro</option>
									<option value="11">Novembro</option>
									<option value="12">Dezembro</option>
								</select>
								<select name="crianca-Nascimento-Ano[]" class="obrigatorio" style="width:80px;">
									<option>Ano</option>
									<?php
										$year = date("Y");
										while ($year > 1899) {  
											echo '<option value="'.$year.'">'.$year.'</option>';
											$year = $year - '1';
										}
									?>
								</select>		

								<label for="crianca-Sexo" class="itemForm7">Sexo:</label>
								<select name="crianca-Sexo[]" class="obrigatorio" style="width:200px;">
									<option value="selecione">Selecione</option>
									<option value="m">Masculino</option>
									<option value="f">Feminino</option>								
								</select>
								<br />
								
								<select name="crianca-Nacionalidade[]" class="nacionalidade">
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
									<option value="Brasil" selected="selected">Brasil</option>
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
								<input type="text" class="itemForm2 obrigatorio" name="crianca-Idioma[]" placeholder="Idioma" maxlength="70" />
								<input type="text" class="itemForm2 obrigatorio" name="crianca-CidadeNatal[]" placeholder="Cidade natal" maxlength="70" /><br />
								
								<input type="text" class="itemForm6 cpf" name="crianca-CPF[]" placeholder="CPF" maxlength="14" />							
								<input type="text" class="itemForm2" name="crianca-RG[]" placeholder="RG" maxlength="14" />
								<input type="text" class="itemForm2" name="crianca-Expedidor[]" placeholder="Orgão expedidor" maxlength="100" /><br />
								
								<div class="dados-passaporte">
									<input type="text" class="itemForm9" name="crianca-Passaporte[]" placeholder="Passaporte" maxlength="50" />
									<label for="crianca-Emissao-Dia" class="itemForm8">Emissão: </label>						
									<select name="crianca-Emissao-Dia[]" style="width:80px;">
										<option>Dia</option>
										<?php
											for($i=1; $i<=31; $i++){
												$d = $i;
												if ($d < 10)
													$d = "0".$d;
												echo "<option value='$d'>$d</option>";
											}
										?>
									</select>					
									<select name="crianca-Emissao-Mes[]" style="width:150px;">
										<option>Mês</option>
										<option value="01">Janeiro</option>
										<option value="02">Fevereiro</option>
										<option value="03">Março</option>
										<option value="04">Abril</option>
										<option value="05">Maio</option>
										<option value="06">Junho</option>
										<option value="07">Julho</option>
										<option value="08">Agosto</option>
										<option value="09">Setembro</option>
										<option value="10">Outubro</option>
										<option value="11">Novembro</option>
										<option value="12">Dezembro</option>
									</select>
									<select name="crianca-Emissao-Ano[]" style="width:80px;">
										<option>Ano</option>
										<?php
											$year = date("Y");
											while ($year > 1899) {  
												echo '<option value="'.$year.'">'.$year.'</option>';
												$year = $year - '1';
											}
										?>
									</select>							
									
									<label for="crianca-Vencimento-Dia">Vencimento: </label>						
									<select name="crianca-Vencimento-Dia[]" style="width:80px; margin-left: 10px;">
										<option>Dia</option>
										<?php
											for($i=1; $i<=31; $i++){
												$d = $i;
												if ($d < 10)
													$d = "0".$d;
												echo "<option value='$d'>$d</option>";
											}
										?>
									</select>					
									<select name="crianca-Vencimento-Mes[]" style="width:150px;">
										<option>Mês</option>
										<option value="01">Janeiro</option>
										<option value="02">Fevereiro</option>
										<option value="03">Março</option>
										<option value="04">Abril</option>
										<option value="05">Maio</option>
										<option value="06">Junho</option>
										<option value="07">Julho</option>
										<option value="08">Agosto</option>
										<option value="09">Setembro</option>
										<option value="10">Outubro</option>
										<option value="11">Novembro</option>
										<option value="12">Dezembro</option>
									</select>
									<select name="crianca-Vencimento-Ano[]" style="width:80px;">
										<option>Ano</option>
										<?php
											$year = date("Y")+10;
											while ($year > 1899) {  
												echo '<option value="'.$year.'">'.$year.'</option>';
												$year = $year - '1';
											}
										?>
									</select>
								</div>
								
								<label for="crianca-Camisa">Tamanho da camisa:</label>						
								<select name="crianca-Camisa[]" class="obrigatorio" style="width:112px; margin-left: 12px;">
									<option value="Selecione">Selecione</option>
									<option value="P">P</option>
									<option value="M">M</option>
									<option value="G">G</option>
									<option value="EXG">EXG</option>
								</select>
								
								
							</fieldset>
						<?
						}
						?>
						
						<textarea id="observacaoGeral" name="observacaoGeral" placeholder="Observações gerais" rows="3" cols="30"></textarea>
						
					</fieldset>				
				</form>					
				
				<a href="javascript:;" id="bt-inserir" class="bt-inserir btnInterno comMargem">Continuar</a>
			
			</div>
			
			<!-- rodape -->
			<?php include_once("rodape.php") ?>		

			<script type="text/javascript">		
			
				$(document).ready(function(){	
				
					$(".cpf").mask("999.999.999-99");
					
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
					
					$(".nacionalidade").change(function(){	
						if ($(this).val() != "Brasil"){
							$(this).parent().find(".dados-passaporte").show("slow");							
							$(this).parent().find("input[name^='adulto-CPF']").removeClass("obrigatorio");
							$(this).parent().find("input[name^='adulto-RG']").removeClass("obrigatorio");
							$(this).parent().find("input[name^='adulto-Expedidor']").removeClass("obrigatorio");
							$(this).parent().find("input[name*='-Passaporte']").addClass("obrigatorio");
							$(this).parent().find("select[name*='-Emissao-']").addClass("obrigatorio");
							$(this).parent().find("select[name*='-Vencimento-']").addClass("obrigatorio");						
						}else{
							$(this).parent().find(".dados-passaporte").hide();
							$(this).parent().find("input[name^='adulto-CPF']").addClass("obrigatorio");
							$(this).parent().find("input[name^='adulto-RG']").addClass("obrigatorio");
							$(this).parent().find("input[name^='adulto-Expedidor']").addClass("obrigatorio");
							$(this).parent().find("input[name*='-Passaporte']").removeClass("obrigatorio");
							$(this).parent().find("select[name*='-Emissao']").removeClass("obrigatorio");
							$(this).parent().find("select[name*='-Vencimento-']").removeClass("obrigatorio");							
						}
					});	
					
					$('#bt-inserir').click(function(){	

						$("#frmDados input:text").css("border-color","#CCC");
						$("#frmDados select").css("border-color","#CCC");
						
						var intErro = 0;
						
						$.each($('.obrigatorio'), function() {								
							var atributo = $(this).attr('name');
							var value = $(this).val();
							
							if (atributo == "adulto-Nome[]"){
								if ((value == "") || (value == "Nome")){
									intErro++;
									$(this).css("border","1px solid red");
								}							
							}
							
							if (atributo == "adulto-Sobrenome[]"){
								if ((value == "") || (value == "Nome")){
									intErro++;
									$(this).css("border","1px solid red");
								}							
							}
							
							if (atributo == "adulto-Nascimento-Dia[]"){
								if (value == "Dia"){
									intErro++;
									$(this).css("border","1px solid red");
								}							
							}
							
							if (atributo == "adulto-Nascimento-Mes[]"){
								if (value == "Mês"){
									intErro++;
									$(this).css("border","1px solid red");
								}							
							}
							
							if (atributo == "adulto-Nascimento-Ano[]"){
								if (value == "Ano"){
									intErro++;
									$(this).css("border","1px solid red");
								}							
							}
							
							if (atributo == "adulto-Sexo[]"){
								if (value == "selecione"){
									intErro++;
									$(this).css("border","1px solid red");
								}							
							}
						
							if (atributo == "adulto-Idioma[]"){
								if ((value == "") || (value == "Idioma")){
									intErro++;
									$(this).css("border","1px solid red");
								}							
							}

							if (atributo == "adulto-Cidadenatal[]"){
								if ((value == "") || (value == "Cidade natal")){
									intErro++;
									$(this).css("border","1px solid red");
								}							
							}
							
							if (atributo == "adulto-CPF[]"){
								if ((value == "") || (value == "CPF")){
									intErro++;
									$(this).css("border","1px solid red");
								}							
							}
							
							if (atributo == "adulto-RG[]"){
								if ((value == "") || (value == "RG")){
									intErro++;
									$(this).css("border","1px solid red");
								}							
							}
							
							if (atributo == "adulto-Expedidor[]"){
								if ((value == "") || (value == "Orgão expedidor")){
									intErro++;
									$(this).css("border","1px solid red");
								}							
							}
							
							if (atributo == "adulto-Passaporte[]"){
								if ((value == "") || (value == "Passaporte")){
									intErro++;
									$(this).css("border","1px solid red");
								}							
							}
							
							if (atributo == "adulto-Emissao-Dia[]"){
								if (value == "Dia"){
									intErro++;
									$(this).css("border","1px solid red");
								}							
							}
							
							if (atributo == "adulto-Emissao-Mes[]"){
								if (value == "Mês"){
									intErro++;
									$(this).css("border","1px solid red");
								}							
							}
							
							if (atributo == "adulto-Emissao-Ano[]"){
								if (value == "Ano"){
									intErro++;
									$(this).css("border","1px solid red");
								}							
							}
							
							if (atributo == "adulto-Vencimento-Dia[]"){
								if (value == "Dia"){
									intErro++;
									$(this).css("border","1px solid red");
								}							
							}
							
							if (atributo == "adulto-Vencimento-Mes[]"){
								if (value == "Mês"){
									intErro++;
									$(this).css("border","1px solid red");
								}							
							}
							
							if (atributo == "adulto-Vencimento-Ano[]"){
								if (value == "Ano"){
									intErro++;
									$(this).css("border","1px solid red");
								}							
							}
							
							if (atributo == "adulto-Camisa[]"){
								if (value == "Selecione"){
									intErro++;
									$(this).css("border","1px solid red");
								}							
							}
							
							
							// Validade das crianças
							
							if (atributo == "crianca-Nome[]"){
								if ((value == "") || (value == "Nome")){
									intErro++;
									$(this).css("border","1px solid red");
								}							
							}
							
							if (atributo == "crianca-Sobrenome[]"){
								if ((value == "") || (value == "Nome")){
									intErro++;
									$(this).css("border","1px solid red");
								}							
							}
							
							if (atributo == "crianca-Nascimento-Dia[]"){
								if (value == "Dia"){
									intErro++;
									$(this).css("border","1px solid red");
								}							
							}
							
							if (atributo == "crianca-Nascimento-Mes[]"){
								if (value == "Mês"){
									intErro++;
									$(this).css("border","1px solid red");
								}							
							}
							
							if (atributo == "crianca-Nascimento-Ano[]"){
								if (value == "Ano"){
									intErro++;
									$(this).css("border","1px solid red");
								}							
							}
							
							if (atributo == "crianca-Sexo[]"){
								if (value == "selecione"){
									intErro++;
									$(this).css("border","1px solid red");
								}							
							}
						
							if (atributo == "crianca-Idioma[]"){
								if ((value == "") || (value == "Idioma")){
									intErro++;
									$(this).css("border","1px solid red");
								}							
							}

							if (atributo == "crianca-CidadeNatal[]"){
								if ((value == "") || (value == "Cidade natal")){
									intErro++;
									$(this).css("border","1px solid red");
								}							
							}
							
							if (atributo == "crianca-Passaporte[]"){
								if ((value == "") || (value == "Passaporte")){
									intErro++;
									$(this).css("border","1px solid red");
								}							
							}
							
							if (atributo == "crianca-Emissao-Dia[]"){
								if (value == "Dia"){
									intErro++;
									$(this).css("border","1px solid red");
								}							
							}
							
							if (atributo == "crianca-Emissao-Mes[]"){
								if (value == "Mês"){
									intErro++;
									$(this).css("border","1px solid red");
								}							
							}
							
							if (atributo == "crianca-Emissao-Ano[]"){
								if (value == "Ano"){
									intErro++;
									$(this).css("border","1px solid red");
								}							
							}
							
							if (atributo == "crianca-Vencimento-Dia[]"){
								if (value == "Dia"){
									intErro++;
									$(this).css("border","1px solid red");
								}							
							}
							
							if (atributo == "crianca-Vencimento-Mes[]"){
								if (value == "Mês"){
									intErro++;
									$(this).css("border","1px solid red");
								}							
							}
							
							if (atributo == "crianca-Vencimento-Ano[]"){
								if (value == "Ano"){
									intErro++;
									$(this).css("border","1px solid red");
								}							
							}	

							if (atributo == "crianca-Camisa[]"){
								if (value == "Selecione"){
									intErro++;
									$(this).css("border","1px solid red");
								}							
							}
							
						});
						
						if(intErro > 0) {						
							$("#MsgErroValida").attr("style", "display:block;");
							$('html, body').animate({ scrollTop: $("#titReserva").offset().top }, 500);
						}else{
							$("#frmDados").submit();	
						}	
						
					});				
				});
			
			</script>
		
		<!-- begin olark code -->
		<script data-cfasync="false" type='text/javascript'>/*<![CDATA[*/window.olark||(function(c){var f=window,d=document,l=f.location.protocol=="https:"?"https:":"http:",z=c.name,r="load";var nt=function(){
		f[z]=function(){
		(a.s=a.s||[]).push(arguments)};var a=f[z]._={
		},q=c.methods.length;while(q--){(function(n){f[z][n]=function(){
		f[z]("call",n,arguments)}})(c.methods[q])}a.l=c.loader;a.i=nt;a.p={
		0:+new Date};a.P=function(u){
		a.p[u]=new Date-a.p[0]};function s(){
		a.P(r);f[z](r)}f.addEventListener?f.addEventListener(r,s,false):f.attachEvent("on"+r,s);var ld=function(){function p(hd){
		hd="head";return["<",hd,"></",hd,"><",i,' onl' + 'oad="var d=',g,";d.getElementsByTagName('head')[0].",j,"(d.",h,"('script')).",k,"='",l,"//",a.l,"'",'"',"></",i,">"].join("")}var i="body",m=d[i];if(!m){
		return setTimeout(ld,100)}a.P(1);var j="appendChild",h="createElement",k="src",n=d[h]("div"),v=n[j](d[h](z)),b=d[h]("iframe"),g="document",e="domain",o;n.style.display="none";m.insertBefore(n,m.firstChild).id=z;b.frameBorder="0";b.id=z+"-loader";if(/MSIE[ ]+6/.test(navigator.userAgent)){
		b.src="javascript:false"}b.allowTransparency="true";v[j](b);try{
		b.contentWindow[g].open()}catch(w){
		c[e]=d[e];o="javascript:var d="+g+".open();d.domain='"+d.domain+"';";b[k]=o+"void(0);"}try{
		var t=b.contentWindow[g];t.write(p());t.close()}catch(x){
		b[k]=o+'d.write("'+p().replace(/"/g,String.fromCharCode(92)+'"')+'");d.close();'}a.P(2)};ld()};nt()})({
		loader: "static.olark.com/jsclient/loader0.js",name:"olark",methods:["configure","extend","declare","identify"]});
		/* custom configuration goes here (www.olark.com/documentation) */
		olark.identify('1426-176-10-9116');/*]]>*/</script><noscript><a href="https://www.olark.com/site/1426-176-10-9116/contact" title="Contact us" target="_blank">Questions? Feedback?</a> powered by <a href="http://www.olark.com?welcome" title="Olark live chat software">Olark live chat software</a></noscript>
		<!-- end olark code -->
		
	</body>
</html>