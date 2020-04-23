<?php


namespace app\api\controller;


class Upload
{
    function upload()
    {
        // 获取表单上传文件 例如上传了001.jpg
        $file = input("file.file");
        if (empty($file)) {
            $this->error("请选择上传文件");
        }
        // 移动到框架应用根目录/public/uploads/ 目录下
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
        if ($info) {
            $name=$info->getSaveName();
            $name=str_replace("\\","/",$name);
            echo "/uploads/".$name;
        } else {
            // 上传失败获取错误信息
            echo "error";
        }

    }
}