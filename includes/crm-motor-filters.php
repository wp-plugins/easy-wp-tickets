<?php

/*
-------------------------------------------------------------------------------
* Utilidad: Controla las funciones del motor del filtros.
* Creador: Cromorama.com
* Fecha de Creación: 01-04-2014
* Última Modificación: 19-02-2015
* Versión: 1.0.1
* Nombre del archivo: crm-motor-filters.php
-------------------------------------------------------------------------------
*/

//Función que maneja los filtros
function showFiltersForm($frmFilters){

	if (!empty($frmFilters)){

		$StatusIDSelect = $frmFilters["filterStatus"];
		$PriorityIDSelect = $frmFilters["filterPriority"];
		$userIDSelect = $frmFilters["filterUser"];
		$SubjectIDSelect = $frmFilters["filterSubject"];
	}

	//$filtrateURL = "admin.php?page=tickets";

?>

    <form id="frmFilters" name="frmFilters" action="<?php echo $filtrateURL;?>" method="post" enctype="multipart/form-data">
    	<select name="filterStatus">
      		<option value="all" <?php if ($StatusIDSelect == "all") echo "selected='selected'"; ?>><?php _e('Estado', 'easywptickets'); ?> (<?php _e('Ver Todo', 'easywptickets'); ?>)</option>
        	<option value="1" <?php if ($StatusIDSelect == "1") echo "selected='selected'"; ?> class="iconOpenTicket" ><?php _e('Tickets Abiertos', 'easywptickets'); ?></option>
        	<option value="2" <?php if ($StatusIDSelect == "2") echo "selected='selected'"; ?> class="iconClosedTicket" ><?php _e('Tickets Cerrados', 'easywptickets'); ?></option>
      	</select>
      	<select name="filterPriority">
      		<option value="all" <?php if ($PriorityIDSelect == "all") echo "selected='selected'"; ?>><?php _e('Prioridad', 'easywptickets'); ?> (<?php _e('Ver Todo', 'easywptickets'); ?>)</option>
        	<option value="1" <?php if ($PriorityIDSelect == "1") echo "selected='selected'"; ?> class="iconPrioA" ><?php _e('Alta', 'easywptickets'); ?></option>
        	<option value="2" <?php if ($PriorityIDSelect == "2") echo "selected='selected'"; ?> class="iconPrioM" ><?php _e('Media', 'easywptickets'); ?></option>
        	<option value="3" <?php if ($PriorityIDSelect == "3") echo "selected='selected'"; ?> class="iconPrioB" ><?php _e('Baja', 'easywptickets'); ?></option>
      	</select>
        <select name="filterUser">
      		<option value="all"><?php _e('Usuarios', 'easywptickets'); ?> (<?php _e('Ver Todo', 'easywptickets'); ?>)</option>
        	<?php getUserFilters($userIDSelect); ?>
      	</select>
       	<select name="filterSubject">
      		<option value="all"><?php _e('Asuntos', 'easywptickets'); ?> (<?php _e('Ver Todo', 'easywptickets'); ?>)</option>
        	<?php getSubjectsFilters($SubjectIDSelect); ?>
      	</select>
      	<input class="button-primary" type="submit" name="button" id="button" value="<?php _e('Filtrar', 'easywptickets'); ?>" />
    </form>

<?php
}


//Función que recoge los usuarios para meterlas en el select del filtro
function getUserFilters($userIDSelect){
	
	$rows= get_users('orderby=user_login&role=subscriber');

	foreach ($rows as $row){
	
		$selected = "";
		if ($userIDSelect == $row->ID) $selected = "selected='selected'";
	
?>
        <option value="<?php echo $row->ID;?>" <?php echo $selected;?>><?php echo $row->display_name;?></option>
<?php	
	}
}


//Función que recoge los asuntos
function getSubjectsFilters($SubjectIDSelect){
	
	global $wpdb;
	$rows = $wpdb->get_results($wpdb->prepare("SELECT id_subject, subject FROM crm_subjects ORDER BY subject"));

	foreach ($rows as $row){
		
		$selected = "";
		if ($SubjectIDSelect == $row->id_subject) $selected = "selected='selected'";
	
?>
		<option value="<?php echo $row->id_subject;?>" <?php echo $selected;?>><?php echo $row->subject;?></option>
<?php	
	}
}

//Función que genera la cadena where
function whereCreator($frmFilters){

	$whereConstruct = "";
	$countForAND = 0;
	
	if($frmFilters["filterStatus"] != "all"){ $x = $countForAND++; $whereAND[$x] = "id_ticket_state=".$frmFilters["filterStatus"]; }
	if($frmFilters["filterPriority"] != "all"){ $x = $countForAND++; $whereAND[$x] = "id_ticket_importance=".$frmFilters["filterPriority"]; }
	if($frmFilters["filterUser"] != "all"){ $x = $countForAND++; $whereAND[$x] = "id_user=".$frmFilters["filterUser"]; }
	if($frmFilters["filterSubject"] != "all"){ $x = $countForAND++; $whereAND[$x] = "crm_tickets.id_subject=".$frmFilters["filterSubject"]; }
	
	for ($i=0 ; $i<$countForAND ; $i++){
	
		$whereConstruct = $whereConstruct." ".$whereAND[$i];
		if ($i != ($countForAND-1)){ $whereConstruct = $whereConstruct." AND ";}
	}
	
	if ($whereConstruct != ""){ $whereString = "WHERE ".$whereConstruct;}
	
	return $whereString;
}
?>