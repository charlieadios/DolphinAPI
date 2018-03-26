<?php
/**
 * Created by PhpStorm.
 * User: DELLPC
 * Date: 2018/3/26
 * Time: 22:48
 */

namespace app\api\controller\v1;


use app\admin\model\Config;
use Payment\Client\Charge;

/**
 * Class Payment
 * @package app\api\controller\v1
 * 充值接口
 */
class Payment extends APIController
{
    /**
     * @throws \Payment\Common\PayException
     */
    public function aop(){
        $param   = $this->checkParam('amount');
        $amount  = $param['amount'];
        $channel = 'ali_app';
        $order_no= date('YmdHis').randNumStr(4);
        $config  = config('aop');
        $res     = (new Config())->getConfig('pay');
        $payData = [
            'body'            => $res['body'],
            'subject'         => $res['subject'],
            'order_no'        => $order_no,
            'amount'          => $amount,
            'return_param'    => 'buy some',
        ];
        $str    =   Charge::run($channel,$config,$payData);
        return $this->success('',$str);
    }


    /**
     * @throws \Payment\Common\PayException
     *
     */
    public function wx(){
        $channel = 'wx_app';// wx_app    wx_pub   wx_qr   wx_bar  wx_lite   wx_wap

        $payData = [
            'body'        => '一个苹果',
            'subject'     => '牛逼公司--付款吧',
            'order_no'    => 'NB12312355',
            'amount'      => '100',
            'terminal_id' => 'WEB',
        ];

        $str = Charge::run($channel, $config, $payData);

        if (is_array($ret)) {
            var_dump($ret);
        } else {
            header('Location:' . $ret);
            echo htmlspecialchars($ret);
        }
    }
}