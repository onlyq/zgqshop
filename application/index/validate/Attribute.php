<?php

namespace app\index\validate;

use think\Validate;

class Attribute extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule =   [
        'name'  => 'require|max:50|min:1|token',
        'attribute_id'   => 'require',
            
    ];
    
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
  
}
