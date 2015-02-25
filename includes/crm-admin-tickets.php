<?php

/*
-------------------------------------------------------------------------------
* Utilidad: Controla las funciones de la administración de los tickets.
* Creador: Cromorama.com - Julen Durán
* Fecha de Creación: 01-04-2014
* Última Modificación: 19-02-2015
* Versión: 1.0.1
* Nombre del archivo: crm-admin-tickets.php
-------------------------------------------------------------------------------
*/

//Función que muestra la tabla de tickets.
function showTicketsTable($rol, $it, $frmFilters, $pag_active, $page, $num_pages){
	
	global $wpdb;
	$whereString = "";
	$limitVar = "";
	
	$limitVar = limitCreator($pag_active, $page, $num_pages);
	
	if (!empty($frmFilters)){
		
		$whereString = whereCreator($frmFilters);
		$limitVar = "";

	}elseif ($it != null){
		$whereString = "WHERE id_ticket=".$it;
	}

	$rows = $wpdb->get_results($wpdb->prepare("SELECT * FROM crm_tickets INNER JOIN crm_subjects ON crm_tickets.id_subject = crm_subjects.id_subject INNER JOIN wp_users ON crm_tickets.id_user = wp_users.ID $whereString ORDER BY crm_tickets.id_ticket DESC $limitVar"));
	
	if (count($rows)>0){
	
?>
	<table id="table_tickets" class="wp-list-table widefat" cellspacing="0">
		<thead>
			<tr>
				<th><?php _e('ID', 'easywptickets'); ?></th>
				<th><?php _e('Fecha', 'easywptickets'); ?></th>
                <th><?php _e('Usuario', 'easywptickets'); ?></th>
				<th><?php _e('Asunto', 'easywptickets'); ?></th>
                <th><?php _e('Descripcion', 'easywptickets'); ?></th>
				<th></th>
                <th></th>
                <th></th>
                <th></th>
			</tr>			
		</thead>
		<tbody>
<?php 			
	foreach ($rows as $row){
		
		$com_rows = $wpdb->get_results($wpdb->prepare("SELECT * FROM crm_tracing WHERE id_ticket = $row->id_ticket"));
?>					
			<tr class="dinTr"> 
                <td><?php echo $row->id_ticket;?></td>
                <td><?php echo ESDateFormat($row->creation_date);?></td>
				<td>
				<?php echo $row->display_name;?></td>
                <td><?php echo $row->subject;?></td>
                <td><?php echo $row->description;?></td>
                <td align="center" width="40">			
<?php 				
					if (count($com_rows) > 0){
?>
						<span class="comentNum"><?php echo count($com_rows); ?></span>
                    	<span class="comentImage"><img src="<?php echo plugins_url( '../img/icons/comment-icon.png' , __FILE__ );?>" alt="" title=""></span>
<?php
					}
?>                
                </td>
                <td align="center" width="20">	
				<?php 
					switch ($row->id_ticket_importance){
						case '3':
							echo '<img src="' . plugins_url( '../img/icons/prio-baja.png' , __FILE__ ) . '" alt="'.$row->importance.'"> ';
							break;
						case '2':
							echo '<img src="' . plugins_url( '../img/icons/prio-media.png' , __FILE__ ) . '" alt="'.$row->importance.'" > ';
							break;
						case '1':
							echo '<img src="' . plugins_url( '../img/icons/prio-alta.png' , __FILE__ ) . '"  alt="'.$row->importance.'"> ';
							break;
					}
				?>
                </td>
                <td align="center" width="20">
				<?php 
					switch ($row->id_ticket_state){
						case '1':
							echo '<img src="' . plugins_url( '../img/icons/icon-open-ticket.png' , __FILE__ ) . '"  alt="'.$row->id_ticket_state.'"> ';
							break;
						case '2':
							echo '<img src="' . plugins_url( '../img/icons/icon-close-lock.png' , __FILE__ ) . '"  alt="'.$row->id_ticket_state.'"> ';
							break;
					}
				?>
                </td>
                <td width="60">
                	<a href="admin.php?page=tickets&hdn_mode=viewTicket&it=<?php echo $row->id_ticket; ?>&st=<?php echo $row->id_ticket_state; ?>">
                    	<img src="<?php echo plugins_url( '../img/icons/view-ticket.png' , __FILE__ );?>" alt="<?php _e('Ver Tiket', 'easywptickets'); ?>" title="<?php _e('Ver Tiket', 'easywptickets'); ?>">
                    </a>
<?php				
					if ($rol == "administrator"){
?>
                	&nbsp
					<a href="javascript:confirmDelete('<?php _e('Deseas borrar este ticket', 'easywptickets'); ?>\n<?php _e('Tambien se eliminaran los comentarios de seguimiento asociados al mismo', 'easywptickets'); ?>.','admin.php?page=tickets&hdn_mode=deleteTicket&it=<?php echo $row->id_ticket; ?>');">
                    	<img src="<?php echo plugins_url( '../img/icons/delete.png' , __FILE__ );?>" alt="<?php _e('Eliminar', 'easywptickets'); ?>" title="<?php _e('Eliminar', 'easywptickets'); ?>">
                    </a>
<?php
					}
?>
                </td>
        	</tr>
<?php
	}
?>
        </tbody>		
	</table>
<?php
    }else{
?>
		<div class="alertOk">
            <img src="<?php echo plugins_url( '../img/icons/ok_hand.png' , __FILE__ );?>" alt="" title="">
            <br />
            <?php _e('No existen tickets', 'easywptickets'); ?>.
        </div>   
<?php
	}
	if($it != null){
?>
		<div class="ticketSendByAdmin">
            <?php _e('Ticket Enviado por', 'easywptickets'); ?>: <b><?php echo $row->contact; ?></b> - <?php _e('Telefono de Contacto', 'easywptickets'); ?>: <b><?php echo $row->contact_tlf; ?></b>
        </div>   
<?php	
	}	
}

