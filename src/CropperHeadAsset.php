<?php

namespace bilginnet\cropper;


use yii\web\AssetBundle;
use yii\web\View;
use yii\web\YiiAsset;
use yii\bootstrap\BootstrapAsset;
use yii\bootstrap\BootstrapPluginAsset;

/**
 * @author Ercan Bilgin <bilginnet@gmail.com>
 */
class CropperHeadAsset extends AssetBundle
{
    public $sourcePath = '@vendor/npm-asset/cropperjs/dist';
    public $jsOptions = ['position' => View::POS_HEAD];
    public $css = [
        'cropper.css',
    ];
    public $js = [
        'cropper.js'
    ];
    public $depends = [
        YiiAsset::class,
        BootstrapAsset::class,
        BootstrapPluginAsset::class,
    ];
}