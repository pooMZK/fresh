<?php
/**
 * Created by PhpStorm.
 * User: my
 * Date: 2018/2/6
 * Time: 17:20
 */

namespace app\xcx\validate;


class Address extends BaseValidate
{

    protected $rule=[
        'name'=>'require|isNotEmpty',
        'mobile'=>'require|isMobile',
        'province'=>'require|isNotEmpty',
        'city'=>'require|isNotEmpty',
        'country'=>'require|isNotEmpty',
        'detail'=>'require|isNotEmpty',
    ];
}