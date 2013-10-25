<?
	session_start();
	
	/*
	===============================================================================
	 Inicialização de Variáveis
	-------------------------------------------------------------------------------
	*/
	// Variável que armazena o handler da conexão com o banco de dados.
	$conexao		= (string) "";
	
	// Variável que armazena o nome do banco de dados referente ao projeto.
	$strBD			= (string) "quality";
	
	// Variável que armazena o login do usuário da conexão com o banco de dados.
	$strUsuario 	= (string) "root";
	
	// Variável que armazena a senha do usuário da conexão com o banco de dados.
	$strSenha 		= (string) "";
	
	// Variável que armazena o camindo do servidor no banco de dados.
	$strServidor	= (string) "localhost";
	
	/*
	===============================================================================
	 Código do Script
	-------------------------------------------------------------------------------
	*/
	
		
	// Estabelecendo conexão com o banco de dados.
	$conexao = mysql_connect($strServidor, $strUsuario, $strSenha) 
				or die ("<br /><br />N&atilde;o foi poss&iacute;vel se conectar ao Banco de Dados.<br />Contate o Administrador do Sistema e relate o seguinte erro: ".mysql_error());
	
	// seleciona o banco de dados
	$db_selected = mysql_select_db($strBD, $conexao);

	if (!$db_selected) {
		die ('Não pode selecionar o banco de dados : ' . mysql_error());
	}
	
?>
