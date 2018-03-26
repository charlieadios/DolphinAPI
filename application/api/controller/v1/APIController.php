<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/23 0023
 * Time: 下午 05:39
 */

namespace app\api\controller\v1;


use think\Request;

class APIController
{

    public $params;
    public $request;

    public function __construct()
    {
        $this->request = Request::instance();
        $this->params  = $this->request->param();
        //unset($this->params['version']);


    }

    protected function rule($paramKeys){
        $paramKeysArr   =   explode(',',trim($paramKeys));
        foreach ($paramKeysArr as $item) {
             if(!array_key_exists(trim($item),$this->params)){
                    abort(400,$item.':参数缺失');
             }
        }
        return $this->params;
    }

}