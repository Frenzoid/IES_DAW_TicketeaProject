<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 23/12/17
 * Time: 14:36
 */

namespace liveticket\app\entities;


class Provincia
{
    private $provincia;

    /**
     * @return mixed
     */
    public function getProvincia()
    {
        return $this->provincia;
    }

    /**
     * @param mixed $provincia
     * @return Provincia
     */
    public function setProvincia($provincia)
    {
        $this->provincia = $provincia;
        return $this;
    }
}