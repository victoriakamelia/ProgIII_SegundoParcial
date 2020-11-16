<?php

class Materia{
    public $id;
    public $nombre;
    public $cuatrimestre;
    public $cupos;
    public $profesor;

    public function InsertarUnaMateria(){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta("INSERT into materia (nombre, cuatrimestre, cupos)values(:nombre, :cuatrimestre, :cupos)");
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':cuatrimestre', $this->cuatrimestre, PDO::PARAM_INT);
        $consulta->bindValue(':cupos', $this->cupos, PDO::PARAM_INT);
        $consulta->execute();
        return $objetoAccesoDato->RetornarUltimoIdInsertado();
    }

    public static function TraerUnaMateriaPorId($id){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta =$objetoAccesoDato->RetornarConsulta("select id as id, nombre as nombre, cuatrimestre as cuatrimestre, cupos as cupos, profesor as profesor from materia WHERE id=:id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();
        $usuarioBuscado= $consulta->fetchObject('Materia');
        return $usuarioBuscado;
    }

    public function InscribirAlumno($id){
        if(!$this->MateriaAlumno($id)){
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
            $consulta =$objetoAccesoDato->RetornarConsulta("update materia SET cupos=:cupos WHERE id=:id");
            $consulta->bindValue(':id', $this->id, PDO::PARAM_INT);
            $consulta->bindValue(':cupos', ((int)$this->cupos)-1, PDO::PARAM_INT);
            $consulta->execute();

            $consulta =$objetoAccesoDato->RetornarConsulta("insert into materia_alumno (materia, legajo) values(:materia, :legajo)");
            $consulta->bindValue(':materia', $this->id, PDO::PARAM_INT);
            $consulta->bindValue(':id', $id, PDO::PARAM_INT);
            $consulta->execute();
        }
    }

    public function MateriaAlumno($id){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta =$objetoAccesoDato->RetornarConsulta("select materia as materia, legajo as legajo from materia_alumno WHERE materia=:materia AND legajo=:legajo");
        $consulta->bindValue(':materia', $this->id, PDO::PARAM_INT);
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();
        return $consulta->fetchAll();
    }


}