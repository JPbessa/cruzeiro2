<? 
	require("../inc/engine/include.php");	

	seguranca();
	bloqueiaUsuarioCotacao();

	$intErros			= 0;
	
	if ($_POST){
	
		$nome			= utf8_decode(RetiraPlicas($_POST['nome']));
		$itinerario		= utf8_decode(RetiraPlicas($_POST['itinerario']));
		$portosaida		= utf8_decode(RetiraPlicas($_POST['portosaida']));	
		$portochegada	= utf8_decode(RetiraPlicas($_POST['portochegada']));	
		$saida			= formataData($_POST['saida']);
		$chegada		= formataData($_POST['chegada']);
		
		$sql = "INSERT INTO cruzeiro(nome, itinerario, portoSaida, portoChegada, dtSaida, dtChegada) 
				VALUES('$nome', '$itinerario', '$portosaida', '$portochegada','$saida','$chegada')";
		$resultado = query_execute($sql, $conexao) or die ("Não foi possivel inserir no banco de dados!");	
		
		$_SESSION['paginaAnterior'] = "inserir.php";
		$_SESSION['strErro'] = "";					
		redirectTo(WEB_ROOT_CRUZEIRO);
		exit;	
		
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pt">
    <head>
        <title>Inserir Cruzeiro | <?=PROJETO?></title>
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
		<script type="text/javascript" src="inc/js/jquery.maskedinput.js"></script>
		<script type="text/javascript" src="inc/js/ui.datepicker.js"></script>
		<script type="text/javascript" src="inc/js/passwordStrengthMeter.js"></script>	
	
		<link rel="stylesheet" type="text/css" href="inc/css/screen.css" />
		<link rel="stylesheet" type="text/css" href="inc/css/calendario.css" />
		<link rel="stylesheet" type="text/css" href="inc/css/screen_imprimir.css" media="print" />

		<script type="text/javascript">
		
			$().ready(function() {
				$("#saida").datepicker({showOn: 'button', buttonImage: 'inc/img/button/calendar.gif', buttonImageOnly: true});
				$("#saida").mask("99/99/9999");
				$("#chegada").datepicker({showOn: 'button', buttonImage: 'inc/img/button/calendar.gif', buttonImageOnly: true});
				$("#chegada").mask("99/99/9999");	

				function Trim(str){
					return str.replace(/^\s+|\s+$/g,"");
				}
				
				$('#bt-limpar').click(function(){
					limpar();
				});
		
				$('#bt-inserir').click(function(){
				
					var intErros	= 0;
					var strErros	= "Os seguintes erros foram encontrados:<br \/>";
					var reDate4 = /^((0?[1-9]|[12]\d)\/(0?[1-9]|1[0-2])|30\/(0?[13-9]|1[0-2])|31\/(0?[13578]|1[02]))\/(19|20)?\d{2}$/;
					
					var nome 			= Trim($('#nome').val());
					var itinerario 		= Trim($('#itinerario').val());
					var portosaida 		= Trim($('#portosaida').val());
					var portochegada 	= Trim($('#portochegada').val());					
					var saida			= Trim($('#saida').val());	
					var chegada			= Trim($('#chegada').val());	
					
					if (nome == ""){
						intErros++;
						strErros	+= intErros + ". O campo 'Nome' não foi preenchido;<br/>";	
					}
					
					if (itinerario == ""){
						intErros++;
						strErros	+= intErros + ". O campo 'Itinerário' não foi preenchido;<br/>";	
					}
					
					if (portosaida == ""){
						intErros++;
						strErros	+= intErros + ". O campo 'Porto saída' não foi preenchido;<br/>";	
					}
					
					if (portochegada == ""){
						intErros++;
						strErros	+= intErros + ". O campo 'Porto chegada' não foi preenchido;<br/>";	
					}
					
					if (saida == ""){
						intErros++;
						strErros	+= intErros + ". O campo 'Data Saída' não foi preenchido;<br/>";	
					}else{
						if(!saida.match(reDate4)){
							intErros++;
							strErros	+= intErros + ". O campo 'Data Saída' foi preenchido com formato inválido.<br/>";
						}else{
							if (ehMenorQueHoje(saida)){
								intErros++;
								strErros	+= intErros + ". O campo 'Data Saída' foi preenchido com valor inválido.<br/>";
							}
						}					
					}
					
					if (chegada == ""){
						intErros++;
						strErros	+= intErros + ". O campo 'Data Chegada' não foi preenchido;<br/>";	
					}else{
						if(!chegada.match(reDate4)){
							intErros++;
							strErros	+= intErros + ". O campo 'Data Chegada' foi preenchido com formato inválido.<br/>";
						}else{
							if (ehMenorQueHoje(chegada)){
								intErros++;
								strErros	+= intErros + ". O campo 'Data Chegada' foi preenchido com valor inválido.<br/>";
							}
						}					
					}
					
					if ((saida != "") && (chegada != "")){
						if (!dt2MaiorIgualDt1(saida, chegada)){
							intErros++;
							strErros	+= intErros + ". A 'Data Chegada' é anterior a 'Data Saída'.<br/>";
						}		
					}
					
					if(intErros != 0) {
						$("#MsgErroValida").empty();
						$("#MsgErroValida").append(strErros);
						$("#MsgErroValida").attr("style", "display:block;");	
						$('html, body').animate({ scrollTop: $("#MsgErroValida").offset().top }, 500);
					}else{
						$("#frmDados").submit();	
					}			
				
				});
			});

		</script>
		
</head>
<body>	

	<?php include_once("topo.php") ?>
		
	<div class="control">				
		<ul>
			<li><a href="<?=WEB_ROOT_CRUZEIRO?>"><img src="inc/img/button/back.png" alt="Voltar" title="Voltar" /></a></li>
		</ul>
		<br/>
		<h2 class="titulo1">Inserir Cruzeiro</h2>
	</div>
		
	<div class="conteudo">
	
		<fieldset class='comInput'>
			<legend>Inserir</legend>					
			
				<div class="boxTabela">		
				
					<div id="MsgErroValida" class='MsgErroValida'></div>				
				
					<form id="frmDados" name="frmDados" method="post" action="" >					
						<label for="nome">Nome:</label>
						<input type="text" id="nome" name="nome" value="" class="sizeCamposBox" maxlength="300" /><br />
						
						<label for="nome">Itinerário:</label>
						<input type="text" id="itinerario" name="itinerario" value="" class="sizeCamposBox" maxlength="200" /><br />
						
						<label for="nome">Porto saída:</label>
						<input type="text" id="portosaida" name="portosaida" value="" class="sizeCamposBox" maxlength="100" /><br />
						
						<label for="nome">Porto chegada:</label>
						<input type="text" id="portochegada" name="portochegada" value="" class="sizeCamposBox" maxlength="100" /><br />
						
						<label for="data">Data saída: </label>
						<input type="text" id="saida" name="saida" value="" class="sizeCamposBox"/><br />
						
						<label for="data">Data chegada: </label>
						<input type="text" id="chegada" name="chegada" value="" class="sizeCamposBox"/><br />						

					</form>
					
					<br/><br/>
					<a href="javascript:;" id="bt-inserir" class="bt-inserir btnInterno comMargem">Enviar</a>
					<a href="javascript:;" id="bt-limpar" class="btnInterno comMargem">Limpar</a>

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
