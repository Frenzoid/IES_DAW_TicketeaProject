<?php
/**
 * Created by PhpStorm.
 * User: dwes
 * Date: 8/12/17
 * Time: 19:29
 */

namespace liveticket\app\entities;
use liveticket\app\helpers\SubirFichero;


class Evento
{
    private $id;
    private $idCreador;
    private $ENombre;
    private $EDesc;
    private $ECategoria;
    private $EExterno;
    private $EProvincia;
    private $EDir;
    private $FVInicio;
    private $FVFinal;
    private $FEvento;
    private $NET;
    private $NER;
    private $EPoster;
    private $PEntrada;

    use SubirFichero;

    /**
     * @return mixed
     */
    public function getECategoria()
    {
        return $this->ECategoria;
    }

    /**
     * @param mixed $ECategoria
     * @return Evento
     */
    public function setECategoria($ECategoria)
    {
        $this->ECategoria = $ECategoria;
        return $this;
    }
    /**
     * @return mixed
     */
    public function getEPoster()
    {
        return $this->EPoster;
    }

    /**
     * @param mixed $EPoster
     */
    public function setEPoster($EPoster)
    {
        $this->EPoster = $EPoster;
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
     * @return Evento
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIdCreador()
    {
        return $this->idCreador;
    }

    /**
     * @param mixed $idCreador
     * @return Evento
     */
    public function setIdCreador($idCreador)
    {
        $this->idCreador = $idCreador;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getENombre()
    {
        return $this->ENombre;
    }

    /**
     * @param mixed $ENombre
     * @return Evento
     */
    public function setENombre($ENombre)
    {
        $this->ENombre = $ENombre;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEDesc()
    {
        return $this->EDesc;
    }

    /**
     * @param mixed $EDesc
     * @return Evento
     */
    public function setEDesc($EDesc)
    {
        $this->EDesc = $EDesc;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEExterno()
    {
        return $this->EExterno;
    }

    /**
     * @param mixed $EEXterno
     * @return Evento
     */
    public function setEExterno($EExterno)
    {
        $this->EExterno = $EExterno;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEProvincia()
    {
        return $this->EProvincia;
    }

    /**
     * @param mixed $EPorvincia
     * @return Evento
     */
    public function setEProvincia($EProvincia)
    {
        $this->EProvincia = $EProvincia;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEDir()
    {
        return $this->EDir;
    }

    /**
     * @param mixed $EDir
     * @return Evento
     */
    public function setEDir($EDir)
    {
        $this->EDir = $EDir;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFVInicio()
    {
        return $this->FVInicio;
    }

    /**
     * @param mixed $FVInicio
     * @return Evento
     */
    public function setFVInicio($FVInicio)
    {
        $this->FVInicio = $FVInicio;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFVFinal()
    {
        return $this->FVFinal;
    }

    /**
     * @param mixed $FVFinal
     * @return Evento
     */
    public function setFVFinal($FVFinal)
    {
        $this->FVFinal = $FVFinal;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFEvento()
    {
        return $this->FEvento;
    }

    /**
     * @param mixed $FEvento
     * @return Evento
     */
    public function setFEvento($FEvento)
    {
        $this->FEvento = $FEvento;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNET()
    {
        return $this->NET;
    }

    /**
     * @param mixed $NET
     * @return Evento
     */
    public function setNET($NET)
    {
        $this->NET = $NET;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNER()
    {
        return $this->NER;
    }

    /**
     * @param mixed $NER
     * @return Evento
     */
    public function setNER($NER)
    {
        $this->NER = $NER;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPEntrada()
    {
        return $this->PEntrada;
    }

    /**
     * @param mixed $PEntrada
     * @return Evento
     */
    public function setPEntrada($PEntrada)
    {
        $this->PEntrada = $PEntrada;
        return $this;
    }

}