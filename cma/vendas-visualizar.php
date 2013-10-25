<? 
	require("../inc/engine/include.php");	

	seguranca();
	bloqueiaUsuarioCotacao();


	if($_GET){					
		$id = $_GET['id'];
		
		if(is_numeric($id)){					
			if(existeEsseRegistro($id, "vendacabine", $conexao)){
				$id = $_GET['id'];
			}else{
				$_SESSION['strErroCB'] = "A venda n&atilde;o existe no banco de dados.";
				redirectTo(WEB_ROOT_VENDAS);
			}						
		}else{
			$_SESSION['strErroCB'] = "O id passado n&atilde;o &eacute; num&eacute;rico.";
			redirectTo(WEB_ROOT_VENDAS);			
		}
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pt">
    <head>
        <title>Visualizar Venda | <?=PROJETO?></title>
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
	
		<script type="text/javascript" src="../inc/js/jquery.js"></script>
		<script type="text/javascript" src="../inc/js/jquery.simplemodal.js"></script>
		<script type="text/javascript" src="../inc/js/confirm.js"></script>
		<script type="text/javascript" src="../inc/js/funcoes.js"></script>
		<script type="text/javascript" src="../inc/js/jquery.maskedinput.js"></script>
		<script type="text/javascript" src="../inc/js/ui.datepicker.js"></script>
		<script type="text/javascript" src="../inc/js/passwordStrengthMeter.js"></script>	
				
		<link rel="stylesheet" type="text/css" href="../inc/css/screen.css" />
		<link rel="stylesheet" type="text/css" href="../inc/css/calendario.css" />
		<link rel="stylesheet" type="text/css" href="../inc/css/screen_imprimir.css" media="print" />

</head>
<body>	

	<?
	include_once("topo.php"); 
	
	// Dados gerais
	$sql 	= 	"SELECT idCabine, adultoHospede, criancaHospede, precoVendaCabine, precoVendaCabineReal, codVendaFinal, observacao
				FROM vendacabine
				WHERE idVendaCabine = ".$id;	

	$resultGeral		= query_execute($sql, $conexao) or die ("Não foi possível executar a consulta");
	$resultRowGeral		= mysql_fetch_array($resultGeral);
	
	$idCabine				= $resultRowGeral['idCabine'];
	$adultoHospede			= $resultRowGeral['adultoHospede'];
	$criancaHospede			= $resultRowGeral['criancaHospede'];
	$precoVendaCabine		= $resultRowGeral['precoVendaCabine'];
	$precoVendaCabineReal	= $resultRowGeral['precoVendaCabineReal'];
	$codVendaFinal			= $resultRowGeral['codVendaFinal'];
	$observacao				= $resultRowGeral['observacao'];
	?>
		
	<div class="control">				
		<ul>
			<li><a href="<?=WEB_ROOT_VENDAS?>"><img src="../inc/img/button/back.png" alt="Voltar" title="Voltar" /></a></li>
		</ul>
		<br/>
		<h2 class="titulo1">Registro da venda <?=$codVendaFinal?></h2>
	</div>
		
	<div class="conteudo">
	
		<fieldset class='comInput'>
			<legend>Visualizar</legend>					
			
				<div class="boxTabela">	
				
					<?
					// PAGAMENTO
					$sql 	= 	"SELECT tipoPagamento, idTelexfree, loginTelexfree, bonusConsumidoTelexfree, nome, sobrenome, telefone, celular, email, cpf, rg, expedidor, endereco, complemento, cep, cidade, estado, pais, idStatusPagamento, DATE_FORMAT( dtProcessamento,  '%d/%m/%Y %H:%i' ) AS dtProcessamento, DATE_FORMAT( dtLogPagamento,  '%d/%m/%Y' ) AS dtLogPagamento, motivoFalha
								FROM pagamento
								WHERE idVendaCabine =".$id;	
					
					$resultado		= query_execute($sql, $conexao) or die ("Não foi possível executar a consulta");
					$resultRow		= mysql_fetch_array($resultado);
					
					// bonus
					if ($resultRow['tipoPagamento'] == PAGAMENTO_BONUS){
						
						$idTelexfree				= $resultRow['idTelexfree'];
						$loginTelexfree				= $resultRow['loginTelexfree'];
						$email						= $resultRow['email'];
						$bonusConsumidoTelexfree	= $resultRow['bonusConsumidoTelexfree'];						
						$dtLogPagamento				= $resultRow['dtLogPagamento'];
						$dtProcessamento			= $resultRow['dtProcessamento'];
						$idStatusPagamento			= $resultRow['idStatusPagamento'];
						$motivoFalha				= $resultRow['motivoFalha'];
						
						?>
						<fieldset class='cadastro'>
							<legend>Pagamento via TelexFREE</legend>
							<strong class="opaco">ID TelexFREE: </strong><?=utf8_encode($idTelexfree)?><br />
							<strong class="opaco">Login TelexFREE: </strong><?=utf8_encode($loginTelexfree)?><br />
							<strong class="opaco">Bônus consumido: </strong><?=utf8_encode($bonusConsumidoTelexfree)?><br />
							<strong class="opaco">Preço em dólar: </strong><?=formataParaDolar($precoVendaCabine)?><br />
							<strong class="opaco">Preço em real: </strong><?=formataParaReal($precoVendaCabineReal)?><br />
							<strong class="opaco">Data do pagamento: </strong><?=$dtLogPagamento?><br />
							<strong class="opaco">Data do finalização do pagamento: </strong><?=$dtProcessamento?><br />
							<strong class="opaco">Status do pagamento: </strong>
							<?
							if ($idStatusPagamento == STATUS_AGUARDANDO_PAGAMENTO){
								echo "<span class='amarelo'>Aguardando pagamento</span>";
							}
							if ($idStatusPagamento == STATUS_PROCESSANDO_PAGAMENTO){
								echo "<span class='verdeClaro'>Processando pagamento</span>";
							}
							if ($idStatusPagamento == STATUS_PAGAMENTO_CONCLUIDO){
								echo "<span class='verde'>Pagamento concluído</span>";
							}
							if ($idStatusPagamento == STATUS_PAGAMENTO_CANCELADO){
								echo "<span class='vermelho'>Pagamento cancelado</span>";
								echo "<br /><strong class='opaco'>Motivo: </strong>".utf8_encode($motivoFalha);
							}
							if ($idStatusPagamento == STATUS_PAGAMENTO_ESTORNO){
								echo "<span class='vermelho'>Pagamento estornado</span>";
							}
							?><br />	
						
						</fieldset>						
						<?
					}else{
					
						$nome				= $resultRow['nome']." ".$resultRow['sobrenome'];
						$telefone			= $resultRow['telefone'];
						$celular			= $resultRow['celular'];
						$email				= $resultRow['email'];
						$cpf				= $resultRow['cpf'];
						$rg					= $resultRow['rg'];
						$expedidor			= $resultRow['expedidor'];
						$endereco			= $resultRow['endereco']." - ".$resultRow['complemento']." - CEP: ".$resultRow['cep'];
						$cidade				= $resultRow['cidade']." - ".$resultRow['estado']." - ".$resultRow['pais'];
						$idStatusPagamento	= $resultRow['idStatusPagamento'];
						$dtLogPagamento		= $resultRow['dtLogPagamento'];
						
						?>
						<fieldset class='cadastro'>
							<legend>Pagamento via Cartão</legend>
							<strong class="opaco">Nome: </strong><?=utf8_encode($nome)?><br />
							<strong class="opaco">Telefone: </strong><?=utf8_encode($telefone)?><br />
							<strong class="opaco">Celular: </strong><?=utf8_encode($celular)?><br />
							<strong class="opaco">CPF: </strong><?=utf8_encode($cpf)?><br />
							<strong class="opaco">RG: </strong><?=utf8_encode($rg)?><br />
							<strong class="opaco">Expedidor: </strong><?=utf8_encode($expedidor)?><br />
							<strong class="opaco">Endereço: </strong><?=utf8_encode($endereco)?><br />
							<strong class="opaco">Complemento: </strong><?=utf8_encode($cidade)?><br />
							<strong class="opaco">Preço em dólar: </strong><?=formataParaDolar($precoVendaCabine)?><br />
							<strong class="opaco">Preço em real: </strong><?=formataParaReal($precoVendaCabineReal)?><br />
							<strong class="opaco">Data do pagamento: </strong><?=utf8_encode($dtLogPagamento)?><br />
							<strong class="opaco">Status do pagamento: </strong>
							<?
							if ($idStatusPagamento == STATUS_AGUARDANDO_PAGAMENTO){
								echo "<span class='amarelo'>Aguardando pagamento</span>";
							}
							if ($idStatusPagamento == STATUS_PROCESSANDO_PAGAMENTO){
								echo "<span class='verdeClaro'>Processando pagamento</span>";
							}
							if ($idStatusPagamento == STATUS_PAGAMENTO_CONCLUIDO){
								echo "<span class='verde'>Pagamento concluído</span>";
							}
							if ($idStatusPagamento == STATUS_PAGAMENTO_CANCELADO){
								echo "<span class='vermelho'>Pagamento cancelado</span>";
							}
							if ($idStatusPagamento == STATUS_PAGAMENTO_ESTORNO){
								echo "<span class='vermelho'>Pagamento estornado</span>";
							}
							?><br />
						</fieldset>
						<?
					}
					
					// CABINE
					$sql 	= 	"SELECT numCabine, descricaoBr, conexaoCabine, deck, categoria, ocupacaoMaxima, terceiroTipo, terceiroOcupacao, quartoTipo, quartoOcupacao, idStatus, precoAdulto, precoCrianca
								FROM cabine
								WHERE idCabine =".$idCabine;	
								
					$resultado		= query_execute($sql, $conexao) or die ("Não foi possível executar a consulta");
					$resultRow		= mysql_fetch_array($resultado);
					
					$numCabine			= $resultRow['numCabine'];
					$descricaoBr		= $resultRow['descricaoBr'];
					$deck				= $resultRow['deck'];
					$categoria			= $resultRow['categoria'];
					$ocupacaoMaxima		= $resultRow['ocupacaoMaxima'];
					$idStatus			= $resultRow['idStatus'];
					$precoAdulto		= $resultRow['precoAdulto'];
					$precoCrianca		= $resultRow['precoCrianca'];
					?>
					
					<fieldset class='cadastro'>
						<legend>Cabine</legend>						
						<strong class="opaco">Nº Cabine: </strong><?=$numCabine?><br />
						<strong class="opaco">Descrição: </strong><?=utf8_encode($descricaoBr)?><br />
						<strong class="opaco">Deck: </strong><?=$deck?><br />
						<strong class="opaco">Categoria: </strong><?=$categoria?><br />
						<strong class="opaco">Ocupação máxima: </strong><?=$ocupacaoMaxima?><br />
						<?
							if ($adultoHospede > 0){
								echo "<strong class='opaco'>Hóspedes adultos: </strong>".$adultoHospede."<br />";
							}
							if ($criancaHospede > 0){
								echo "<strong class='opaco'>Hóspedes crianças: </strong>".$criancaHospede."<br />";
							}
						?>
						<strong class="opaco">Status: </strong>
						<?
							if ($idStatus == STATUS_LIVRE){
								echo "<span class='verde'>Livre</span>";
							}
							if ($idStatus == STATUS_RESERVADO){
								echo "<span class='amarelo'>Reservado</span>";
							}
							if ($idStatus == STATUS_OCUPADO){
								echo "<span class='cinza'>Ocupado</span>";
							}
						?>
					</fieldset>
					
					<?		
					// PASSAGEIROS
					$sql 	= 	"SELECT tipoPassageiro, nome, sobrenome, DATE_FORMAT( dtNascimento, '%d/%m/%Y') AS dtNascimento, sexo, nacionalidade, idioma, cidadeNatal, cpf, rg, orgaoExpedidor, passaporte, DATE_FORMAT( dtEmissao,  '%d/%m/%Y' ) AS dtEmissao, DATE_FORMAT( dtVencimento,  '%d/%m/%Y' ) AS dtVencimento, tamanhoCamisa
								FROM passageiro
								WHERE idVendaCabine = ".$id."
								ORDER BY tipoPassageiro ASC";	
					
					$resultado		= query_execute($sql, $conexao) or die ("Não foi possível executar a consulta");
					
					echo "<fieldset class='cadastro'>";
					echo "<legend>Passageiro</legend>";		
					
					while ($resultRow = mysql_fetch_array($resultado)) {
					
						$tipoPassageiro		= $resultRow['tipoPassageiro'];
						$nome				= $resultRow['nome']." ".$resultRow['sobrenome'] ;
						$dtNascimento		= $resultRow['dtNascimento'];
						$sexo				= $resultRow['sexo'];
						$nacionalidade		= $resultRow['nacionalidade'];
						$idioma				= $resultRow['idioma'];
						$cidadeNatal		= $resultRow['cidadeNatal'];
						$cpf				= $resultRow['cpf'];
						$rg					= $resultRow['rg'];
						$orgaoExpedidor		= $resultRow['orgaoExpedidor'];
						$passaporte			= $resultRow['passaporte'];
						$dtEmissao			= $resultRow['dtEmissao'];
						$dtVencimento		= $resultRow['dtVencimento'];
						$tamanhoCamisa		= $resultRow['tamanhoCamisa'];
						
						?>						
						<fieldset class='cadastro2'>
							<legend>
							<?								
							if ($tipoPassageiro == "a"){
								echo "Adulto";
							}else{
								echo "Criança";
							}								
							?>							
							</legend>	
						
							<strong class="opaco">Nome: </strong><?=utf8_encode($nome)?><br />
							<strong class="opaco">Data de nascimento: </strong><?=$dtNascimento?><br />
							<strong class="opaco">Sexo: </strong>
							<?
							if ($sexo == "m"){
								echo "Masculino";
							}else{
								echo "Feminino";
							}					
							?>	<br />
							<strong class="opaco">Nacionalidade: </strong><?=utf8_encode($nacionalidade)?><br />
							<strong class="opaco">Idioma: </strong><?=utf8_encode($idioma)?><br />
							<strong class="opaco">Cidade natal: </strong><?=utf8_encode($cidadeNatal)?><br />
							<strong class="opaco">CPF: </strong><?=utf8_encode($cpf)?><br />
							<strong class="opaco">RG: </strong><?=utf8_encode($rg)?><br />
							<strong class="opaco">Órgao expedidor: </strong><?=utf8_encode($orgaoExpedidor)?><br />
							<strong class="opaco">Tamanho da camisa: </strong><?=utf8_encode($tamanhoCamisa)?><br />
							<?
							if ($passaporte != ""){
								echo "<strong class='opaco'>Passaporte: </strong>".utf8_encode($passaporte)."<br />";
								echo "<strong class='opaco'>Data de emissão: </strong>".utf8_encode($dtEmissao)."<br />";
								echo "<strong class='opaco'>Data de vencimento: </strong>".utf8_encode($dtVencimento)."<br />";
							}						
							?>						
						</fieldset>						
					<?
					}
						
					if (trim($observacao) != ""){
						echo "<strong class='opaco'>Observação: </strong>".utf8_encode($observacao);
					}
					echo "</fieldset>";
					
					// CRUZEIRO					
					$sql 	= 	"SELECT nome, itinerario, portoSaida, portoChegada, DATE_FORMAT(dtSaida, '%d/%m/%Y') AS dtSaida, DATE_FORMAT(dtChegada, '%d/%m/%Y') AS dtChegada
								FROM cruzeiro WHERE idCruzeiro = ".NUMERO_CRUZEIRO;	
					
					$resultado		= query_execute($sql, $conexao) or die ("Não foi possível executar a consulta");
					$resultRow		= mysql_fetch_array($resultado);
					
					$nome			= $resultRow['nome'];
					$itinerario		= $resultRow['itinerario'];
					$portoSaida		= $resultRow['portoSaida'];
					$portoChegada	= $resultRow['portoChegada'];
					$dtSaida		= $resultRow['dtSaida'];
					$dtChegada		= $resultRow['dtChegada'];
					?>
					
					<fieldset class='cadastro'>
						<legend>Cruzeiro</legend>						
						<strong class="opaco">Cruzeiro: </strong><?=utf8_encode($nome)?><br />
						<strong class="opaco">Itinerário: </strong><?=utf8_encode($itinerario)?><br />
						<strong class="opaco">Porto saída: </strong><?=utf8_encode($portoSaida)?><br />
						<strong class="opaco">Porto chegada: </strong><?=utf8_encode($portoChegada)?><br />
						<strong class="opaco">Data saída: </strong><?=utf8_encode($dtSaida)?><br />
						<strong class="opaco">Data chegada: </strong><?=utf8_encode($dtChegada)?><br />					
					</fieldset>
					
					<?
					
					$sql		= 	"SELECT idControleCma, nome, DATE_FORMAT( dtLogControle, '%d/%m/%Y %H:%m:%s') AS dtLogControle, motivo, operacao
									FROM controlecma c, usuario u
									WHERE c.idUsuario = u.idUsuario
									AND idVendaCabine = ".$id;
						
					$resultado		= query_execute($sql, $conexao) or die ("Não foi possível executar a consulta");
					$intContagem	= (int) mysql_num_rows($resultado);				
					?>
					<fieldset class='cadastro3'>
						<legend>Log CMA</legend>
					
						<table id="gerenciar" class="tablesorter listaValores">
							<thead>
								<tr>
									<th>Id</th>
									<th>Data da alteração</th>							
									<th>Operação</th>
									<th>Motivo</th>
									<th>Usuário</th>
								</tr>
							</thead>
							<?
							if ($intContagem > 0){	
							
								while ($linha = mysql_fetch_array($resultado)) {
								?>
									<tr class='linhaTabela'>
										<td class="trCenter"><?=utf8_encode($linha["idControleCma"])?></td>
										<td class="trCenter"><?=utf8_encode($linha["dtLogControle"])?></td>
										<td class="trCenter"><?=$linha["operacao"]?></td>
										<td class="trCenter"><?=utf8_encode($linha["motivo"])?></td>
										<td class="trCenter"><?=utf8_encode($linha["nome"])?></td>									
									</tr>
								<?
								}
							}
							?>
						</table>
					</fieldset>
					
					<br/><br/>
					<a href="<?=WEB_ROOT_VENDAS?>" id="bt-voltar" class="bt-inserir btnInterno comMargem">Voltar</a>
					
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
