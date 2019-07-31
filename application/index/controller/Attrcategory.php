<?php
namespace app\index\controller;
use Request;
use Db;
class Attrcategory extends Common
{
    // 列表方法
    public function list()
    { 
      return $this->fetch();
    }
    public function attribute()
    { 
      $id=Request::get('id');
      $this->assign('id',$id);
      return $this->fetch();
    }
    public function attrdetails()
    { 
      $id=Request::get('id');
      $this->assign('id',$id);
      return $this->fetch();
    }
    public function show()
    {
      $arr=Db::query("select * from attr_category");
      $json=['code'=>'0','status'=>'ok','data'=>$arr];
      return json($json);
    }
    // 添加方法
    public function addAction()
    {
      // $validate = new \app\index\validate\Attribute;
      //   if (!$validate->check($data)) {
      //       $arr=['code'=>'1','status'=>'error','data'=>$validate->getError()];
      //       return json($arr);
      //   }
      $name=Request::post('name');
      // $name=$data['name'];
      $arr=Db::query("select * from attr_category where name='$name'");

      if (empty($arr)) {
        $arr=Db::query("insert into `attr_category`(`name`)values('$name')");
        $json=['code'=>'0','status'=>'ok','data'=>'添加成功'];
        echo json_encode($json);
      }else{
        $json=['code'=>'1','status'=>'error','data'=>'添加失败'];
        echo json_encode($json);
      }
    }
    public function attrshow()
    {
      $data=Request::post('attrcate_id');
      $arr=Db::query("select * from attribute where attrcate_id='$data'");
      $json=['code'=>'0','status'=>'ok','data'=>$arr];
      return json($json);
    }
    public function adda()
    {
      $data=Request::post();
      $name=$data['name'];
      $attrcate_id=$data['attrcate_id'];
      $arr=Db::query("select * from attribute where name='$name' and attrcate_id='$attrcate_id'");
      if (empty($arr)) {
        $arr=Db::query("insert into `attribute`(`name`,`attrcate_id`)values('$name','$attrcate_id')");
        $json=['code'=>'0','status'=>'ok','data'=>'添加成功'];
        echo json_encode($json);
      }else{
        $json=['code'=>'1','status'=>'error','data'=>'添加失败'];
        echo json_encode($json);
      }
    }
    public function addshow()
    {
      $data=Request::post('attr_id');
      $arr=Db::query("select * from attr_details where attr_id='$data'");
      $json=['code'=>'0','status'=>'ok','data'=>$arr];
      return json($json);
    }
    public function addb()
    {
      $data=Request::post();
      $name=$data['name'];
      $attr_id=$data['attr_id'];
      $arr=Db::query("select * from attr_details where name='$name' and attr_id='$attr_id'");
      if (empty($arr)) {
        $arr=Db::query("insert into `attr_details`(`name`,`attr_id`)values('$name','$attr_id')");
        $json=['code'=>'0','status'=>'ok','data'=>'添加成功'];
        return json($json);
      }else{
        $json=['code'=>'0','status'=>'error','data'=>'添加失败'];
        return json($json);
      }
    }
    public function goodsattrshow()
    {
      $id=Request::get('attrcate_id');
      $arr=Db::query("select a.id,a.name,ad.id as ad_id,ad.name as ad_name from attribute as a
        join attr_details as ad on a.id=ad.attr_id where attrcate_id='$id'");
       $newarr=[];
        foreach ($arr as $key => $value) {
           $newarr[$value['name']][]=$value;
        }
      $json=['code'=>'0','status'=>'ok','data'=>$newarr];
      return json($json);
    }
  }