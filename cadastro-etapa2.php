<?
	require("inc/engine/include.php");	
	
	//seguranca
	if (isset($_SESSION['idVendaCabine'])){
		unset($_SESSION['idVendaCabine']);
		$_SESSION['permissionDenied'] = true;
		header("Location: ".WEB_ROOT."/quantidade-de-passageiro");
		die();
	}
	
	if ($_POST){
	
		secure();	
		$intErros		= 0;
		$strErros		= "Os seguintes erros foram encontrados:<br \/>";	
		
		$cabine				= utf8_decode(RetiraPlicas($_POST['cabine']));	
		$valorVenda			= utf8_decode(RetiraPlicas($_POST['precoEstimado']));	
		$valorVendaReal		= utf8_decode(RetiraPlicas($_POST['precoEstimadoReal']));	
	
		if ($cabine	== ""){
			$strErros	.= "- Nenhuma cabine foi selecionada;<br/>";	
			echo $strErros;	
		}else{			
		
			list ($text, $tipo, $ocupacao) = split ('-', $cabine);
			
			$dtEscolhaCabine = date("Y-m-d H:i:s");
			
			iniciaTransacao();
			
			// reserva a cabine e retorna o idCabine
			$idCabine = reservaCabine(limpaFormataNomeCabine($tipo), $ocupacao, STATUS_LIVRE, STATUS_RESERVADO);	
			
			// reserva com sucesso
			if ($idCabine > 0){
				
				$sql = "INSERT INTO vendacabine (
							idCabine,
							adultoHospede,
							criancaHospede,
							dtLogHospede,
							descricaoCabine,
							ocupacaoMaximaCabine,
							precoVendaCabine,
							precoVendaCabineReal,
							dtLogCabine,
							blnAtivo
						)VALUES(
							".$idCabine.",
							".$_SESSION['quantAdulto'].",
							".$_SESSION['quantCrianca'].",
							'".$_SESSION['dataQuantHospede']."',
							'".limpaFormataNomeCabine($tipo)."',
							".$ocupacao.",
							".moneyToBD($valorVenda).",
							".moneyToBD($valorVendaReal).",
							'".$dtEscolhaCabine."',
							1
						)";
				
				$resultado = query_execute($sql, $conexao) or die ("Não foi possivel inserir no banco de dados!");
				
				$sql 	= 	"SELECT idVendaCabine FROM vendacabine 
							WHERE dtLogHospede = '".$_SESSION['dataQuantHospede']."' AND descricaoCabine = '".limpaFormataNomeCabine($tipo)."' AND dtLogCabine = '".$dtEscolhaCabine."'"; 	
				$resultado	= query_execute($sql, $conexao) or die ("Não foi possível executar a consulta");
				$resultRow	= mysql_fetch_array($resultado);
				
				$_SESSION['idVendaCabine'] = $resultRow['idVendaCabine'];	
				
				finalizaTransacao();
				
				redirectTo(WEB_ROOT."/informacoes-dos-passageiros");		
			
			// falha na reserva
			}else{
			
				finalizaTransacao();	

				$_SESSION['cabineOcupada'] = true;
				redirectTo(WEB_ROOT."/escolha-de-cabine");			
			}
		}		
	}	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pt">
    <head>
         <title>Escolha sua Cabine | <?=PROJETO?></title>
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
		<script type="text/javascript" src="inc/js/shadowbox.js"></script>
		<script type="text/javascript" src="inc/js/jquery.html5form-1.5-min.js"></script>	
		<script type="text/javascript" src="inc/js/jquery.maskedinput-1.1.3.js"></script>	
	
		<link href="inc/css/reset.css" rel="stylesheet" type="text/css" media="screen" />
		<link href="inc/css/cadastro.css" rel="stylesheet" type="text/css" media="screen" />
		<link href="inc/css/shadowbox.css" rel="stylesheet" type="text/css" media="screen" />

	</head>
    <body class="home">
		
		<?php include_once("topo.php") ?>
			
		<div class="conteudo">
		
			<h2 id="titReserva">Reserva de Cabines</h2>
		
			<h3 id="titCabine">Escolha sua Cabine</h3>
			
			<p>Selecione uma Cabine de sua preferência e clique em continuar.</p>					
			
			<?
			if ($_SESSION['cabineOcupada']){
				?>
				<div id="MsgPermissionDenied" class='MsgError2'>
					Esta cabine não está mais disponível!<br />
					Por favor escolha uma outra cabine.
				</div>	
				<?
				unset($_SESSION['cabineOcupada']);				
			}				
			?>			
			
			<div id="MsgErroValida" class='MsgError'></div>	
			<form id="frmDados" name="frmDados" method="post" action="" >	

				<fieldset class='comInput'>
					<legend>Cadastro</legend>
					
				<?
				$cotacaoDolar = cotacaoDolar();
				
				$sql	= 	"SELECT count( * ) AS quantCabine, descricaoIngles, descricaoBR, ocupacaoMaxima, foto, precoAdulto, precoCrianca
							FROM cabine
							WHERE descricaoIngles <> 'SUITE' AND ocupacaoMaxima >= ".($_SESSION['quantAdulto'] + $_SESSION['quantCrianca'])."
							GROUP BY descricaoBR, descricaoBR, ocupacaoMaxima
							ORDER BY ocupacaoMaxima ASC, descricaoBR ASC";

				$resultado		= query_execute($sql, $conexao) or die ("Não foi possível executar a consulta");
				$intContagem	= (int) mysql_num_rows($resultado);
					
				if ($intContagem > 0){	
					?>						
					
					<table class="boxTabela">
						<tr>
							<th>Minha escolha</th>
							<th>Descrição</th>
							<th>Foto</th>
							<th>Ocupação máxima da cabine</th>
							<th>Preço por ocupação</th>
							<th>Preço do Cruzeiro</th>								
							<th>Disponível</th>
						</tr>
						
						<?					
						while ($linha = mysql_fetch_array($resultado)) {
						
							$disponivel = verificaDisponibilidade($linha["descricaoBR"], $linha["ocupacaoMaxima"], STATUS_LIVRE);				
						?>	
						<tr class="linhaTabela <? if ($disponivel){echo "ativa";} ?>">
							<td class="trCenter"><input type="radio" name="cabine" <? if (!$disponivel){echo "disabled='disabled'";} ?> class="escolhaCabine" value="cabine-<?=formataNomeCabine($linha["descricaoBR"])?>-<?=$linha["ocupacaoMaxima"]?>" /></td>
							<td><?=ucfirst(strtolower($linha["descricaoBR"]))."<br /><span class='detalheCabine'>Livre:".quantidadeDisponibilidade($linha["descricaoBR"], $linha["ocupacaoMaxima"], STATUS_LIVRE)." de um total de ".$linha["quantCabine"]."</span>"?></td>
							<td class="trCenter"><a href="inc/img/cabine/<?=$linha["foto"]?>" rel="shadowbox" class="<? if ($disponivel){ echo "fotoCabine"; }else{ echo "fotoCabine-off";} ?>" title="Foto da Cabine '<?=ucfirst(strtolower($linha["descricaoBR"]))?>'"><span>Foto da Cabine '<?=ucfirst(strtolower($linha["descricaoBR"]))?>'</span></a></td>
							<td class="trCenter"><?=$linha["ocupacaoMaxima"]?></td>
							<td class="trCenter"><?=ocupacao($_SESSION['quantAdulto'], $_SESSION['quantCrianca']);?></td>
							<td class="trCenter"><?=calculaPreco($linha["precoAdulto"], $linha["precoCrianca"], $_SESSION['quantAdulto'], $_SESSION['quantCrianca']);?></td>
							<?
								if ($disponivel){
									echo "<td class='trCenter disponivel'><span>Disponível</span></td>";
								}else{
									echo "<td class='trCenter indisponivel'><span>Indisponível</span></td>";
								}							
							?>
						</tr>
						<?
						}	
						?>							
						<tr class="linhaTotal">
							<td colspan="4" id="titValorEstimado">Valor Total</td>
							<td colspan="3" id="valorEstimado" class="trCenter">
								<span id="valorTotal">US$ 0</span> - <span id="valorTotalReal">R$ 0</span>
								<input type="hidden" id="precoEstimado" name="precoEstimado" value="0" />									
								<input type="hidden" id="cotacaoDolar" name="cotacaoDolar" value="<?=$cotacaoDolar?>" />
								<input type="hidden" id="precoEstimadoReal" name="precoEstimadoReal" value="0" />
							</td>
						</tr>	
						
					</table>
				<?
				}
				?>
				</fieldset>		

				<div>
					<a href="javascript:;" id="bt-inserir" class="bt-inserir btnInterno comMargem">Continuar</a>
				</div>
				
			</form>	
		
		</div>
		
		<?php include_once("rodape.php") ?>

		<script type="text/javascript">		
		
			Shadowbox.init({
				overlayOpacity: 0.8,
				modal: true
			});
			
			$(document).ready(function(){	
			
				$('.linhaTabela:even').addClass('zebra1');
				$('.linhaTabela:odd').addClass('zebra');	
				$('.quantCabine').numeric({nocaps:true,ichars:'~´`^çáàãâéèêíìóòôõúùûüäëïöü_!@#$%¨&*+={}[]?/:;<>.,'});
				$('.ativa').css("cursor", "pointer");
				
				function Trim(str){
					return str.replace(/^\s+|\s+$/g,"");
				}		
				
				$('.ativa').click(function(){	
					$(this).find(".escolhaCabine").attr("checked",true);
					var valor = $(this).find(".precoFinal").html();
					var cotacaoDolar = $("#cotacaoDolar").val();
					
					$("#valorTotal").html(valor);	
					$("#precoEstimado").val(valor);
					
					var valorFloat = valor.replace("US$ ", "");
					var valorFloat = valorFloat.replace(",", "");
					var valorReal = parseFloat(valorFloat)*parseFloat(cotacaoDolar);
					
					$("#precoEstimadoReal").val(valorReal.toFixed(2));
					$("#valorTotalReal").html("R$ "+valorReal.toFixed(2));
				});		

				
			
				$('#bt-inserir').click(function(){							
					
					var intErros = 0;
					var strErros		= "Os seguintes erros foram encontrados:<br />";
					
					if ($('input[name=cabine]:checked').val() != null) {           
						$("#frmDados").submit();	
					}else{
						strErros	+= "- Nenhuma cabine foi selecionada;<br />";
						$("#MsgErroValida").empty();
						$("#MsgErroValida").append(strErros);
						$("#MsgErroValida").attr("style", "display:block;");
						$('html, body').animate({ scrollTop: $("#titReserva").offset().top }, 500);
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