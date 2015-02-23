<?php

/*
-------------------------------------------------------------------------------
* Utilidad: Mostrar la administración de asuntos.
* Creador: Cromorama.com - Julen Durán
* Fecha de Creación: 30-01-2014
* Última Modificación: 10-04-2014
* Versión: 1.0.0
* Nombre del archivo: admin-custom-subjects.php
-------------------------------------------------------------------------------
*/

//Limitamos el acceso desde fuera del plugin.
if (stristr(htmlentities($_SERVER['PHP_SELF']), "admin-custom-subjects.php")) {
	   Header("Location: ../../../../index.php");
    die();
}

$rol = FindRol();

?>

<div class="CRMTS_Container_Admin">
	<h1><?php _e('Administracion de Asuntos', 'easywptickets'); ?></h1>
	<?php 

		switch ($mode){
			case null:
				viewSubjectMenu();			
				showSubjectsTable(null, $rol);
				break;
			case 'editSubject':
				showEditSubjects($_REQUEST['is'], $rol);
				break;
			case 'updateSubject':
				$frmUpdateSubject = $_POST;
				setUpdateSubject($frmUpdateSubject);
				viewSubjectMenu();
				showSubjectsTable(null, $rol);
				break;
			case 'newSubject':
				showAddDataSubjects();
				break;
			case 'addSubject':
				$frmNewSubject = $_POST;
				setNewSubject($frmNewSubject);
				viewSubjectMenu();			
				showSubjectsTable(null, $rol);
				break;
			case 'deleteSubject':
				DeleteSubject($_REQUEST['is']);
				viewSubjectMenu();
				showSubjectsTable(null, $rol);
			break;
			}
	?>
</div>
<?php
?>