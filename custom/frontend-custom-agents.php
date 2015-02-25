<?php

/*
-------------------------------------------------------------------------------
* Utilidad: Muestra el FrontEnd para el rol Editor (Soporte).
* Creador: Cromorama.com - Julen Durán
* Fecha de Creación: 08-04-2014
* Fecha de la última modificación: 09-04-2014
* Versión: 1.0.0
* Nombre del archivo: frontend-custom-support.php
-------------------------------------------------------------------------------
*/

//Limitamos el acceso desde fuera del plugin.
if (stristr(htmlentities($_SERVER['PHP_SELF']), "frontend-custom-support.php")) {
	   Header("Location: ../../../../index.php");
    die();
}

if (isset($_REQUEST['clt_mode'])) $mode = $_REQUEST['clt_mode'];

	switch ($mode){
		case null:
			$frmFilters = $_POST;
?>		
            <div class="">
                <?php showSupportTicketTable($sppid, null); ?>
            </div>
<?php
			break;
		case 'viewSupportTicket':
?>		
            <div class="">
            	<?php supportMenuFrontEnd(); ?>
            </div>
            <div class="">
                <?php showSupportTicketTable($sppid, $_REQUEST['it']); ?>
            </div>
            <div class="">
                <?php showTracingSupport($_REQUEST['it'], $_REQUEST['ic'], $_REQUEST['st']); ?>
            </div>
<?php
			break;
		case 'sendCommentSupport':
			$frm_comment = $_POST;
			saveCommentSupport($frm_comment);
?>		
            <div class="">
            	<?php supportMenuFrontEnd(); ?>
            </div>
            <div class="">
                <?php showSupportTicketTable($sppid, $frm_comment['id_ticket']); ?>
            </div>
            <div class="">
                <?php showTracingSupport($frm_comment['id_ticket'], $frm_comment['$id_cia'], $frm_comment['st']); ?>
            </div>
<?php
			break;
		case 'closeSupportTicket':
			setCloseTicketSupport($_REQUEST['it']);
?>		
            <div class="">
                <?php showSupportTicketTable($sppid, null); ?>
            </div>
<?php
			break;
	}
?>