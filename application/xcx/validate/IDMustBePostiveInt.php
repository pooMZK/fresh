<?php
/**
 * Created by PhpStorm.
 * User: my
 * Date: 2018/1/12
 * Time: 16:53
 */

namespace app\xcx\validate;
class IDMustBePostiveInt extends BaseValidate
{

    protected $rule=[

        'id'=>'require|isPositiveInteger'
    ];


    protected $message=[
        'id.isPositiveInteger'=>'id参数必须为正整数'

    ];

}