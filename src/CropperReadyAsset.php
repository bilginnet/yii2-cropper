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
class CropperReadyAsset extends AssetBundle
{
    public $sourcePath = '@vendor/npm-asset';
    public $jsOptions = ['position' => View::POS_READY];
    public $css = [
        'cropperjs/dist/cropper.css',
    ];
    public $js = [
        'cropperjs/dist/cropper.js',
        'jquery-cropper/dist/jquery-cropper.js',
    ];
    public $depends = [
        YiiAsset::class,
        BootstrapAsset::class,
        BootstrapPluginAsset::class,
    ];
}