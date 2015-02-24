<?php

/*
-------------------------------------------------------------------------------
* Utilidad: Controla las funciones de paginación.
* Creador: Cromorama.com - Julen Durán
* Fecha de Creación: 07-04-2014
* Última Modificación: 19-02-2015
* Versión: 1.0.1
* Nombre del archivo: crm-motor-pagination.php
-------------------------------------------------------------------------------
*/

//Función que muestra la páginación
function getPagination($page, $num_pages, $frmFilters){
	
	global $wpdb;
	$whereString_pagination = "";
	
	//if (!empty($frmFilters)){ $whereString_pagination = whereCreator($frmFilters); }	
	
	$pagination_count = $wpdb->get_results($wpdb->prepare("SELECT * FROM crm_tickets $whereString_pagination"));
	$conteo = count($pagination_count);
	$max_num_paginas = intval($conteo/$num_pages);
	
	if ($page == null){ $page = 1;}
	
?>
	<ul class="paginationMenu">
<?php
	for($i=0; $i<$max_num_paginas;$i++){
?>
		<li>
<?php
			if($page == ($i+1)){

				echo ($i+1);

			}else{
?>
            	<a href="admin.php?page=tickets&n_page=<?php echo ($i+1); ?>"><?php echo ($i+1); ?></a>
<?php
			}
?>
        </li>
<?php
    } 
?>
	</ul>
<?php
}

//Función que construlle los límites
function limitCreator($pag_active, $page, $num_pages){

	if ($pag_active){
		
		if (isset($page)){
			$limitVar = "LIMIT ".(($page-1)*$num_pages).",".$num_pages;
		}else{		
			$limitVar = "LIMIT ".((1-1)*$num_pages).",".$num_pages;
		}
	}
	
	return $limitVar;
}
?>