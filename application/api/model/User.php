<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/23 0023
 * Time: 下午 02:55
 */
namespace app\api\model;
use think\Model;


class User extends Model
{

    /**
     * @param $tel
     * @return bool
     */
    public function isPhoneExist($tel){
        $bool  = false;
        $count = self::where('phone',$tel)->count();
        $count > 0 && $bool = true;
        return $bool;
    }

    //注册
    public function register($param){
        $tel   = $param['tel'];

    }




}