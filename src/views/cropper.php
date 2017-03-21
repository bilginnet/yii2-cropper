<?php

use yii\bootstrap\Html;
use yii\web\View;


/** @var $this View */
/** @var $cropperOptions mixed */
/** @var $inputOptions  mixed */


\bilginnet\cropper\CropperAsset::register($this);


$unique = uniqid('cropper_');


$cropWidth = $cropperOptions['width'];
$cropHeight = $cropperOptions['height'];
$aspectRatio = $cropWidth / $cropHeight;






$browseLabel = $cropperOptions['icons']['browse'] . ' ' . Yii::t('cropper', 'Browse');
$cropLabel = $cropperOptions['icons']['crop'] . ' ' . Yii::t('cropper', 'Crop');
$closeLabel = $cropperOptions['icons']['close'] . ' ' . Yii::t('cropper', 'Crop') . ' & ' . Yii::t('cropper', 'Close');

$label = $inputOptions['label'];
if ($label !== false) {
    $browseLabel = $cropperOptions['icons']['browse'] . ' ' . $label;

}

?>


<div class="cropper-container clearfix">

    <input type="text" id="<?= $inputOptions['id'] ?>" name="<?=  $inputOptions['name'] ?>" title="" class="hidden">

    <?= Html::button($browseLabel, [
        'class' => 'btn btn-primary',
        'data-toggle' => 'modal',
        'data-target' => '#cropper-modal-' . $unique,
        //'data-keyboard' => 'false',
        'data-backdrop' => 'static',
    ]) ?>

    <?php if ($cropperOptions['preview'] !== false) : ?>
        <?php $preview = $cropperOptions['preview']; ?>
        <div class="cropper-result" id="cropper-result-<?= $unique ?>" style="margin-top: 10px; width: <?= $preview['width'] ?>px; height: <?= $preview['height'] ?>px; border: 1px dotted #bfbfbf">
            <?php if (isset($preview['url'])) {
                echo Html::img($preview['url'], ['width' => $preview['width'], 'height' => $preview['height']]);
            } ?>
        </div>
    <?php endif; ?>
</div>

<?php $this->registerCss('
    label[for='.$inputOptions['id'].'] {
        display: none;
    }
    #cropper-modal-'.$unique.' img{
        max-width: 100%;
    }
    #cropper-modal-'.$unique.' .btn-file {
        position: relative;
        overflow: hidden;
    }
    #cropper-modal-'.$unique.' .btn-file input[type=file] {
        position: absolute;
        top: 0;
        right: 0;
        min-width: 100%;
        min-height: 100%;
        font-size: 100px;
        text-align: right;
        filter: alpha(opacity=0);
        opacity: 0;
        outline: none;
        background: white;
        cursor: inherit;
        display: block;
    }
    #cropper-modal-'.$unique.' .input-group .input-group-addon {
        border-radius: 0;
        border-color: #d2d6de;
        background-color: #efefef;
        color: #555;
    }
    #cropper-modal-'.$unique.' .height-warning.has-success .input-group-addon,
    #cropper-modal-'.$unique.' .width-warning.has-success .input-group-addon{
        background-color: #00a65a;
        border-color: #00a65a;
        color: #fff;
    }
    #cropper-modal-'.$unique.' .height-warning.has-error .input-group-addon,
    #cropper-modal-'.$unique.' .width-warning.has-error .input-group-addon{
        background-color: #dd4b39;
        border-color: #dd4b39;
        color: #fff;
    }
') ?>


<?php
$inputId = $inputOptions['id'];
$modal = $this->render('modal', [
    'unique' => $unique,
    'cropperOptions' => $cropperOptions,
]);



