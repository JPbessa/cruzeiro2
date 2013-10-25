<?php include_once("../inc/engine/include.php");

if ((isset($_SESSION['userNome'])) and (isset($_SESSION['userId']))){	
	if (strtolower($_SESSION['userNome']) == 'telexfree'){
		header("Location: ".WEB_ROOT_COTACAO);
	}else{
		header("Location: ".WEB_ROOT_VENDAS);
	}	
}

if ($_POST){
		
	$txtlogin 	= utf8_decode(RetiraPlicas($_POST['txtlogin']));
	$txtsenha 	= utf8_decode(RetiraPlicas($_POST['txtsenha']));
	
	$strSQL			= 	"SELECT nome, idUsuario
						FROM usuario
						WHERE login = '$txtlogin' AND senha = MD5('$txtsenha') AND indHabilitado = 1"; 
	
	$resultado		= query_execute($strSQL, $conexao) or die ("Não foi possível executar a consulta");
	$intContagem	= (int) mysql_num_rows($resultado);

	if ($intContagem > 0){
		$linha = mysql_fetch_array($resultado);
		$_SESSION['userNome'] = $linha['nome'];
		$_SESSION['userId'] = $linha['idUsuario'];
		
		if (strtolower($_SESSION['userNome']) == 'telexfree'){
			header("Location: ".WEB_ROOT_COTACAO);
		}else{
			header("Location: ".WEB_ROOT_VENDAS);
		}
		
	}else{	
		$_SESSION['userFail'] = true;
		header("Location: ".WEB_ROOT_CMA);
		die();
	}
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pt">
<html>
	<head>		
        <title>Login | <?=PROJETO?></title>
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
		
		<link rel="stylesheet" type="text/css" href="inc/css/screen.css" />
		<link rel="stylesheet" type="text/css" href="inc/css/login.css" />
			
	</head>
	<body>	

		<div id="login">
			<div id='titulo'>
				<img src='inc/img/layout/logo.png' alt="1ª Convenção Internacional TelexFREE" title="1ª Convenção Internacional TelexFREE"/>
			</div>		
			<div id='cxLogin'>				
				<div id='tableLogin'>
					<div id="erroVazio" class="erro">Preencha todos os campos.</div>
					<?
					if ($_SESSION['userFail']){
						echo "<div id='erroLogin' class='erroLogin'>Erro: login ou senha inválidos.</div>";
						unset($_SESSION['userFail']);				
					}
					
					if ($_SESSION['permissionCmaDenied']){
						echo "<div id='erroLogin' class='erroLogin'>Área restrita. Logue-se.</div>";
						unset($_SESSION['userFail']);				
					}
					
					?>
					<form action="" method="post" id="formLogin" name="formLogin">
						<fieldset>
							<legend>Autenticação de Usuário</legend>
							<label>Login:&nbsp; <input type="text" id="txtlogin" name="txtlogin" value=""/></label><br />
							<label>Senha: <input type="password"  id="txtsenha" name="txtsenha"/></label><br />
							
							<a href="javascript:;" id="bt-entrar" class="bt-entrar btnInterno comMargem">Entrar</a>
						</fieldset>
					</form>
				</div>	
			</div>
		</div>
		
		
		<script type="text/javascript">
		
			$().ready(function() {
			
				$('#txtlogin').focus();
				
				function Trim(str){
					return str.replace(/^\s+|\s+$/g,"");
				}
				
				$(document).keypress(function(e) {
					if(e.which == 13) {
						validaLogin();
					}
				});
				
				
				$('#bt-entrar').click(function(){
					validaLogin();
				});
				
				
				function validaLogin(){
					$("#formLogin input:text").css("border-color","#CCC");
					var intErro	= 0;
					
					var login 		= Trim($('#txtlogin').val());
					var senha 		= Trim($('#txtsenha').val());
					
					if (login == ""){
						intErro++;
						$('#txtlogin').css("border","1px solid red");												
					}
					
					if (senha == ""){
						intErro++;
						$('#txtsenha').css("border","1px solid red");												
					}
					
					if(intErro > 0) {						
						$("#erroVazio").show();
					}else{
						$("#formLogin").submit();	
					}			
				
				}
			});

		</script>
		
		
	</body>
</html>
