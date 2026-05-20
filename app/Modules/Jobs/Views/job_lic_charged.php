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

					<?php $uriSegment2 = service('request')->getUri()->getSegment(2); ?>
					<ul class="nav nav-pills">
						<li <?php if($uriSegment2 == "job_detail"){ echo "class='active'";} ?>><a href="<?php echo base_url("jobs/job_detail/" . $jobInfo[0]["id_job"]); ?>">List of Active LIC</a>
						</li>
						<li <?php if($uriSegment2 == "charged_lic" && $status == 2){ echo "class='active'";} ?>><a href="<?php echo base_url("jobs/charged_lic/" . $jobInfo[0]["id_job"] . "/2"); ?>">List of Executed LIC</a>
						</li>
						<li <?php if($uriSegment2 == "charged_lic" && $status == 3){ echo "class='active'";} ?>><a href="<?php echo base_url("jobs/charged_lic/" . $jobInfo[0]["id_job"] . "/3"); ?>">List of Closed LIC</a>
						</li>
					</ul>
                    
                    <?php
                    if($chapterList){
                        foreach ($chapterList as $lista):
                            $jobDetails = $lista['jobDetails'];

                            if($jobDetails){
                                $totalExtendedAmount = 0;
                                $totalPercentage = 0;
                                $totalExpenses = 0;
                                $totalBalance = 0;
					?>

                            <div class="panel-body">
                                <div class="panel-group" id="accordion">	
                                    <h2><?php echo $lista['chapter_name']; ?></h2>
                                    <?php
                                        foreach ($jobDetails as $data):
                                            $class = "default";
                                            if($data['unit_price'] == 0){
                                                $balance = $data['expenses'];
                                            }else{
                                                $balance = $data['extended_amount'] - $data['expenses'];

                                                $veintePorciento = $data['extended_amount'] * 0.2;
                                                $class = $balance <= $veintePorciento ? "danger" : "default";
                                            }
                                            
                                            $totalExtendedAmount += $data['extended_amount'];
                                            $totalPercentage += $data['percentage'];
                                            $totalExpenses += $data['expenses'];
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
                                                    <?php
                                                        echo "</td>";
                                                        echo "<td width='42%'><p class='text-" . $class . "'><b>Description</b><br>" . $data['description'] . "</p></td>";
                                                        echo "<td width='4%' class='text-center'><p class='text-" . $class . "'><b>Unit</b><br>" . $data['unit'] . "</p></td>";
                                                        echo "<td width='8%' class='text-center'><p class='text-" . $class . "'><b>Quantity</b><br>" . $data['quantity'] . "</p></td>";
                                                        echo "<td width='8%' class='text-right'><p class='text-" . $class . "'><b>Unit Price</b><br>$ " . number_format($data['unit_price'],2) . "</p></td>";
                                                        echo "<td width='10%' class='text-right'><p class='text-" . $class . "'><b>Extended Amount</b><br>$ " . number_format($data['extended_amount'],2) . "</p></td>";
                                                        echo "<td width='5%' class='text-right'><p class='text-" . $class . "'><b>%</b><br>" . $data['percentage'] . " %</p></td>";
                                                        echo "<td width='10%' class='text-right'><p class='text-" . $class . "'><b>W.O. Expenses</b><br>$ " . number_format($data['expenses'],2) . "</p></td>";
                                                        echo "<td width='9%' class='text-right'><p class='text-" . $class . "'><b>Balance</b><br>$ " . number_format($balance,2) . "</p></td>";
                                                        echo "</tr>";
                                                    ?>
                                                    </table>
                                                </h4>
                                            </div>
                                        </div>
                                    <?php
                                        endforeach;
                                    ?>
                                </div>
                            </div>
                    <?php 
                            }
                        endforeach;
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