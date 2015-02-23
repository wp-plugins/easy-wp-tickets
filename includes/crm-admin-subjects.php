<?php

/*
-------------------------------------------------------------------------------
* Utilidad: Controla las funciones de la administración de Asuntos.
* Creador: Cromorama.com - Julen Durán
* Fecha de Creación: 01-04-2014
* Última Modificación: 19-02-2015
* Versión: 1.0.1
* Nombre del archivo: crm-admin-subjects.php
-------------------------------------------------------------------------------
*/

//Función para mostrar la tabla de Asuntos
function showSubjectsTable($is, $rol){
	
	global $wpdb;
	
	$rows = $wpdb->get_results($wpdb->prepare("SELECT crm_subjects.id_subject, crm_subjects.subject, crm_subjects.active, wp_users.display_name, wp_users.user_email FROM crm_subjects INNER JOIN wp_users ON crm_subjects.id_agent = wp_users.ID"));
	
	?>
    <table id="table_subjects" class="wp-list-table widefat" cellspacing="0">
		<thead>
			<tr>
				<th><?php _e('Asunto', 'easywptickets'); ?></th>
				<th><?php _e('Agente Asociado', 'easywptickets'); ?></th>
				<th><?php _e('Estado', 'easywptickets'); ?></th>
				<th><?php _e('Acciones', 'easywptickets'); ?></th>
			</tr>			
		</thead>
		<tbody>
<?php
		foreach ($rows as $row){
			
			$subjectDelURL = "admin.php?page=subjects&hdn_mode=deleteSubject&is=".$row->id_subject;
			$subjectEditURL = "admin.php?page=subjects&hdn_mode=editSubject&is=".$row->id_subject;

			$active = "<img src='".plugins_url( '../img/icons/no-active-icon.png' , __FILE__ )."' alt='' title=''>";
			if ($row->active == 0){
				$active = "<img src='".plugins_url( '../img/icons/active-icon.png' , __FILE__ )."' alt='' title=''>";
			}
?>		
			<tr class="dinTr">
				<td><?php echo $row->subject;?></td>
				<td><?php echo $row->display_name;?>&nbsp;-&nbsp;<?php echo $row->user_email;?></td>
				<td align="center" width="20"><?php echo $active;?></td>				
				<td align="center" width="60">
					<a href="<?php echo $subjectEditURL;?>">
                    	<img src="<?php echo plugins_url( '../img/icons/edit.png' , __FILE__ );?>" alt="<?php _e('Editar', 'easywptickets'); ?>" title="<?php _e('Editar', 'easywptickets'); ?>">
                    </a>
					&nbsp
					<a href="javascript:confirmDelete('<?php _e('Deseas borrar este asunto', 'easywptickets'); ?>\n<?php _e('Se eliminaran los tickets y comentarios asociados al mismo', 'easywptickets'); ?>.','<?php echo $subjectDelURL?>');">
                    	<img src="<?php echo plugins_url( '../img/icons/delete.png' , __FILE__ );?>" alt="<?php _e('Eliminar', 'easywptickets'); ?>" title="<?php _e('Eliminar', 'easywptickets'); ?>">
                    </a>
					</td>
			</tr>
<?php 
		}
?>			
		</tbody>
	</table>
<?php 	
} 

//Función para el menú de los asuntos
function viewSubjectMenu(){

	$subjectNewURL = "admin.php?page=subjects&hdn_mode=newSubject";
?>
	<div class="contentMenu">
    	<a class="button-primary" href="<?php echo $subjectNewURL;?>"><?php _e('Nuevo Asunto', 'easywptickets'); ?></a>
	</div>
<?php
}

//Función para crear un nuevo asunto
function showAddDataSubjects(){

	$subjectUrl = "admin.php?page=subjects";
?>
    <form id="frm_subject" name="frm_subject" action="<?php echo $subjectUrl;?>" method="post">
		<div class="configOptionContainer">
            <label><?php _e('Asunto', 'easywptickets'); ?>:</label><input type="text" name="subject" value="" maxlength="80" size="40" required/><br/>		
            <label><?php _e('Agente Asociado', 'easywptickets'); ?>:</label><?php showDataComboSupport(null); ?> <i><b><?php _e('Nota', 'easywptickets'); ?>:</b> <?php _e('El Agente Asociado debe ser un', 'easywptickets'); ?> <a href="../wp-admin/user-new.php" title="Nuevo Usuario" target="_blank"><?php _e('usuario registrado', 'easywptickets'); ?></a> <?php _e('con el perfil', 'easywptickets'); ?> "<?php _e('Agente', 'easywptickets'); ?>"</i><br/>
            <input type="checkbox" name="subject_active" checked="checked" style="margin-left:120px;"/> <?php _e('Activar', 'easywptickets'); ?>
        </div>
        <p style=float:right;">
            <input type="submit" name="Submit" class="button-primary" value="<?php _e('Crear Nuevo Asunto', 'easywptickets'); ?>" />
            &nbsp;
            <a href="admin.php?page=subjects" class="button-secondary"><?php _e('Cancelar Nuevo Asunto', 'easywptickets'); ?></a>
            <input type="hidden" name="subject_id" value="<?php echo $row->id_subject;?>"/><br/>
            <input type="hidden" name="hdn_mode" value="addSubject"/><br/>
		</p>
    </form>
<?php
}

