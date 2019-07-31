<?php
namespace app\index\controller;
use gmars\rbac\Rbac;
use Request;
use Db;
use Session;
class Brand extends Common
{
    public function list()
    {
      return $this->fetch();		
    }
    public function show()
    {
     $rbac = new Rbac();
     $arr=Db::query("select * from brand"); 
     $json=['code'=>'0','status'=>'ok','data'=>$arr];
     return json($json);
    }
    //添加方法
    public function addAction()
    {
    	$brand_name=Request::post('brand_name');
		$describe=Request::post('describe');
		$arr=Db::query("select * from brand where brand_name='$brand_name'");
		if (!empty($arr)) {
			$json=['code'=>'0','status'=>'error','data'=>'名字不能重复'];
    			return json($json);
		}
    	$brand_logo = request()->file('brand_logo');
    	if ($brand_logo) {
    		//这里是表明路径
    		$info = $brand_logo->validate(['size'=>1024*1024,'ext'=>'jpg,png,gif'])->move( './uploads');
		    if($info){
		    	// $data=Request::post();
		    			        // 成功上传后 获取上传信息
		        // 输出 jpg
		        // echo $info->getExtension();
		        // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
		        $path=$info->getSaveName();
		        //转换/为\
		        $path=str_replace("\\", "/",$info->getSaveName());
		        Db::query("insert into brand (`brand_name`,`describe`,`brand_logo`)value('$brand_name','$describe','$path')");
		        $json=['code'=>'0','status'=>'ok','data'=>'添加成功'];
    			return json($json);
		        // var_dump($data);
		    }else{
		        // 上传失败获取错误信息
		        echo $file->getError();
		        $json=['code'=>'1','status'=>'error','data'=>$info->getSaveName()];
    			return json($json);
		    }
    	}else{
    		$json=['code'=>'1','status'=>'error','data'=>'文件不能为空'];
    		return json($json);
    	}
    	
    	$validate = new \app\index\validate\Brand;
        if (!$validate->check($data)) {
            $arr=['code'=>'1','status'=>'error','data'=>$validate->getError()];
            return json($arr);
        }
    	// $rbac = new Rbac();

    	// $brand_name=$data['brand_name'];
    	// $describe=$data['describe'];
    	// // $brand_logo=$data['brand_logo'];
    	// // var_dump($brand_logo);die;
    	// $arr=Db::query("select * from brand where brand_name='$brand_name'");
    	// // var_dump($arr);die;
    	// if (empty($arr)) {
    	// 	$arr=Db::query("insert into brand (`brand_name`,`describe`,`brand_logo`) value ('$brand_name','$describe','$brand_logo')");
    	// 	$json=['code'=>'0','status'=>'ok','data'=>'添加成功'];
    	// 	return json($json);
    	// }else{
    	// 	$json=['code'=>'1','status'=>'error','data'=>'添加失败'];
    	// 	return json($json);
    	// 	die;
    	// }
    }
    //删除方法
    public function delete()
    {
    	$data=Request::post();

    	$id=Request::post('id');
    	$brand_logo=Request::post('brand_logo'); 
    	unlink("./uploads/".$brand_logo);
    	$arr=Db::table('brand')->where('id',$id)->delete();
    	// Db::tabl("delete from brand where id='$id=id");
    	$json=['code'=>'0','status'=>'ok','data'=>'删除成功'];
    	return json($json);
    }
    //修改方法
    public function updateAction()
    {
    	$data=Request::post();
    	$validate = new \app\index\validate\Brand;
        if (!$validate->check($data)) {
            $arr=['code'=>'1','status'=>'error','data'=>$validate->getError()];
            return json($arr);
        }
        unset($data['__token__']);
    	$brand_name=$data['brand_name'];
    	$describe=$data['describe'];
    	$arr=Db::table("select * from brand where brand_name='$brand_name' or describe='$describe'");
    	if (empty($arr)) {
            $arr=Db::table('brand')->update($data);
            $json=['code'=>'0','status'=>'ok','data'=>'修改成功'];
    		return json($json);
    	}else{
			foreach ($arr as $key => $value) {
	           if ($value['id']!=$data['id']) {
	               	$json=['code'=>'1','status'=>'error','data'=>'name已经存在'];
	                return json($json);
	        	}
    		}
    	$arr=Db::table('brand')->update($data);
            $json=['code'=>'0','status'=>'ok','data'=>'修改成功'];
            return json($json);
    	}
	}
	public function update_img()
	{
		$id=Request::post('id');
		$brand_logo=Request::post('brand_logo');
		$new_logo = request()->file('new_logo');
    	if ($new_logo) {
    		//这里是表明路径
    		$info = $new_logo->validate(['size'=>1024*1024,'ext'=>'jpg,png,gif'])->move( './uploads');
		    if($info){
		        $path=$info->getSaveName();
		        $path=str_replace("\\", "/",$info->getSaveName());
		        $where=['id'=>$id,'brand_logo'=>$path];
		        unlink("./uploads/".$brand_logo);
		        $mysqli=Db::table('brand')->where('id','=',$id)->update($where);
		        $json=['code'=>'0','status'=>'ok','data'=>'修改成功'];
    			return json($json);
		    }else{
		        echo $file->getError();
		        $json=['code'=>'1','status'=>'error','data'=>$info->getSaveName()];
    			return json($json);
		    }
    	}
	}
}