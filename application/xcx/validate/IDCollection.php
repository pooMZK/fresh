<?php
/**
 * Created by PhpStorm.
 * User: my
 * Date: 2018/1/12
 * Time: 16:53
 */

namespace app\xcx\validate;
class IDCollection extends BaseValidate
{

    protected $rule=[

        'ids'=>'require|checkIDs'
    ];
    protected $message=[
        'ids.checkIDs'=>'ids参数为单个正整数或以逗号分割的多个不同的正整数'

    ];

    /***
     * 正确的参数模式为单个正整数或者如‘1,2,3’
     * @param $value
     * @return bool
     */
    protected function checkIDs($value)
    {
      switch ($value){
          case $this->isPositiveInteger($value);
             return true;
             break;
          case $this->arrV($value);
              return true;
              break;
          default;
              return false;
              break;
      }
    }

    private function arrV($value){
      if($arry=explode(',',$value)){
          $arry=array_unique($arry);
          foreach ($arry as $id){//检测每个值
              if($this->isPositiveInteger($id)==true){
                  $res=true;
              }else{
                  $res=false;
                  break;
              }

          }

      }else{
          $res=false;
      }

//        //检测重复值
//        if(count($arry) != count(array_unique($arry))){//aray_unique:删除数组中重复的值
//          $res=false;
//        }

        return $res;
    }
}