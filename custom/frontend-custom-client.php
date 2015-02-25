<?php

/*
-------------------------------------------------------------------------------
* Utilidad: Muestra el FrontEnd para el rol Suscriber (Cliente).
* Creador: Cromorama.com - Julen Durán
* Fecha de Creación: 04-04-2014
* Fecha de la última modificación: 19-02-2015
* Versión: 1.0.0
* Nombre del archivo: frontend-custom-client.php
-------------------------------------------------------------------------------
*/

//Limitamos el acceso desde fuera del plugin.
if (stristr(htmlentities($_SERVER['PHP_SELF']), "frontend-custom-client.php")) {
	   Header("Location: ../../../../index.php");
    die();
}

if (isset($_REQUEST['clt_mode'])) $mode = $_REQUEST['clt_mode'];
if (isset($_REQUEST['ic'])) $ic = $_REQUEST['ic'];

?>
<div class="">
<?php

	switch ($mode){
		case null:	
?>
			<div class="clientDataContainer">
				<?php showMenuClient($sppid, null); ?>
            </div>
            <div class="">
            	<?php showTicketsTableClient($sppid, null); ?>
            </div>
<?php
			break;
			
		case 'return':
?>
			<div class="clientDataContainer">
				<?php showMenuClient($ic, null); ?>
            </div>
            <div class="">
            	<?php showTicketsTableClient($ic, null); ?>
            </div>
<?php
			break;

		case 'viewTicket':
?>
            <div class="clientDataContainer">
				<?php showMenuClient($ic, $mode); ?>
            </div>
            <div class="">
            	<?php showTicketsTableClient($ic, $it); ?>
            </div>
			<div class="">
				<?php viewTicketTraCIA($it, $ic, $st); ?>
			</div>
<?php
			break;

		case 'newTicket':
?>
			<div class="clientDataContainer">
				<?php showMenuClient($ic, $mode); ?>
            </div>
			<div class="">
				<?php createNewTicket($ic); ?>
			</div>
<?php
            break;
		case 'sendTicket':
			$frmNewTicket = $_POST;
			setNewTicket($frmNewTicket);
?>
			<div class="clientDataContainer">
				<?php showMenuClient($frmNewTicket['ic'], $mode); ?>
            </div>
            <div class="">
            	<?php showTicketsTableClient($frmNewTicket['ic'], null); ?>
            </div>
<?php
			break;
	}
?>
</div>
<?php
?>