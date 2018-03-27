<?php
/**
 * Created by PhpStorm.
 * User: my
 * Date: 2018/1/23
 * Time: 17:02
 */

namespace app\xcx\controller\v1;


use app\xcx\service\UserToken;
use app\xcx\validate\TokenGet;

class Token
{

    public function getToken($code=''){
        (new TokenGet())->gocheck();


        $ut=new UserToken();
        $token=$ut->get($code);
        $res=array('token'=>$token);
        $res=json_encode($res);
        return $res;

//        $res=memcacheGet($token);
//        var_dump($res);
      //  return $token;

    }
}