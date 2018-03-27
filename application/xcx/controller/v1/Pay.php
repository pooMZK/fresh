<?php
/**
 * Created by PhpStorm.
 * User: my
 * Date: 2018/2/9
 * Time: 17:03
 */

namespace app\xcx\controller\v1;


use app\xcx\validate\IDMustBePostiveInt;

class Pay extends BaseController
{
    //    protected $beforeActionList=[
//        'checkExclusiveScope'=>['only'=>'getPreOrder']
//    ];
    public function getPreOrder($id=''){

        (new IDMustBePostiveInt())->gocheck();
    }

}