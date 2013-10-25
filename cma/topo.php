<div class="topo">
  <h1><span class="logo"><?=PROJETO?></span></h1>
  <div class="session">Ol&aacute; <?=$_SESSION['userNome']?>, <a href="<?=WEB_ROOT_CMA?>/sair" title="sair">Sair</a></div>
</div>
<div class="menu">
	<ul>
		<?
		if (strtolower($_SESSION['userNome']) == 'telexfree'){
			?>
			<li><a href="<?=WEB_ROOT_COTACAO?>">Cotação do Dólar</a></li>
			<?
		}else{
			?>
			<li><a href="<?=WEB_ROOT_VENDAS?>">Registro de vendas</a></li>
			<li><a href="<?=WEB_ROOT_RESERVA?>">Gerenciar reservas</a></li>
			<li><a href="<?=WEB_ROOT_CABINE?>">Cabine</a></li>		
			<li><a href="<?=WEB_ROOT_COTACAO?>">Cotação do Dólar</a></li>
			<li><a href="<?=WEB_ROOT_CAMISA?>">Camisa</a></li>
			<li><a href="<?=WEB_ROOT_CRUZEIRO?>">Cruzeiro</a></li>
			<li><a href="<?=WEB_ROOT_USUARIO?>">Usuário</a></li>		
			<?			
		}
		?>
	</ul>
</div>