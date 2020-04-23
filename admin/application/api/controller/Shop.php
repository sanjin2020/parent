<?php


namespace app\api\controller;


use app\model\ShopModel;
use think\controller\Rest;

class Shop extends  Rest
{
    public function shop(){
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
    private  function get()
    {
        $data=input("get.");
        $where=[];
        //添加过滤条件
        if (isset($data["state"])) {
            $where["state"] = $data["state"];
        }
        if(isset($data["type"])){
            $where["type"]=$data["type"];
        }
        if(isset($data["search"])&&$data["search"]!==""){
            $search=$data["search"];
            $where["name"]=["like","%$search%"];//模糊查询
        }

        //添加截取条件
        //通过模型查询数据
        if (isset($data["page"]) && isset($data["pageSize"])) {
            $page = $data["page"];
            $pageSize = $data["pageSize"];
            $start = ($page - 1) * $pageSize;
            $r = ShopModel::where($where)->limit($start, $pageSize)->order("id","desc")->select();
            $total = ShopModel::where($where)->count();
            return json(["code" => 200, "msg" => "获取成功", "data" => $r,"total"=>$total]);
        }
        if(isset($data["state"])&&isset($data["quit"])){
            $where["quit"]=1;
            $result=ShopModel::where($where)->select();
            return json(["msg"=>"获取成功","code"=>200,"data"=>$result]);
        }
        if(isset($data["id"])){
            $result=ShopModel::get($data["id"]);
            return json(["msg"=>"获取成功","code"=>200,"data"=>$result]);
        }
    }
    private  function post()
    {
        $data=input("post.");
        $obj=new ShopModel;
        if(!isset($data["name"])){
            return json(["msg"=>"请提交店铺的名称","code"=>401]);
        }
        $res=$obj->where("name",$data["name"])->find();
        if(isset($res)){
            if($res->state===1){
                return json(["msg"=>"当前店铺正在被审核","code"=>402]);
            }else{
                return json(["msg"=>"当前店铺已经审核通过,请根据管理员提供的账号密码登陆","code"=>403]);
            }
        }
        $r=$obj->allowField(true)->save($data);
        if($r){
            return json(["msg"=>"提交成功","code"=>200]);
        }else{
            return json(["msg"=>"提交失败","code"=>400]);
        }
    }
    private  function put(){
        $data=input("put.");
        if(isset($data["id"])){
            $obj=ShopModel::get($data["id"]);
            $r=$obj->allowField(true)->save($data);
            if($r){
                return json(["msg"=>"修改成功","code"=>200]);
            }else{
                return json(["msg"=>"修改失败","code"=>400]);
            }
        }else{
            return json(["msg"=>"修改失败","code"=>400]);
        }
    }
    private  function delete(){}
}