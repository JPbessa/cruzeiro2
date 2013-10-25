<?php include_once("../inc/engine/include.php");

	unset($_SESSION['userNome']);
	unset($_SESSION['userId']);	
	header("Location: ".WEB_ROOT."/cma/");
?>