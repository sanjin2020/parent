<?php


namespace app\api\controller;


use app\model\AdminModel;
use app\model\RoleAdmin;
use think\captcha\Captcha;
use think\controller\Rest;

class Admin extends Rest
{
//    function createHash(){
//        return md5(time());
//    }
//
    private function createPassword($pass, $hash)
    {
        //加盐 salt
        return md5(sha1($pass) . $hash);
    }

//    function captcha()
//    {
//        $config = [
//            // 验证码字体大小
//            'fontSize' => 30,
//            // 验证码位数
//            'length' => 4,
//            //关闭杂点
//            'usenoise' => true,
//        ];
//        $captcha = new Captcha($config);
//        return $captcha->entry();
//    }
    public function captcha()
    {
        $config = [
            // 验证码字体大小
            'fontSize' => 30,
            // 验证码位数
            'length' => 4,
        ];
        $captcha = new Captcha($config);
        return $captcha->entry();
    }

    function login()
    {
        $username = input("post.username");
        $password = input("post.password");
        $code = input("post.captcha");
        $captcha = new Captcha();
        if (!$captcha->check($code)) {
            return json(["msg" => "验证码错误", "code" => 400]);
        }
        $r = AdminModel::where("username", $username)->find();
//        dump($r->password);
//        dump($r->hahttp://localhost:8080/#/login);
        if (isset($r)) {
            $pass = $this->createPassword($password, $r->hash);
            if ($pass === $r->password) {
                $route = RoleAdmin::where("role",$r->role)->find()->route;
                $r->save(["last_login_time"=>date("Y-m-d H:i:s")]);
//                $shopid=$r->shopid;
//                return json(["msg" => "登陆成功", "code" => 200,"route" => $route,"shopid"=>$shopid]);
                  return json(["msg" => "登陆成功", "code" => 200,"route" => $route,]);

            } else {
                return json(["msg" => "登陆失败", "code" => 400]);
            }
        } else {
            return json(["msg" => "登陆失败", "code" => 400]);
        }

    }

    public function password()
    {
        $data = input("put."); //接受通过put提交的内容
        $r = AdminModel::where("username", $data['username'])->find(); //
        if (isset($r)) {
            $pass = $this->createPassword($data["password"], $r->hash);
            if ($pass === $r->password) {
                $newpassword = $this->createPassword($data["newpassword1"], $r->hash);
                $res = $r->save(["password" => $newpassword]);
                if ($res) {
                    return json(["msg" => "修改成功", "code" => 200]);
                } else {
                    return json(["msg" => "修改失败", "code" => 400]);
                }
            } else {
                return json(["msg" => "原始密码错误", "code" => 400]);
            }
        } else {
            return json(["msg" => "用户名错误", "code" => 400]);
        }

    }

    public function manager()
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
        $where = [];

        //添加过滤条件
        if (isset($data["role"])) {
            $where["role"] = ["<>",1];
        }

        if(isset($data["search"])&&$data["search"]!==""){
            $search=$data["search"];
            $where["username"]=["like","%$search%"];//模糊查询
        }

        //添加截取条件
        //通过模型查询数据
        if (isset($data["page"]) && isset($data["pageSize"])) {
            $page = $data["page"];
            $pageSize = $data["pageSize"];
            $start = ($page - 1) * $pageSize;
            $r = AdminModel::where($where)->limit($start, $pageSize)->select();
            $total = AdminModel::where($where)->count();
            return json(["code" => 200, "msg" => "获取成功", "data" => $r,"total"=>$total]);
        }

    }

    private function post()
    {
        $data=input("post.");
        $obj = new AdminModel();
//        dump($data["username"]);
        if(isset($data["username"])){
            $r=AdminModel::where("username",$data["username"])->find();
            if(isset($r)){
                return json(["msg" => "已存在该管理员", "code" => 400]);
            }
            $obj->username = $data["username"];
            $obj->role=$data["role"];
            $obj->last_login_time=date("Y-m-d H:i:s");
            $obj->hash=md5(time());
            $obj->password=$this->createPassword("123456",$obj->hash);
//            if(isset($data["shopid"])){
//              $obj->shopid=$data["shopid"];
//            };
            $r=$obj->save();
            if($r){
                return json(["msg" => "添加成功", "code" => 200]);
            }else{
                return json(["msg" => "添加失败", "code" => 400]);
            }
        }else{
            return json(["msg" => "请输入管理员名称", "code" => 400]);
        }
    }

    private function put()
    {
        $data=input("put.");
        if(isset($data["id"])){
            $obj=AdminModel::get($data["id"]);
            if(isset($data["password"])){
                $data["password"]=$this->createPassword($data["password"],$obj->hash);
            }
            $r=$obj->allowField(true)->save($data);
            if($r){
                return json(["msg" => "修改成功", "code" => 200]);

            }else{
                return json(["msg" => "修改失败", "code" => 400]);
            }
        }else{
            return json(["msg" => "修改失败", "code" => 400]);

        }
    }

    private function delete()
    {

//        dump($data);
//        $r=AdminModel::destroy($data["id"]);
//        $r=$data;
        $data=input("get.");
        $r=AdminModel::destroy($data["id"]);
        if ($r) {
            return json(["msg" => "删除成功", "code" => 200]);
        } else {
            return json(["msg" => "删除失败", "code" => 400]);
        }
    }
}