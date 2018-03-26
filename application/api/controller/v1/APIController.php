<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/23 0023
 * Time: 下午 05:39
 */

namespace app\api\controller\v1;
use think\Request;

/**
 * Class APIController
 * @package app\api\controller\v1
 * API接口父类控制器
 */
class APIController
{

    public $params;
    public $request;
    public $header;

    public function __construct()
    {
        $this->request = Request::instance();
        $this->params  = $this->request->param();
        $this->header  = $this->request->header();
    }

    /**
     * @param $paramKeys
     * @param bool $isNull 是否不能为空
     * @return mixed
     * 参数校验
     */
    protected function checkParam($paramKeys , $isNull = false){
        $paramKeysArr   =   explode(',',trim($paramKeys));
        foreach ($paramKeysArr as $item) {
             if(!array_key_exists(trim($item),$this->params)){
                    abort(400,$item.':参数缺失');
             }
             if($isNull){
                 if($this->params[$item] == '' || $this->params[$item]  == null){
                     abort(400,$item.':不能为空');
                 }
             }
        }
        return $this->params;
    }


    /**
     * @param $msg
     * @param null $data
     * @return \think\response\Json
     * 成功返回
     */
    public function success($msg , $data = null){
        return json(['code'=>200,'msg'=>$msg,'data'=>$data]);
    }


    /**
     * @param $msg
     * @param int $code
     * @param null $data
     * @return \think\response\Json
     * 失败返回
     */
    public function error($msg , $code = 400, $data = null){
        return json(['code'=>$code,'msg'=>$msg , 'data'=>$data]);
    }


}