<?php

/*
-------------------------------------------------------------------------------
* Utilidad: Mostrar la tabla de tickets en la administración del sistema.
* Creador: Cromorama.com - Julen Durán
* Fecha de Creación: 01-04-2014
* Última Modificación: 19-02-2015
* Versión: 1.0.1
* Nombre del archivo: admin-custom-tickets.php
-------------------------------------------------------------------------------
*/

//Limitamos el acceso desde fuera del plugin.
if (stristr(htmlentities($_SERVER['PHP_SELF']), "admin-custom-tickets.php")) {
	   Header("Location: ../../../../index.php");
    die();
}

global $wpdb;
$pag_active = false;
$config_row = $wpdb->get_row($wpdb->prepare("SELECT pagination_active, num_pages FROM crm_config WHERE c_ref = 'easywptickets'"));
if($config_row->pagination_active == 0){
	$pag_active = true;
}

$rol = FindRol();

?>

<div class="CRMTS_Container_Admin">
	<h1><?php _e('Administracion de Tickets', 'easywptickets'); ?></h1>
	<?php 

		switch ($mode){
			case null:
				$frmFilters = $_POST;	
?>
				<div class="filterContainer">
					<?php showFiltersForm($frmFilters); ?>
                </div>
<?php
				showTicketsTable($rol, null, $frmFilters, $pag_active, $_REQUEST['n_page'], $config_row->num_pages);
	
				if($pag_active && ($frmFilters == null)){
?>
                    <div class="paginationContainer">
                        <?php getPagination($_REQUEST['n_page'], $config_row->num_pages, $frmFilters); ?>
                    </div>
<?php	
				}
				break;
			case 'viewTicket':
				include_once 'admin-custom-tickets-tracing.php';
				break;
			case 'sendComment':
				$frmComment = $_POST;					
				saveComment($frmComment);
				$mode = "";
				$it = $frmComment['id_ticket'];
				include_once 'admin-custom-tickets-tracing.php';
				break;
			case "closeTicket":
				setCloseTicket($it);
?>
				<div class="filterContainer">
					<?php showFiltersForm(null); ?>
                </div>
<?php
				showTicketsTable($rol, null, null, $pag_active, $_REQUEST['n_page'], $config_row->num_pages);
				
				if($pag_active && ($frmFilters == null)){
?>
                    <div class="paginationContainer">
                        <?php getPagination($_REQUEST['n_page'], $config_row->num_pages, null); ?>
                    </div>
<?php	
				}
				break;
			case "sendBill":					
				$frmBill = $_POST;
				setBill($frmBill);
				$mode = "";
				$it = $frmBill['it'];
				include_once 'admin-custom-tickets-tracing.php';
				break;
			case 'deleteTicket':
				setDeleteTicket($_REQUEST['it']);
?>
				<div class="filterContainer">
					<?php showFiltersForm(null); ?>
                </div>
<?php
				showTicketsTable($rol, null, null, $pag_active, $_REQUEST['n_page'], $config_row->num_pages);
				
				if($pag_active && ($frmFilters == null)){
?>
                    <div class="paginationContainer">
                        <?php getPagination($_REQUEST['n_page'], $config_row->num_pages, null); ?>
                    </div>
<?php	
				}
			break;
			}
	?>
</div>