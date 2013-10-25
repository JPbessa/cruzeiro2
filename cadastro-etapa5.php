<?
	require("inc/engine/include.php");	
	require("email.php");	
	
	if (!isset($_SESSION['idVendaCabine'])){
		$_SESSION['permissionDenied'] = true;
		header("Location: ".WEB_ROOT."/quantidade-de-passageiro");
		die();
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pt">
    <head>
         <title>Sucesso | <?=PROJETO?></title>
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
				
				<h3 id="titFinal">Reserva efetuada com sucesso</h3>
			
				<?
				$strSQL = 	"SELECT v.codVendaFinal, p.tipoPagamento, p.email
							FROM vendacabine v, pagamento p
							WHERE v.idVendaCabine = ".$_SESSION['idVendaCabine']."
							AND v.idVendaCabine = p.idVendaCabine";
				
				$resultado		= query_execute($strSQL, $conexao) or die ("Não foi possível executar a consulta");
				$resultRow		= mysql_fetch_array($resultado);
				
				unset($_SESSION['idVendaCabine']);
				
				if ($resultRow['tipoPagamento'] == PAGAMENTO_BONUS){
					?>
					<div class="divSucesso">
						<span class="titSucesso">Recebemos seu pedido!</span>
						<span class="numPedido">Número do pedido: <?=$resultRow['codVendaFinal']?></span>
						<p>Você receberá um email com os dados do pedido e também o status da verificação dos seus bônus junto a TelexFREE. Aguarde!</p>	
					</div>
					<?				
				}
				if ($resultRow['tipoPagamento'] == PAGAMENTO_CARTAO){
					?>
					<div class="divSucesso">
						<span class="titSucesso">Recebemos seu pedido!</span>
						<span class="numPedido">Número do pedido: <?=$resultRow['codVendaFinal']?></span>
						<p>Você receberá um email com os dados do pedido e também algumas instruções para proceder o pagamento via vartão de crédito. Aguarde nosso contato.</p>				
					</div>	
					<?	
				}

				$mensagem = criaEmail($resultRow['codVendaFinal']);
				// enviaEmail(EMAIL_DE, NOME_EMAIL_DE, $resultRow['email'].",".EMAIL_EVENTO, "Recebemos seu pedido", $mensagem);	
				enviaEmail(EMAIL_DE, utf8_decode(NOME_EMAIL_DE), $resultRow['email'].";".EMAIL_EVENTO.";".USER_EMAIL, "Recebemos seu pedido", $mensagem);	
				
				?>
				<br />
				<p>
					Estamos <strong>processando o seu pedido de reserva</strong> na 1ª Convenção Internacional TelexFREE, que acontecerá no Rio de Janeiro de 15 a 18 de Dezembro.<br />
					Dentro em breve entraremos em contato informando o status da sua reserva.	
				</p>
				
				<p>Para reservar mais cabines, clique no link abaixo.</p>
				
				<a href="quantidade-de-passageiro" title="Continuar comprando" id="comprarMais">Continuar comprando</a>
				
				<br />
				<a href="http://www.qualityviagens.com.br" id="passagemAerea">
					<img src="inc/img/passagem.jpg" alt="Precisa de passagem aérea até o local de embarque da Convenção?" title="Precisa de passagem aérea até o local de embarque da Convenção?" />
				</a>

			</div>			
		
		<?php include_once("rodape.php") ?>	
		
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
