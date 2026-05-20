<?php
$hiddenName = $hiddenName ?? 'image';
$width  = $width ?? '100%';
$height = $height ?? '50vh';

$buttonText = $buttonText ?? 'Draw Map';
$id = $id ?? null;
$uid = !empty($id) ? 'map_' . $id : uniqid('map_');

$imageUrl = $imageUrl ?? null;
$formAction = $formAction ?? '#';
$extraValue = $extraValue ?? null;
$otherValue = $otherValue ?? null;
?>

<div class="map-draw-panel">

    <div class="text-center mb-2">

        <?php if(!empty($savedImage)): ?>
            <button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#mapViewModal_<?= $uid ?>">
                View Map
            </button>

            <div id="mapViewModal_<?= $uid ?>" class="modal fade">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-body text-center">
                            <img src="<?= base_url($savedImage) ?>" style="max-width:100%;">
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#mapModal_<?= $uid ?>">
            <?= esc($buttonText) ?>
        </button>
    </div>
</div>

<!-- Modal -->
<div id="mapModal_<?= $uid ?>" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <form id="mapForm_<?= $uid ?>" method="post" action="<?= $formAction ?>">

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
                    <h4 class="modal-title">Sketch or Diagram</h4>
                </div>

                <div class="modal-body text-center">
                    <canvas id="mapCanvas_<?= $uid ?>"
                        style="border:1px solid #ccc; width:<?= $width ?>; height:<?= $height ?>;">
                    </canvas>

                    <input type="hidden" name="<?= $hiddenName ?>" id="mapInput_<?= $uid ?>">
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" id="clearMap_<?= $uid ?>">Clear</button>
                    <button type="submit" class="btn btn-success">Save</button>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function(){

    var canvas = document.getElementById('mapCanvas_<?= $uid ?>');
    var form   = document.getElementById('mapForm_<?= $uid ?>');
    var input  = document.getElementById('mapInput_<?= $uid ?>');
    var clear  = document.getElementById('clearMap_<?= $uid ?>');

    var signaturePad = null;
    var backgroundImage = null;

    function resizeCanvas() {
        var ratio = Math.max(window.devicePixelRatio || 1, 1);

        const width = canvas.offsetWidth;
        const height = canvas.offsetHeight;

        canvas.width = width * ratio;
        canvas.height = height * ratio;

        canvas.getContext("2d").setTransform(ratio, 0, 0, ratio, 0, 0);

        drawBackground();
    }

    function drawBackground() {
        if (backgroundImage) {
            const ctx = canvas.getContext("2d");
            ctx.drawImage(backgroundImage, 0, 0, canvas.width, canvas.height);
        }
    }

    $(document).on('shown.bs.modal', '#mapModal_<?= $uid ?>', function () {

        resizeCanvas();

        if (!signaturePad) {
            signaturePad = new SignaturePad(canvas, {
                penColor: "blue"
            });
        }

        signaturePad.clear();
        drawBackground();
    });

    <?php if(!empty($imageUrl)): ?>
        backgroundImage = new Image();
        backgroundImage.src = "<?= base_url($imageUrl) ?>";
    <?php endif; ?>

    if (clear) {
        clear.addEventListener('click', function(){
            if(signaturePad){
                signaturePad.clear();
                drawBackground();
            }
        });
    }

    if (form) {
        form.addEventListener('submit', function(){
            if(signaturePad){
                input.value = signaturePad.toDataURL();
            }
        });
    }

});
</script>