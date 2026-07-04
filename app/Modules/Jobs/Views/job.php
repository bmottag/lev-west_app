<script type="text/javascript" src="<?php echo base_url("assets/js/validate/jobs/job_detail.js"); ?>"></script>

<script>
$(function(){ 
	$(".btn-success").click(function () {	
			var oID = $(this).attr("id");
            $.ajax ({
                type: 'POST',
				url: base_url + '/jobs/cargarModalJobDetail',
                data: {'idJob': '', 'idJobDetail': oID},
                cache: false,
                success: function (data) {
                    $('#tablaDatos').html(data);
                }
            });
	});
    
	$(".lic_add_modal").click(function () {	
			var oID = $(this).attr("id");
            $.ajax ({
                type: 'POST',
				url: base_url + 'jobs/cargarModalJobDetail',
				data: {'identification': oID, 'idJobDetail': 'x'},
                cache: false,
                success: function (data) {
                    $('#tablaDatos').html(data);
                }
            });
	});	
});
</script>

<div id="page-wrapper">
    <br>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-dark">
                <div class="panel-heading">
                    <a class="btn btn-dark btn-xs" href=" <?php echo base_url().'admin/job/1'; ?> "><span class="glyphicon glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Go back </a> 
                    <i class="fa fa-gears fa-fw"></i> <b>Line Item Contract (LIC)</b>
                </div>
                <div class="panel-body small">

                    <div class="alert alert-info">
                        <h2>
						    <span class="fa fa-briefcase" aria-hidden="true"></span>
						    <strong>Job Code/Name: </strong><?php echo $jobInfo[0]['job_description']; ?>
                        </h2>
					</div>

					<ul class="nav nav-pills">
						<li <?php if($isJobDetail){ echo "class='active'";} ?>><a href="<?php echo base_url("jobs/job_detail/" . $jobInfo[0]["id_job"]); ?>">List of Active LIC</a>
						</li>
						<li><a href="<?php echo base_url("jobs/charged_lic/" . $jobInfo[0]["id_job"] . "/2"); ?>">List of Executed LIC</a>
						</li>
						<li><a href="<?php echo base_url("jobs/charged_lic/" . $jobInfo[0]["id_job"] . "/3"); ?>">List of Closed LIC</a>
						</li>
					</ul>
                    
					<?php if (session()->getFlashdata('retornoExito')): ?>
						<div class="alert alert-success">
							<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
							<?= session()->getFlashdata('retornoExito') ?>
						</div>
					<?php endif; ?>

					<?php if (session()->getFlashdata('retornoError')): ?>
						<div class="alert alert-danger">
							<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
							<?= session()->getFlashdata('retornoError') ?>
						</div>
					<?php endif; ?>

                    <?php
                        if($jobInfo[0]['flag_expenses'] != 1){
                    ?>	
                        <?php 
                            if (!empty($success)) {
                                echo '<div class="col-lg-12">';
                                echo '<div class="alert text-center alert-success"><label>' . $success . '</label></div>';
                                echo '</div>';
                            } 
                        ?>

                        <form  name="formCargue" id="formCargue" class="form-horizontal" method="post" enctype="multipart/form-data" action="<?php echo base_url("jobs/do_upload_job_info"); ?>">
                            <input type="hidden" id="hddIdJob" name="hddIdJob" value="<?php echo $jobInfo[0]["id_job"]; ?>"/>
                            <input type="hidden" id="hddFlagExpenses" name="hddFlagExpenses" value="<?php echo $jobInfo[0]["flag_expenses"]; ?>"/>
                            <input type="hidden" id="hddFlagUploadDetails" name="hddFlagUploadDetails" value="<?php echo $jobInfo[0]["flag_upload_details"]; ?>"/>
                                
                            <div class="col-lg-6">				
                                <div class="form-group">					
                                    <label class="col-sm-5 control-label" for="hddTask">Attach LIC File:</label>
                                    <div class="col-sm-6">
                                        <input type="file" name="userfile" class="form-control" />
                                    </div>
                                </div>
                            </div>
                        
                            <div class="col-lg-3">				
                                <div class="form-group">
                                    <div class="row" align="center">
                                        <div style="width:50%;" align="center">
                                            <button type="submit" id="btnSubir" name="btnSubir" class='btn btn-primary btn-sm'>
                                                Upload LIC <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true">
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <div class="col-lg-12">
                            <div class="alert alert-info">
                                    <strong>Note :</strong><br>
                                    Allowed format: CSV<br>
                                    Maximum size: 4096 KB
                            </div>
                        </div>
                    <?php
                        }else{
                    ?>	
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="panel panel-default">
                                    <div class="panel-footer">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="alert alert-danger ">
                                                    <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                                                    Currently, there are entries in the WO (Work Tickets) detailing various expenses related to this Job Code. Unfortunately, there is an inability to upload additional information at this time.
                                                </div>

                                                <form  name="formDeleteInfo" id="formDeleteInfo" method="post" action="<?php echo base_url("jobs/delete_job_detail_info"); ?>">
                                                    <div class="panel panel-default">
                                                        <div class="panel-footer">
                                                            <div class="row">
                                                                <div class="col-lg-2">
                                                                    <div class="form-group">
                                                                        <input type="hidden" id="hddIdJob" name="hddIdJob" value="<?php echo $jobInfo[0]["id_job"]; ?>"/>
                                                                        <button type="button" id="btnSubmit" name="btnSubmit" class="btn btn-danger btn-sm" onclick="deleteInformation()">
                                                                            Reset all the Information <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                                                                        </button> 
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                                
                                                <button type="button" class="btn btn-dark btn-block lic_add_modal" data-toggle="modal" data-target="#modal" id="<?php echo $jobInfo[0]["id_job"] . "-x-x"; ?>">
                                                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add APU's
                                                </button><br>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php
                    }
                    if($chapterList){
                        foreach ($chapterList as $lista):
                            $jobDetails = $chapterDetails[$lista['chapter_number']] ?? false;

                            if($jobDetails){

                                $totalExtendedAmount = 0;
                                $totalPercentage = 0;
                                $totalExpenses = 0;
                                $totalFaExpenses = 0;
                                $totalBalance = 0;
					?>

                            <div class="panel-body">
                                <div class="panel-group" id="accordion">	
                                    <h2><?php echo $lista['chapter_name']; ?></h2>
                                    <button type="button" class="btn btn-dark btn-block lic_add_modal" data-toggle="modal" data-target="#modal" id="<?php echo $jobInfo[0]["id_job"] . "-" . $lista["chapter_number"] . "-" . $lista["chapter_name"]; ?>">
                                            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add APU's
                                    </button><br>
                                    <?php
                                        foreach ($jobDetails as $data):
                                            $class = "default";
                                            $faExpensesTotal = $faExpensesTotalByDetail[$data['id_job_detail']] ?? 0;
                                            if($data['unit_price'] == 0){
                                                $balance = $data['expenses'] + $faExpensesTotal;
                                            }else{
                                                $balance = $data['extended_amount'] - $data['expenses'] - $faExpensesTotal;

                                                $veintePorciento = $data['extended_amount'] * 0.2;
                                                $class = $balance < 0 ? "danger" : ($balance <= $veintePorciento ? "info" : "default");
                                            }

                                            $totalExtendedAmount += $data['extended_amount'];
                                            $totalPercentage += $data['percentage'];
                                            $totalExpenses += $data['expenses'];
                                            $totalFaExpenses += $faExpensesTotal;
                                            $totalBalance += $balance;
                                    ?>
                                        <div class="panel panel-<?php echo $class ?>" >
                                            <div class="panel-heading">
                                                <h4 class="panel-title">
                                                    <table width="100%" class="table table-striped table-bordered table-hover small" id="dataTables">
                                                    <?php
                                                        echo "<tr class='" . $class . "'>";
                                                        echo "<td width='4%' class='text-center'><p class='text-" . $class . "'><b>Item</b><br>" . $data['chapter_number'] . "." . $data['item'] . "</p>";
                                                    ?>
                                                        <button type="button" class="btn btn-success btn-xs" data-toggle="modal" data-target="#modal" id="<?php echo $data['id_job_detail']; ?>" >
                                                            <span class="glyphicon glyphicon-edit" aria-hidden="true">
                                                        </button>

                                                        <button type="button" class="btn btn-info btn-xs" onclick="toggleCollapse('<?php echo $data['id_job_detail']; ?>')">
                                                            <i class="fa fa-eye"></i> W.O.
                                                        </button>

                                                        <button type="button" class="btn btn-primary btn-xs" onclick="toggleCollapse('FA<?php echo $data['id_job_detail']; ?>')">
                                                            <i class="fa fa-eye"></i> F.A.
                                                        </button>

                                                        <button type="button" class="btn btn-warning btn-xs" onclick="loadClaims('<?php echo $data['id_job_detail']; ?>')">
                                                            <i class="fa fa-cubes"></i> Claims
                                                        </button>

                                                        <?php
                                                            echo "</td>";

                                                            echo "<td width='38%'>
                                                                    <p class='text-" . esc($class) . "'>
                                                                        <b>Description</b><br>" . esc($data['description'] ?? '') . "
                                                                    </p>
                                                                </td>";

                                                            echo "<td width='4%' class='text-center'>
                                                                    <p class='text-" . esc($class) . "'>
                                                                        <b>Unit</b><br>" . esc($data['unit'] ?? '') . "
                                                                    </p>
                                                                </td>";

                                                            echo "<td width='7%' class='text-center'>
                                                                    <p class='text-" . esc($class) . "'>
                                                                        <b>Quantity</b><br>" . esc($data['quantity'] ?? 0) . "
                                                                    </p>
                                                                </td>";

                                                            echo "<td width='7%' class='text-right'>
                                                                    <p class='text-" . esc($class) . "'>
                                                                        <b>Unit Price</b><br>$ " . number_format((float)($data['unit_price'] ?? 0), 2) . "
                                                                    </p>
                                                                </td>";

                                                            echo "<td width='9%' class='text-right'>
                                                                    <p class='text-" . esc($class) . "'>
                                                                        <b>Extended Amount</b><br>$ " . number_format((float)($data['extended_amount'] ?? 0), 2) . "
                                                                    </p>
                                                                </td>";

                                                            echo "<td width='5%' class='text-right'>
                                                                    <p class='text-" . esc($class) . "'>
                                                                        <b>%</b><br>" . esc($data['percentage'] ?? 0) . " %
                                                                    </p>
                                                                </td>";

                                                            echo "<td width='9%' class='text-right'>
                                                                    <p class='text-" . esc($class) . "'>
                                                                        <b>W.O. Expenses</b><br>$ " . number_format((float)($data['expenses'] ?? 0), 2) . "
                                                                    </p>
                                                                </td>";

                                                            echo "<td width='9%' class='text-right'>
                                                                    <p class='text-" . esc($class) . "'>
                                                                        <b>F.A. Expenses</b><br>$ " . number_format((float)$faExpensesTotal, 2) . "
                                                                    </p>
                                                                </td>";

                                                            echo "<td width='8%' class='text-right'>
                                                                    <p class='text-" . esc($class) . "'>
                                                                        <b>Balance</b><br>$ " . number_format((float)($balance ?? 0), 2) . "
                                                                    </p>
                                                                </td>";

                                                            echo "</tr>";
                                                        ?>
                                                    </table>
                                                </h4>
                                            </div>
                                            <div id="collapse<?php echo $data['id_job_detail']; ?>" class="panel-collapse collapse">
                                                <div class="panel-body">
                                                    <?php
                                                        $expenses = $expensesByDetail[$data['id_job_detail']] ?? false;

                                                        if($expenses){
                                                    ?>
                                                        <table width="100%" class="table table-hover dataTable no-footer" id="dataTables">
                                                            <thead>
                                                                <tr>
                                                                    <th class="text-center" >W.O. #</th>
                                                                    <th class="text-center" >Date W.O.</th>
                                                                    <th class="text-left" >Work Done</th>
                                                                    <th class="text-right" >Expense Value</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>					
                                                            <?php
                                                                foreach ($expenses as $expense):
                                                                    echo "<tr>";
                                                                    echo "<td class='text-center'>";
                                                                    echo "<a href='" . base_url('workorders/add_workorder/' . $expense['id_workorder']) . "' target='_blank'>" . $expense['id_workorder'] . "</a>";
                                                                    echo "</td>";
                                                                    echo "<td class='text-center'>" . $expense['date'] . "</td>";
                                                                    echo "<td class='text-left'>" . $expense['observation'] . "</td>";
                                                                    echo "<td class='text-right'>$ " . number_format($expense['total_expenses'],2) . "</td>";
                                                                    echo "</tr>";
                                                                endforeach;
                                                            ?>
                                                            </tbody>
                                                        </table>
                                                    <?php } else { ?>
                                                        <p class="text-muted">No hay Work Orders registradas para este ítem.</p>
                                                    <?php } ?>
                                                </div>
                                            </div>

                                            <div id="collapseFA<?php echo $data['id_job_detail']; ?>" class="panel-collapse collapse">
                                                <div class="panel-body">
                                                    <?php
                                                        $faExpenses = $forceAccountExpensesByDetail[$data['id_job_detail']] ?? false;

                                                        if($faExpenses){
                                                    ?>
                                                        <table width="100%" class="table table-hover dataTable no-footer" id="dataTables">
                                                            <thead>
                                                                <tr>
                                                                    <th class="text-center" >F.A. #</th>
                                                                    <th class="text-center" >Date F.A.</th>
                                                                    <th class="text-left" >Work Done</th>
                                                                    <th class="text-right" >Expense Value</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                            <?php
                                                                foreach ($faExpenses as $faExpense):
                                                                    echo "<tr>";
                                                                    echo "<td class='text-center'>";
                                                                    echo "<a href='" . base_url('forceaccount/add_forceaccount/' . $faExpense['id_forceaccount']) . "' target='_blank'>" . $faExpense['id_forceaccount'] . "</a>";
                                                                    echo "</td>";
                                                                    echo "<td class='text-center'>" . $faExpense['date'] . "</td>";
                                                                    echo "<td class='text-left'>" . $faExpense['observation'] . "</td>";
                                                                    echo "<td class='text-right'>$ " . number_format($faExpense['total_expenses'],2) . "</td>";
                                                                    echo "</tr>";
                                                                endforeach;
                                                            ?>
                                                            </tbody>
                                                        </table>
                                                    <?php } else { ?>
                                                        <p class="text-muted">No hay Force Account registradas para este ítem.</p>
                                                    <?php } ?>
                                                </div>
                                            </div>

                                            <div id="claims_<?php echo $data['id_job_detail']; ?>" style="display:none; margin-top:10px;"></div>

                                        </div>
                                    <?php
                                        endforeach;

                                        echo "<br>";
                                        echo '<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables">';
                                            echo "<tr>";
                                            echo "<td width='40%' class='text-right'><h2>Subtotal</h2></td>";
                                            echo "<td width='25%' class='text-right'><b>Extended Amount<br>$ " . number_format($totalExtendedAmount,2) . "</b></td>";
                                            //echo "<td class='text-right'><b>Percentage<br>" . $totalPercentage  . "%</b></td>";
                                            echo "<td width='13%' class='text-right'><b>W.O. Expenses<br>$ " . number_format($totalExpenses,2) . "</b></td>";
                                            echo "<td width='13%' class='text-right'><b>F.A. Expenses<br>$ " . number_format($totalFaExpenses,2) . "</b></td>";
                                            echo "<td width='9%' class='text-right'><b>Balance<br>$ " . number_format($totalBalance,2) . "</b></td>";
                                            echo "</tr>";

                                        echo "</table>";
                                    ?>
                                </div>
                            </div>

                    <?php 
                            }
                        endforeach;
					?>

                        <hr>

                        <div class="panel-body">
                            <div class="panel-group" id="accordion">	
                                <h2>Summary</h2>

                                <table width="100%" class="table table-hover dataTable no-footer" id="dataTables">
                                    <thead>
                                        <tr>
                                            <th class="text-left" >Chapter</th>
                                            <th class="text-right" >Extended Amount</th>
                                            <th class="text-right" >W.O. Expenses</th>
                                            <th class="text-right" >F.A. Expenses</th>
                                            <th class="text-right" >Balance</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                <?php
                                $finalTotalExtendedAmount = 0;
                                $finalTotalExpenses = 0;
                                $finalTotalFaExpenses = 0;
                                $finalTotalBalance = 0;
                                foreach ($chapterList as $lista):
                                    $jobDetails = $chapterDetails[$lista['chapter_number']] ?? false;

                                    if($jobDetails){
                                        $subTotalExtendedAmount = 0;
                                        $totalPercentage = 0;
                                        $subTotalExpenses = 0;
                                        $subTotalFaExpenses = 0;
                                        $subTotalBalance = 0;

                                        foreach ($jobDetails as $data):
                                            $faExpensesTotal = $faExpensesTotalByDetail[$data['id_job_detail']] ?? 0;
                                            if($data['unit_price'] == 0){
                                                $balance = $data['expenses'] + $faExpensesTotal;
                                            }else{
                                                $balance = $data['extended_amount'] - $data['expenses'] - $faExpensesTotal;
                                            }

                                            $subTotalExtendedAmount += $data['extended_amount'];
                                            $totalPercentage += $data['percentage'];
                                            $subTotalExpenses += $data['expenses'];
                                            $subTotalFaExpenses += $faExpensesTotal;
                                            $subTotalBalance += $balance;
                                        endforeach;


                                        $finalTotalExtendedAmount += $subTotalExtendedAmount;
                                        $finalTotalExpenses += $subTotalExpenses;
                                        $finalTotalFaExpenses += $subTotalFaExpenses;
                                        $finalTotalBalance += $subTotalBalance;
                                ?>

                                <?php
                                        echo "<tr>";
                                        echo "<td width='38%' class='text-left'>" . $lista['chapter_name'] . "</td>";
                                        echo "<td width='25%' class='text-right'>$ " . number_format($subTotalExtendedAmount,2) . "</td>";
                                        echo "<td width='12%' class='text-right'>$ " . number_format($subTotalExpenses,2) . "</td>";
                                        echo "<td width='12%' class='text-right'>$ " . number_format($subTotalFaExpenses,2) . "</td>";
                                        echo "<td width='9%' class='text-right'>$ " . number_format($subTotalBalance,2) . "</td>";
                                        echo "</tr>";
                                    }
                                endforeach;
                                echo "<tr>";
                                echo "<td width='38%' class='text-right'><b>TOTAL</b></td>";
                                echo "<td width='25%' class='text-right'><b>$ " . number_format($finalTotalExtendedAmount,2) . "</b></td>";
                                echo "<td width='12%' class='text-right'><b>$ " . number_format($finalTotalExpenses,2) . "</b></td>";
                                echo "<td width='12%' class='text-right'><b>$ " . number_format($finalTotalFaExpenses,2) . "</b></td>";
                                echo "<td width='9%' class='text-right'><b>$ " . number_format($finalTotalBalance,2) . "</b></td>";
                                echo "</tr>";
                                ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php 
                        } 
                    ?>

                </div>
            </div>
        </div>
    </div>
</div>

<!--INICIO Modal -->
<div class="modal fade text-center" id="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">    
	<div class="modal-dialog  modal-lg" role="document">
		<div class="modal-content" id="tablaDatos">

		</div>
	</div>
</div>                       
<!--FIN Modal -->



<script>

    $(function() {
        $('#btnSubir').click(function(event) {
            event.preventDefault();
            $('#btnSubir').addClass('disabled');
            $('#animationload').fadeIn();
            $('#formCargue').submit();
        });
    });

    /*
    * Function Delelete information
    */
    function deleteInformation(attachmentId, status) {
        if (window.confirm('Are you sure you want to reset all the information?')) {
            document.getElementById('formDeleteInfo').submit();
        }
    }

    function toggleCollapse(id) {
        $('#collapse' + id).collapse('toggle');
    }

    function loadClaims(idJobDetail) {
        let target = '#claims_' + idJobDetail;
        if ($(target).is(':visible')) {
            $(target).slideUp();
            return;
        }

        $.ajax({
            url: '<?php echo base_url("jobs/load_claims_view"); ?>',
            type: 'POST',
            data: { idJobDetail: idJobDetail },
            success: function(response) {
                $(target).html(response).slideDown();
            }
        });
    }


</script>