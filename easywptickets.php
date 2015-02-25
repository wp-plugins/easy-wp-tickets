<?php

/**
*
* Plugin Name: Easy WP Tickets
* Plugin URI: http://www.cromorama.com
* Description: Simple and easy tickets system for you website.
* Version: 1.0.5
* Author: Cromorama.com
* Author URI: http://www.cromorama.com
*
**/

//Includes Admin Sections
include_once 'includes/crm-admin-tickets.php';
include_once 'includes/crm-admin-agents.php';
include_once 'includes/crm-admin-subjects.php';
include_once 'includes/crm-admin-configuration.php';

//Includes FrontEnd
include_once 'includes/crm-frontend-client.php';
include_once 'includes/crm-frontend-support.php';

//Includes Motor
include_once 'includes/crm-motor-filters.php';
include_once 'includes/crm-motor-mail.php';
include_once 'includes/crm_motor_functions.php';
include_once 'includes/crm-motor-pagination.php';

//Inicializamos los idiomas
function easywptickets_init() {
  load_plugin_textdomain( 'easywptickets', false, 'easywptickets/languages' );
}
add_action('init', 'easywptickets_init');

//Creamos un nuevo rol Agente cuando se activa el Plugin
function create_agent_rol() {

	add_role(
		'agent',
		__( 'Agente' ),
		array(
			'read'         => true,  // true allows this capability
			'edit_posts'   => true,
			'delete_posts' => false, // Use false to explicitly deny
		)
	);
	
	firstConfig();
}
register_activation_hook( __FILE__, 'create_agent_rol' );

//Eliminamos el rol cuando desactivamos el Plugin
function delete_agent_rol() {
	remove_role( 'agent' );
}
register_deactivation_hook( __FILE__, 'delete_agent_rol' );

function easywptickets_uninstall(){
	uninstallPlugin();
}
register_uninstall_hook( __FILE__, 'easywptickets_uninstall' );

//Registramos el archivo CSS del Plugin tanto para Administración como para FrontEnd
function crm_ticket_system_css() {
	wp_register_style('ticketscrmStyle', plugins_url( 'css/easywptickets.css' , __FILE__ ) );
	wp_register_style('crmTinyEditorCSS', plugins_url( 'js/tinyeditor/tinyeditor.css' , __FILE__ ) );
	wp_enqueue_style('ticketscrmStyle');
	wp_enqueue_style('crmTinyEditorCSS');
}
add_action('admin_enqueue_scripts', 'crm_ticket_system_css');
add_action('wp_enqueue_scripts', 'crm_ticket_system_css');

//Registramos y encolamos en archivo javascript del plugin
function crm_ticket_system_admin_scripts() {	
	//wp_enqueue_script('crmJs', plugins_url().'/easywptickets/js/crm_tickets_system_java.js');
	//wp_enqueue_script('crmTinyEditor', plugins_url().'/easywptickets/js/tinyeditor/tiny.editor.packed.js');
	wp_enqueue_script('crmJs', plugins_url( 'js/crm_tickets_system_java.js' , __FILE__ ) );
	wp_enqueue_script('crmTinyEditor', plugins_url( 'js/tinyeditor/tiny.editor.packed.js' , __FILE__ ) );
}
add_action('admin_enqueue_scripts', 'crm_ticket_system_admin_scripts');
add_action('wp_enqueue_scripts', 'crm_ticket_system_admin_scripts');

//Generamos el menú de la administración.
function crm_menu_tickets(){
	add_menu_page ( 'CRM Ticket System', 'Easy WP Tickets', 'administrator', 'easywptickets.php', 'ticketController', plugin_dir_url( __FILE__ ) . 'img/icons/cromo-tickets-icon.png');	
	add_submenu_page( 'easywptickets.php', 'Tickets', 'Tickets', 'administrator', 'tickets', 'adminTicketsController');	
	add_submenu_page( 'easywptickets.php', 'Asuntos', 'Asuntos', 'administrator', 'subjects', 'subjectsController');
	add_submenu_page( 'easywptickets.php', 'Configuración', 'Configuración', 'administrator', 'config', 'configController');

	remove_submenu_page( 'easywptickets.php','easywptickets.php' );
}
add_action('admin_menu', 'crm_menu_tickets');

function frontendMainControler(){
	
	global $wpdb;
	$sppid = get_current_user_id();
	$type = FindRol();
	
	$mode = null;
	if (isset($_REQUEST['hdn_mode'])) $mode = $_REQUEST['hdn_mode'];
	if (isset($_REQUEST['ic'])) $ic = $_REQUEST['ic'];
	if (isset($_REQUEST['it'])) $it = $_REQUEST['it'];
	if (isset($_REQUEST['st'])) $st = $_REQUEST['st'];
	
	switch ($type){
		case 'subscriber':
			include_once 'custom/frontend-custom-client.php';
			break;
		case 'agent':
			include_once 'custom/frontend-custom-agents.php';
			break;
		case 'administrator':
			include_once 'custom/frontend-custom-administrator.php';
			break;
	}	

}
add_shortcode( 'easy_wp_tickets', 'frontendMainControler' );

function adminTicketsController(){	
	
	$mode = null;
	if (isset($_REQUEST['hdn_mode'])) $mode = $_REQUEST['hdn_mode'];
	if (isset($_REQUEST['it'])) $it = $_REQUEST['it'];
	if (isset($_REQUEST['ic'])) $ic = $_REQUEST['ic'];
	if (isset($_REQUEST['st'])) $st = $_REQUEST['st'];
	
	include_once 'custom/admin-custom-tickets.php';
}

function subjectsController(){
	
	$mode = null;
	if (isset($_REQUEST['hdn_mode'])) $mode = $_REQUEST['hdn_mode'];
	
	include_once 'custom/admin-custom-subjects.php';
}

function configController(){
	
	$mode = null;
	if (isset($_REQUEST['hdn_mode'])) $mode = $_REQUEST['hdn_mode'];
	
	include_once 'custom/admin-custom-configuration.php';
}
?>