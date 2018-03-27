<?php
/**
 * Created by PhpStorm.
 * User: my
 * Date: 2018/1/20
 * Time: 14:35
 */

namespace app\xcx\model;


use think\Model;

class BaseModel extends Model
{

    protected function prefixImgUrl($value,$data){//url前缀,类似函数，只是这种写法更加面向对象

        if($data['from']==1){//数据库设计：1代表本地来源
            $url=config('xcx.img_prefix').$value;
            return $url;
        }else{
            return $value;
        }

    }

}