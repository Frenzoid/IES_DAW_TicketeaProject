<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 15/12/17
 * Time: 22:47
 */

namespace liveticket\app\entities;


class Mensaje
{

    private $id;
    private $mensaje;
    private $emisorid;
    private $receptorid;
    private $fecha;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Mensaje
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMensaje()
    {
        return $this->mensaje;
    }

    /**
     * @param mixed $mensaje
     * @return Mensaje
     */
    public function setMensaje($mensaje)
    {
        $this->mensaje = $mensaje;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEmisorid()
    {
        return $this->emisorid;
    }

    /**
     * @param mixed $emisorid
     * @return Mensaje
     */
    public function setEmisorid($emisorid)
    {
        $this->emisorid = $emisorid;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getReceptorid()
    {
        return $this->receptorid;
    }

    /**
     * @param mixed $receptorid
     * @return Mensaje
     */
    public function setReceptorid($receptorid)
    {
        $this->receptorid = $receptorid;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * @param mixed $fecha
     * @return Mensaje
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;
        return $this;
    }

}