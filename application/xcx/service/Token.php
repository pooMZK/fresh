<?php
/**
 * Created by PhpStorm.
 * User: my
 * Date: 2018/2/5
 * Time: 16:03
 */

namespace app\xcx\service;


use app\lib\enum\ScopeEnum;
use app\lib\exception\MyException;
use think\Exception;
use think\Request;

class Token
{

    /**
     * 生成token
     * @return string
     */
    public static function generateToken(){
        //32个字符组成一组随机字符串
        $randChars=getRandChar(32);

        //增加安全性：时间戳 盐 MD5

        $timestamp=$_SERVER['REQUEST_TIME_FLOAT'];//当前访问的时间戳
        //salt 加盐
        $salt=config('secure.token_salt');
        return md5($randChars.$timestamp.$salt);

    }

    public static function getCurrentTokenVar($key){
        $token=Request::instance()//从http头里获取token
               ->header('token');
        $vars=memcacheGet($token);

        $vars=json_decode($vars,true);

        if(array_key_exists($key,$vars)){
            return $vars[$key];
        }else{
            throw new Exception('尝试获取的token属性并不存在');
        }
    }

    public static function getCurrentUid(){
        $uid=self::getCurrentTokenVar('uid');
        return $uid;

    }


    /*
     * 只有用户才能访问的接口权限
     */
    public static function needUserScope(){
        $scope=self::getCurrentTokenVar('scope');
        if($scope=ScopeEnum::User){
            return true;

        }else{
            throw new MyException('权限不够',403,10001);
        }
    }


    /**
     * 用户和管理员能访问的权限接口
     * @return bool
     */
    public static function UserSuperScope(){
        $scope=self::getCurrentTokenVar('scope');
        if($scope>=ScopeEnum::User){
            return true;

        }else{
            throw new MyException('权限不够',403,10001);
        }
    }

    public static function isValidOperate($checkedUID){
        if(!$checkedUID){
            throw new Exception('检测UID时必须传入一个被检查的UID');
        }
        $currentOperateUID=self::getCurrentUid();
        if($currentOperateUID==$checkedUID){
            return true;
        }
        return false;
    }

}