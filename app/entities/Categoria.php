<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 23/12/17
 * Time: 14:36
 */

namespace liveticket\app\entities;


class Categoria
{
    private $categoria;

    /**
     * @return mixed
     */
    public function getCategoria()
    {
        return $this->categoria;
    }

    /**
     * @param mixed $categoria
     * @return Categoria
     */
    public function setCategoria($categoria)
    {
        $this->categoria = $categoria;
        return $this;
    }
}