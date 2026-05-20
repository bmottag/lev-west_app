<?php
	// create some HTML content
	$html = '<p><h1 align="center" style="color:#337ab7;">FIRE EMERGENCY</h1></p>
	<p></p>';

	// create some HTML content
	$html .= '<h4 style="color:#337ab7;">When fire is discovered:</h4>
	
	<ul>
		<li>Activate the nearest fire alarm (if installed)</li>
		<li>Notify the local Fire Department by calling 9-1-1</li>
		<li>If the fire alarm is not available, notify the site personnel about the fire
emergency by the following means :

				 <ul >';
				 
	$html .= $info[0]['voice']?"<li>Voice</li>":"";
	$html .= $info[0]['phone']?"<li>Phone Paging</li>":"";
	$html .= $info[0]['radio']?"<li>Radio</li>":"";
	$html .= $info[0]['other']?"<li>" . $info[0]['specify'] .  "</li>":"";
	$html .= '			</ul> 
		</li>
	</ul>';

	// create some HTML content
	$html .= '<h4 style="color:#337ab7;">Fight the fire ONLY if:</h4>
	
	<ul>
		<li>The Fire Department has been notified.</li>
		<li>The fire is small and is not spreading to other areas.</li>
		<li>Escaping the area is possible by backing up to the nearest exit.</li>
		<li>The fire extinguisher is in working condition and personnel are trained to use it.</li>
	</ul>';
	
	// create some HTML content
	$location = $info[0]['location'];
	if($info[0]['location2']){
		$location .= ', ' . $info[0]['location2']; 
	}
	if($info[0]['location3']){
		$location .= ', ' . $info[0]['location3']; 
	}

	$html .= '<h4 style="color:#337ab7;">Upon being notified about the fire emergency, occupants must:</h4>
	
	<ul>
		<li>Leave the building using the designated escape routes.</li>
		<li>Assemble in the designated area : <strong>' . $location . '</strong></li>
		<li>Remain outside until the competent authority (Designated Official or
designee) announces that it is safe to reenter.</li>
	</ul>';
	
	// create some HTML content
	$html .= '<h4 style="color:#337ab7;">Designated Official, Emergency Coordinator or supervisors must (underline one):</h4>
	
	<ul>
		<li>Disconnect utilities and equipment unless doing so jeopardizes his/her safety.</li>
		<li>Coordinate an orderly evacuation of personnel.</li>
		<li>Perform an accurate head count of personnel reported to the designated area.</li>
		<li>Determine a rescue method to locate missing personnel.</li>		
		<li>Provide the Fire Department personnel with the necessary information
		about the facility.</li>
		<li>Perform assessment and coordinate weather forecast office emergency
		closing procedures	</li>
	</ul>';
	
	// create some HTML content
	$html .= '<h4 style="color:#337ab7;">Area/Floor Monitors must:</h4>
	
	<ul>
		<li>Ensure that all employees have evacuated the area/floor.</li>
		<li>Report any problems to the Emergency Coordinator at the assembly area.	</li>
	</ul>';

	// create some HTML content
	$html .= '<h4 style="color:#337ab7;">Assistants to Physically Challenged should:</h4>
	
	<ul>
		<li>Assist all physically challenged employees in emergency evacuation.</li>
	</ul>';

	
		
		

echo $html;
						
?>