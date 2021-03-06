<?php


namespace app\api\controller;


use app\model\StudentModel;
use think\controller\Rest;
use think\Db;

class Student extends Rest
{
    public function student()
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
        $data = input('get.');
        if (isset($data['id'])) {
            $result = StudentModel::get($data["id"]);
            return json(["msg" => "获取成功", "code" => 200, "data" => $result]);
        }

        $page = $data['page'];
        $pageSize = $data['pageSize'];
        $where = [];

        if (isset($data["name"])) {
            $name = $data["name"];
            $where["name"] = ["like", "%$name%"];
        }

        if (isset($data["grade"])) {
            $grade = $data["grade"];
            $where["grade"] = ["like", "%$grade%"];
        }

        if (isset($data["class"])) {
            $class = $data["class"];
            $where["class"] = ["like", "%$class%"];
        }


        if (isset($page) && isset($pageSize)) {
            $start = ($page - 1) * $pageSize;
            $result = Db::view("student", "id,studentid,name,tel,class,grade,mother,mtel,father,ftel")
                ->where($where)
                ->order("id")
                ->limit($start, $pageSize)
                ->select();
            $total = StudentModel::where($where)->count();
            $t = StudentModel::where($where)->select();
            return json(["msg" => "获取成功", "code" => 200, "data" => $result, "total" => $total]);
        }
    }

    private function post()
    {
        $data = input("post.");
        $obj = new StudentModel();
//        $res=$obj->where("name",$data["name"])->find();
//        if(isset($res)){
//            return json(["msg"=>"已存在当前分类","code"=>400]);
//        }
        $r = $obj->allowField(true)->save($data);
        if ($r) {
            return json(["msg" => "提交成功", "code" => 200]);
        } else {
            return json(["msg" => "提交失败", "code" => 400]);
        }
    }

    private function put()
    {
        $data = input("put.");
        if (isset($data["id"])) {
            $obj = StudentModel::get($data["id"]);
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
        $r = StudentModel::destroy($data["id"]);
        if ($r) {
            return json(["msg" => "删除成功", "code" => 200]);
        } else {
            return json(["msg" => "删除失败", "code" => 400]);
        }
    }
}