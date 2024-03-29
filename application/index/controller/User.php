<?php
namespace app\index\controller;
use gmars\rbac\Rbac;
use Request;
use Db;
use Session;
class User extends Common
{
    // 列表方法
    public function list()
    { 
      return $this->fetch();
    }
    public function add()
    { 
      return $this->fetch();
    }
    public function show()
    {
     $rbac = new Rbac();
     $arr=Db::query("select user.id,user.user_name,user.password,user.mobile,role.name,create_time from user join user_role on user.id=user_role.user_id join role on role.id=user_role.role_id");
     
     $json=['code'=>'0','status'=>'ok','data'=>$arr];
     return json($json);
    }
    // 添加方法
    public function addAction()
    {
        $data=Request::post();
        $validate = new \app\index\validate\User;
        if (!$validate->check($data)) {
            $arr=['code'=>'1','status'=>'error','data'=>$validate->getError()];
            return json($arr);
        }
        $rbac= new Rbac();
        $id=$data['id'];
        $user_name=$data['user_name'];
        $password=md5($data['password']);
        $mobile=$data['mobile'];
        $role_id=$data['role_id'];
        $getname=Db::query("select * from user where user_name='$user_name' or mobile='$mobile'");
        if (empty($getname)) {
            $datatime=date("Y-m-d H:i:s",time());
                $arr=Db::query("insert into user(`user_name`,`password`,`mobile`,`create_time`)values('$user_name','$password','$mobile','$datatime')");
                $user_id=Db::query("select id from user where user_name='$user_name'");
                $user_id=$user_id[0]['id'];
                $arr=$rbac->assignUserRole($user_id,[$role_id]);
                $json=['code'=>'0','status'=>'ok','data'=>'添加成功'];
                return json($json);
            }else{
                $json=['code'=>'1','status'=>'error','data'=>'角色名字不能重复'];
                return json($json);
            }
    }
        //删除方法
    public function delete()
    {
        $data=Request::post();
        $validate = new \app\index\validate\Delete;
        if (!$validate->check($data)) {
            $data=['code'=>'1','status'=>'error','data'=>$validate->getError()];
            echo $json=json_encode($data);
            die;
        }
        $id=Request::post('id');
        $arr=Db::table('user')->where('id',$id)->delete();
        
        $arr=Db::table('user_role')->where('user_id',$id)->delete();
        $arr=['code'=>'0','status'=>'ok','data'=>'删除成功'];
        echo json_encode($arr);
        die;
    }
    public function datade2()
        {
          $data=Request::post();
          $id=Request::post('id');
 
         $validate = new \app\index\validate\Delete;
            if (!$validate->check($data)) {
          $data=['code'=>'1','status'=>'error', 'data'=>$validate->getError()];
            echo $json=json_encode($data);
            die;
          }
         if (empty($id)){
            $arr=['code'=>1,'status'=>'error','data'=>'未选择删除对象'];
            echo json_encode($arr);
          }
          $id=explode(",", $id);
          array_shift($id);
          $id=implode(',', $id);
          $arr=Db::table('user')->where('id','in',$id)->delete();
          $arr=Db::table('user_role')->where('id','in',$id)->delete();
          $arr=['code'=>'0','status'=>'ok', 'data'=>'删除成功'];
          echo json_encode($arr);
          die;
        }
        public function updelete()
        {
          $data=Request::post();
          // $validate = new \app\index\validate\;
          // if (!$validate->check($data)) {
          //     $arr=['code'=>'1','status'=>'error','data'=>$validate->getError()];
          //     return json($arr);
          // }
          $id=$data['id'];
          $user_name=$data['user_name'];
          $password=$data['password'];
          $mobile=$data['mobile'];
          $role_id=$data['role_id'];
          unset($data['__token__']);
          $arr=Db::query("select * from user where user_name='$user_name'");
          if (empty($arr)) {
            $arr=Db::table('user')->update($data);
            $json=['code'=>'0','status'=>'ok','data'=>'修改成功'];
            return json($json);
            }else{
            foreach ($arr as $key => $value) {
               if ($value['id']!=$data['id']) {
                    $json=['code'=>'2','status'=>'error','data'=>'name已经存在'];
                    return json($json);
                }
            }
            $arr=Db::table('user')->update($data);
            $json=['code'=>'0','status'=>'ok','data'=>'修改成功'];
            return json($json);
          }
        }
  }