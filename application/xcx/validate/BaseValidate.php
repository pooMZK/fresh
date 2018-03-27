<?php
/**
 * Created by PhpStorm.
 * User: my
 * Date: 2018/1/12
 * Time: 18:01
 */

namespace app\xcx\validate;


use app\lib\exception\MyException;
use app\lib\exception\ValidateException;
use think\Exception;
use think\Request;
use think\Validate;

class BaseValidate extends Validate
{
    public function gocheck(){
       $request=Request::instance();
       $params=$request->param();//取出所有数据
        //

        $res=$this->batch()->check($params);//check调用$rule
        if(!$res){
            $error=$this->error;//error是变量，geterror（）是方法，该方法返回的就是error变量
          //var_dump($error);  Exception只能返回字符串，报错机制嘛。自然会中断程序

           $res=new ValidateException();
           $res->msg=$error;
           throw $res;

//            $error=implode('  ',$error);
//            throw new MyException($error);//throw只能抛exception

        }
        else{
            return true;
        }
    }

    /***
     * 正整数验证，自定义
     * @param $value
     * @param string $rule
     * @param string $data
     * @param string $field
     * @return bool|string
     */
    protected function isPositiveInteger($value,$rule='',$data='',$field=''){
        //mylog($field);
        if(is_numeric($value) && is_int($value+0) && ($value+0)>0){
            return true;
        }else{
            return false;
          //  return $field.'必须是正整数';
        }
    }

    protected function isNotEmpty($value,$rule='',$data='',$field=''){
        //mylog($field);
        if(empty($value)){
            return false;
        }else{
            return true;
            //  return $field.'必须是正整数';
        }
    }

    public function isMobile($value){
        $rule='^1(3|4|5|7|8)[0-9]\d{8}$^';
        $res=preg_match($rule,$value);
        if($res){
            return true;
        }else{
            return false;
        }

    }
}