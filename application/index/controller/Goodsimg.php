<?php
namespace app\index\controller;
use Request;
use Db;
class Goodsimg extends Common
{
    public function goodsImgshow()
    {
    	$goods_id=Request::get('id');

    	$this->assign('goods_id',$goods_id);
      	return $this->fetch();
    }
    public function goodsImg()
    {
      $arr=Db::query("select * from goods_img");
      $json=['code'=>'0','status'=>'ok','data'=>$arr];
      return json($json);
    }
    public function goodsImgadd()
    {
    	$goods_id=Request::post('goods_id');
    	// var_dump($goods_id);
    	// die;
	    $files = request()->file('file');
	    foreach($files as $file){
	    	$info = $file->validate(['size'=>1024*1024,'ext'=>'jpg,png,gif'])->move( './uploads/goodsimg');

			    if($info){
			    	
			        // $path=$info->getSaveName();
			        $name=$info->getFilename();
			        $data=date("Ymd");
			        // $path=str_replace("\\", "/",$info->getSaveName());
			        $path="$data/$name";
			        $image = \think\Image::open("./uploads/goodsimg/$path");
					// 按照原图的比例生成一个最大为150*150的缩略图并保存为thumb.png
					$img_big="$data/big_$name";
					$image->thumb(150, 150)->save('./uploads/goodsimg/'.$img_big);
					$img_middle="$data/middle_$name";
					$image->thumb(100, 100)->save('./uploads/goodsimg/'.$img_middle);
					$img_small="$data/small_$name";
					$image->thumb(50, 50)->save('./uploads/goodsimg/'.$img_small);

			        Db::query("insert into goods_img (`img_origin`,`goods_id`,`img_big`,`img_middle`,`img_small`) value('$path','$goods_id','$img_big','$img_middle','$img_small')");
		
			    }else{
			        
			        $json=['code'=>'1','status'=>'error','data'=>$info->getError()];
	    			return json($json);
				}
        // 移动到框架应用根目录/uploads/ 目录下
	        // $info = $file->move( '../uploads');
	        // if($info){
	        //     // 成功上传后 获取上传信息
	        //     // 输出 jpg
	        //     echo $info->getExtension(); 
	        //     // 输出 42a79759f284b767dfcb2a0197904287.jpg
	        //     echo $info->getFilename(); 
	        // }else{
	        //     // 上传失败获取错误信息
	        //     echo $file->getError();
	        // }    
	    }
	    $json=['code'=>'0','status'=>'ok','data'=>'添加成功'];
	    return json($json);
	}	    
    //删除方法

    //修改方法
  }