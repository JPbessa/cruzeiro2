<?
session_start();
require("../inc/engine/include.php");

	if($_GET){					
		$id = $_GET['id'];
		
		if(is_numeric($id)){					
			if(existeEsseRegistro($id, "cruzeiro", $conexao)){
				$id = $_GET['id'];
			}else{
				$_SESSION['strErroCB'] = "O cruzeiro n&atilde;o existe no banco de dados.";
				redirectTo(WEB_ROOT_CRUZEIRO);
			}						
		}else{
			$_SESSION['strErroCB'] = "O id passado n&atilde;o &eacute; num&eacute;rico.";
			redirectTo(WEB_ROOT_CRUZEIRO);			
		}	
	}

	$strSQL			= 	"SELECT COUNT(*) 
						FROM cabine
						WHERE idCruzeiro =" . $id;			
	$resultSet		= query_execute($strSQL);
	$numRegistro	= mysql_result($resultSet, 0,0);

	if($numRegistro > 0){
		$_SESSION['paginaAnterior'] = "excluirErro.php";			
		redirectTo(WEB_ROOT_CRUZEIRO);	
	}else{		
		$sql		= "DELETE FROM cruzeiro WHERE idCruzeiro =" . $id;				
		$resultado	= query_execute($sql, $conexao);			
		$_SESSION['paginaAnterior'] = "excluir.php";			
		redirectTo(WEB_ROOT_CRUZEIRO);
	}
	
?>