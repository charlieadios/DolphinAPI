<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/23 0023
 * Time: 下午 02:43
 */

namespace app\api\controller\v1;

use app\api\model\User as UserModel;
use app\api\service\Redis;
use app\api\service\SmsService;
use think\helper\Hash;

class User extends APIController
{


    /**
     * @param UserModel $user
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * 登录
     */
    public function login(UserModel $user)
    {
        $param = $this->checkParam('tel,pwd');
        $tel = $param['tel'];
        $pwd = $param['pwd'];
        $res = $user->where('phone', $tel)->find();
        if (!$res) {
            abort(400, '手机号未注册');
        } else {
            if (Hash::check($pwd, $res['password'])) {
                return json(['code' => 200, 'data' => $res]);
            } else {
                abort(400, '密码错误');
            }
        }
    }

    /**
     * @param UserModel $user
     * 注册
     */
    public function register(UserModel $user)
    {
        $param = $this->checkParam('phone');
        $tel = $param['tel'];
        $inputSmsCode = $param['smsCode'];
        if ($user->isPhoneExist($tel)) abort(400, '手机号已注册');

        //验证码判断
        $cacheCode = Redis::getInstance()->get('smsCode:' . $tel);
        if (!$cacheCode) abort(400, '验证码已失效');
        if ($cacheCode == $inputSmsCode) {
            //执行注册业务代码
        } else {
            abort(400, '验证码错误');
        }
    }


    public function forgotPwd(UserModel $user)
    {
        $param        = $this->checkParam('tel,smsCode,newPwd');
        $tel          = $param['tel'];
        $inputSmsCode = $param['smsCode'];
        $newPwd       = $param['newPwd'];

        $cacheCode = Redis::getInstance()->get('smsCode:' . $tel);
        if (!$cacheCode) abort(400, '验证码已失效');
        if ($cacheCode != $inputSmsCode ) abort(400, '短信验证码错误');

        if($user->where('tel',$tel)->save(['pwd'=>Hash::make($newPwd)])){
                return $this->success('重置密码成功');
        }else{
                return $this->error('重置密码失败');
        }
    }


    /**
     * @param UserModel $user
     * @return \think\response\Json
     * 发送短信验证码
     */
    public function sendSmsCode(UserModel $user)
    {
        $param = $this->checkParam('tel, type');
        $tel   = $param['tel'];
        $type  = $param['type'];
        switch ($type) {
            case 1:
                //注册
                if ($user->isPhoneExist($tel) == true) {
                    abort(400, '手机号存在');
                }
                break;
            case 2:
                if (!$user->isPhoneExist($tel)) {
                    abort(400, '手机号尚未注册');
                }
                //登录
                break;
            case 3:
                if (!$user->isPhoneExist($tel)) {
                    abort(400, '手机号尚未注册');
                }
                //找回密码
                break;
            default:
                abort(400, '没有此类型的验证码');
                break;
        }

        $code = randNumStr(4);
        Redis::getInstance()->setex('smsCode:' . $tel, 300, $code);
        (new SmsService())->sendTemplateSMS($tel, [$code, 5], 193061);
        return json(['code' => 200, 'msg' => '短信验证码已发送至:' . $tel]);

    }

}