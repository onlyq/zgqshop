<?php
namespace app\index\controller;
use gmars\rbac\Rbac;
use Request;
use Db;
use Session;
class Permission extends Common
{
    public function list()
    { 
        //存一个串
        $token=uniqid();
        Session::set('token',$token);
        $this->assign('token',$token);
      return $this->fetch();
    }
    public function show()
    {
        $rbac= new Rbac();
        $arr=Db::query("select p.id,p.name,p.description,p.path,p_c.name as p_c_name,p.category_id from permission as p join permission_category as p_c on p.category_id=p_c.id");
        $json=['code'=>'0','status'=>'ok','data'=>$arr];
        return json($json);
    }
    public function updateAction()
    {
        $data=Request::post();
        $validate = new \app\index\validate\Permission;
        if (!$validate->check($data)) {
            $arr=['code'=>'1','status'=>'error','data'=>$validate->getError()];
            return json($arr);
        }
        unset($data['__token__']);
        $rbac= new Rbac();
        $name=$data['name'];
        $path=$data['path'];

        $arr=Db::table("select * from permission where name='$name' or path='$path'");
        if (empty($arr)) {
            $arr=Db::table('permission')->update($data);
            $arr=['code'=>'0','status'=>'ok','data'=>'修改成功'];
            return json($arr);
        }else{
            foreach ($arr as $key => $value) {
               if ($value['id']!=$data['id']) {
                    $arr=['code'=>'2','status'=>'error','data'=>'name或path已经存在'];
                    return json($arr);
                }
            }
            $arr=Db::table('permission')->update($data);
            $arr=['code'=>'0','status'=>'ok','data'=>'修改成功'];
            return json($arr);
        }
    }
    public function addAction()
    {
        $data=Request::post();
        $validate = new \app\index\validate\Permission;
        if (!$validate->check($data)) {
            $arr=['code'=>'1','status'=>'error','data'=>$validate->getError()];
            return json($arr);
        }
        $rbac = new Rbac();
        $getname=$rbac->getPermission([['name', '=', $data['name']]]);
        $getpath=$rbac->getPermission([['path', '=', $data['path']]]);
        if (empty($getname)&&empty($getpath)) {
           $arr=$rbac->createPermission([
                'name' => $data['name'],
                'description' => $data['description'],
                'status' => 1,
                'type' => 1,
                'category_id' => $data['category_id'],
                'path' => $data['path'],
            ]);
            $json=['code'=>'0','status'=>'ok','data'=>$arr];
            return json($json);
        }else{
            $json=['code'=>'0','status'=>'ok','data'=>'名字或路径不能重复'];
            return json($json);
        }
    }
       function delete()
       {
        $data=Request::post();
        $validate = new \app\index\validate\Delete;
        if (!$validate->check($data)) {
            $arr=['code'=>'0','status'=>'error','data'=>$validate->getError()];
            return json($arr);
        }
        $rbac= new Rbac();
        $id=$data['id'];
        $rbac->delPermission([$id]);
        $arr=['code'=>'0','status'=>'ok','data'=>'删除成功'];
        return json($arr);      
    }
    // public function deleteMore()
    // {
    //     $data=Request::post('id');
    //     // var_dump($data);
    //     // die;
    //     $validate = new \app\index\validate\Delete;
    //     if (!$validate->check($data)) {
    //         $arr=['code'=>'1','status'=>'error','data'=>$validate->getError()];
    //         echo json_encode($arr);
    //         die;
    //     }
    //     if (empty($id)) {
    //         $arr=['code'=>'0','status'=>'error','data'=>'未选择删除对象'];
    //         echo json_encode($arr);
    //         die;
    //     }
    //     $id=ltrim($id,',');
    //     $arr=explode(',', $id);
    //     $rbac = new Rbac();
    //     $rbac->delPermissionCategory([$id]);
    //     $arr=['code'=>'2','status'=>'ok','data'=>'删除成功','token'=>$token];
    //     echo json_encode($arr);
    // }
    public function datadel()
    {
        $data= Request::post();
        $id = $data['id'];
        $rbac = new Rbac();
        $validate = new \app\index\validate\Delete;
        //1、使用验证器初步验证权限分类名称和描述是否符合要求
        if (!$validate->check($data)) {  
            $arr = ['code'=>'1','status'=>'error','data'=>$validate->getError()];
            echo $json = json_encode($arr);
            die;
        }
        if (empty($id)) {
            $arr = ['code'=>'1','status'=>'error','data'=>'未选择任何对象'];
            $json = json_encode($arr);
            echo $json;
            die;
        }else{
            $rbac = new Rbac();
            $id=explode(',', $id);
            array_shift($id);
            $rbac->delPermission($id);
            $arr=['code'=>'0','staus'=>'ok', 'data'=>'删除成功'];
            $json = json_encode($arr);
            echo $json;
        }
    }
//     public function deleteMore()
//     {
//         $id=Request::post('id');
//         if (empty($id)) {
//             $arr=['code'=>'0','status'=>'error','data'=>'未选择删除对象'];
//             echo json_encode($arr);
//             die;
//         }

//         $arr=explode(',', $id);
//         array_shift($arr);
//         $rbac = new Rbac();
//         $rbac->delPermission($arr);
//         $arr=['code'=>'0','status'=>'ok','data'=>'删除成功'];
//         echo json_encode($arr);
//     }
}