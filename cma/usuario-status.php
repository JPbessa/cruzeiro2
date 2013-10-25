<? 

require("../inc/engine/include.php");	

if ($_GET){
	
	$idUsuario		= $_GET['idUsuario'];
	$acao			= $_GET['status'];

	$sql = "UPDATE usuario SET indHabilitado = $acao WHERE idUsuario= $idUsuario";
	$resultado = query_execute($sql, $conexao) or die ("Não foi possivel inserir no banco de dados!");	

}	
?>