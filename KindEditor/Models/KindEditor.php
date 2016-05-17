<?php

namespace KindEditor\Models;

use yii\base\Action;
use yii;
use yii\web\Response;

class KindEditor extends Action
{
    public $config;

    public function init()
    {
        $this->config['php_url'] = isset($this->config['php_url']) ? $this->config['php_url'] : '/upload/';
        $this->config['save_path'] = isset($this->config['save_path']) ? $this->config['save_path'] : Yii::$app->basePath . '/web/upload/';
        $this->config['image_ext'] = isset($this->config['image_ext']) ? $this->config['image_ext'] : ['gif', 'jpg', 'jpeg', 'png', 'bmp'];
        $this->config['max_size'] = isset($this->config['max_size']) ? $this->config['max_size'] : 1024 * 1024 * 10;

        parent::init();
    }

    public function run()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $image_ext = $this->config['image_ext'];
        $max_size = $this->config['max_size'];
        $save_path = $this->config['save_path'];
        $php_url = $this->config['php_url'];
        //检查目录
        if (false === file_exists($save_path)) {
            if (false === mkdir($save_path)) {
                return ['error' => 1, 'message' => '上传目录不存在'];
            }
            if (false === chmod($save_path, 0755)) {
                return ['error' => 1, 'message' => '更改文件权限失败'];
            }
        } else {
            if (false === is_dir($save_path)) {
                return ['error' => 1, 'message' => '上传文件夹不存在'];
            }
        }
        //检查目录写权限
        if (false === is_writable($save_path)) {
            return ['error' => 1, 'message' => '上传目录没有写权限'];
        }
        //有上传文件时
        if (count($_FILES['imgFile']) > 0) {
            if (0 !== $_FILES['imgFile']['error']) {
                return ['error' => 1, 'message' => '上传失败'];
            }
            //原文件名
            $file_name = $_FILES['imgFile']['name'];
            //服务器上临时文件名
            $tmp_name = $_FILES['imgFile']['tmp_name'];
            //文件大小
            $file_size = $_FILES['imgFile']['size'];
            //检查文件名
            if (empty($file_name)) {
                return ['error' => 1, 'message' => '请选择文件'];
            }
            //检查是否已上传
            if (false === is_uploaded_file($tmp_name)) {
                return ['error' => 1, 'message' => '上传失败'];
            }
            //检查文件大小
            if ($file_size > $max_size) {
                return ['error' => 1, 'message' => '上传文件超过限制'];
            }
            //获得文件扩展名
            $temp_arr = explode(".", $file_name);
            $file_ext = strtolower(trim(array_pop($temp_arr)));
            //检查扩展名
            if (false === in_array($file_ext, $image_ext)) {
                return ['error' => 1, 'message' => "上传文件扩展名是不允许的扩展名。\n只允许" . implode(",", $image_ext) . "格式。"];
            }
            $new_file_name = date("YmdHis") . '_' . rand(10000, 99999) . '.' . $file_ext;
            $file_path = $save_path . $new_file_name;
            if (false === move_uploaded_file($tmp_name, $file_path)) {
                return ['error' => 1, 'message' => '上传文件失败'];
            }
            @chmod($file_path, 0644);
            $file_url = $php_url . $new_file_name;
            return ['error' => 0, 'url' => $file_url];
        } else {
            return ['error' => 1, 'message' => '请选择文件'];
        }
    }
}