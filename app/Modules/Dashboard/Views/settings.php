<div id="page-wrapper">
	<br>
	<!-- /.row -->
	<div class="row">
		<!-- /.col-lg-4 -->
		<div class="col-lg-4">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<strong>Administrator</strong>
				</div>
				<div class="panel-body">
					<p>
<strong>Name:</strong> <?php echo $parametric[2]['value']; ?>
<br><strong>Email:</strong> <?php echo $parametric[7]['value']; ?>
<br><strong>Mobile Number:</strong> <?php echo $parametric[6]['value']; ?>
					</p>
				</div>
				<div class="panel-footer">
					<strong>Mobile number: </strong>Used to send SMS of a new Day Off requests. </br>
				</div>
			</div>
		</div>
		<!-- /.col-lg-4 -->
		<div class="col-lg-4">
			<div class="panel panel-success">
				<div class="panel-heading">
					<strong>Safety</strong>
				</div>
				<div class="panel-body">
					<p>
<strong>Email:</strong> <?php echo $parametric[0]['value']; ?>
					</p>
				</div>
				<div class="panel-footer">
					<strong>Email: </strong>Used to send email of a new Day Off requests. </br>
					<strong>Email: </strong>Used to send email of a new Incidence requests. </br>
					<strong>Email: </strong>Used to send email when an Inspection has comments.
				</div>
			</div>
		</div>
		<!-- /.col-lg-4 -->

	</div>

</div>
<!-- /#page-wrapper -->