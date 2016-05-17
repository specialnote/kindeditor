<?php

namespace KindEditor\Widgets;

use KindEditor\Assets\KindEditorAsset;
use yii\base\InvalidConfigException;
use \yii\bootstrap\Widget;
use yii\helpers\Html;

class KindEditor extends Widget
{
    public $model;
    public $attribute;
    public $name;
    public $id;
    public $uploadPath;
    public $class;

    private $html;

    public function init()
    {
        if ($this->model && $this->attribute) {
            $model = $this->model;
            $attribute = $this->attribute;
            if ($model->$attribute) {
                $name = $model->$attribute;
            } else {
                $name = $this->name;
            }
        } else {
            $name = $this->name;
        }
        $uploadPath = $this->uploadPath;
        $id = $this->id;
        if (!$uploadPath || !$name || !$id) {
            throw new InvalidConfigException('缺少参数');
        }

        $csrf = \Yii::$app->request->csrfToken;
        KindEditorAsset::register($this->view);
        $this->view->registerJs(<<<JS
            KindEditor.ready(function(K) {
                var editor1 = K.create('#'+"$id", {
                    items:[ 'fontname', 'fontsize',  'forecolor', 'hilitecolor', 'bold', 'italic', 'underline', 'removeformat', '|','wordpaste', '|', 'justifyleft', 'justifycenter', 'justifyright','justifyfull', 'insertorderedlist','insertunorderedlist', '|', 'image', 'table', 'link', 'unlink'],
                    uploadJson :"$uploadPath",//指定上传文件的服务器端程序
                     extraFileUploadParams : {
                       _csrf:"$csrf"
                     }
                });
                prettyPrint();
            });
JS
);
        $this->html = Html::textarea($name,null,['id' => $id, 'class' => $this->class]);
    }

    public function run()
    {
        return $this->html;
    }
}