<?php

/*
-------------------------------------------------------------------------------
* Utilidad: Controla las funciones del frontend para los clientes.
* Creador: Cromorama.com - Julen Durán
* Fecha de Creación: 04-04-2014
* Última Modificación: 19-02-2015
* Versión: 1.0.1
* Nombre del archivo: crm-frontend-client.php
-------------------------------------------------------------------------------
*/

//Función que muestra la tabla de tickets del cliente en el front end
function showTicketsTableClient($ciaID, $it){
	
	global $wpdb;
	$moreWhere = "";
	$backURL = get_permalink();
	
	if($it != null){
	
		$moreWhere = "AND id_ticket = ".$it;
	
	}
	
	//echo "ID: ".$ciaID;
	
	$rows = $wpdb->get_results($wpdb->prepare("SELECT * FROM crm_tickets INNER JOIN wp_users ON crm_tickets.id_user = wp_users.ID INNER JOIN crm_subjects ON crm_tickets.id_subject = crm_subjects.id_subject WHERE id_user = $ciaID $moreWhere ORDER BY id_ticket DESC"));

	if (count($rows)>0){
	
?>
		<table id="table_tickets" class="wp-list-table widefat" cellspacing="0">
			<thead>
				<tr>
					<th><?php _e('ID', 'easywptickets'); ?></th>
					<th><?php _e('Fecha', 'easywptickets'); ?></th>
					<th><?php _e('Asunto', 'easywptickets'); ?></th>
					<th><?php _e('Descripcion', 'easywptickets'); ?></th>
					<th></th>
					<th></th>					
					<th></th>
<?php
					if($it == null){
						echo "<th></th>";
					}
?>
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
					<td align="right" width="40">
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
					<td align="center" width="20">
						<a href="<?php echo $backURL; ?>?clt_mode=viewTicket&it=<?php echo $row->id_ticket; ?>&ic=<?php echo $ciaID; ?>&st=<?php echo $row->id_ticket_state; ?>">
							<img src="<?php echo plugins_url( '../img/icons/view-ticket.png' , __FILE__ );?>" alt="<?php _e('Ver Tiket', 'easywptickets'); ?>" title="<?php _e('Ver Tiket', 'easywptickets'); ?>">
						</a>
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
            <?php _e('Aun no has enviado ningun ticket', 'easywptickets'); ?>.
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

//Función que muestra el menú para clientes.
function showMenuClient($ciaID, $mode){
	global $wpdb;
	$row = $wpdb->get_row($wpdb->prepare("SELECT * FROM wp_users WHERE ID = $ciaID"));

	$backURL = get_permalink();
	$isFriendly = strpos($backURL, '?');
	
	if($isFriendly == false){
		$urlVolverAtras = $backURL."?clt_mode=return&ic=$ciaID";
		$newTicket = $backURL."?clt_mode=newTicket&ic=$ciaID";
	}else{
		$urlVolverAtras = $backURL."&clt_mode=return&ic=$ciaID";
		$newTicket = $backURL."&clt_mode=newTicket&ic=$ciaID";
	}
	
?>
	<p class="clientName"><?php _e('Bienvenido', 'easywptickets'); ?> <?php echo $row->display_name; ?></p>
    
	<ul class="clientMenuItems">
<?php
		$user_ID = get_current_user_id();

		if ($mode == "viewTicket" OR $mode == "newTicket"){
?>
			<li><a href="<?php echo $urlVolverAtras ?>"><?php _e('Volver Atras', 'easywptickets'); ?></a></li>
<?php	
		}
		if ($mode != "newTicket"){
?>
        	<li><a href="<?php echo $newTicket; ?>"><?php _e('Enviar Nuevo Ticket', 'easywptickets'); ?></a></li>
<?php
		}
?>
    <ul>

<?php
}

//Función que muestra el form para crear un nuevo ticket
function createNewTicket($ic){
	
	global $wpdb;

	$rows = $wpdb->get_results($wpdb->prepare("SELECT id_subject, subject, id_agent FROM crm_subjects WHERE active = 0 ORDER BY subject"));
	$row_client_info = $wpdb->get_row($wpdb->prepare("SELECT display_name FROM wp_users WHERE ID = $ic"));
	
	if (count($rows)<1){
?>
        <div class="alertBad">
	       	<p>
    	        <img src="<?php echo plugins_url( '../img/icons/alert-icon.png' , __FILE__ );?>" alt="" title="">
                <?php _e('No existen asuntos', 'easywptickets'); ?>. <?php _e('Consulta con un administrador', 'easywptickets'); ?>.
        	</p>
		</div>
<?php	
	}else{
		
?>
		<form id="frm_newTicket" name="frm_newTicket" action="<?php echo $newTicket;?>" method="post">	
			<div class="">
            	<div class="clientSendTicketIzq">
                    <p>
                        <label><?php _e('Asunto', 'easywptickets'); ?>:</label>
                        <select id="subject_ticket" name="subject_ticket" required="required">
                            <option value=""><?php _e('Selecciona un Asunto', 'easywptickets'); ?></option>
<?php 
                foreach ($rows as $row){
?>
                            <option value="<?php echo $row->id_subject;?>"><?php echo $row->subject;?></option>
<?php 
                } 
?>
                        </select>
                    </p>
                    <p>
                        <label><?php _e('Importancia', 'easywptickets'); ?>:</label>
                        <select id="importance_ticket" name="importance_ticket">
                            <option value="3"><?php _e('Baja', 'easywptickets'); ?></option>
                            <option value="2"><?php _e('Media', 'easywptickets'); ?></option>
                            <option value="1"><?php _e('Alta', 'easywptickets'); ?></option>
                        </select>
                    </p>
               </div>
               <div class="clientSendTicketIzq">
               		<p><label><?php _e('Contacto', 'easywptickets'); ?>:</label><input type="text" name="contact" value="<?php echo $row_client_info->display_name;?>" maxlength="90"/></p>
                    <p><label><?php _e('Tlf Contacto', 'easywptickets'); ?>:</label><input type="text" name="tlf_contact" value="" maxlength="9" size="9"/></p>
               </div>
               <div class="clientSendTicketDrch">
                    <p>
                        <label><?php _e('Descripcion', 'easywptickets'); ?>:</label>
                        <textarea id="description_ticket" name="description_ticket" rows="5" cols="80" width="100%"></textarea><br/>
                    </p>
               </div>
			</div>
		       <hr style="clear:both;"/>
                    <input type="hidden" name="ic" value="<?php echo $ic;?>" />
                    <input type="submit" name="Submit" class="button-primary" id="btn_send" value="<?php _e('Enviar Ticket', 'easywptickets'); ?>" style="float:right;"/>
                    <input type="hidden" name="hdn_mode" value="viewClientCIA" />
                    <input type="hidden" name="clt_mode" value="sendTicket" />
        </form>
<?php	
	}
}

//Función que graba un nuevo ticket en la base de datos
function setNewTicket($frmNewTicket){
	
	global $wpdb;	
	
	if ($wpdb->insert(
			'crm_tickets',
			array(
					'id_subject' => $frmNewTicket['subject_ticket'],
					'id_user' => $frmNewTicket['ic'], 
					'description' => $frmNewTicket['description_ticket'],
					'contact' => $frmNewTicket['contact'],
					'contact_tlf' => $frmNewTicket['tlf_contact'],
					'id_ticket_importance' => $frmNewTicket['importance_ticket'],
					'id_ticket_state' => '1'
			)
	)){

	$id_ticket = $wpdb->insert_id; 
	
	$emailAgent = $wpdb->get_row($wpdb->prepare(
			"SELECT display_name, user_email
			FROM wp_users AS cst
			INNER JOIN crm_subjects AS cts ON cts.id_agent = cst.ID
			AND cts.id_subject = ".$frmNewTicket['subject_ticket']));
	
	switch($frmNewTicket['importance_ticket']){
	
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
	
	mailMotor($emailAgent->user_email, "ticket", null, $emailAgent->display_name, $frmNewTicket['description_ticket'], $ticketImport, null);
	
	}else{
		//echo NO_INSERT;
	}
}

//Función que muestra el tracing de un ticket en concreto
function viewTicketTraCIA($it, $st){
	
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
}
?>