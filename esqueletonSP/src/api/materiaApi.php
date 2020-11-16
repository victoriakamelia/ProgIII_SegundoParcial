<?php


class MateriaApi extends Materia{
    public static function CargarUno($request, $response) {
        $ArrayDeParametros = $request->getParsedBody();
        $nombre= $ArrayDeParametros['nombre'];
        $cuatrimestre= $ArrayDeParametros['cuatrimestre'];
        $cupos= $ArrayDeParametros['cupos'];
        $materia = new MateriaApi();
        $materia->nombre=$nombre;
        $materia->cuatrimestre=$cuatrimestre;
        $materia->cupos=$cupos;

        $id = $materia->InsertarUnaMateria();
        $respuesta = array("estado" => "Ok", "Mensaje" => "se guardo la materia");
        return $response->withJson($respuesta, 200);
    }

    public static function Inscribir($request, $response, $args){
        $ArrayDeParametros = $request->getParsedBody();
        $materia = MateriaApi::TraerUnaMateriaPorId($args['idMateria']);
        $datos = $request->getHeaderLine('token');
        $payload = AutentificadorJWT::ObtenerPayload($datos);
        $id = $payload->id;
        if($materia){
            $materia->InscribirAlumno($id);
            $respuesta = array("estado" => "Ok", "Mensaje" => "se realizó la inscripcion");
            return $response->withJson($respuesta, 200);
        }
    }

    public static function ObtenerMaterias($request, $response, $args){
        $ArrayDeParametros = $request->getParsedBody();
        switch($request->getAttribute('caso')){
            case 'admin':
                break;
            case 'alumno':
                break;
            case 'profesor':
                break;
        }
    }

    public static function ObtenerMateria($request, $response, $args){
        $ArrayDeParametros = $request->getParsedBody();
        $materia = Materia::TraerUnaMateriaPorId($args['id']);
        $datos = $request->getHeaderLine('token');
        $payload = AutentificadorJWT::ObtenerPayload($datos);
        switch($request->getAttribute('caso')){
            case 'admin':
                $respuesta = $materia->ObtenerAlumnos();
                break;
            case 'profesor':
           
                    $respuesta = $materia->ObtenerAlumnos();
            
                break;
        }
        return $response->withJson(array_values($respuesta), 200);
    }
}