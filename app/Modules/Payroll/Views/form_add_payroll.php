<script type="text/javascript" src="<?php echo base_url("assets/js/validate/payroll/payrollStart.js?v=1.0.0"); ?>"></script>

<script>
	$(document).ready(function() {
		$('.js-example-basic-single').select2();
	});
</script>

<!-- Agrega esto en el <head> o antes del cierre de </body> -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
	let map;
	let marker;

	function initMap() {
		if (navigator.geolocation) {
			navigator.geolocation.getCurrentPosition(showPosition, showError, {
				enableHighAccuracy: true,
				timeout: 5000,
				maximumAge: 0
			});
		} else {
			alert("Geolocalización no es soportada por este navegador.");
		}
	}

	function showPosition(position) {
		const lat = position.coords.latitude;
		const lng = position.coords.longitude;

		document.getElementById("latitud").value = lat;
		document.getElementById("longitud").value = lng;

		// Inicializa el mapa con Leaflet
		map = L.map('map').setView([lat, lng], 14);

		// Carga mapas desde OpenStreetMap
		L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
			attribution: '&copy; OpenStreetMap contributors'
		}).addTo(map);

		// Agrega marcador
		marker = L.marker([lat, lng]).addTo(map);

		// Obtiene la dirección con Nominatim
		fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
			.then(response => response.json())
			.then(data => {
				const address = data.display_name;
				document.getElementById("viewaddress").value = address;
				document.getElementById("address").value = address;
			})
			.catch(error => {
				console.error("Error obteniendo dirección:", error);
			});
	}

	function showError(error) {
		let msg = "";
		switch (error.code) {
			case error.PERMISSION_DENIED:
				msg = "Permiso denegado para obtener ubicación.";
				break;
			case error.POSITION_UNAVAILABLE:
				msg = "Ubicación no disponible.";
				break;
			case error.TIMEOUT:
				msg = "Tiempo de espera agotado.";
				break;
			default:
				msg = "Error desconocido.";
				break;
		}
		alert(msg);
	}

	// Ejecutar al cargar la página
	window.onload = initMap;
</script>

<div id="page-wrapper">
	<br>

	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<i class="fa fa-book"></i> <strong>RECORD TASK(S) - PAYROLL</strong>
					<br><small>Time Stamp - Start</small>
				</div>
				<div class="panel-body">
					<form name="form" id="form" class="form-horizontal" method="post" action="<?php echo base_url("payroll/savePayroll"); ?>">

						<!-- Task : Time Stamp  -->
						<input type="hidden" id="hddTask" name="hddTask" value="1" />

						<div class="form-group">
							<label class="col-sm-4 control-label" for="address">Address:</label>
							<div class="col-sm-4">
								<input id="viewaddress" name="viewaddress" class="form-control" type="text" disabled>
								<input id="latitud" name="latitud" type="hidden">
								<input id="longitud" name="longitud" type="hidden">
								<input id="address" name="address" type="hidden">
							</div>
							<div class="col-sm-1">
								<a class="btn btn-success btn-circle" href=" <?php echo base_url() . 'payroll/add_payroll/'; ?> "><i class="fa fa-refresh "></i> </a>
							</div>
						</div>

						<div class="form-group">
							<div class="row" align="center">
								<div style="width:80%;" align="center">
									<div id="map" style="width: 100%; height: 150px"></div>
								</div>
							</div>
						</div>

						<input id="programming" name="programming" type="hidden" value="<?php echo $programming; ?>">
						<div class="form-group">
							<label class="col-sm-4 control-label" for="jobName">Job Code/Name:
								<?php if ($job_programming) { ?>
									<p class="help-block">Are you logging in under this Job Code/Name?</p>
								<?php } ?>
							</label>
							<div class="col-sm-5">
								<select name="jobName" id="jobName" class="form-control js-example-basic-single">
									<option value=''>Select...</option>
									<?php for ($i = 0; $i < count($jobs); $i++) { ?>
										<option value="<?php echo $jobs[$i]["id_job"]; ?>" <?php if ($job_programming == $jobs[$i]["id_job"]) {
																								echo "selected";
																							}; ?>><?php echo $jobs[$i]["job_description"]; ?></option>
									<?php } ?>
								</select>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-6 control-label small" for="certify">
								I certify to be clean for the last 8 hours of any substance such:
								recreational cannabis, alcohol, drugs or any over the counter medicine that may or will affect
								the fitness of my work performance.
							</label>
							<div class="col-sm-3">
								<select name="certify" id="certify" class="form-control" required>
									<option value="">Select...</option>
									<option value=1>Yes</option>
									<option value=2>No</option>
								</select>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-6 control-label small" for="certify">
								I certify to be well-rested, having slept a minimun of 6 - 8 hours. I certify my ability or alertness to perform my work for this shift will NOT be impaired by the amount or quality of sleep I had before coming to work.
							</label>
							<div class="col-sm-3">
								<select name="slept_certify" id="slept_certify" class="form-control" required>
									<option value="">Select...</option>
									<option value=1>Yes</option>
									<option value=2>No</option>
								</select>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-4 control-label" for="taskDescription">Task/Report Description:</label>
							<div class="col-sm-5">
								<textarea id="taskDescription" name="taskDescription" class="form-control" rows="3"></textarea>
							</div>
						</div>

						<div class="row" align="center">
							<div style="width:50%;" align="center">
								<button type="submit" id="btnSubmit" name="btnSubmit" class="btn btn-primary">
									Submit <span class="glyphicon glyphicon-log-in" aria-hidden="true">
								</button>
							</div>
						</div>

					</form>
				</div>
			</div>
		</div>
	</div>
</div>