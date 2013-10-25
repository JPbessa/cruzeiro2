<? 
	require("../inc/engine/include.php");	

	seguranca();
	bloqueiaUsuarioCotacao();

	$intErros			= 0;
	
	if ($_POST){
	
		$nome		= utf8_decode(RetiraPlicas($_POST['nome']));
		$login		= utf8_decode(RetiraPlicas($_POST['login']));
		$senha		= utf8_decode(RetiraPlicas($_POST['senha']));
	
		$sql = "INSERT INTO usuario(login, nome, senha, indHabilitado, dtLogUsuario) 
				VALUES('".$login."', '".$nome."', MD5('".$senha."')', 1, '".date("Y-m-d H:i:s")."')";
				
		$resultado = query_execute($sql, $conexao) or die ("Não foi possivel inserir no banco de dados!");	
		
		$_SESSION['paginaAnterior'] = "inserir.php";
		$_SESSION['strErro'] = "";					
		redirectTo(WEB_ROOT_USUARIO);
		exit;	
		
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pt">
    <head>
        <title>Inserir Usuário | <?=PROJETO?></title>
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
				
				function Trim(str){
					return str.replace(/^\s+|\s+$/g,"");
				}
				
				$('#bt-limpar').click(function(){
					limpar();
				});
		
				$('#bt-inserir').click(function(){
				
					var intErros	= 0;
					var strErros	= "Os seguintes erros foram encontrados:<br \/>";
					
					var nome 		= Trim($('#nome').val());
					var login 		= Trim($('#login').val());
					var senha 		= Trim($('#senha').val());
					
					if (nome == ""){
						intErros++;
						strErros	+= intErros + ". O campo 'Nome' não foi preenchido;<br/>";	
					}
					
					if (login == ""){
						intErros++;
						strErros	+= intErros + ". O campo 'Login' não foi preenchido;<br/>";	
					}
					
					if (senha == ""){
						intErros++;
						strErros	+= intErros + ". O campo 'Senha' não foi preenchido;<br/>";	
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
			<li><a href="<?=WEB_ROOT_COTACAO?>"><img src="inc/img/button/back.png" alt="Voltar" title="Voltar" /></a></li>
		</ul>
		<br/>
		<h2 class="titulo1">Inserir Usuário do Dólar</h2>
	</div>
		
	<div class="conteudo">
	
		<fieldset class='comInput'>
			<legend>Inserir</legend>					
			
				<div class="boxTabela">		
				
					<div id="MsgErroValida" class='MsgErroValida'></div>				
				
					<form id="frmDados" name="frmDados" method="post" action="" >					
						<label for="nome">Nome:</label>
						<input type="text" id="nome" name="nome" value="" class="sizeCamposBox" maxlength="200" /><br />
						
						<label for="login">Login</label>
						<input type="text" id="login" name="login" value="" class="sizeCamposBox" maxlength="100" /><br />
						
						<label for="senha">Senha:</label>
						<input type="password" id="senha" name="senha" value="" class="sizeCamposBox" maxlength="15" /><br />
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
