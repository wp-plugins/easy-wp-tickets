<?php

/*
-------------------------------------------------------------------------------
* Utilidad: Mostrar la configuración del Plugin
* Creador: Cromorama.com - Julen Durán
* Fecha de Creación: 01-04-2014
* Última Modificación: 03-04-2014
* Versión: 1.0.0
* Nombre del archivo: admin-custom-configuration.php
-------------------------------------------------------------------------------
*/

//Limitamos el acceso desde fuera del plugin.
if (stristr(htmlentities($_SERVER['PHP_SELF']), "admin-custom-configuration.php")) {
	   Header("Location: ../../../../index.php");
    die();
}

$configUrl = "admin.php?page=config";

?>

<div class="CRMTS_Container_Admin">
	<h1><?php _e('Opciones de Configuracion', 'easywptickets'); ?></h1>
		<form onsubmit="activeText()" id="frm_config" name="frm_config" action="<?php echo $configUrl ;?>" method="post">
			
            <div class="configOptionContainer">
            	<p><?php _e('Crea un nuevo usuario con el rol <b>Agente</b>', 'easywptickets'); ?></p>
                <p><?php _e('Crea una pagina para tu gestor de tickets y añadele el siguiente shortcode', 'easywptickets'); ?>: <b>[easy_wp_tickets]</b></p>
            </div>
            
            <h2><?php _e('Envio de Notificaciones', 'easywptickets'); ?>:</h2>
            <div class="configOptionContainer">
<?php 
				switch ($mode){
					case null:
						configNotification(); 
						break;
					case saveConfig:
						$frm_config = $_POST;
						saveConfigData($frm_config);
						configNotification();
						break;
					case testMail:
						sendTestMail($_REQUEST['etype']);
						configNotification();
						break;
				}
?>
            </div>
            <h2><?php _e('Paginacion', 'easywptickets'); ?>:</h2>
            <div class="configOptionContainer">
                <?php configPagination(); ?>
            </div>
            <p style="float:right;">
            
                <input type="submit" name="Submit" class="button-primary" value="Guardar Cambios" />
                &nbsp;
                <a href="admin.php?page=tickets" class="button-secondary">Cancelar</a>
                <input type="hidden" name="hdn_mode" value="saveConfig"/>
            </p>
       </form>
</div>