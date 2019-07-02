<?php
namespace app\index\controller;
use gmars\rbac\Rbac;
class Index extends Common
{
    public function index()
    {
      return $this->fetch();		
    }
    // public function noControl(){
    // 	return $this->fetch();
    // }
    
}
