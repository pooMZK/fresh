<?php
/**
 * Created by PhpStorm.
 * User: my
 * Date: 2018/2/7
 * Time: 16:19
 */

namespace app\xcx\controller\v1;


use app\xcx\validate\OrderPlace;
use app\xcx\service\OrderMine as OrderService;

class Order extends BaseController
{

//    protected $beforeActionList=[
//        'checkExclusiveScope'=>['only'=>'placeOrder']
//    ];



    public function placeOrder(){
//       $validate=new OrderPlace();
//       $validate->gocheck();

//       $products=input('post.products/a');//获取数组必须加/a
//       $uid=TokenService::getCurrentUid();
//
        $uid='444534322';
        $products=[
            ['product_id'=>13, 'count'=>3],
            ['product_id'=>6, 'count'=>23],
            ['product_id'=>2, 'count'=>33],
            ['product_id'=>4, 'count'=>33],
            ['product_id'=>3, 'count'=>13],
        ];
       //$data=(new OrderService())->order($uid,$products);
       $order=new \app\xcx\service\Order();
       $data=$order->place($uid,$products);
       halt($data);
       return $data;


    }
}