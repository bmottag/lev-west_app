<div class="panel panel-violeta">
    <div class="panel-heading">
        <i class="fa fa-wrench"></i> <strong>Parts by Store</strong>
    </div>
    <div class="panel-body small">

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
            if(!$infoPartsByStore){ 
                echo '<div class="col-lg-12">
                        <p class="text-danger"><span class="glyphicon glyphicon-alert" aria-hidden="true"></span> There are no records in the system.</p>
                    </div>';
            } else {
        ?>
        <table width="100%" class="table table-striped table-bordered table-hover" id="dataPreventiveMaintenance">
            <thead>
                <tr>
                    <th class="text-center">Description</th>
                    <th class="text-center">Shop Name</th>
                    <th class="text-center">Contact</th>
                    <th class="text-center">Address</th>
                    <th class="text-center">Mobile number</th>
                    <th class="text-center">Email</th>
                </tr>
            </thead>
            <tbody>							
            <?php
                foreach ($infoPartsByStore as $lista):
                    echo "<tr>";
                    echo "<td>" . $lista['part_description'] . "</td>";
                    echo "<td>" . $lista['shop_name'] . "</td>";
                    echo "<td>" . $lista['shop_contact'] . "</td>";
                    echo "<td>" . $lista['shop_address'] . "</td>";
                    echo "<td>" . $lista['mobile_number'] . "</td>";
                    echo "<td>" . $lista['shop_email'] . "</td>";
                    echo "</tr>";
                endforeach;
            ?>
            </tbody>
        </table>
    <?php } ?>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#dataPreventiveMaintenance').DataTable({
        responsive: true,
		"ordering": false,
		paging: false,
		"searching": false,
		"info": false
    });
});
</script>