<?php
namespace app\index\controller;
use Request;
use Db;
class Goodsattr extends Common
{
    // 列表方法
    public function list()
    { 
      $goods_id=Request::get('id');
      $this->assign('goods_id',$goods_id);
      return $this->fetch();
    }
    public function show()
    {
      $id=Request::get('attrcate_id');
      $goods_id=Request::get('goods_id');
      $arr=Db::query("select a.id,a.name,ad.id as ad_id,ad.name as ad_name from attribute as a
        join attr_details as ad on a.id=ad.attr_id where attrcate_id='$id'");
       $newarr=[];
        foreach ($arr as $key => $value) {
           $newarr[$value['name']][]=$value;
        }
       $sql= Db::query("select * from goods_attr where goods_id='$goods_id'");
        
      $json=['code'=>'0','status'=>'ok','data'=>$newarr,'default'=>$sql];
      return json($json);
    }
     public function attrcateshow()
    {
      $arr=Db::query("select * from attr_category");
      $json=['code'=>'0','status'=>'ok','data'=>$arr];
      return json($json);
    }

    // 添加方法
    public function addAction()
    {
      $id=Request::post('arr');
      $attrcate_id=Request::post('attrcate_id');
      $goods_id=Request::post('goods_id');
      Db::table('goods_attr')->where('goods_id',$goods_id)->delete();
      if ($id!=NULL) {
       foreach ($id as $key => $value) {
         $arr=explode('-', $value);
         $arr1=$arr[0];
         $arr2=$arr[1];        
         Db::query("insert into goods_attr(`goods_id`,`attr_details_id`,`attr_id`)value('$goods_id','$arr2','$arr1')");
        }
        Db::table("goods")->where('id',$goods_id)->update(['attrcate_id'=>$goods_id]);
        $json=['code'=>'0','status'=>'ok','data'=>'添加成功'];
        return json($json);
      }else{
        $json=['code'=>'1','status'=>'error','data'=>'添加失败'];
        return json($json);
      }
    }
  }