//Función para grabar los datos del nuevo asunto
function setNewSubject($frmNewSubject){
	global $wpdb;
	$active = 1;
	if ($frmNewSubject['subject_active'] == 'on') $active = 0;
	if ($wpdb->insert(
			'crm_subjects',
			array(
					'subject' => $frmNewSubject['subject'],
					'id_agent' => $frmNewSubject['id_agent'],
					'active' => $active		
			)
	)){
		
	}else{
		//echo NO_INSERT;
	}
}

//Función para editar los asuntos
function showEditSubjects($is, $rol){
	
	global $wpdb;
	$subjectUrl = "admin.php?page=subjects";
	$row = $wpdb->get_row($wpdb->prepare("SELECT * FROM crm_subjects WHERE id_subject=$is"));

?>
		<form id="frm_subject" name="frm_subject" action="<?php echo $subjectUrl;?>" method="post">
<?php	
		$checked = "";		
		if ($row->active == 0) $checked = "checked='checked'";
?>
			<div class="configOptionContainer">
				<label><?php _e('Asunto', 'easywptickets'); ?>:</label><input type="text" name="subject" value="<?php echo $row->subject;?>" maxlength="80" size="40" required/><br />			
				<label><?php _e('Agente Asociado', 'easywptickets'); ?>:</label><?php showDataComboSupport($row->id_agent); ?><i><b><?php _e('Nota', 'easywptickets'); ?>:</b> <?php _e('El Agente Asociado debe ser un', 'easywptickets'); ?> <a href="../wp-admin/user-new.php" title="Nuevo Usuario" target="_blank"><?php _e('usuario registrado', 'easywptickets'); ?></a> <?php _e('con el perfil', 'easywptickets'); ?> "<?php _e('Agente', 'easywptickets'); ?>"</i><br />
				<label><?php _e('Activado', 'easywptickets'); ?>:</label><input type="checkbox" name="subject_active" <?php echo $checked;?>/><br />
			</div>
			<p style=float:right;">
                <input type="submit" name="Submit" class="button-primary" value="<?php _e('Actualizar Asunto', 'easywptickets'); ?>" />
				&nbsp;
				<a href="admin.php?page=subjects" class="button-secondary"><?php _e('Cancelar la Edicion', 'easywptickets'); ?></a>
				<input type="hidden" name="subject_id" value="<?php echo $row->id_subject;?>"/><br/>
				<input type="hidden" name="hdn_mode" value="updateSubject"/><br/>
			</p>
		</form>
<?php
}

//Función para obtener los soportes
function showDataComboSupport($id_agent){

	$rows= get_users('orderby=display_name&role=agent');
	
?>
	<select name="id_agent">
<?php
		foreach ($rows as $row){
			$selected = "";
			if ($id_agent ==  $row->ID) $selected = "selected='selected'";	
?>
			<option value="<?php echo $row->ID;?>" <?php echo $selected;?>><?php echo $row->display_name;?></option>
<?php
		} 
?>	
	</select>
<?php 	
}

//Función para salvar la edición de un asunto
function setUpdateSubject($frmUpdateSubject){
	global $wpdb;

	$active = 1;
	if ($frmUpdateSubject['subject_active'] == 'on') $active = 0;

	if ($wpdb->update('crm_subjects',
			array(
					'subject' => $frmUpdateSubject['subject'],
					'id_agent' => $frmUpdateSubject['id_agent'],
					'active' => $active
			),
			array( 'id_subject' => $frmUpdateSubject['subject_id']))){
	}else{
		//echo NO_INSERT;
	}
}

//Función para eliminar un asunto
function DeleteSubject($id_subject){
	global $wpdb;
	$rol = FindRol();
	
	// Si entramos con usuario del sistema
	if ($rol == ROL_ADMIN){
		
		$tracing_rows = $wpdb->get_results($wpdb->prepare("SELECT id_ticket FROM crm_tickets WHERE id_subject = $row->id_subject"));
		foreach ($tracing_rows as $row){
			
				$wpdb->delete('crm_tracing', array( 'id_ticket' => $row->id_ticket ) );
			
		}	
		
		$wpdb->delete('crm_tickets', array( 'id_subject' => $row->id_subject ) );
		$wpdb->delete('crm_subjects', array( 'id_subject' => $id_subject ) );
	}else{
?>
	<div class="">
    	<?php _e('No tienes permiso para realizar esta accion', 'easywptickets'); ?>.
    </div>
<?php
	}
}
?>