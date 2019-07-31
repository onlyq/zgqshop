<?php
namespace app\index\controller;
use Request;
use Db;
use Session;
class Goodscate extends Common
{
    // 列表方法 
    public function list()
    { 
		return $this->fetch();
	}
	private function getTree($array, $pid =0, $level = 0){


	    //声明静态数组,避免递归调用时,多次声明导致数组覆盖
	    // static $list = [];
	    echo "<ul>";
	    foreach ($array as $key => $value){
	    		//第一次遍历,找到父节点为根节点的节点 也就是pid=0的节点
	        if ($value['pid'] == $pid){
	            //父节点为根节点的节点,级别为0，也就是第一级
	            // $flg = str_repeat('|--',$level);
	            // 更新 名称值
	            $value['name'] = $value['name'];
	            $m_id=$value['id'];
	            // 输出 名称
	            echo "<li value='$m_id' id='id'".$m_id.">".$value['name']."<a style='text-decoration:none' onclick='modaldemo(\"".$m_id."\",\"".$value['name']."\")'  title='编辑'><i class='Hui-iconfont'>&#xe6df;</i><a style='text-decoration:none' onclick='del(".$value['id'].")' title='删除'><i class='Hui-iconfont'>&#xe6e2;</i></a></li>";
	            //把这个节点从数组中移除,减少后续递归消耗
	            unset($array[$key]);
	            //开始递归,查找父ID为该节点ID的节点,级别则为原级别+1
	            $this->getTree($array, $value['id'], $level+1);
	        } 
	    }
	     echo "</ul>";
	}
    public function add()
    { 
      return $this->fetch();
    }
    public function show()
    {

     $arr=Db::query("select * from goods_category");
     $this->getTree($arr);
     // $json=['code'=>'0','status'=>'ok','data'=>$arr];
     // return json($json);

    }
    // 添加方法
    public function addAction()
    {	
    	$data=Request::post();
        $name=Request::post('name');
        $pid=Request::post('pid');
        // var_dump($name);die;
        $validate = new \app\index\validate\Goodscate;
        if (!$validate->check($data)) {
            $arr=['code'=>'1','status'=>'error','data'=>$validate->getError()];
            return json($arr);
        }
        $arr=Db::query("select id from goods_category where name='$name'");
        if (!empty($arr)) {
        	$json=['code'=>'1','status'=>'error','data'=>'角色名字不能重复'];
                return json($json);
            }else{
            	$data=['name'=>$name,'pid'=>$pid];
            	$id=Db::name('goods_category')->insertGetId($data);
            	// Db::query("insert into goods_category (`name`,`pid`)value('$name','$pid')");
                $json=['code'=>'0','status'=>'ok','data'=>'添加成功'];
                return json($json);
                
            }
        }
        //删除方法
  //  public function del()
  // {
  //   $data=Request::post();
  //   $id=$data['id'];
  //   Db::query("delete from goods_category where id=$id");
  //   $this->delAction($id);//调用方法delAction
  //   $json=['code'=>'0','status'=>'ok','data'=>'删除成功'];
  //   echo json_encode($json);

  //  }
  //  public function delAction($id)
  // {
  //   $arr=Db::query("select * from goods_category where pid=$id");
  //   if (empty($arr)) {
      
  //   }else{
  //       foreach ($arr as $key => $value) {
  //       $di=$value['id'];
  //        Db::query("delete from goods_category where id=$di");
  //        $this->delAction($di);
  //     }
  //   }
     
  // }
    public function del()
    {
      $data=Request::post();
      $id=$data['id'];
      Db::query("delete from goods_category where id=$id");
      $this->delAction($id);
      $json=['code'=>'0','status'=>'ok','data'=>'删除成功'];
      return json($json);
    }
    public function delAction($id)
    {
      $arr=Db::query("select * from goods_category where pid=$id");
      if (empty($arr)) {
        
      }else{
          foreach ($arr as $key => $value) {
          $di=$value['id'];
          Db::query("delete from goods_category where id=$di");
          $this->delAction($di);
        }
      }
    }
    public function updelete()
    {
      $data=Request::post();
      $id=$data['id'];
      $name=$data['name'];
      $arr=Db::query("select * from goods_category where name='$name'");
      if (empty($arr)) {
          $arr=Db::table('goods_category')->update($data);
          $json=['code'=>'0','status'=>'ok','data'=>'修改成功'];
          return json($json);
      }else{
        foreach ($arr as $key => $value) {
          if ($value['id']!=$data['id']) {
            $json=['code'=>'1','status'=>'error','data'=>'name已存在'];
            return json($json);
          }
        }
        $arr=Db::table('goods_category')->unlink($data);
        $json=['code'=>'2','suatus'=>'ok','data'=>'修改成功'];
        return json($json);
      }
    }
}