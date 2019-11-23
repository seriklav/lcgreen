<?php

namespace app\assets;

use yii\web\AssetBundle;

class GreenAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/libs.min.css',
        'css/style.css?v=1311',
        'css/hexagon.css?v=1311',
    ];
    public $js = [
        'js/libs.min.js',
	    'js/main.js?v= 12',
//        'js/jquery.min.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}

