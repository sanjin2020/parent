<?php


namespace app\api\controller;


use app\model\GoodsModel;
use think\controller\Rest;
use think\Db;

class Goods extends Rest
{
    public function goods(){
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
        $data=input('get.');
        if(isset($data['id'])){
            $result=GoodsModel::get($data["id"]);
            return json(["msg"=>"获取成功","code"=>200,"data"=>$result]);
        }

        $page=$data['page'];
        $pageSize=$data['pageSize'];
        $where=[];
        if(isset($data["sid"])){
            $where["sid"]=$data["sid"];
        }

        if(isset($data["name"])){
            $name=$data["name"];
            $where["name"]=["like","%$name%"];
        }

        if(isset($data["price_low"])&&isset($data["price_high"])){
            $low = $data["price_low"];
            $high = $data["price_high"];
            $where["price"] = ["between",[$low,$high]];
        }elseif (isset($data["price_high"])){
            $high = $data["price_high"];
            $where["price"] = ["<=",$high];
        }elseif (isset($data["price_low"])){
            $low = $data["price_low"];
            $where["price"] = ["<=",$low];
        }

        if(isset($data["state"])){
            $where["state"]=1;
        }

        if(isset($data["audit"])){
            if($data["audit"]==0){
                $where['audit']=['<>',1];
            }else{
                $where["audit"]=$data["audit"];
            }
        }

        if(isset($page)&&isset($pageSize)){
            $start=($page-1)*$pageSize;
            if(isset($data["storage"])){
            $result=Db::view("goods","id,name,price,cid,state,audit,storage,storage_alert")
                    ->view("category",["name"=>"catname"],"goods.cid=category.id")
                    ->where($where)
                    ->where("goods.storage<goods.storage_alert")
                    ->limit($start,$pageSize)
                    ->select();
                $total=GoodsModel::where($where)->where("goods.storage<goods.storage_alert")->count();
            }else{
                $result=Db::view("goods","id,name,price,cid,state,audit,storage,storage_alert")
                    ->view("category",["name"=>"catname"],"goods.cid=category.id")
                    ->view("shop",["name"=>"shop"],"goods.cid=shop.id")
                    ->where($where)
                    ->limit($start,$pageSize)
                    ->select();
                $total=GoodsModel::where($where)->count();
                $t=GoodsModel::where($where)->select();
            }
            return json(["msg"=>"获取成功","code"=>200,"data"=>$result,"total"=>$total]);
        }
    }

    private function post()
    {
        $data=input("post.");
        $obj=new GoodsModel();
//        $res=$obj->where("name",$data["name"])->find();
//        if(isset($res)){
//            return json(["msg"=>"已存在当前分类","code"=>400]);
//        }
        $r=$obj->allowField(true)->save($data);
        if($r){
            return json(["msg"=>"提交成功","code"=>200]);
        }else {
            return json(["msg" => "提交失败", "code" => 400]);
        }
    }

    private function put()
    {
        $data=input("put.");
        if(isset($data["id"])){
            $obj=GoodsModel::get($data["id"]);
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

    private function delete()
    {
        $data=input("get.");
        $r=GoodsModel::destroy($data["id"]);
        if ($r) {
            return json(["msg" => "删除成功", "code" => 200]);
        } else {
            return json(["msg" => "删除失败", "code" => 400]);
        }
    }
}