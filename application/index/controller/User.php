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
    // 删除方法

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
        // $validate = new \app\index\validate\User;
        // if (!$validate->check($data)) {
        //     $arr=['code'=>'1','status'=>'error','data'=>$validate->getError()];
        //     return json($arr);
        // }
        $rbac= new Rbac();
        $id=$data['id'];
        // var_dump($id);die;
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
}