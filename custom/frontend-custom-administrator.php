<?php

/*
-------------------------------------------------------------------------------
* Utilidad: Muestra el FrontEnd para el rol Administrador (administrator).
* Creador: Cromorama.com - Julen Durán
* Fecha de Creación: 10-02-2014
* Última Modificación: 10-02-2014
* Versión: 1.0.0
* Nombre del archivo: frontend-custom-administrator.php
-------------------------------------------------------------------------------
*/

//Limitamos el acceso desde fuera del plugin.
if (stristr(htmlentities($_SERVER['PHP_SELF']), "frontend-custom-administrator.php")) {
	   Header("Location: ../../../../index.php");
    die();
}

?>
	<div class="">
		<?php _e('Accede al panel de control haciendo', 'easywptickets'); ?> <a href="<?php echo get_admin_url(); ?>admin.php?page=tickets"><?php _e('click aqui', 'easywptickets'); ?></a>.
	</div>
<?php
?>