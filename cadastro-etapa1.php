<?
	require("inc/engine/include.php");	
	
	// segurança
	unset($_SESSION['idVendaCabine']);
	
	if ($_POST){
	
		secure();	
		$intErros		= 0;
		$strErros		= "Os seguintes erros foram encontrados:<br \/>";	
		
		$quantAdulto		= utf8_decode(RetiraPlicas($_POST['quantAdulto']));	
		$quantCrianca		= utf8_decode(RetiraPlicas($_POST['quantCrianca']));
		
		if (($quantAdulto + $quantCrianca) <= 0){
			$intErros++;	
			$strErros	.= "- Escolha a quantidade de adultos ou crianças;<br/>";	
		}
		
		if ($intErros > 0){
			echo $strErros;			
		}else{
			$_SESSION['quantAdulto'] = $quantAdulto;
			$_SESSION['quantCrianca'] = $quantCrianca;		
			$_SESSION['quantTotalHospede'] = $quantAdulto + $quantCrianca;		
			$_SESSION['dataQuantHospede']	= date("Y-m-d H:i:s");
			
			redirectTo(WEB_ROOT."/escolha-de-cabine");				
		}
	}	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pt">
    <head>
        <title>Passageiros | <?=PROJETO?></title>
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
			
			<p>Claritas est etiam processus dynamicus, qui sequitur mutationem consuetudium lectorum. Mirum est notare quam littera gothica, quam nunc putamus parum claram, anteposuerit litterarum formas humanitatis per seacula quarta decima et quinta decima. Eodem modo typi, qui nunc nobis videntur parum clari, fiant sollemnes in futurum.</p>

			<h3 id="titPassageiro">Passageiros</h3>
			
			<p>Indique a quantidade de adultos e crianças (de 0 a 18 anos).</p>				
			
			<div id="MsgErroValida" class='MsgError'></div>	
			
			<?
			if ($_SESSION['permissionDenied']){
				?>
				<div id="MsgPermissionDenied" class='MsgError2'>
					Você executou uma operação ilegal!<br />
					Por favor escolha a quantidade de passageiros para o seu cruzeiro.
				</div>	
				<?
				unset($_SESSION['permissionDenied']);				
			}				
			?>
			
			<form id="frmDados" name="frmDados" method="post" action="" >	

				<fieldset class='comInput'>
					<legend>Cadastro</legend>
					
					<div class="quantidade">
						<label for="quantAdulto">Adultos: &nbsp;</label>						
						<input type="text" class="itemForm4" name="quantAdulto" id="quantAdulto" value="0" maxlength="3" />						
					</div>
					<div class="controleQuantidade">
						<span class="adicionarItem">
							<a id="adultoMais" class="bt" title="Adicionar Adulto">Adicionar</a>
						</span>
						<span class="removeItem">
							<a id="adultoMenos" class="bt" title="Remover Adulto">Remover</a>
						</span>
					</div>
					
					<br /><br /><br />
					
					<div class="quantidade">
						<label for="quantCrianca">Crianças: </label>						
						<input type="text" class="itemForm4" name="quantCrianca" id="quantCrianca" value="0" maxlength="3" />						
					</div>
					<div class="controleQuantidade">
						<span class="adicionarItem">
							<a id="criancaMais" class="bt" title="Adicionar Criança">Adicionar</a>
						</span>
						<span class="removeItem">
							<a id="criancaMenos" class="bt" title="Remover Criança">Remover</a>
						</span>
					</div>
					
					
				
				</fieldset>
				
				<div>
					<a href="javascript:;" id="bt-inserir" class="bt-inserir btnInterno comMargem">Continuar</a>
				</div>
			</form>
			
		</div>
			
		<?php include_once("rodape.php") ?>
			
		<script type="text/javascript">		
		
			$(document).ready(function(){	
			
				$('#quantAdulto').numeric({nocaps:true,ichars:'~´`^çáàãâéèêíìóòôõúùûüäëïöü_!@#$%¨&*+={}[]?/:;<>.,'});
				$('#quantCrianca').numeric({nocaps:true,ichars:'~´`^çáàãâéèêíìóòôõúùûüäëïöü_!@#$%¨&*+={}[]?/:;<>.,'});
				
				function Trim(str){
					return str.replace(/^\s+|\s+$/g,"");
				}		
			
				$('#adultoMais').click(function(){						
					$('#quantAdulto').val(parseInt($('#quantAdulto').val())+1);
				});
				
				$('#adultoMenos').click(function(){	
					if ($('#quantAdulto').val() > 0){				
						$('#quantAdulto').val(parseInt($('#quantAdulto').val())-1);
					}
				});
				
				$('#criancaMais').click(function(){						
					$('#quantCrianca').val(parseInt($('#quantCrianca').val())+1);
				});
				
				$('#criancaMenos').click(function(){	
					if ($('#quantCrianca').val() > 0){				
						$('#quantCrianca').val(parseInt($('#quantCrianca').val())-1);
					}
				});
				
				$('#bt-inserir').click(function(){							
					
					var intErros 		= 0;
					var strErros		= "Os seguintes erros foram encontrados:<br />";
					
					var quantAdulto		= Trim($('#quantAdulto').val());
					var quantCrianca	= Trim($('#quantCrianca').val());
					
					if ((parseInt(quantAdulto)+parseInt(quantCrianca)) <= 0){
						intErros++;	
						strErros	+= "- Escolha a quantidade de adultos ou crianças;<br />";	
					}else{
						if ((parseInt(quantAdulto)+parseInt(quantCrianca)) > 4){
							intErros++;	
							strErros	+= "- A reserva de cabine possui um limite máximo de 4 pessoas por transação;<br />";
						}
					}
					
					if(intErros != 0) {	
						$("#MsgPermissionDenied").attr("style", "display:none;");
						$("#MsgErroValida").empty();
						$("#MsgErroValida").append(strErros);
						$("#MsgErroValida").attr("style", "display:block;");
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
