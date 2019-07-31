<?php
namespace app\index\controller;
use Request;
use Db;
class Goodsp extends Common
{
    // 列表方法
    public function list()
    { 
      $id=Request::get('id');
      $cate_id = Request::get('cate_id');
      $this->assign('g_id',$id);
      $this->assign('c_id',$cate_id);
      return $this->fetch();
    }
    public function add()
    { 
      $g_id=Request::get('g_id');
      $c_id=Request::get('c_id');
      $this->assign('g_id',$g_id);
      $this->assign('c_id',$c_id);
      return $this->fetch();
    }

    public function attrshow()
    { 
      $goods_id=Request::get('goods_id');
      $goodsp=Db::query("select * from goodsp where goods_id='$goods_id");
      for ($i=0; $i < count($goodsp); $i++) { 
        $new_arr=[];
        $attr_id=$goodsp[$i]['goods_attr_id'];
        $all_attr_id=explode("-", $attr_id);
        for ($j=0; $j < count($all_attr_id); $j++) { 
          $details_id=$goodsp[$i]['goods_attr_id'];
          $details=Db::query("select * from attr_details where id='$details_id'");
          $new_arr[]=$details[0]['name'];
        }
        $str=implode("-", $new_arr);
        $goodsp[$i]['attr_name']=$str;
      }
      $json=['code'=>'0','status'=>'ok','data'=>$goodsp];
      // return json($json)$goods_id=Request::get('goods_id');
      return $json;
    }
    public function addAction()
    {
      $data=Request::post();
      $g_id=$data['g_id'];
      $new_arr=$data['new_arr'];
      $price=$data['price'];
      $number=$data['number'];
      $attr_id=implode("-",$new_arr);
      $arr=Db::query("select * from goodsp where goods_attr_id='$attr_id'");
      if (empty($arr)) {
        Db::query("insert into goodsp(`goods_id`,`goods_attr_id`,`price`,`number`)values('$g_id','$attr_id','$price','$number')");
        $json=['code'=>'1','status'=>'ok','data'=>'添加成功'];
       
      }else{
        $json=['code'=>'0','status'=>'error','data'=>'货品名字不能重复！'];
      }
      return json($json);
    }
    //修改方法
    // public function show()
    // { 
    //  return $this->fetch();
    // }
  }