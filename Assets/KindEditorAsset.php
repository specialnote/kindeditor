<?php

namespace KindEditor\Assets;

use yii\web\AssetBundle;

class KindEditorAsset extends AssetBundle
{

    public $css = [
        'themes/default/default.css',
        'plugins/code/prettify.css',
    ];
    public $js = [
        'kindeditor-all-min.js',
        'lang/zh-CN.js',
        'plugins/code/prettify.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];

    public function init()
    {
        $this->sourcePath = dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'kindeditor' . DIRECTORY_SEPARATOR . '4.1.11';
    }
}