<?
	error_reporting(0);
	
	date_default_timezone_set('America/Sao_Paulo');
	
	define("WEB_ROOT_ERRO", "http://" . $_SERVER['HTTP_HOST']."/cruzeiro2/deuruim");
	define("WEB_ROOT_CMA", "http://".$_SERVER['HTTP_HOST']."/cruzeiro2/cma");
	define("WEB_ROOT_CRUZEIRO", "http://".$_SERVER['HTTP_HOST']."/cruzeiro2/cma/cruzeiro");
	define("WEB_ROOT_VENDAS", "http://".$_SERVER['HTTP_HOST']."/cruzeiro2/cma/vendas");
	define("WEB_ROOT_COTACAO", "http://".$_SERVER['HTTP_HOST']."/cruzeiro2/cma/cotacao");
	define("WEB_ROOT_USUARIO", "http://".$_SERVER['HTTP_HOST']."/cruzeiro2/cma/usuario");
	define("WEB_ROOT_CABINE", "http://".$_SERVER['HTTP_HOST']."/cruzeiro2/cma/cabine");
	define("WEB_ROOT_CAMISA", "http://".$_SERVER['HTTP_HOST']."/cruzeiro2/cma/camisa");
	define("WEB_ROOT_RESERVA", "http://".$_SERVER['HTTP_HOST']."/cruzeiro2/cma/gerenciar-reservas");
	define("WEB_ROOT", "http://" . $_SERVER['HTTP_HOST']."/cruzeiro2");
	
	define("STATUS_LIVRE", 0);
	define("STATUS_RESERVADO", 1);
	define("STATUS_OCUPADO", 2);
	
	define("STATUS_AGUARDANDO_PAGAMENTO", 1);
	define("STATUS_PROCESSANDO_PAGAMENTO", 2);
	define("STATUS_PAGAMENTO_CONCLUIDO", 3);	
	define("STATUS_PAGAMENTO_CANCELADO", 4);	
	define("STATUS_PAGAMENTO_ESTORNO", 5);	
	
	define("PAGAMENTO_BONUS", 1);
	define("PAGAMENTO_CARTAO", 2);
	
	define("NUMERO_CRUZEIRO", 1);
	
	define("EMAIL_DE", "eventos@qualityviagens.com.br");
	define("NOME_EMAIL_DE", "Convenção Internacional TelexFREE");
	define("EMAIL_PARA", "eventos@qualityviagens.com.br");
	define("EMAIL_EVENTO", "eventos@qualityviagens.com.br");
	
	define("USER_EMAIL", "convencaotelexfree@gmail.com");
	define("PASSWORD_EMAIL", "telexfree2013");	
	define("PROJETO", "1ª Convenção Internacional TelexFREE");
	
	require("abre_conexao.php");
	require("funcoes/funcoes.php");
	
	require("PHPMailer/class.phpmailer.php");	
	
	$_SESSION['usuario'] = "João Paulo";	
?>
