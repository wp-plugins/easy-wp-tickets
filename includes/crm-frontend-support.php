<?php

/*
-------------------------------------------------------------------------------
* Utilidad: Controla las funciones del frontend para los soportes.
* Creador: Cromorama.com
* Fecha de Creación: 08-04-2014
* Última Modificación: 19-02-2015
* Versión: 1.0.1
* Nombre del archivo: crm-admin-cias.php
-------------------------------------------------------------------------------
*/

//Función que muestra la tabla con los tickets de este soporte en concreto
function showSupportTicketTable($sppid, $it){
	
	global $wpdb;
	$backURL = get_permalink();
	$data[]=array();
	$itMoreString = "";
	
	$rows_tikets = $wpdb->get_results($wpdb->prepare("SELECT crm_tickets.id_ticket FROM crm_tickets INNER JOIN crm_subjects ON crm_tickets.id_subject = crm_subjects.id_subject WHERE crm_subjects.id_agent = $sppid AND crm_subjects.active = 0 ORDER BY crm_tickets.id_ticket DESC"));

	if (count($rows_tikets)>0){

		if($it != null){
			unset($rows_tikets);
			$rows_tikets = new stdClass();
			$rows_tikets->id_ticket = $it;
		}
		
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
	
		foreach ($rows_tikets as $date){
			
			if($it != null){
				$itMoreString = "id_ticket = ".$it;
			}else{
				$itMoreString = "id_ticket = ".$date->id_ticket;
			}

			$com_rows = $wpdb->get_results($wpdb->prepare("SELECT * FROM crm_tracing WHERE $itMoreString"));
			$row = $wpdb->get_row($wpdb->prepare("SELECT * FROM crm_tickets INNER JOIN wp_users ON crm_tickets.id_user = wp_users.ID INNER JOIN crm_subjects ON crm_tickets.id_subject = crm_subjects.id_subject WHERE $itMoreString"));
			
?>					
				<tr class="dinTr"> 
					<td><?php echo $row->id_ticket;?></td>
					<td><?php echo ESDateFormat($row->creation_date);?></td>
					<td><?php echo $row->display_name;?></td>
					<td><?php echo $row->subject;?></td>
					<td><?php echo $row->description;?></td>
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
					<td align="center" width="40">
<?php 				
						if (count($com_rows) > 0){
							echo count($com_rows);
?>
							<img src="<?php echo plugins_url( '../img/icons/comment-icon.png' , __FILE__ );?>" alt="" title="">
<?php
						}
?>
					</td>
					<td align="center" width="20">
<?php 
						switch ($row->id_ticket_state){
							case '1':
								echo '<img src="' . plugins_url( '../img/icons/icon-open-ticket.png' , __FILE__ ) . '"  alt="'.$row->importance.'"> ';
								break;
							case '2':
								echo '<img src="' . plugins_url( '../img/icons/icon-close-lock.png' , __FILE__ ) . '"  alt="'.$row->importance.'"> ';
								break;
						}
?>
					</td>	
<?php
					if($it == null){
?>
					<td align="center" width="60">
						<a href="<?php echo $backURL; ?>?clt_mode=viewSupportTicket&it=<?php echo $row->id_ticket; ?>&ic=<?php echo $row->id_user; ?>&st=<?php echo $row->id_ticket_state; ?>"><img src="<?php echo plugins_url( '../img/icons/view-ticket.png' , __FILE__ );?>" alt="<?php _e('Ver Tiket', 'easywptickets'); ?>" title="<?php _e('Ver Tiket', 'easywptickets'); ?>"></a>
<?php
						if ($row->id_ticket_state == 1){   
?>               
							&nbsp;
							<a href="javascript:confirmDelete('<?php _e('Desea cerrar el ticket definitivamente', 'easywptickets'); ?>','<?php echo $backURL; ?>?clt_mode=closeSupportTicket&it=<?php echo $row->id_ticket; ?>');">
								<img src="<?php echo plugins_url( '../img/icons/no-active-icon.png' , __FILE__ );?>" alt="<?php _e('Cerrar Ticket', 'easywptickets'); ?>" title="<?php _e('Cerrar Ticket', 'easywptickets'); ?>">
							</a> 
						</td>
<?php
						}
					}else{
?>
					<td align="center" width="20">
<?php
						if ($row->id_ticket_state == 1){
?>
							<a href="javascript:confirmDelete('<?php _e('Desea cerrar el ticket definitivamente', 'easywptickets'); ?>','<?php echo $backURL; ?>?clt_mode=closeSupportTicket&it=<?php echo $row->id_ticket; ?>');">
								<img src="<?php echo plugins_url( '../img/icons/no-active-icon.png' , __FILE__ );?>" alt="<?php _e('Cerrar Ticket', 'easywptickets'); ?>" title="<?php _e('Cerrar Ticket', 'easywptickets'); ?>">
							</a> 
<?php
						}
?>
					</td>
<?php				
					}
?>
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
            <?php _e('Aun no has recibido ningun ticket', 'easywptickets'); ?>.
        </div>   

<?php
	}    
	if($it != null){
?>
		<div class="ticketSendBy">
            <img src="<?php echo plugins_url( '../img/icons/green_arrow.png' , __FILE__ );?>" alt="" title="">
            <?php _e('Ticket Enviado por', 'easywptickets'); ?>: <b><?php echo $row->contact; ?></b> - <?php _e('Telefono de Contacto', 'easywptickets'); ?>: <b><?php echo $row->contact_tlf; ?></b>
        </div>
        <hr /> 	
<?php	
	}
}

//Función que muestra el tracing del ticket
function showTracingSupport($it, $ic, $st){
		
	global $wpdb;

	$rows = $wpdb->get_results($wpdb->prepare("SELECT * FROM crm_tracing INNER JOIN wp_users ON crm_tracing.id_agent = wp_users.ID WHERE crm_tracing.id_ticket = $it ORDER BY crm_tracing.id_tracing ASC"));
	
	if (count($rows) > 0){
?>
			<div class="tracingContent">
<?php
		foreach ($rows as $row){
?>
                <div class="tracingTopContent">
                    <p class="tracingUserName">
                        <img src="<?php echo plugins_url( '../img/icons/comment-icon.png' , __FILE__ );?>" alt="" title="">
                        <?php echo $row->user_nicename;?>
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
                <?php _e('No existen comentarios de seguimiento', 'easywptickets'); ?>.
            </p>	
        </div>	
<?php
	}
	if ($st != STATE_CLOSED){
		showAddCommentSupport($it, $ic, $st);	
	}
?>
    </div>
<?php
}

//Función para añadir el menú de soporte
function supportMenuFrontEnd(){

	$backURL = get_permalink();
?>
	   	<ul class="clientMenuItems">
            <li>
                <a href="<?php echo $backURL ?>">
                	<img src="<?php echo plugins_url( '../img/icons/back-icon.png' , __FILE__ );?>" alt="" title="">
                    &nbsp;
                    <?php _e('Regresar', 'easywptickets'); ?>
                </a>
            </li>
        </ul>	
<?php	
}
//Función para añadir comentarios de seguimiento.
function showAddCommentSupport($id_ticket, $id_cia, $st){

	$commentUrl = strstr($_SERVER["REQUEST_URI"],'?', true);

?>
    <div class="tracingForm">
		<label><?php _e('Nuevo Comentario de Seguimiento', 'easywptickets'); ?>:</label>
		<form id="frm_comment" name="frm_comment" action="<?php echo $commentUrl;?>" method="post">			
			<textarea name="comment" rows="5" cols="80" required="required"></textarea>
            <br/>
            <input type="submit" name="Submit" class="button-primary" id="btn_send" value="<?php _e('Enviar', 'easywptickets'); ?>" />			
			<input type="hidden" name="id_ticket" value="<?php echo $id_ticket;?>" />
			<input type="hidden" name="ic" value="<?php echo $id_cia;?>" />
            <input type="hidden" name="st" value="<?php echo $st;?>" />
            <input type="hidden" name="clt_mode" value="sendCommentSupport" />
		</form>
	</div>
<?php
}

//Función que salva el comentario de seguimiento en la DB
function saveCommentSupport($frmComment){

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

		$idticket = $frmComment['id_ticket'];

		$mailData = $wpdb->get_row($wpdb->prepare(
		"SELECT id_ticket_importance, user_email, display_name
				FROM crm_tickets AS ti
				INNER JOIN wp_users AS wpu
				ON ti.id_user = wpu.ID
				WHERE ti.id_ticket = $idticket"));
		
		switch($mailData->id_ticket_importance){
	
		case '1':
			$ticketImport = "Alta";
			break;
		case '2':
			$ticketImport = "Media";
			break;
		case '3':
			$ticketImport = "Baja";
			break;
		}
		
		mailMotor($mailData->user_email, "comment", $mailData->display_name, $current_user->display_name, $frmComment['comment'], $ticketImport, $frmComment['id_ticket']);
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
function setCloseTicketSupport($id_ticket){
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
?>