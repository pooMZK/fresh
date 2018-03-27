<?php
/**
 * Created by PhpStorm.
 * User: my
 * Date: 2018/1/18
 * Time: 19:25
 */

namespace app\xcx\model;




class Image extends BaseModel
{

    protected $visible=['url'];

   // 读取器
    public function getUrlAttr($value,$data){
        return $this->prefixImgUrl($value,$data);
    }

}