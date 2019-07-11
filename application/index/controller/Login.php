<?php
namespace app\index\controller;
use think\Controller;
use think\captcha\Captcha;
use Request;
use Db;
use md5;
// use gmars\rbac\Rbac;
use think\facade\Session;
class Login extends Controller
{
    public function login()
    {
      	return $this->fetch();		
    }
    public function loginAction()
    {
    	$code=Request::post('code');
     	$name=Request::post('name');
    	$password=Request::post('password');	
     	$captcha = new Captcha();
        if ( !$captcha->check($code)){
      		$arr=['code'=>'1','status'=>'error','message'=>'验证码错误'];
      	}else{
      		$where=['user_name'=>$name,'password'=>md5($password)];
      		$res=Db::table('user')->where($where)->find();
      		if (empty($res)) {
      			$arr=['code'=>'2','status'=>'error','message'=>'账号或密码错误'];
      		}else{
      			$arr=['code'=>'0','status'=>'ok','message'=>'登陆成功'];
      			Session::set('name',$name);
      			// $rbac=new Rbac();
      			// $rbac->cachePermission($res['id']);
      		}
      	}
        $json=json_encode($arr);
      	echo $json;
    }
	    public function loginOut(){
	    	Session::delete('name');
	    	$this->redirect('login/login');
    }
    //生成验证码，生成一张图
    // public function verify()
    // {
    // 	$captcha = new Captcha();
    //     return $captcha->entry();
    // }
    public function md5(){
      echo md5(12345);
    }
}
