<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 27/12/17
 * Time: 23:18
 */

namespace liveticket\app\helpers;


trait GetRemoteAddr
{

    private $ip;

    /**
     * @return mixed
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * @param mixed $ip
     * @return GetRemoteAddr
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
        return $this;
    }

    private function returnRemoteIp(){
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        $this->ip = $ip;
        return $this->ip;
    }

}