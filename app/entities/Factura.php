<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 23/12/17
 * Time: 14:37
 */

namespace liveticket\app\entities;


class Factura
{

    private $id;
    private $usuarioid;
    private $eventoid;
    private $fechacompra;
    private $cantidad;
    private $barcode;
    private $IP;

    /**
     * @return mixed
     */
    public function getIP()
    {
        return $this->IP;
    }

    /**
     * @param mixed $IP
     * @return Factura
     */
    public function setIP($IP)
    {
        $this->IP = $IP;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Factura
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUsuarioid()
    {
        return $this->usuarioid;
    }

    /**
     * @param mixed $usuarioid
     * @return Factura
     */
    public function setUsuarioid($usuarioid)
    {
        $this->usuarioid = $usuarioid;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEventoid()
    {
        return $this->eventoid;
    }

    /**
     * @param mixed $eventoid
     * @return Factura
     */
    public function setEventoid($eventoid)
    {
        $this->eventoid = $eventoid;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFechacompra()
    {
        return $this->fechacompra;
    }

    /**
     * @param mixed $fechacompra
     * @return Factura
     */
    public function setFechacompra($fechacompra)
    {
        $this->fechacompra = $fechacompra;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCantidad()
    {
        return $this->cantidad;
    }

    /**
     * @param mixed $cantidad
     * @return Factura
     */
    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBarcode()
    {
        return $this->barcode;
    }

    /**
     * @param mixed $barcode
     * @return Factura
     */
    public function setBarcode($barcode)
    {
        $this->barcode = $barcode;
        return $this;
    }

}