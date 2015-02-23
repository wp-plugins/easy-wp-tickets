<?php

/*
-------------------------------------------------------------------------------
* Utilidad: Mostrar el tracing de un ticket
* Creador: Cromorama.com - Julen Durán
* Fecha de Creación: 29-01-2014
* Última Modificación: 29-01-2014
* Versión: 1.0.0
* Nombre del archivo: admin-custom-tickets-tracing.php
-------------------------------------------------------------------------------
*/

//Limitamos el acceso desde fuera del plugin.
if (stristr(htmlentities($_SERVER['PHP_SELF']), "admin-custom-tickets-tracing.php")) {
	   Header("Location: ../../../../index.php");
    die();
}

$rol = FindRol();

?>

<div class="contentMenu">
<?php 
	viewTicketMenu($rol, $it, $st);
?>
</div>

<div class="">
<?php 
	showTicketsTable($rol, $it, null);
?>
</div>

<div class="">
<?php 
	viewTicket($rol, $it, $st);
?>
</div>