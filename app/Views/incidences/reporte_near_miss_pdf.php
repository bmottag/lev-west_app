<?php
	// create some HTML content
	$html = '<br><h1 align="center" style="color:#337ab7;">' . strtoupper($title) . '<br><br></h1>
				<style>
				table {
					font-family: arial, sans-serif;
					border-collapse: collapse;
					width: 100%;
				}

				td, th {
					border: 1px solid #dddddd;
					text-align: left;
					padding: 8px;
				}
				</style>
			<table border="0" cellspacing="0" cellpadding="5">
				<tr>
					<th bgcolor="#337ab7" style="color:white;"><strong>Reported by: </strong></th>
					<th>' . $info[0]["name"] . '</th>
					<th bgcolor="#337ab7" style="color:white;"><strong>Near Miss type: </strong></th>
					<th>' . $info[0]["incident_type"] . '</th>
				</tr>		
				<tr>
					<th bgcolor="#337ab7" style="color:white;"><strong>Date of Near Miss: </strong></th>
					<th>' . $info[0]["date_near_miss"] . '</th>
					<th bgcolor="#337ab7" style="color:white;"><strong>Time: </strong></th>
					<th>' . $info[0]["time"] . '</th>
				</tr>
				<tr>
					<th bgcolor="#337ab7" style="color:white;"><strong>Location: </strong></th>
					<th>' . $info[0]["location"] . '</th>
					<th bgcolor="#337ab7" style="color:white;"><strong>Job Code/Name: </strong></th>
					<th>' . $info[0]["job_description"] . '</th>
				</tr>
				<tr>
					<th bgcolor="#337ab7" style="color:white;"><strong>Who was involved? </strong></th>
					<th colspan="3">';
	if($personsInvolved){
		foreach ($personsInvolved as $data):
			$html.= $data['person_name'] . '<br>';
		endforeach;
	}

	$html.= '</th>
				</tr>
				<tr>
					<th bgcolor="#337ab7" style="color:white;"><strong>What happened?</strong></th>
					<th colspan="3">' . $info[0]['what_happened']. '</th>
				</tr>
			</table>';
			
			
			
	$html.= '<br><br>
			<table border="0" cellspacing="0" cellpadding="5">
				<tr>
					<th colspan="3" bgcolor="#337ab7" style="color:white;">
					<strong>What was the immediate cause of the Near Miss? </strong><br>
					* (sequence of unsafe acts that led to incident. ex water on the floor, awkward positioning) 
					</th>
				</tr>		
				<tr>
					<th colspan="3">' . $info[0]['immediate_cause']. '</th>
				</tr>
			</table>';
			
	$html.= '<br><br>
			<table border="0" cellspacing="0" cellpadding="5">
				<tr>
					<th colspan="3" bgcolor="#337ab7" style="color:white;">
					<strong>What were the underlying causes? </strong><br>
					* (what caused the behavior or unsafe act, what controls were ignored)  
					</th>
				</tr>		
				<tr>
					<th colspan="3">' . $info[0]['uderlying_causes']. '</th>
				</tr>
			</table>';
			
	$html.= '<br><br>
			<table border="0" cellspacing="0" cellpadding="5">
				<tr>
					<th colspan="3" bgcolor="#337ab7" style="color:white;">
					<strong>Corrective Actions:</strong> <br>
					* What actions were taken to correct immediately?  
					</th>
				</tr>		
				<tr>
					<th colspan="3">' . $info[0]['corrective_actions']. '</th>
				</tr>
			</table>';
			
	$html.= '<br><br>
			<table border="0" cellspacing="0" cellpadding="5">
				<tr>
					<th colspan="3" bgcolor="#337ab7" style="color:white;">
					<strong>Preventative Action: </strong><br>
					* How can similar incidents be prevented in the future?   
					</th>
				</tr>		
				<tr>
					<th colspan="3">' . $info[0]['preventative_action']. '</th>
				</tr>
			</table>';
			
	$html.= '<br><br>
			<table border="0" cellspacing="0" cellpadding="5">
				<tr>
					<th colspan="3" bgcolor="#337ab7" style="color:white;">
					<strong>Comments: </strong><br>
					</th>
				</tr>		
				<tr>
					<th colspan="3">' . $info[0]['comments']. '</th>
				</tr>
			</table><br><br>';

	if($personsInvolved)
	{
			$html.= '<table border="1" cellspacing="0" cellpadding="5">';
					
			//pintar las firmas de a 4 por fila
			$total = count($personsInvolved);//contar numero de trabajadores
			$totalFilas  = 1;
			if($total>=4)
			{//si es mayor 4 entonces calcular cuantas filas deben ser
				$div = $total / 4;
				$totalFilas = ceil($div); //redondeo hace arriba
			}

			$n = 1;
			for($i=0;$i<$totalFilas;$i++){
				$html.= '<tr>';	
				
						$finish = $n * 4;
						$star = $finish - 4;
						if($finish > $total){
							$finish = $total;
						}
						$n++;			
																				
						for ($j = $star; $j < $finish; $j++) {
	
							$html.= '<th align="center" width="20%">';
							
							if($personsInvolved[$j]['person_signature']){
								//$url = base_url($workers[$j]['signature']);
								$html.= '<img src="'.$personsInvolved[$j]['person_signature'].'" border="0" width="70" height="70" />';
							}
							$html.= '</th>';
						}

				$html.= '</tr>';
								
				$html.= '<tr bgcolor="#337ab7" style="color:white;">';		
							for ($j = $star; $j < $finish; $j++) {	
								$html.= '<th align="center"><strong>' . $personsInvolved[$j]['person_name'] . '</strong></th>';
							}
				$html.= '</tr>';
			}
			
			$html.= '</table>';
	}
		
	$html.= '<br><br>';
	
	$signatureSupervisor = "";
	$signatureCoordinator = "";
	if($info[0]['supervisor_signature']){
		$signatureSupervisor = '<img src="'.$info[0]['supervisor_signature'].'" border="0" width="100" height="100" />';
	}
	
	if($info[0]['coordinator_signature']){
		$signatureCoordinator = '<img src="'.$info[0]['coordinator_signature'].'" border="0" width="100" height="100" />';
	}

	$html.= '<table border="0" cellspacing="0" cellpadding="5">
			<tr bgcolor="#337ab7" style="color:white;">
				<th align="center" colspan="3"><strong>Signatures</strong></th>
			</tr>
			<tr>
				<th width="35%" align="center">' . $signatureSupervisor . '</th>
				<th width="30%"></th>
				<th width="35%" align="center">' . $signatureCoordinator . '</th>
			</tr>
			
			<tr bgcolor="#337ab7" style="color:white;">
				<th align="center"><strong>Supervisor<br>' . $info[0]['supervisor']. '</strong></th>
				<th ></th>
				<th align="center"><strong>Coordinator<br>' . $info[0]['coordinator']. '</strong></th>
			</tr>
			</table>';		

echo $html;
						
?>