$this->registerJs(<<<JS

    $('body').prepend('$modal');

    var options_$unique = {
        croppable: false,
        croppedCanvas: '',
        
        element: {
            modal: $('#cropper-modal-$unique'),
            image: $('#cropper-image-$unique'),
            _image: document.getElementById('cropper-image-$unique'),
            result: $('#cropper-result-$unique')        
        },
        
        input: {
            model: $('#$inputId'),
            crop: $('#cropper-input-$unique')
        },
        
        button: {
            crop: $('#crop-button-$unique'),
            close: $('#close-button-$unique'),
        },
        
        data: {
            cropWidth: $cropWidth,
            cropHeight: $cropHeight,
            scaleX: 1,
            scaleY: 1,
            width: '',
            height: '',
            X: '',
            Y: ''
        },
     
        inputData: {
            width: $('#dataWidth-$unique'),
            height: $('#dataHeight-$unique'),
            X: $('#dataX-$unique'),
            Y: $('#dataY-$unique')
        }
    };
    
    
    options_$unique.input.crop.change(function(event) {
        
        
        // cropper reset
        options_$unique.croppable = false;
        options_$unique.element.image.cropper('destroy');        
        options_$unique.element.modal.find('.width-warning, .height-warning').removeClass('has-success').removeClass('has-error');
        
        
        // image loading        
        if (typeof event.target.files[0] === 'undefined') {
            options_$unique.element._image.src = "";
            return;
        }               
        options_$unique.element._image.src = URL.createObjectURL(event.target.files[0]);
        
        
        // cropper start
        options_$unique.element.image.cropper({
            aspectRatio: $aspectRatio,
            viewMode: 2,
            autoCropArea: 0.5,     
            crop: function (e) {

                options_$unique.data.width = Math.round(e.width);
                options_$unique.data.height = Math.round(e.height);
                options_$unique.data.X = e.scaleX;
                options_$unique.data.Y = e.scaleY;                                               
                
                options_$unique.inputData.width.val(Math.round(e.width));
                options_$unique.inputData.height.val(Math.round(e.height));
                options_$unique.inputData.X.val(Math.round(e.x));
                options_$unique.inputData.Y.val(Math.round(e.y));                
                
                
                if (options_$unique.data.width < options_$unique.data.cropWidth) {
                    options_$unique.element.modal.find('.width-warning').removeClass('has-success').addClass('has-error');
                } else {
                    options_$unique.element.modal.find('.width-warning').removeClass('has-error').addClass('has-success');
                }
                
                if (options_$unique.data.height < options_$unique.data.cropHeight) {
                    options_$unique.element.modal.find('.height-warning').removeClass('has-success').addClass('has-error');                   
                } else {
                    options_$unique.element.modal.find('.height-warning').removeClass('has-error').addClass('has-success');                     
                }
            }, 
            
            built: function () {
                options_$unique.croppable = true;               
            }
        });        
    });
    
    
    
    
    function setCrop$unique() {        
        if (!options_$unique.croppable) {
            return;
        }        
        options_$unique.croppedCanvas = options_$unique.element.image.cropper('getCroppedCanvas', {
            width: options_$unique.data.cropWidth,
            height: options_$unique.data.cropHeight
        });
        options_$unique.element.result.html('<img src="' + options_$unique.croppedCanvas.toDataURL() + '">');
        
        options_$unique.input.model.attr('type', 'text');
        options_$unique.input.model.val(options_$unique.croppedCanvas.toDataURL());
    }
    

    options_$unique.button.crop.click(function() { setCrop$unique(); });
    options_$unique.button.close.click(function() { setCrop$unique(); });
    $('[data-target="#cropper-modal-$unique"]').click(function() {
        var src_$unique = $('#cropper-modal-$unique').find('.modal-body').find('img').attr('src');        
        if (src_$unique === '') {
            options_$unique.input.crop.click();
        }
    });
    
  
    options_$unique.element.modal.find('.move-left').click(function() { 
        if (!options_$unique.croppable) return;
        options_$unique.element.image.cropper('move', -10, 0);
    });
    options_$unique.element.modal.find('.move-right').click(function() {
        if (!options_$unique.croppable) return;
        options_$unique.element.image.cropper('move', 10, 0);     
    });
    options_$unique.element.modal.find('.move-up').click(function() { 
        if (!options_$unique.croppable) return;
        options_$unique.element.image.cropper('move', 0, -10);      
    });
    options_$unique.element.modal.find('.move-down').click(function() { 
        if (!options_$unique.croppable) return;
        options_$unique.element.image.cropper('move', 0, 10);
    });
    options_$unique.element.modal.find('.zoom-in').click(function() {
        if (!options_$unique.croppable) return;
        options_$unique.element.image.cropper('zoom', 0.1); 
    });
    options_$unique.element.modal.find('.zoom-out').click(function() {
        if (!options_$unique.croppable) return;
        options_$unique.element.image.cropper('zoom', -0.1);         
    });
    options_$unique.element.modal.find('.rotate-left').click(function() { 
        if (!options_$unique.croppable) return;
        options_$unique.element.image.cropper('rotate', -15);
    });
    options_$unique.element.modal.find('.rotate-right').click(function() { 
        if (!options_$unique.croppable) return;
        options_$unique.element.image.cropper('rotate', 15); 
    });
    options_$unique.element.modal.find('.flip-horizontal').click(function() { 
        if (!options_$unique.croppable) return;
        options_$unique.data.scaleX = -1 * options_$unique.data.scaleX;        
        options_$unique.element.image.cropper('scaleX', options_$unique.data.scaleX);
    });
    options_$unique.element.modal.find('.flip-vertical').click(function() { 
        if (!options_$unique.croppable) return;
        options_$unique.data.scaleY = -1 * options_$unique.data.scaleY;
        options_$unique.element.image.cropper('scaleY', options_$unique.data.scaleY);
    });
    
JS
, View::POS_END) ?>
