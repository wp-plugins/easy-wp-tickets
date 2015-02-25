<?php

/*
-------------------------------------------------------------------------------
* Utilidad: Controla las funciones de la configuración.
* Creador: Cromorama.com - Julen Durán
* Fecha de Creación: 02-04-2014
* Última Modificación: 19-02-2015
* Versión: 1.0.1
* Nombre del archivo: crm-admin-configuration.php
-------------------------------------------------------------------------------
*/

//Función que muestra la configuración del envío de notificaciones.
function configNotification(){
	
	global $wpdb;
	$configUrl = "admin.php?page=config";
	$current_user = wp_get_current_user();	
	$row = $wpdb->get_row($wpdb->prepare("SELECT * FROM crm_config WHERE c_ref = 'easywptickets'"));
	
	$checked_mail = "";	
	$checked_plant_nt = "";
	$checked_plant_nc = "";
		
	if ($row->not_email_active == 0){ $checked_mail = "checked='checked'"; }
	if ($row->plant_nt_active == 0){ $checked_plant_nt = "checked='checked'"; }
	if ($row->plant_nc_active == 0){ $checked_plant_nc = "checked='checked'"; }
?>
    <table width="100%" border="0" cellpadding="5">
  		<tr>
    		<td width="150px" valign="top"><label><?php _e('Email para notificaciones', 'easywptickets'); ?>:</label></td>
   			<td><input type="email" name="config_admin_mail" value="<?php echo $row->not_email; ?>" maxlength="80" size="40" required /></td>
  		</tr>
  		<tr>
    		<td width="150px" valign="top">&nbsp;</td>
    		<td><input type="checkbox" name="config_admin_mail_active" <?php echo $checked_mail; ?> /><?php _e('Recibir notificaciones como administrador', 'easywptickets'); ?>.</td>
  		</tr>
  		<tr>
    		<td width="150px" valign="top"><label><?php _e('Plantilla Nuevo Ticket', 'easywptickets'); ?>:</label><br /><br /></td>
    		<td><textarea id="tinyeditor" name="config_plant_nuevo_ticket" rows="8" cols="70" ><?php echo $row->plant_nt; ?></textarea></td>
  		</tr>
        <tr>
    		<td width="150px" valign="top">&nbsp;</td>
    		<td>
            <input type="checkbox" name="config_mail_newticket_active" <?php echo $checked_plant_nt; ?> /><?php _e('Activar notificacion de nuevo ticket', 'easywptickets'); ?>.<br />
            <a href="<?php echo $configUrl."&hdn_mode=testMail&etype=newt"; ?>"><?php _e('Enviar email de prueba', 'easywptickets'); ?></a> (<?php _e('Guarda antes para ver cambios', 'easywptickets'); ?>)
            </td>
  		</tr>
          		<tr>
    		<td width="150px" valign="top"><label><?php _e('Plantilla Nuevo Comentario', 'easywptickets'); ?>:</label></td>
    		<td><textarea id="tinyeditor_dos" name="config_plant_nuevo_comment" rows="8" cols="70"><?php echo $row->plant_nc; ?></textarea></td>
  		</tr>
        <tr>
    		<td width="150px" valign="top">&nbsp;</td>
    		<td>
            <input type="checkbox" name="config_mail_newcomment_active" <?php echo $checked_plant_nc; ?> /><?php _e('Activar notificacion de nuevo comentario', 'easywptickets'); ?>.<br />
            <a href="<?php echo $configUrl."&hdn_mode=testMail&etype=newc"; ?>"><?php _e('Enviar email de prueba', 'easywptickets'); ?></a> (<?php _e('Guarda antes para ver cambios', 'easywptickets'); ?>)
            </td>
  		</tr>
	</table>

<script>
	var editor = new TINY.editor.edit('editor', {
	id: 'tinyeditor',
	width: 584,
	height: 175,
	cssclass: 'tinyeditor',
	controlclass: 'tinyeditor-control',
	rowclass: 'tinyeditor-header',
	dividerclass: 'tinyeditor-divider',
	controls: ['bold', 'italic', 'underline', 'strikethrough', '|', 'subscript', 'superscript', '|',
		'orderedlist', 'unorderedlist', '|', 'outdent', 'indent', '|', 'leftalign',
		'centeralign', 'rightalign', 'blockjustify', '|', 'unformat', '|', 'undo', 'redo', 'n',
		'font', 'size', 'style', '|', 'image', 'hr', 'link', 'unlink', '|', 'print'],
	footer: true,
	fonts: ['Verdana','Arial','Georgia','Trebuchet MS'],
	xhtml: true,
	cssfile: 'custom.css',
	bodyid: 'editor',
	footerclass: 'tinyeditor-footer',
	resize: {cssclass: 'resize'}
	});
	
	var editor_dos = new TINY.editor.edit('editor_dos', {
	id: 'tinyeditor_dos',
	width: 584,
	height: 175,
	cssclass: 'tinyeditor',
	controlclass: 'tinyeditor-control',
	rowclass: 'tinyeditor-header',
	dividerclass: 'tinyeditor-divider',
	controls: ['bold', 'italic', 'underline', 'strikethrough', '|', 'subscript', 'superscript', '|',
		'orderedlist', 'unorderedlist', '|', 'outdent', 'indent', '|', 'leftalign',
		'centeralign', 'rightalign', 'blockjustify', '|', 'unformat', '|', 'undo', 'redo', 'n',
		'font', 'size', 'style', '|', 'image', 'hr', 'link', 'unlink', '|', 'print'],
	footer: true,
	fonts: ['Verdana','Arial','Georgia','Trebuchet MS'],
	xhtml: true,
	cssfile: 'custom.css',
	bodyid: 'editor',
	footerclass: 'tinyeditor-footer',
	resize: {cssclass: 'resize'}
	});
	
	function activeText(){
		editor.post();
		editor_dos.post();
	};
	
</script>

<?php
}

