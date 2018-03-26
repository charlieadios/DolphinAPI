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
     * @param $phone
     * @return bool
     * 手机号是否存在
     */
    public function isPhoneExist($phone){
        $bool = false;
        $count = self::where('phone',$phone)->count();
        $count > 0 && $bool = true;
        return $bool;
    }




}