## KindEditor for Yii2

1. 说明
2. 使用
2.1 在视图文件中

  ```
  <?= \KindEditor\Widgets\KindEditor::widget([
    'id' => 'content',//文本域ID
    'name' => 'content',//文本域名称
    'uploadPath' => \yii\helpers\Url::to(['/dir/test/upload']),//图片上传地址
  ])?>
  ```
2.2 在控制器中 

  ```php
    public function actions(){
        return [
            'upload'=>[
                'class' => 'KindEditor\Models\KindEditor',
                'config' => [
                    'save_path' => \Yii::$app->basePath.'/web/upload/',
                    'php_url' => '/upload/',//图片访问地址前部分，url = php_url.$file_name.'.'.$ext
                ]
            ]
        ];
    }
  ```
