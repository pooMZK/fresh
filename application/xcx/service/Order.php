<?php
/**
 * Created by PhpStorm.
 * User: my
 * Date: 2018/2/7
 * Time: 17:49
 */

namespace app\xcx\service;


use app\lib\exception\MyException;
use app\xcx\model\OrderProduct;
use app\xcx\model\Product;
use app\xcx\model\UserAddress;
use think\Db;

class Order
{
    //订单的商品列表，也就是客户端传过来的products参数
    protected $oproducts;
    //真实的商品信息（包括库存量）
    protected $products;

    protected $uid;

    public function place($uid,$oproducts){
        $this->oproducts=$oproducts;
        $this->products=$this->getProducts($oproducts) ;
        $this->uid=$uid;
        $status=$this->getOrderStatus();

        if(!$status['pass']){
            $status['order_id']=-1;
            return $status;
        }
        //开始创建订单
        $orderSnap=$this->snapOrder($status);//生成订单的快照
        $order=$this->createOrder($orderSnap);
        halt($order);
        $order['pass']=true;
        return $order;


    }


    /**
     * 订单信息存入数据库
     * @param $snap
     *
     */
    private function createOrder($snap){

        $orderNo=makeOrderNo();//订单号
        $order=new \app\xcx\model\Order();
        $order->user_id=$this->uid;
        $order->order_no=$orderNo;
        $order->total_price=$snap['orderPrice'];
//        $order->total_count=$snap['orderCount'];
//        $order->snap_img=$snap['snapImg'];
//        $order->snap_name=$snap['snapName'];
//        $order->snap_address=$snap['snapAddress'];
        $order->snap_items=json_encode($snap['pStatus']);

        $order->save();
        //halt($res);

        $orderID=$order->id;
        $create_time=$order->create_time;

        foreach ($this->oproducts as &$p){//注意加&
            $p['order_id']=$orderID;
        }
        $orderProduct=new OrderProduct();
        $orderProduct->saveAll($this->oproducts);
     //   Db::commit();//事务
        return[
            'order_no'=>$orderNo,
            'order_id'=>$orderID,
            'create_time'=>$create_time,
        ];


    }


    /***
     * 生成订单快照
     * @param $status
     */
    private function snapOrder($status){
        $snap=[
            'orderPrice'=>0,
            'totalCount'=>0,
            'pStatus'=>[],
            'snapAddress'=>null,
            'snapName'=>'',
            'snapImg'=>'',
        ];

        $snap['orderPrice']=$status['orderPrice'];
        $snap['totalCount']=$status['totalCount'];
        $snap['pStatus']=$status['pStatusArray'];
        $snap['snapAddress']=json_encode($this->getUserAddress());
        $snap['snapName']=$this->products[0]['name'];
        $snap['snapImg']=$this->products[0]['main_img_url'];

        if(count($this->products)>1){
            $snap['snapName']=$snap['snapName'].'等';
        }

        return $snap;



    }

    private function getUserAddress(){
        $userAddress=UserAddress::where('user_id','=',$this->uid)->find();

        if(!$userAddress){
            return ['address'=>'测试地址'];
            //  测试环境注释掉
//            throw new MyException('地址不存在',401,60001);
        }
        return $userAddress->toArray();
    }


    /**
     * 外部调用的检查库存方法
     * @param $orderID
     */
    public function checkOrderStock($orderID){

        $oProducts=OrderProduct::where('order_id','=',$orderID)->select();
        $this->oproducts=$oProducts;
        $this->products=$this->getProducts($oProducts);
        $status=$this->getOrderStatus();
        return $status;
    }



    /**
     * 检查库存等订单状态
     * @return array
     */
    private function getOrderStatus(){
        $status=[
            'pass'=>true,
            'orderPrice'=>0,
            'totalCount'=>0,
            'pStatusArray'=>[],
        ];
        foreach ($this->oproducts as $oproduct){

            $pStatus=$this->getProductStatus(
                $oproduct['product_id'],$oproduct['count'],$this->products
            );
            if(!$pStatus['haveStock']){
                $status['pass']=false;
            }
            $status['totalCount']+=$pStatus['count'];
            $status['orderPrice']+=$pStatus['totalPrice'];
            array_push($status['pStatusArray'],$pStatus);
        }
        return $status;

    }

    private function getProductStatus($oPID,$ocount,$products){

        $pIndex=-1;

        $pStatus=[
            'id'=>null,
            'haveStock'=>false,
            'count'=>0,
            'name'=>'',
            'totalPrice'=>0
        ];

        for($i=0;$i<count($products);$i++){
            if($oPID==$products[$i]['id']){
                $pIndex=$i;
            }
        }
        if($pIndex == -1){
            throw new MyException($oPID.'商品不存在',404,80000);
        }else{
            $product=$products[$pIndex];
            $pStatus['id']=$product['id'];
            $pStatus['name']=$product['name'];
            $pStatus['count']=$ocount;
            $pStatus['totalPrice']=$product['price']*$ocount;
         //   $pStatus['haveStock']=
            if($product['stock']-$ocount >=0){
                 $pStatus['haveStock']=true;
            }
        }
        return $pStatus;

    }

    //根据订单信息查找真实的商品信息
    private function getProducts($oproducts){

        $oPIDs=[];
        foreach ($oproducts as $item){
            array_push($oPIDs,$item['product_id']);
        }

        $products=Product::all($oPIDs)
            ->visible(['id','price','stock','name','main_img_url'])
            ->toArray();

        return $products;

    }

}