<?php
/**
 * Created by PhpStorm.
 * User: my
 * Date: 2018/1/24
 * Time: 14:18
 */

namespace app\xcx\service;


use app\lib\enum\ScopeEnum;
use app\lib\exception\MyException;
use app\xcx\model\User;
use think\Exception;
use app\xcx\model\User as UserModel;

class UserToken extends Token
{
    protected $code;
    protected $AppID;
    protected $AppSecret;
    protected $LoginUrl;

//    function __construct($code)
//    {
//
//        $this->code=$code;
//        $this->AppID=config('xcx.app_id');
//        $this->AppSecret=config('xcx.app_secret');
//        $this->LoginUrl=sprintf(config('xcx.login_url'), $this->AppID,$this->AppSecret,$this->code);
//    }

    public function get($code){
        //https://api.weixin.qq.com/sns/jscode2session?appid=APPID&secret=SECRET&js_code=JSCODE&grant_type=authorization_code
        $this->code=$code;
        $this->AppID=config('xcx.app_id');
        $this->AppSecret=config('xcx.app_secret');
        $this->LoginUrl=sprintf(config('xcx.login_url'), $this->AppID,$this->AppSecret,$this->code);
        $res= myCurl($this->LoginUrl,1,$this->code);
        //$res=myCurl($this->LoginUrl,1,$this->code);
        $res=json_decode($res,true);
        if(empty($res)){
            throw new Exception('从微信服务器获取数据出错');
        }

        //没写else，继续执行
        if(array_key_exists('errcode',$res)){
           throw new MyException($res['errmsg']);
        }

        //return $res;
        return $this->grantToken($res);//颁发令牌


    }

    private function grantToken($res){

        //取出uid，存入缓存
        //key:令牌
        //value: $res ,$uid,scope
        $openid=$res['openid'];
        $user=UserModel::getByOpenID($openid);
        if($user){
            $uid=$user->id;
        }else{
            $user=UserModel::create([
                'openid'=>$openid
            ]);
            $uid=$user->id;
        }
        $cachedValue=$this->prepareCachedValue($res,$uid);
        $token=$this->savecache($cachedValue);
        return $token;

    }

    private function savecache($cachedValue){
        $key=self::generateToken();
        $value=json_encode($cachedValue);//令牌的失效时间==缓存的失效时间
        $expire_in=config('xcx.token_expire_in');

      //  $request=cache($key,$value,$expire_in);//tp5的缓存封装
        memcacheSet($key,$value,$expire_in);//异常抛送写在函数里

        return $key;
    }


    /**
     * 设置缓存
     * @param $res
     * @param $uid
     * @return mixed
     */
    private function prepareCachedValue($res,$uid){
        $cachedValue=$res;
        $cachedValue['uid']=$uid;
       // $cachedValue['scope']=16;
        $cachedValue['scope']=ScopeEnum::User;
        //scope=16代表app用户的权限信息
        //scope=32代表CMS（管理员）用户的权限数值
        return $cachedValue;

    }


}