//Función que muestra la configuración de las paginaciones.
function configPagination(){
	
	global $wpdb;
	$configUrl = "admin.php?page=config";
	$row = $wpdb->get_row($wpdb->prepare("SELECT pagination_active, num_pages FROM crm_config WHERE c_ref = 'easywptickets'"));
	
	$checked_mail = "";	
		
	if ($row->pagination_active == 0){ $checked_mail = "checked='checked'"; }

?>
	<table width="100%" border="0" cellpadding="5">
        <tr>
    		<td width="150px" valign="top"><label><?php _e('Activar Paginacion', 'easywptickets'); ?>:</label></td>
    		<td><input type="checkbox" name="config_active_pagination" <?php echo $checked_mail; ?> /><?php _e('Se mostrara la paginacion solo si es necesaria', 'easywptickets'); ?>.</td>
  		</tr>
  		<tr>
    		<td width="150px" valign="top"><label><?php _e('Elementos por pagina', 'easywptickets'); ?>:</label><br /><br /></td>
    		<td><input type="number" name="config_num_pages" value="<?php echo $row->num_pages; ?>" maxlength="2" size="5" required /></td>
  		</tr>
  		 <tr>
    		<td width="150px" valign="top"></td>
    		<td><i><b><?php _e('Nota', 'easywptickets'); ?>:</b> <?php _e('Esta version de paginacion no es compatible con los filtros', 'easywptickets'); ?>.</i></td>
  		</tr>
	</table>

<?php
}

function saveConfigData($frm_config){
	
	global $wpdb;
	$current_user = wp_get_current_user();
	
	if ($frm_config['config_admin_mail_active'] != 'on') $active_admin_mail = 1;
	if ($frm_config['config_mail_newticket_active'] != 'on') $active_mail_newticket = 1;
	if ($frm_config['config_mail_newcomment_active'] != 'on') $active_mail_newcomment = 1;
	if ($frm_config['config_active_pagination'] != 'on') $config_active_pagination = 1;
	
	$nuevo_config_plant_nuevo_ticket = ereg_replace("[\]", "", $frm_config['config_plant_nuevo_ticket']);
	$nuevo_config_plant_nuevo_comment = ereg_replace("[\]", "", $frm_config['config_plant_nuevo_comment']);
	
	$wpdb->update(
		'crm_config', 
		array( 
			'plant_nt' => "",
			'plant_nc' => ""
		), 
		array( 'c_ref' => "easywptickets" ));

	if($wpdb->update( 
		'crm_config', 
		array( 
			'not_email' => $frm_config['config_admin_mail'],
			'not_email_active' => $active_admin_mail,
			'plant_nt' => $nuevo_config_plant_nuevo_ticket,
			'plant_nt_active' => $active_mail_newticket,
			'plant_nc' => $nuevo_config_plant_nuevo_comment,
			'plant_nc_active' => $active_mail_newcomment,
			'pagination_active' => $config_active_pagination,
			'num_pages' => $frm_config['config_num_pages']
		), 
		array( 'c_ref' => "easywptickets" ))){
?>		
		<div id="aok" class="alertOk">
        	<img src="<?php echo plugins_url( '../img/icons/icon-ok-alerts.png' , __FILE__ );?>" alt="" title="">
            <?php _e('Cambios guardados correctamente', 'easywptickets'); ?>.
        </div>
<?php	 
	}else{
?>		
        <div id="aba" class="alertBad">
        	<img src="<?php echo plugins_url( '../img/icons/alert-icon.png' , __FILE__ );?>" alt="" title="">
        	<?php _e('No se han podido guardar los cambios', 'easywptickets'); ?>, <?php _e('Contacte con un administrador', 'easywptickets'); ?>.
        </div>
<?php
	}
}
?>