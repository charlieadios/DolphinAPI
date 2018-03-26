<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/23 0023
 * Time: 下午 02:43
 */

namespace app\api\controller\v1;
use app\api\model\User as UserModel;
use app\api\service\SmsService;
use think\Cache;
use think\helper\Hash;

class User extends APIController
{


    /**
     * @param UserModel $user
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function login(UserModel $user)
    {
        $param = $this->rule('phone,pwd');
        $phone = $param['phone'];
        $pwd   = $param['pwd'];
        $res   = $user->where('phone',$phone)->find();
        if(!$res){
                abort(400,'手机号未注册');
        }else{
            if(Hash::check($pwd,$res['password'])){
                return json(['code'=>200,'data'=>$res]);
            }else{
                abort(400,'密码错误');
            }
        }
    }

    public function register(UserModel $user){
        $param      =  $this->rule('phone');
        $phone      =  $param['phone'];
        //$pwd        =  $param['pwd'];
       // $smsCode    =  $param['smsCode'];

        $code = Cache::get('smsCode:'.$phone);






    }

    public function sendSmsCode(UserModel $user){
        $param      =  $this->rule('phone, type');
        $phone      =  $param['phone'];
        $type       =  $param['type'];
        switch ($type){
            case 1:
                //注册
                if($user->isPhoneExist($phone) == true){
                    abort(400,'手机号存在');
                }
                break;
            case 2:
                if(!$user->isPhoneExist($phone)){
                    abort(400,'手机号尚未注册');
                }
                //登录
                break;
            case 3:
                if(!$user->isPhoneExist($phone)){
                    abort(400,'手机号尚未注册');
                }
                //找回密码
                break;
            default:
                abort(400,'没有此类型的验证码');
                break;
        }

        $code   = randNumStr(4);
        Cache::set('smsCode:'.$phone,$code,300);
        (new SmsService())->sendTemplateSMS($phone,[$code,5],193061);
        return json(['code'=>200,'msg'=>'短信验证码已发送至:'.$phone]);

    }

}