//Función para controlar el menú del tracing
function viewTicketMenu($rol, $it, $st){
	
	switch ($rol){
	case ROL_ADMIN: 
		$closeURL = "admin.php?page=tickets&hdn_mode=closeTicket&it=$it";
		$urlHome = "admin.php?page=tickets";
		break;
	case ROL_SUPPORT:
		$preUrl = $_SERVER["REQUEST_URI"]."&hdn_mode=closeTicket";		
		$closeURL = strstr($preUrl,'?');
		$urlHome = strstr($_SERVER["REQUEST_URI"],'?', true);
		break;
	case ROL_SECRETARY:
		$preUrl = $_SERVER["REQUEST_URI"]."&hdn_mode=closeTicket";
		$closeURL = strstr($preUrl,'?');
		$preUrlHome = strstr($_SERVER["REQUEST_URI"],'?', true);
		$urlHome = $preUrlHome."?hdn_mode=viewCIA&ic=1";
		break;
	default:
		$preUrl = $_SERVER["REQUEST_URI"]."&hdn_mode=closeTicket";
		$closeURL = strstr($preUrl,'?');
		$urlHome = strstr($_SERVER["REQUEST_URI"],'?', true);
		break;
	}
?>
	<div class="contentMenu">
		<a class="button-primary" href="<?php echo $urlHome;?>"><?php _e('Volver Atras', 'easywptickets'); ?></a>
<?php
	if (($rol == ROL_ADMIN or $rol == ROL_SUPPORT) and $st !== STATE_CLOSED){
?>	
		<a class="button-primary" href="<?php echo $closeURL;?>"><?php _e('Cerrar Ticket', 'easywptickets'); ?></a>
<?php	
	}
?>
	</div>
<?php
}

//Función para controlar la vista del ticket
function viewTicket($rol, $it, $st){
	
	global $wpdb;
	
	$rows = $wpdb->get_results($wpdb->prepare("SELECT * FROM crm_tracing INNER JOIN wp_users ON crm_tracing.id_agent = wp_users.ID WHERE crm_tracing.id_ticket = $it ORDER BY crm_tracing.id_tracing DESC"));
	
	if (count($rows) > 0){
?>
			<div class="tracingContent">
<?php	
		foreach ($rows as $row){
?>
                <div class="tracingTopContent">
                    <p class="tracingUserName">
                        <img src="<?php echo plugins_url( '../img/icons/comment-icon.png' , __FILE__ );?>" alt="" title="">
                        <?php echo $row->display_name;?>
                    </p>
                    <p class="tracingDate"><?php echo ESDateFormat($row->creation_date);?></p>
                </div>
                    <p class="tracingComment"><?php echo $row->comment_ticket;?></p>
<?php 		
		}
?>
			</div>
<?php
	}else{
?>
		<div class="tracingContent">
        	<p>
                <img src="<?php echo plugins_url( '../img/icons/alert-icon.png' , __FILE__ );?>" alt="" title="">
                <?php _e('No exiten comentarios de seguimiento', 'easywptickets'); ?>.
            </p>	
        </div>	
<?php
	}
	if ($st != STATE_CLOSED){
		showAddComment($it);
	}
?>
    </div>
<?php
}

