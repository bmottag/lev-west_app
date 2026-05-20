<script type="text/javascript" src="<?php echo base_url("assets/js/validate/admin/notifications.js?v=2.0"); ?>"></script>

<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="exampleModalLabel">Notifications Access Settings Form
		<br><small>Add/Edit Notifications Access</small>
	</h4>
</div>

<div class="modal-body">
	<form name="form" id="form" role="form" method="post">
		<input type="hidden" id="hddId" name="hddId" value="<?php echo $information ? $information[0]["id_notification_access"] : ""; ?>" />

		<div class="row">
			<div class="col-sm-12">
				<div class="form-group text-left">
					<label class="control-label" for="notification">Notification: *</label>
					<?php

					if ($information == false) {

					?>
						<select name="notification" id="notification" class="form-control">
							<option value=''>Select...</option>
							<?php for ($i = 0; $i < count($notificationsList); $i++) { ?>
								<option value="<?php echo $notificationsList[$i]["id_notification"]; ?>"><?php echo $notificationsList[$i]["notification"]; ?></option>
							<?php } ?>
						</select>
					<?php
					} else {
						echo "<input type='hidden' class='form-control' value='" . $information[0]["fk_id_notification"] . "' disabled>";
						echo "<input type='text' class='form-control' value='" . $information[0]["notification"] . "' disabled>";
					}
					?>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12">
				<div class="form-group text-left">
					<label class="control-label" for="emailTo">Send Email to:</label><br>
					<select multiple="multiple" name="emailTo[]" id="emailTo" class="form-control js-example-basic-multiple">
						<option value=''>Select...</option>
						<?php

						// Decodificar fk_id_user_email si es un JSON string
						$selectedUsers = isset($information[0]["fk_id_user_email"])
							? json_decode($information[0]["fk_id_user_email"], true)
							: [];


						for ($i = 0; $i < count($workersList); $i++) {
							$userId = $workersList[$i]["id_user"];
							$isSelected = in_array($userId, $selectedUsers) ? "selected" : "";
						?>
							<option value="<?php echo $userId; ?>" <?php echo $isSelected; ?>>
								<?php echo $workersList[$i]["first_name"] . ' ' . $workersList[$i]["last_name"]; ?>
							</option>
						<?php } ?>
					</select>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12">
				<div class="form-group text-left">
					<label class="control-label" for="smsTo">Send SMS to:</label> <br>
					<select multiple="multiple" name="smsTo[]" id="smsTo" class="form-control js-example-basic-multiple">
						<option value=''>Select...</option>
						<?php

						// Decodificar fk_id_user_sms si es un JSON string
						$selectedUsers = isset($information[0]["fk_id_user_sms"])
							? json_decode($information[0]["fk_id_user_sms"], true)
							: [];


						for ($i = 0; $i < count($workersList); $i++) {
							$userId = $workersList[$i]["id_user"];
							$isSelected = in_array($userId, $selectedUsers) ? "selected" : "";
						?>
							<option value="<?php echo $userId; ?>" <?php echo $isSelected; ?>>
								<?php echo $workersList[$i]["first_name"] . ' ' . $workersList[$i]["last_name"]; ?>
							</option>
						<?php } ?>
					</select>
				</div>
			</div>
		</div>

		<div class="form-group">
			<div id="div_load" style="display:none">
				<div class="progress progress-striped active">
					<div class="progress-bar" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 45%">
						<span class="sr-only">45% completado</span>
					</div>
				</div>
			</div>
			<div id="div_error" style="display:none">
				<div class="alert alert-danger"><span class="glyphicon glyphicon-remove" id="span_msj">&nbsp;</span></div>
			</div>
		</div>

		<div class="form-group">
			<div class="row" align="center">
				<div style="width:50%;" align="center">
					<button type="button" id="btnSubmit" name="btnSubmit" class="btn btn-primary">
						Save <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true">
					</button>
				</div>
			</div>
		</div>

	</form>
</div>