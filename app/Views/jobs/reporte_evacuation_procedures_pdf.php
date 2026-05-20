<?php
	// create some HTML content
	$html = '<p><h1 align="center" style="color:#337ab7;">EMERGENCY REPORTING AND EVACUATION PROCEDURES</h1></p>';

	// create some HTML content
	$html .= 'Types of emergencies to be reported by site personnel are:

	<ul>
		<li>Medical</li>
		<li>Fire</li>
		<li>Severe weather</li>
		<li>Chemical spill</li>
	</ul>';

			// create some HTML content
	$html .= '<p><h1 align="center" style="color:#337ab7;">MEDICAL EMERGENCY</h1></p>
	
		<ul>
			<li>Call medical emergency phone number and provide the following information:<br>
						
				 <ul type:"a">
				  <li>Nature of medical emergency</li>
				  <li>Location of the emergency (address, building, room number)</li>
				  <li>Your name and phone number from which you are calling.</li>
				</ul> 
			</li><br>
			
			<li>Do not move victim unless absolutely necessary.</li><br>
			
			<li>
				Call the following personnel trained in CPR and First Aid to provide the
				required assistance prior to the arrival of the professional medical help:
				<br>

				<ul style="list-style-type:none">
					<li>
						<strong>Name: </strong>' . $info[0]['emer_1'] . '
						<strong>Phone: </strong>' . $info[0]['phone_emer_1'] . '
					</li>
					<li>
						<strong>Name: </strong>' . $info[0]['emer_2'] . '
						<strong>Phone: </strong>' . $info[0]['phone_emer_2'] . '
					</li>
				</ul><br> 
			</li>
			
			<li>If personnel trained in First Aid are not available, as a minimum, attempt to provide the following assistance:<br>
			
				<ol>
					<li>Stop the bleeding with firm pressure on the wounds (note: avoid
					contact with blood or other bodily fluids).</li>
					<li>Clear the air passages using the Heimlich Maneuver in case of choking.</li>
				</ol> 
				
			</li><br>
			
			<li>In case of rendering assistance to personnel exposed to hazardous materials,
consult the Material Safety Data Sheet (MSDS) and wear the appropriate personal
protective equipment. Attempt first aid ONLY if trained and qualified.</li><br>

			<li>Directions to get to the hospital:<br>
			
				<ol>
					<li>' . $info[0]['directions'] . '</li>
				</ol> 
			
			</li>
		</ul>
	
	
	
	';
		

echo $html;
						
?>