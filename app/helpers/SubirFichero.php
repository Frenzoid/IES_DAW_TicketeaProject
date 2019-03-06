<?php

namespace liveticket\app\helpers;

use InvalidArgumentException;
use liveticket\app\exceptions\UploadException;
use Exception;

trait SubirFichero
{
    private $tiposPermitidos;
    private $dirUpload;
    private $nombreCampoFile;
    private $pesolimite;
    private $nuevoAncho;
    private $requiereThumbnail;

    /**
     * @return mixed
     */
    public function getRequiereThumbnail()
    {
        return $this->requiereThumbnail;
    }

    /**
     * @param mixed $requiereThumbnail
     * @return SubirFichero
     */
    public function setRequiereThumbnail($requiereThumbnail)
    {
        $this->requiereThumbnail = $requiereThumbnail;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNuevoAncho()
    {
        return $this->nuevoAncho;
    }

    /**
     * @param mixed $nuevoAncho
     * @return SubirFichero
     */
    public function setNuevoAncho($nuevoAncho)
    {
        $this->nuevoAncho = $nuevoAncho;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPesolimite()
    {
        return $this->pesolimite;
    }

    /**
     * @param mixed $pesolimite
     * @return SubirFichero
     */
    public function setPesolimite($pesolimite)
    {
        $this->pesolimite = $pesolimite;
        return $this;
    }
    /**
     * @return mixed
     */
    public function getTiposPermitidos()
    {
        return $this->tiposPermitidos;
    }

    /**
     * @param mixed $tiposPermitidos
     * @return SubirFichero
     */
    public function setTiposPermitidos($tiposPermitidos)
    {
        $this->tiposPermitidos = $tiposPermitidos;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDirUpload()
    {
        return $this->dirUpload;
    }

    /**
     * @param mixed $dirUpload
     * @return SubirFichero
     */
    public function setDirUpload($dirUpload)
    {
        $this->dirUpload = rtrim($dirUpload, '/');
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNombreCampoFile()
    {
        return $this->nombreCampoFile;
    }

    /**
     * @param mixed $nombreCampoFile
     * @return SubirFichero
     */
    public function setNombreCampoFile($nombreCampoFile)
    {
        $this->nombreCampoFile = $nombreCampoFile;
        return $this;
    }

    public function compruebaTipo()
    {
        $permitido = false;

        for($i = 0;$i < count($this->tiposPermitidos) && $permitido === false;$i++)
        {
            if ($_FILES[$this->nombreCampoFile]['type'] === $this->tiposPermitidos[$i]){
                $permitido = true;
                break;
            }
        }

        if(!$permitido)
            $_SESSION['error_messages'][] = "Solo imagenes jpg o png";

        return $permitido;
    }

    public function compruebaPeso(){
        if ($this->pesolimite != null && $_FILES[$this->nombreCampoFile]['size'] > $this->pesolimite){
            $_SESSION['error_messages'][] = "El archivo supera el peso limite: 10kBytes";
            return true;
        }
        else
            return false;
    }

    public function compruebaConflictoThumbnail(){
        if (preg_match('[\'^£\$%&*()}{#~?><>,\\/|=+¬]', $_FILES[$this->nombreCampoFile]['name'])) {
            $_SESSION['error_messages'][] = 'No se admiten caracteres especiales en el nombre de la imágen';
        }
    }

    private function getNombreImagen()
    {
        $nombre = $this->dirUpload . '/' . $_FILES[$this->nombreCampoFile]['name'];

        if (is_file($nombre) === true)
        {
            $idUnico = time();
            $nombre =  $this->dirUpload . '/' . $idUnico.'_' . $_FILES[$this->nombreCampoFile]['name'];
        }

        return $nombre;
    }

    private function crearThumbnail($imagen){
        $logicalimg = $this->imagecreatefromfile($imagen);
        $resizedimg = imagescale($logicalimg ,  720, 480);
        // $local = explode('/', $imagen)[1];
        $imagen = $this->dirUpload . '/' . "thumbnail_" . explode('/', $imagen)[1];
        imagepng($resizedimg, $imagen);
    }

    private function resizeImg($imagen, $ancho){
        $logicalimg = $this->imagecreatefromfile($imagen);
        $resizedimg = imagescale($logicalimg ,  $ancho);
        imagepng($resizedimg, $imagen);
    }

    private function muestraImagen(string $nombreImagen)
    {
        header('Content-type: ' . $_FILES[$this->nombreCampoFile]['type']);

        $fp = fopen($nombreImagen, 'rb');
        $contenido = fread ($fp, filesize ($nombreImagen));
        fclose ($fp);

        echo $contenido;
    }

    private function imagecreatefromfile($filename) {
        if (!file_exists($filename)) {
            throw new InvalidArgumentException("Imagen " . $filename . " no encontrada.");
        }
        switch ( strtolower( pathinfo( $filename, PATHINFO_EXTENSION ))) {
            case 'jpeg':
            case 'jpg':
                ini_set("gd.jpeg_ignore_warning", true);
                return imagecreatefromjpeg($filename);
                break;

            case 'png':
                return imagecreatefrompng($filename);
                break;

            case 'gif':
                return imagecreatefromgif($filename);
                break;

            default:
                throw new InvalidArgumentException('Imagen ' . $filename . ' no es un archivo de imagen valido.');
                break;
        }
    }

    public function subeImagen($antiguo, $devolverImagen = false)
    {
        if (($_FILES[$this->nombreCampoFile]['error']) !== UPLOAD_ERR_OK)
            throw new UploadException(
                $_FILES[$this->nombreCampoFile]['error']);

        if($this->compruebaPeso()){
            throw new Exception(
        'Error: El peso del archivo supera los limites asignados: ' . $this->pesolimite);
        }

        $permitido = $this->compruebaTipo();
        if ($permitido === false)
            throw new Exception(
        'Error: No se trata de un fichero .GIF.');

        if (is_uploaded_file($_FILES[$this->nombreCampoFile]['tmp_name']) === false)
            throw new Exception(
        'Error: posible ataque. Nombre: '.$_FILES['imagen']['name']);

        $nombreImagen = $this->getNombreImagen();

        if (move_uploaded_file($_FILES[$this->nombreCampoFile]['tmp_name'], $nombreImagen))
        {
            if ($devolverImagen === true)
                $this->muestraImagen($nombreImagen);
        }
        else
            throw new Exception(
                'Error: No se puede mover el fichero a su destino');

        if($this->nuevoAncho != null && strpos($nombreImagen, 'uploads') !== false)
            $this->resizeImg($nombreImagen, $this->nuevoAncho);


        if($this->requiereThumbnail == true && strpos($nombreImagen, 'uploads') !== false)
            $this->crearThumbnail($nombreImagen);


        if($nombreImagen != $antiguo && strpos($antiguo, 'uploads') !== false && $antiguo != null && $antiguo != ""){
            unlink($antiguo);
                if($this->requiereThumbnail)
                    unlink(substr_replace( $antiguo,'/thumbnail_', strpos($antiguo,'/'), 1));
        }

        return $nombreImagen;
    }
}