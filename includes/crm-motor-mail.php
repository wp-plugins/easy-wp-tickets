<?php

/*
-------------------------------------------------------------------------------
* Utilidad: Controla las funciones del motor de envío de emails.
* Creador: Cromorama.com - Julen Durán
* Fecha de Creación: 04-04-2014
* Última Modificación: 19-02-2015
* Versión: 1.0.1
* Nombre del archivo: crm-motor-mail.php
-------------------------------------------------------------------------------
*/

//Función que gestiona el envío de emails de notificación
function mailMotor($to, $type, $userName, $supportName, $description, $importance, $tid){	

	global $wpdb;
	$siteDomain = $_SERVER['SERVER_NAME'];

	$email_p = "plant_nc";
	$subject = get_bloginfo('name')." - [".__('Nuevo Comentario de Seguimiento', 'easywptickets')."]";
	if ($type == "ticket"){
		$email_p = "plant_nt";
		$subject = get_bloginfo('name')." - [".__('Nuevo Ticket', 'easywptickets')."]";
	}
	
	$row = $wpdb->get_row($wpdb->prepare("SELECT not_email, not_email_active, $email_p, plant_nt_active, plant_nc_active FROM crm_config WHERE c_ref = 'easywptickets'"));
		
	$message = $row->$email_p;
	$message = filterMail($message, null, $supportName, $userName, $description, $importance, $tid);

	$isActive = $email_p."_active";
	if ($row->$isActive == 0){	

		// Para enviar un correo HTML mail, la cabecera Content-type debe fijarse
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=utf8_unicode_ci' . "\r\n";
		
		// Cabeceras adicionales
		$headers .= "To: $to \r\n";
		$headers .= "From: <noreply@".$siteDomain.">\r\n";
		if ($row->not_email_active == 0){ $headers .= "Bcc: ".$row->not_email."\r\n"; }
		$attachments = '';
		
		wp_mail($to, $subject, $message, $headers);
	
	}
}

//Función que controla el envío de email de prueba
function sendTestMail($etype){

	$email_p = "plant_nc";
	if ($etype == "newt"){$email_p = "plant_nt";}
	
	global $wpdb;
	$row = $wpdb->get_row($wpdb->prepare("SELECT not_email, $email_p FROM crm_config WHERE c_ref = 'easywptickets'"));
	
	$to = $row->not_email;
	$subject = "Easy WP Tickets Test Email";
	$message = $row->$email_p;
	
	$message = filterMail($message, sample);
	
	// Para enviar un correo HTML mail, la cabecera Content-type debe fijarse
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=utf8_unicode_ci' . "\r\n";
	
	// Cabeceras adicionales
	$headers .= "To: $to \r\n";
	$headers .= "From: <$to> $to" . "\r\n";
	$attachments = '';
	
	if (wp_mail($to, $subject, $message, $headers)){
?>		
		<div id="aok" class="alertOk">
        	<img src="<?php echo plugins_url( '../img/icons/icon-ok-alerts.png' , __FILE__ );?>" alt="" title="">
            <?php _e('Email de prueba enviado con exito', 'easywptickets'); ?>.
        </div>
<?php	 
	}else{
?>		
		<div id="aba" class="alertBad">
        	<img src="<?php echo plugins_url( '../img/icons/alert-icon.png' , __FILE__ );?>" alt="" title="">
        	<?php _e('No se ha podido enviar el email de prueba', 'easywptickets'); ?>. <?php _e('Contacte con un administrador', 'easywptickets'); ?>.
        </div>
<?php	
	}
}

//Función que traduce los shortcodes de los email a datos reales
function filterMail($mail, $mode, $supportName, $userName, $description, $importance, $tid){

	global $wpdb;

	if ($mode == "sample"){
		
		$site_url = get_site_url();
	
		$filtered_mail = str_replace("%agent%", "Agente", $mail);
		$filtered_mail = str_replace("%user%", "Usuario", $filtered_mail);
		$filtered_mail = str_replace("%prio%", "Prioridad", $filtered_mail);
		$filtered_mail = str_replace("%ticket_url%", $site_url, $filtered_mail);
		$filtered_mail = str_replace("%ticket_desc%", "Descripción", $filtered_mail);
	
	}else{
	
		$site_url = get_site_url()."&id=".$tid;
	
		$filtered_mail = str_replace("%agent%", $supportName, $mail);
		$filtered_mail = str_replace("%user%", $userName, $filtered_mail);
		$filtered_mail = str_replace("%prio%", $importance, $filtered_mail);
		$filtered_mail = str_replace("%ticket_url%", $site_url, $filtered_mail);
		$filtered_mail = str_replace("%ticket_desc%", $description, $filtered_mail);
	
	}
	
	return $filtered_mail;
}
?>