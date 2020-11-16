<?php

class Usuario{
    public $legajo;
    public $nombre;
    public $clave;
    public $tipo;
    public $email;

    public function InsertarUsuario(){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta("INSERT into usuario (nombre, clave, tipo)values(:nombre, :clave, :tipo)");
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':clave', $this->clave, PDO::PARAM_STR);
        $consulta->bindValue(':tipo', $this->tipo, PDO::PARAM_STR);
        $consulta->execute();
        return $objetoAccesoDato->RetornarUltimoIdInsertado();
    }

 

    public static function ObtenerTipos(){
        return array("alumno", "profesor", "admin");
    }


    public static function TraerUnUsuarioPorEmail($email) {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta =$objetoAccesoDato->RetornarConsulta("select nombre as nombre, email as email, clave as clave, tipo as tipo from users WHERE email=:email");
        $consulta->bindValue(':email', $email, PDO::PARAM_INT);
        $consulta->execute();
        $usuarioBuscado= $consulta->fetchObject('Usuario');
        return $usuarioBuscado;
    }

    public static function TraerUnUsuarioPorNombre($nombre) {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta =$objetoAccesoDato->RetornarConsulta("select nombre as nombre, email as email, clave as clave, tipo as tipo from users WHERE nombre=:nombre");
        $consulta->bindValue(':nombre', $nombre, PDO::PARAM_INT);
        $consulta->execute();
        $usuarioBuscado= $consulta->fetchObject('Usuario');
        return $usuarioBuscado;
    }
}