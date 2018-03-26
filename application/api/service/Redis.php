<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/26 0026
 * Time: 下午 03:00
 */

namespace app\api\service;


class Redis
{
    private static $instance;
    private $con;

    public static function getInstance(){
        if(!isset(self::$instance)){
            self::$instance = new \Redis();
        }
        return self::$instance;
    }

    public function __construct()
    {
        $conf =  config('redis');
        $this->con = new \Redis();
        $this->con->connect($conf['host'],$conf['port']);
        $this->con->auth($conf['auth']);
        $this->con->setOption(\Redis::OPT_SERIALIZER,\Redis::SERIALIZER_PHP);
    }

    public function getConnect(){
        return $this->con;
    }
}