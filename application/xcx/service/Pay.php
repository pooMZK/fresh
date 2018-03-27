<?php
/**
 * Created by PhpStorm.
 * User: my
 * Date: 2018/2/10
 * Time: 13:29
 */

namespace app\xcx\service;


use app\lib\enum\OrderStatusEnum;
use app\lib\exception\MyException;
use app\xcx\model\Order as OrderModel;
use app\xcx\service\Order as OrderService;
use think\Exception;
use think\Loader;

//extend/WxPay/WxPay.Api.php   TP5手册讲Loader类库加载效率会比命名空间定位更高效
Loader::import('WxPay.WxPay',EXTEND_PATH,'Api.php');
class Pay
{
    private $orderID;
    private $orderNO;

    function __construct($orderID)
    {
        if(!$orderID){
            throw new Exception('订单号不允许为空');
        }
        $this->orderID=$orderID;
    }
    public function pay(){
        //订单号可能根本就不存在
        //订单号存在，但是用户名与订单号不匹配
        //订单号可能已经被支付
        $this->checkOrderValid();

        //进行库存量检测
        $orderService=new OrderService();
        $status=$orderService->checkOrderStock($this->orderID);
        if(!$status['pass']){
            return $status;
        }
    }

    private function makeWxPreOrder(){
        $openid=Token::getCurrentTokenVar('openid');
        if(!$openid){
            throw new Exception('openid未获取到');
        }

        $wxOrderData=new \WxPayUnifiedOrder();
    }

    /**
     * 订单检测前三步
     * @return bool
     * @throws Exception
     * @throws MyException
     */
    private function checkOrderValid(){
        $order=OrderMOdel::where('id','=',$this->orderID)
            ->find();
        if(!$order){
            throw new Exception('id号不存在');
        }
        if(!Token::isValidOperate($order->user_id)){
            throw new MyException('订单与用户不匹配',404,10003);
        }


        if($order->status!=OrderStatusEnum::UNPAID){

            throw new MyException('订单状态异常',400,80003);
        }
        $this->orderNO=$order->order_no;
        return true;
    }
}