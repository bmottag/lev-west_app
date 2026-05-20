<link href="<?php echo base_url("assets/bootstrap/fullcalendar/lib/main.css"); ?>" rel="stylesheet">
<script src="<?php echo base_url("assets/bootstrap/fullcalendar/lib/main.js"); ?>"></script>

<script>
	const dashboardURL = "<?php echo base_url($dashboardURL); ?>";
    const consultaURL = "<?= base_url('dashboard/consulta') ?>";
</script>

<script>
	document.addEventListener('DOMContentLoaded', function() {
		var calendarEl = document.getElementById('calendar');

		var calendar = new FullCalendar.Calendar(calendarEl, {

			headerToolbar: {
				left: 'prev,next today myCustomButton',
				center: 'title',
				right: 'listDay,listWeek'
			},

			customButtons: {
				myCustomButton: {
					text: 'My Work Space',
					click: function() {
						window.location.href = dashboardURL;
					}
				}
			},


			// customize the button names,
			// otherwise they'd all just say "list"
			views: {
				listDay: { buttonText: 'List day' },
				listWeek: { buttonText: 'List week' }
			},

			buttonText: { today:    'Today' },
			firstDay: 1, //para iniciar en lunes
			 
			initialView: 'listWeek',
			navLinks: true, // can click day/week names to navigate views
			editable: true,
			dayMaxEvents: true, // allow "more" link when too many events
			events: {
				url: consultaURL,
				method: 'POST',
				extraParams: {
					custom_param1: 'something',
					custom_param2: 'somethingelse'
				},
				failure: function() {
					alert('There was an error while fetching events!');
				},
				color: 'green',   // a non-ajax option
				textColor: 'black' // a non-ajax option
			}
    	});
    	calendar.render();
  	});
</script>

<div id="page-wrapper">
	<br>	
	<div class="row">
		<div class="col-lg-12">
			<div id='calendar'></div>
			<br>
		</div>
	</div>
</div>