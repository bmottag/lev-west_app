<?php
/**
 * Componente de captura de firma reutilizable.
 */

$hiddenName = $hiddenName ?? 'image';
$width  = $width ?? '100%';
$height = $height ?? '30vh';

$showAlert = $showAlert ?? false;
$alertText = $alertText ?? 'The signature is personal and must be authorized with user credentials.';
$signButtonText = $signButtonText ?? 'Sign';
$id = $id ?? null;
$uid = !empty($id) ? 'sign_' . $id : uniqid('sign_');
$extraValue = $extraValue ?? null;
$otherValue = $otherValue ?? null;
?>

<div class="signature-panel">

    <?php if($showAlert): ?>
        <div class="alert alert-info">
            <?= esc($alertText) ?>
        </div>
    <?php endif; ?>

    <div class="text-center mb-2">

        <?php if(!empty($imageUrl)): ?>
            <button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#signatureViewModal_<?= $uid ?>">
                <span class="glyphicon glyphicon-eye-open"></span> View Signature
            </button>

            <div id="signatureViewModal_<?= $uid ?>" class="modal fade" role="dialog">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">×</button>
                            <h4 class="modal-title">Current Signature</h4>
                        </div>
                        <div class="modal-body text-center">
                            <img src="<?= base_url($imageUrl) ?>" class="img-rounded" style="max-width:100%;">
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#signaturePadModal_<?= $uid ?>">
            <span class="glyphicon glyphicon-edit"></span> <?= esc($signButtonText) ?>
        </button>
    </div>
</div>

<!-- Modal -->
<div id="signaturePadModal_<?= $uid ?>" class="modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">

            <form id="signatureForm_<?= $uid ?>" method="post" action="<?= $formAction ?>">

                <?php if (!empty($id)): ?>
                    <input type="hidden" name="id" value="<?= esc($id) ?>">
                <?php endif; ?>
                <?php if (!empty($extraValue)): ?>
                    <input type="hidden" name="extraValue" value="<?= esc($extraValue) ?>">
                <?php endif; ?>
                <?php if (!empty($otherValue)): ?>
                    <input type="hidden" name="otherValue" value="<?= esc($otherValue) ?>">
                <?php endif; ?>

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h4 class="modal-title">Sign here</h4>
                </div>

                <div class="modal-body text-center">    
                    <canvas id="signatureCanvas_<?= $uid ?>"
                        style="border:1px solid #ccc; width:<?= $width ?>; height:<?= $height ?>; visibility:hidden;">
                    </canvas>

                    <input type="hidden" name="<?= $hiddenName ?>" id="signatureInput_<?= $uid ?>">
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" id="clearSignature_<?= $uid ?>">Clear</button>
                    <button type="submit" class="btn btn-success">Save</button>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function(){

    var canvas = document.getElementById('signatureCanvas_<?= $uid ?>');
    var form   = document.getElementById('signatureForm_<?= $uid ?>');
    var input  = document.getElementById('signatureInput_<?= $uid ?>');
    var clear  = document.getElementById('clearSignature_<?= $uid ?>');

    var signaturePad = null;

    canvas.style.touchAction = "none";

    function resizeCanvas() {
        var ratio = Math.max(window.devicePixelRatio || 1, 1);

        const width = canvas.offsetWidth;
        const height = canvas.offsetHeight;

        canvas.width = width * ratio;
        canvas.height = height * ratio;

        canvas.getContext("2d").setTransform(ratio, 0, 0, ratio, 0, 0);
    }

    $(document).on('shown.bs.modal', '#signaturePadModal_<?= $uid ?>', function () {

        resizeCanvas();

        if (!signaturePad) {
            signaturePad = new SignaturePad(canvas);
        } else {
            signaturePad.clear();
        }

        canvas.style.visibility = 'visible';
    });

    $('#signaturePadModal_<?= $uid ?>').on('hidden.bs.modal', function () {
        canvas.style.visibility = 'hidden';
    });

    if (clear) {
        clear.addEventListener('click', function(){
            if(signaturePad){
                signaturePad.clear();
            }
        });
    }

    if (form) {
        form.addEventListener('submit', function(e){

            if(!signaturePad || signaturePad.isEmpty()){
                e.preventDefault();
                alert('Please provide a signature first.');
                return;
            }

            input.value = signaturePad.toDataURL();
        });
    }

});
</script>