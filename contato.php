<?
	require("inc/engine/include.php");	
	
	if ($_POST){
	
		secure();	
		$intErros		= 0;
		$strErros		= "Os seguintes erros foram encontrados:<br \/>";	
		
		$nome		= utf8_decode(RetiraPlicas($_POST['nome']));	
		$email		= utf8_decode(RetiraPlicas($_POST['email']));
		$telefone	= utf8_decode(RetiraPlicas($_POST['telefone']));
		$mensagem	= utf8_decode(RetiraPlicas($_POST['mensagem']));
		
		$sql = "INSERT INTO contato (
					nome,
					email,
					telefone,
					mensagem,
					dtEnvio
				)VALUES(
					'".$nome."',
					'".$email."',
					'".$telefone."',
					'".$mensagem."',
					'".date("Y-m-d H:i:s")."'
				)";
				
		$resultado = query_execute($sql, $conexao) or die ("Não foi possivel inserir no banco de dados!");
		
		$mensagemFormatada = "";		
		$mensagemFormatada .= "<strong>Nome: </strong>".$nome."<br />";
		$mensagemFormatada .= "<strong>Email: </strong>".$email."<br />";
		$mensagemFormatada .= "<strong>Telefone: </strong>".$telefone."<br />";
		$mensagemFormatada .= "<strong>Data de envio: </strong>".$nome."<br />";
		$mensagemFormatada .= "<strong>Mensagem: </strong>".$mensagem;	
		
		enviaEmail($email, $nome, EMAIL_DE.";".USER_EMAIL , "Contato", $mensagemFormatada);

		$_SESSION['emailEnviado'] = true;
		
	}	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pt">
    <head>
         <title>Contato | <?=PROJETO?></title>
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
		
			<h2 id="titContato">Contato</h2>
			
			<p>Caso queira tirar alguma dúvida sobre a 1ª Convenção Internacional TelexFREE ou compartilhar uma sugestão com nossa equipe, basta entrar em contato através do formulário abaixo. Garantimos que você será respondido o mais breve possível!</p>
			
			<div id="MsgErroValida" class='MsgError'>
				Os seguintes erros foram encontrados:<br />
				- Os campos em vermelho são de preenchimento obrigatório ou contém um valor inválido.
			</div>	
			
			<?
			if ($_SESSION['emailEnviado']){
				echo "<div class='divSucesso'>";
					echo "<strong>Mensagem enviada com sucesso!</strong><br />";
					echo "Nossa equipe responderá sua mensagem o mais rápido possível.";
				echo "</div>";
				unset($_SESSION['emailEnviado']);
			}
			?>
			
			<form id="frmDados" name="frmDados" method="post" action="" >	

				<fieldset class='comInput'>
					<legend>Contato</legend>
					
					<label for="nome" class="labelContato1">Nome:</label>						
					<input type="text" class="itemForm4" name="nome" id="nome" maxlength="270" /><br />
					
					<label for="email" class="labelContato1">Email:</label>						
					<input type="text" class="itemForm4" name="email" id="email" maxlength="70" /><br />
					
					<label for="telefone" class="labelContato2">Telefone:</label>						
					<input type="text" class="itemForm4" name="telefone" id="telefone" maxlength="20" /><br />
					
					<label for="mensagem" class="labelContato3">Mensagem:</label>	
					<textarea id="mensagem" name="mensagem" class="textarea2" rows="3" cols="30"></textarea>
				
				</fieldset>
				
				<div>
					<a href="javascript:;" id="bt-inserir" class="bt-inserir btnInterno comMargem">Enviar</a>
				</div>
			</form>
			
		</div>
			
		<?php include_once("rodape.php") ?>
			
		<script type="text/javascript">		
		
			$(document).ready(function(){				
				
				$('#telefone').numeric({nocaps:true,ichars:'~´`^çáàãâéèêíìóòôõúùûüäëïöü_!@#$%¨&*+={}[]?/:;<>.,'});
				
				function Trim(str){
					return str.replace(/^\s+|\s+$/g,"");
				}		
			
				$('#bt-inserir').click(function(){							
					
					var intErros 		= 0;
					var strErros		= "Os seguintes erros foram encontrados:<br />";
					var reEmail 	= /^[\w-]+(\.[\w-]+)*@(([A-Za-z\d][A-Za-z\d-]{0,61}[A-Za-z\d]\.)+[A-Za-z]{2,6}|\[\d{1,3}(\.\d{1,3}){3}\])$/;
					
					$("#frmDados input:text").css("border-color","#CCC");
					$("#frmDados textarea").css("border-color","#CCC");
					
					var nome		= Trim($('#nome').val());
					var email		= Trim($('#email').val());
					var telefone	= Trim($('#telefone').val());
					var mensagem	= Trim($('#mensagem').val());
					
					if (nome == ""){
						$('#nome').css("border-color","red");
						intErros++;
					}
					
					if (email == ""){
						$('#email').css("border-color","red");
						intErros++;
					}else{
						if(!email.match(reEmail)){
							$('#email').css("border-color","red");
							intErros++;
						}						
					}
					
					if (telefone == ""){
						$('#telefone').css("border-color","red");
						intErros++;
					}
					
					if (mensagem == ""){
						$('#mensagem').css("border-color","red");
						intErros++;
					}
					
					if(intErros != 0) {	
						$("#MsgErroValida").attr("style", "display:block;");
						$('html, body').animate({ scrollTop: $("#titContato").offset().top }, 800);	
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