//Función para añadir comentarios de seguimiento.
function showAddComment($id_ticket){
	$rol = FindRol();
	$commentUrl = strstr($_SERVER["REQUEST_URI"],'?', true);
	
	if ($rol == ROL_ADMIN){
		$commentUrl = "admin.php?page=tickets";		
	}
 
?>
    <div class="tracingForm">
		<label style="width:auto;"><?php _e('Nuevo Comentario de Seguimiento', 'easywptickets'); ?>:</label>
		<form id="frm_comment" name="frm_comment" action="<?php echo $commentUrl;?>" method="post">			
			<textarea name="comment" rows="5" cols="80" required="required"></textarea>
            <br/><br/>
            <input type="submit" name="Submit" class="button-primary" id="btn_send" value="<?php _e('Enviar', 'easywptickets'); ?>" />			
			<input type="hidden" name="id_ticket" value="<?php echo $id_ticket;?>" />
			<input type="hidden" name="hdn_mode" value="sendComment" />
		</form>
	</div>
<?php
}

//Función que salva el comentario de seguimiento en la DB
function saveComment($frmComment){

	global $wpdb;	
	$current_user = wp_get_current_user();
	
	if ($wpdb->insert(
		'crm_tracing',		
		array(
				'id_ticket' => $frmComment['id_ticket'],
				'comment_ticket' => $frmComment['comment'],
				'id_agent' => $current_user->ID
			)
	)){		
		
		$emailAgent = $wpdb->get_row($wpdb->prepare(
		"SELECT display_name, user_email
				FROM wp_users AS cst
				INNER JOIN crm_tickets AS ct ON ct.id_ticket = ".$frmComment['id_ticket']));
				
		$emailUser = $wpdb->get_row($wpdb->prepare(
		"SELECT display_name, user_email
				FROM wp_users AS cst
				INNER JOIN crm_tickets AS ct ON ct.id_user = cst.ID WHERE ct.id_ticket = ".$frmComment['id_ticket']));
		
		$to = $emailUser->user_email;
		
		mailMotor($to, "comment", $emailUser->display_name, $emailAgent->display_name, $frmComment['comment'], null, $frmComment['id_ticket']);
?>		
		<div id="aok" class="alertOk">
        	<img src="<?php echo plugins_url( '../img/icons/icon-ok-alerts.png' , __FILE__ );?>" alt="" title="">
        	<?php _e('El nuevo comentario se ha introducido con exito', 'easywptickets'); ?>.
        </div>
<?php		
	}else{
?>		
		<div id="aba" class="alertBad">
        	<img src="<?php echo plugins_url( '../img/icons/alert-icon.png' , __FILE__ );?>" alt="" title="">
        	<?php _e('No se ha podido introducir el registro', 'easywptickets'); ?>. <?php _e('Contacte con un administrador', 'easywptickets'); ?>.
        </div>
<?php
	}
}

//Función que cierra el ticket
function setCloseTicket($id_ticket){
	global $wpdb;
	if ($wpdb->update('crm_tickets',
			array(
				'id_ticket_state' => 2,
				'modify_date' => date("Y-m-d h:i:s")
			),
			array( 'id_ticket' => $id_ticket))){
?>		
		<div id="aok" class="alertOk">
        	<img src="<?php echo plugins_url( '../img/icons/icon-ok-alerts.png' , __FILE__ );?>" alt="" title="">
            <?php _e('Se ha cerrado el ticket con exito', 'easywptickets'); ?>.
        </div>
<?php	 
	}else{
?>		
		<div id="aba" class="alertBad">
        	<img src="<?php echo plugins_url( '../img/icons/alert-icon.png' , __FILE__ );?>" alt="" title="">
        	<?php _e('No se ha podido cerrar el ticket', 'easywptickets'); ?>. <?php _e('Contacte con un administrador', 'easywptickets'); ?>.
        </div>
<?php
	}
}

//Función para eliminar tickets
function setDeleteTicket($id_ticket){
	
	global $wpdb;
	
	$rol = FindRol();
	
	// Si entramos con usuario del sistema
	if ($rol == ROL_ADMIN){
		$wpdb->delete('crm_tickets', array( 'id_ticket' => $id_ticket ) );
		$wpdb->delete('crm_tracing', array( 'id_ticket' => $id_ticket ) );
	}else{
?>
		<div class="">
			<?php _e('No tienes permiso para realizar esta accion', 'easywptickets'); ?>.
		</div>
<?php
    }
}
?>