<?
	session_start();
	
	/*
	===============================================================================
	 Inicializa��o de Vari�veis
	-------------------------------------------------------------------------------
	*/
	// Vari�vel que armazena o handler da conex�o com o banco de dados.
	$conexao		= (string) "";
	
	// Vari�vel que armazena o nome do banco de dados referente ao projeto.
	$strBD			= (string) "quality";
	
	// Vari�vel que armazena o login do usu�rio da conex�o com o banco de dados.
	$strUsuario 	= (string) "root";
	
	// Vari�vel que armazena a senha do usu�rio da conex�o com o banco de dados.
	$strSenha 		= (string) "";
	
	// Vari�vel que armazena o camindo do servidor no banco de dados.
	$strServidor	= (string) "localhost";
	
	/*
	===============================================================================
	 C�digo do Script
	-------------------------------------------------------------------------------
	*/
	
		
	// Estabelecendo conex�o com o banco de dados.
	$conexao = mysql_connect($strServidor, $strUsuario, $strSenha) 
				or die ("<br /><br />N&atilde;o foi poss&iacute;vel se conectar ao Banco de Dados.<br />Contate o Administrador do Sistema e relate o seguinte erro: ".mysql_error());
	
	// seleciona o banco de dados
	$db_selected = mysql_select_db($strBD, $conexao);

	if (!$db_selected) {
		die ('N�o pode selecionar o banco de dados : ' . mysql_error());
	}
	
?>
