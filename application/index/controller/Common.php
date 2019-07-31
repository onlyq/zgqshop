<?php
namespace app\index\controller;
use think\Controller;
use think\facade\Session;
use Redis;
class Common extends Controller
{
	public function __construct()
	{
		// $redis = new Redis();
		// $redis->connect('127.0.0.1',6379);
		// // var_dump($redis);die;
		// $redis->incr('key');
		// $redis->set('numbers',1);
		// $numbers=$redis->get('numbers');
		// var_dump($numbers);die;

		parent::__construct();
		$name=Session::get('name');
		if (empty($name)) {
			$this->redirect('login/login');
		}else{
			$this->assign('name',$name);
		}
	}
	public function commonToken()
    {
        $token = $this->request->token('__token__', 'sha1');
        $arr=['token'=>$token];
        echo json_encode($arr);
        // $this->assign('token', $token);
        // return $this->fetch();
    }

}