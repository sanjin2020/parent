<?php


namespace app\api\controller;


use app\model\CategoryModel;
use app\model\CclassModel;
use app\model\GradeModel;
use app\model\StudentModel;
use think\controller\Rest;

class Category extends Rest
{
    public function category()
    {
        //判断当前请求方法
        switch ($this->method) {
            case"get":
                return $this->get();
            case"post":
                return $this->post();
            case"put":
                return $this->put();
            case"delete":
                return $this->delete();
        }
    }

    private function get()
    {
        $data = input("get.");
        if (isset($data["id"])) {
            $result = CategoryModel::get($data["id"]);
        } else {
            $result = new CategoryModel();
            $result = $result->order("id")->select();
        }
        return json(["msg" => "获取成功", "code" => 200, "data" => $result]);
    }

    private function post()
    {
        $data = input("post.");
        $obj = new CategoryModel();
        $res = $obj->where("name", $data["name"])->find();
        if (isset($res)) {
            return json(["msg" => "已存在当前分类", "code" => 400]);
        }

        if ($data['g_number']) {
            $g = new GradeModel();
            $g->allowfield(true)->save($data);
            $x = "g_number";
        } else {
            $c = new CclassModel();
            $c->allowfield(true)->save($data);
            $x = "c_number";
        }

        $r = $obj->allowField(true)->save($data);
        if ($r) {
            return json(["msg" => "提交成功", "code" => 200, "x" => "$x"]);
        } else {
            return json(["msg" => "提交失败", "code" => 400, "x" => "$x"]);
        }
    }

    private function put()
    {
        $data = input("put.");
        if (isset($data["id"])) {
            $obj = CategoryModel::get($data["id"]);
            $r = $obj->allowField(true)->save($data);
            if ($r) {
                return json(["msg" => "修改成功", "code" => 200]);
            } else {
                return json(["msg" => "修改失败", "code" => 400]);
            }
        } else {
            return json(["msg" => "修改失败", "code" => 400]);
        }
    }

    private function delete()
    {
        $data = input("get.");
        $count = CategoryModel::where("pid", $data["id"])->count();
        if ($count === 0) {
            $r = CategoryModel::destroy($data["id"]);
            if ($r) {
                return json(["msg" => "删除成功", "code" => 200]);
            } else {
                return json(["msg" => "删除失败", "code" => 400]);
            }
        } else {
            $r1 = CategoryModel::destroy($data["id"]);
            $r2 = CategoryModel::Where("pid", $data["id"])->delete();
            GradeModel::Where("time", $data["time"])->delete();
            foreach ($data["arr"] as $value) {
                CclassModel::Where("class", $value)->delete();
                StudentModel::Where("class",$value)->delete();
            }
            if ($r1 && $r2) {
                return json(["msg" => "删除成功", "code" => 200]);
            } else {
                return json(["msg" => "删除失败", "code" => 400]);
            }
        }

    }

}