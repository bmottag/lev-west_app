<?php
	// create some HTML content
	$html = '<br><h2 align="center" style="color:#337ab7;">JOB HAZARDS ANALYSIS<br><br></h2>
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
					<th bgcolor="#337ab7" style="color:white;"><strong>Job Code/Name: </strong></th>
					<th colspan="3">' . strtoupper($info[0]['job_description']). '</th>
				</tr>
			
				<tr>
					<th bgcolor="#337ab7" style="color:white;"><strong>Done by: </strong></th>
					<th>' . $info[0]['name']. '</th>
					<th bgcolor="#337ab7" style="color:white;"><strong>Date & Time: </strong></th>
					<th>' . $info[0]['date_log']. '</th>
				</tr>
			
				<tr>
					<th bgcolor="#337ab7" style="color:white;"><strong>Observation: </strong></th>
					<th colspan="3">' . $info[0]['observation']. '</th>
				</tr>

			</table>';
								
				$html.= '<br><br>';
				
				$html .= '<table border="1" cellspacing="0" cellpadding="5">
						<tr>
							<th colspan="4"><strong><i>Identify and prioritize hazards below, then identify plans to eliminate/control the hazards</i></strong></th>
						</tr>
						<tr bgcolor="#337ab7" style="color:white;">
							<th width="5%" align="center"><strong>#</strong></th>
							<th width="25%" align="center"><strong>Activity</strong></th>
							<th width="20%" align="center"><strong>Hazard</strong></th>
							<th width="10%" align="center"><strong>Priority</strong></th>
							<th width="40%" align="center"><strong>Control/Eliminate</strong></th>
						</tr>';
					if(!$hazards){
							$html.= '<tr>';					
							$html.= '<th colspan="4" align="center"> ---- No data was found for Hazard -----</th>';
							$html.= '</tr>';					
					}else{
						$i = 0;
						foreach ($hazards as $data):
							$i++;
							$html.= '<tr>';					
							$html.= '<th align="center">' . $i . '</th>';
							$html .= '<th >' . $data['hazard_activity'] . '</th>';
							$html.= '<th >' . $data['hazard_description'] . '</th>';					
							$priority = $data['priority_description']==""?"-":$data['priority_description'];
							$html.= '<th align="center">' . $priority . '</th>';
							$html.= '<th >' . $data['solution']  . '</th>';
							$html.= '</tr>';
						endforeach;
					}
				$html.= "</table><br><br><br><br>";
			

echo $html;
						
?>