<?php
namespace app\index\controller;
use Request;
use Db;
use Redis;
class Goods extends Common
{
  function get($num = 20000)  // $num为生成汉字的数量
    {
        //$b = '';
        for ($i=0; $i<$num; $i++) {
            // 使用chr()函数拼接双字节汉字，前一个chr()为高位字节，后一个为低位字节
              $a = chr(mt_rand(0xB0,0xD0)).chr(mt_rand(0xA1, 0xF0));
              $d = chr(mt_rand(0xB0,0xD0)).chr(mt_rand(0xA1, 0xF0));
              $c = chr(mt_rand(0xB0,0xD0)).chr(mt_rand(0xA1, 0xF0));
              $d=$a.$d.$c;
            // 转码
             $b= iconv('GB2312', 'UTF-8', $d);
             "<br>";
        $add = ['name' => $b,'brand_id'=>12,'cate_id'=>1];
        $acc=Db::name('goods')->insert($add);

        }
        //return $b;

    }
    // 列表方法
    public function list()
    { 
      return $this->fetch();
    }
    public function add()
    { 
      return $this->fetch();
    }
    public function showCate()
    {
      $arr=Db::query("select * from goods_category");
      $this->getTree($arr);
    }
    private function getTree($array, $pid =0, $level = 0)
    {
       foreach ($array as $key => $value){
        if ($value['pid'] == $pid){
            $flg = str_repeat('|--',$level);
            $value['name'] = $flg.$value['name'];
            $name=$value['name'];
            $id=$value['id'];
            echo "<option value='$id'>$name</option>";
            $list[] = $value;
            unset($array[$key]);
            $this->getTree($array, $value['id'], $level+1);
        }
    }
  }
  public function show()
  {
    $redis = new Redis();
    //引入redis类
    $redis->connect('127.0.0.1',6379);
    //链接服务器
    $data=Request::post('name');
    if (!empty($data)) {
      $count =$redis->hIncrBy('list',$data,+1);
      //搜索一次自增1
      $redis->zAdd('search_goods',$count,$data);
      //存集合 并分组
    }
    $sort = $redis->zrevrange('search_goods','0','3','withscores');
    //在分组中取集合
    $res = $redis->hget('goods',$data);
    $res=json_decode($res);
    if (!$res) {
      if (empty($data)) {
          $arr=Db::query("select goods.id,goods.name,brand.brand_name as brand_name,goods_category.name as cate_name,goods.online_status from goods join brand on goods.brand_id=brand.id join goods_category on goods_category.id=goods.cate_id");
          // $json=['code'=>'0','status'=>'ok','data'=>$arr];
          // return json($json);
          }else{
          $arr=Db::query("select goods.id,goods.name,brand.brand_name as brand_name,goods_category.name as cate_name,goods.online_status from goods join brand on goods.brand_id=brand.id join goods_category on goods_category.id=goods.cate_id where goods.name like '%$data%'");
          if ($count>=10) {
            $res=json_encode($res);
            $redis->hset('goods',$data,$res);
        }
      }
    }
    $json=['code'=>'0','status'=>'ok','top'=>$sort,'data'=>$arr];
       return json($json);
    }
    //   $arr=json_encode($arr);
      //   $num=$redis->hGet("goods","$arr");
      //   if ($num>10) {
      //     $redis->hset("goods_arr","$data","$arr");
      //     $arr=$redis->hGet("goods_arr","$data");
      //     $arr=json_decode($arr);
      //     $json=['code'=>'0','status'=>'ok','data'=>$arr,'file'=>'redis'];
      //     return json($json);
      //     die;
      //   }
      //   if (empty($num)) {
      //     $redis->hSet("goods","$arr","1");
      //     $redis->zAdd("top",1,"$data");
      //   }else{
      //     $num=$num+1;
      //     $redis->hSet("goods","$arr","$num");
      //     $redis->zAdd("top","$num","$data");
      //   }
      //   $arr=json_decode($arr);
      //   $json=['code'=>'0','status'=>'ok','num'=>'$num','data'=>$arr];
      //   return json($json);
      // }
      // $a=$redis->zRevRange('top',0,-1);
      // if (empty($a)) {
      //   $json=['code'=>'0','status'=>'ok','top'=>'没有数据','data'=>$arr];
      //   return json($json);
      // }else{
      //   $json=['code'=>'0','status'=>'ok','top'=>$a,'data'=>$arr];
      //   return json($json);
      // }
    // 添加方法
    public function addAction()
    {
      $data=Request::post();
      // var_dump($data);die;
      unset($data['__token__']);
      $userId=Db::name('goods')->insertGetId($data);
      $json=['code'=>'0','status'=>'ok','data'=>'添加成功'];
      return json($json);
      
    }
    public function goodsImg()
    {
      return $this->fetch();
    }
    public function goodsImgshow()
    {
      $arr=Db::query("select * from goods_img");
      $json=['code'=>'0','status'=>'ok','data'=>$arr];
      return json($json);
    }
    public function goodsImgadd()
    {
      var_dump($_FILES);
    }
    //删除方法
    public function detele()
    {
      $data=Request::post();
      $id=Request::post('id');
      $arr=Db::table('goods')->where('id',$id)->delete();
      $json=['code'=>'0','status'=>'ok','data'=>'删除成功'];
      return json($json);
    }
    //修改方法
  }
   // Db::query("select * from goods join brand on goods.brand_id=brand.id join goods_category on goods_category.id=goods.cate_id");
      // $json=['code'=>'0','atstus'=>'ok','data'=>$arr];
      // return json($json);