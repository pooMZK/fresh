<?php
/**
 * Created by PhpStorm.
 * User: my
 * Date: 2018/1/24
 * Time: 14:16
 */

namespace app\xcx\model;


class User extends BaseModel
{
    public function address(){
        return $this->hasOne('UserAddress','user_id','id ');
    }

    public static function getByOpenID($openid){

        $res=self::where('openid',$openid)
            ->find();
        return $res;
    }




}