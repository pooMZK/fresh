<?php
/**
 * Created by PhpStorm.
 * User: my
 * Date: 2018/2/6
 * Time: 17:18
 */

namespace app\xcx\controller\v1;

use app\lib\enum\ScopeEnum;
use think\Request;
use app\lib\exception\MyException;
use app\xcx\service\Token as TokenService;
class Address extends BaseController
{
    //前置方法，tp5自带，虽然tp5手册讲不用继承think\Controller就能使用大部分方法，但不包括前置
    protected $beforeActionList=[
        'checkPrimaryScope'=>['only'=>'Address']
    ];




    /***
     * 用户地址的更新或创建
     * @throws MyException，这不是异常是抛给用户的信息
     */
    public function Address(){
        (new \app\xcx\validate\Address())->gocheck();
        //根据token来获取id
        $data=input('post.');

        $uid=TokenService::getCurrentUid();

        $data['user_id']=$uid;


        $user=model('User')->get($uid);

        if(!$user){
            throw new MyException('用户不存在',400,6000);
        }

        $address=model('UserAddress')->get(['user_id'=>$uid]);


        if(!$address){
           model('UserAddress')->save($data);

        }else{

            model('UserAddress')->save($data,['user_id'=>$uid]);
        }
     //   return $user;
        throw new MyException('OK',201,0);



    }

}