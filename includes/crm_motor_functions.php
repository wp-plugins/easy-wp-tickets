<?php

/*
-------------------------------------------------------------------------------
* Utilidad: Controla las funciones comunes del plugin.
* Creador: Cromorama.com - Julen Durán
* Fecha de Creación: 04-04-2014
* Última Modificación: 19-02-2015
* Versión: 1.0.1
* Nombre del archivo: crm_motor_functions.php
-------------------------------------------------------------------------------
*/

// CONSTANTES
define ('urlAdmin', '');
define ('emailAdmin', get_settings('admin_email'));
define ('ROL_ADMIN', 'administrator');
define ('ROL_CLIENT', 'subscriber');
define ('ROL_SECRETARY', 'contributor');
define ('ROL_SUPPORT', 'editor');
define ('STATE_CLOSED', '2');
define ('NO_LOGIN','Debe estar logueado para ver los tickets');

//Función para averigual el rol del usuario.
function FindRol(){

	$current_user = wp_get_current_user();
	$user_ID = get_userdata($current_user->ID);
	$rol = implode(', ', $user_ID->roles);	
	
	return $rol;
}

//Función para mostrar la hora en el formato español.
function ESDateFormat($old_date){

	return date("d-m-Y", strtotime($old_date));

}

function actualURL(){
	$url="http://".$_SERVER['HTTP_HOST'].":".$_SERVER['SERVER_PORT'].$_SERVER['REQUEST_URI'];
	return $url;
}

function getIdUserCia($user_ID, $id_cia){
	global $wpdb;

	$data = $wpdb->get_col($wpdb->prepare("SELECT id_user_cia FROM crm_wpusers_cias WHERE id_cia = ". $id_cia ));	
	if (count($data)) return $data[0];
}

//Función que genera la primera configuración
function firstConfig(){
	global $wpdb;
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		
	$current_user = wp_get_current_user();
	
	$sql_crm_config = "CREATE TABLE IF NOT EXISTS `crm_config` (
	  `id` int(111) NOT NULL AUTO_INCREMENT,
	  `c_ref` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
	  `not_email` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
	  `not_email_active` tinyint(1) NOT NULL DEFAULT '0',
	  `plant_nt` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
	  `plant_nt_active` tinyint(1) NOT NULL DEFAULT '0',
	  `plant_nc` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
	  `plant_nc_active` tinyint(1) NOT NULL DEFAULT '0',
	  `pagination_active` tinyint(1) NOT NULL DEFAULT '0',
	  `num_pages` smallint(11) NOT NULL DEFAULT '20',
	  PRIMARY KEY (`id`)
	);";
	
	dbDelta( $sql_crm_config );
	
	$sql_crm_subjects = "CREATE TABLE IF NOT EXISTS `crm_subjects` (
	  `id_subject` int(11) NOT NULL AUTO_INCREMENT,
	  `subject` varchar(80) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
	  `id_agent` tinyint(3) unsigned NOT NULL,
	  `active` tinyint(1) unsigned NOT NULL DEFAULT '0',
	  PRIMARY KEY (`id_subject`)
	);";
	
	dbDelta( $sql_crm_subjects );
	
	$sql_crm_tickets = "CREATE TABLE IF NOT EXISTS `crm_tickets` (
	  `id_ticket` int(10) unsigned NOT NULL AUTO_INCREMENT,
	  `id_subject` int(11) NOT NULL,
	  `id_user` int(11) NOT NULL,
	  `description` mediumtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
	  `contact` varchar(90) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
	  `contact_tlf` varchar(90) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
	  `creation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	  `modify_date` timestamp NULL DEFAULT NULL,
	  `id_ticket_importance` tinyint(3) unsigned NOT NULL,
	  `id_ticket_state` tinyint(3) unsigned NOT NULL DEFAULT '1',
	  PRIMARY KEY (`id_ticket`)
	);";
	
	dbDelta( $sql_crm_tickets );
	
	$sql_crm_tracing = "CREATE TABLE IF NOT EXISTS `crm_tracing` (
	  `id_tracing` int(10) unsigned NOT NULL AUTO_INCREMENT,
	  `id_ticket` int(10) unsigned NOT NULL,
	  `comment_ticket` mediumtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
	  `id_agent` bigint(20) unsigned NOT NULL,
	  `creation_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
	  PRIMARY KEY (`id_tracing`)
	);";
	
	dbDelta( $sql_crm_tracing );
	
	
	$row_check = $wpdb->get_row($wpdb->prepare("SELECT * FROM crm_config WHERE id_user = $current_user->ID"));
	
	if (count($row_check)<1){
	
		//Plantillas por defecto
		$plant_nt = "<p>Hola %agent%,</p>
					<p>
					Has recibido un nuevo ticket de %user% con prioridad %prio%
					</p>
					<p>
					<i>%ticket_desc%</i>
					</p>
					<p>
					Puedes revisar el ticket haciendo click aquí: %ticket_url%
					</p>
					<hr />
					<p><em>Powered by Easy WP Tickets</em></p>";
		
		$plant_nc = "<p>Hola %user%,</p>
					<p>
					Has recibido un nuevo comentario de seguimiento de %agent%
					</p>
					<p>
					<i>%ticket_desc%</i>
					</p>
					<p>
					Puedes revisar el ticket haciendo click aquí: %ticket_url%
					</p>
					<hr />
					<p><em>Powered by Easy WP Tickets</em></p>";
		
		
		$wpdb->insert( 
			'crm_config', 
			array( 
				'c_ref' => 'easywptickets', 
				'not_email' => $current_user->user_email,
				'plant_nt' => $plant_nt,
				'plant_nc' => $plant_nc
			)
		);
	}
}

//Función que borra las tablas de la db al desinstalar el plugin
function uninstallPlugin(){

	global $wpdb;
    $crm_config = "crm_config";
	$crm_subjects = "crm_subjects";
	$crm_tickets = "crm_tickets";
	$crm_tracing = "crm_tracing";

	$wpdb->query("DROP TABLE IF EXISTS $crm_config");
	$wpdb->query("DROP TABLE IF EXISTS $crm_subjects");
	$wpdb->query("DROP TABLE IF EXISTS $crm_tickets");
	$wpdb->query("DROP TABLE IF EXISTS $crm_tracing");
}
?>