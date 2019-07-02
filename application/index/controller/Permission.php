<?php
namespace app\index\controller;
use gmars\rbac\Rbac;
use Request;
use Db;
class Permission extends Common
{
    public function list()
    { 
      return $this->fetch();
    }
    public function show()
    {
      $rbac = new Rbac();
        $arr=$rbac->getPermissionCategory([['status', '=', 1]]);
        echo json_encode($arr);
    }
    // public function addAction()
    // {
    //   // $name=Request::post('name');
    //   // $description=Request::post('description');
    //   $data=Request::post();
    //   $validate = new \app\index\validate\Permission;
    //   if (!$validate->check($data)) {
    //     $arr=['code'=>'1','status'=>'error','data'=>$validate->getError()];
    //     echo json_encode($arr);
    //     die;
    //   }
    //   $rbac = new Rbac();
    //   $getarr=$rbac->getPermissionCategory([['name', '=', $data['name']]]);
    //   if (empty($getarr)) {
    //   //   $rbac->savePermissionCategory([
    //   //   'name' => $data['name'],
    //   //   'description' => $data['description'],
    //   //   'status' => 1
    //   // ]);
    //   $rbac->createPermission([
    //       'name' => '文章列表查询',
    //       'description' => '文章列表查询',
    //       'status' => 1,
    //       'type' => 1,
    //       'category_id' => 1,
    //       'path' => 'article/content/list',
    //   ]);
    //     $arr=['code'=>'0','status'=>'ok','data'=>'添加成功'];
    //     echo json_encode($arr); 
    //   }else{
    //     $arr=['code'=>'1','status'=>'error','data'=>'分类已经存在'];
    //     echo json_encode($arr);
    //     die;
    //   } 
    //}
    public function delete()
    {
      $id=Request::post('id');
      $rbac = new Rbac();
        $rbac->delPermissionCategory([$id]);
      $arr=['code'=>'0','status'=>'ok','data'=>'删除成功'];
      echo json_encode($arr);
    }
    //  public function deleteMore()
    // {
    //   $id=Request::post('id');
    //   $rbac = new Rbac();
    //   $rbac->delPermissionCategory([$id]);
    //   $arr=['code'=>'0','status'=>'ok','data'=>'删除成功'];
    //   echo json_encode($arr);
    // }
//     public function updateAction()
//     {
//       $data=Request::post();
//       $validate = new \app\index\validate\Permission;
//       if (!$validate->check($data)) {
//         $arr=['code'=>'1','status'=>'error','data'=>$validate->getError()];
//         echo json_encode($arr);
//         die;
//       }
//       $rbac = new Rbac();
//       $getarr=$rbac->getPermissionCategory([['name', '=', $data['name']]]);
//       if (empty($getarr)) {
//         Db::table('permission_category')->update($data);
//         $arr=['code'=>'1','status'=>'ok','data'=>'修改成功'];
//         echo json_encode($arr); 
//       }else{
//         if ($getarr[0]['id']!=$data['id']) {
//           $arr=['code'=>'1','status'=>'error','data'=>'分类已经存在'];
//           echo json_encode($arr);
//           die;
//         }else{
//           Db::table('permission_category')->update($data);
//           $arr=['code'=>'0','status'=>'ok','data'=>'修改成功'];
//           echo json_encode($arr);
//         }
        
//       }   
//     }
}