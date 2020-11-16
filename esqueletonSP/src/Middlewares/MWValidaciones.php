<?php


class MWValidaciones{
    public static function ValidarCredenciales($request, $response, $next){
        $ArrayDeParametros = $request->getParsedBody();
        if(isset($ArrayDeParametros['legajo']) && isset($ArrayDeParametros['clave'])){
            return $next($request, $response);
        }
        return $response;
    }

    public static function ValidarEntrada($request, $response, $next){
        $ArrayDeParametros = $request->getParsedBody();
        if(isset($ArrayDeParametros['nombre']) && isset($ArrayDeParametros['clave']) && isset($ArrayDeParametros['tipo'])
            && in_array($ArrayDeParametros['tipo'], Usuario::ObtenerTipos())){
            return $next($request, $response);
        }else{
            return $response->withJson(array("estado" => "Error", "mensaje" => "Faltan datos"), 200);
        }
        return $response;
    }

    public static function ValidarEntradaModificar($request, $response, $next){
        $ArrayDeParametros = $request->getParsedBody();
        $datos = $request->getHeaderLine('token');
        $uploadedFiles = $request->getUploadedFiles();
        $payload = AutentificadorJWT::ObtenerPayload($datos);
        switch($payload->tipo){
            case 'admin':
                if(isset($ArrayDeParametros['email']) && isset($ArrayDeParametros['materias'])){
                    $request = $request->withAttribute('caso', 'profesor');
                }else if(isset($ArrayDeParametros['email']) && isset($uploadedFiles['foto'])){
                    $request = $request->withAttribute('caso', 'alumno');
                }
                break;
            case 'alumno':
                if(isset($ArrayDeParametros['email']) && isset($uploadedFiles['foto'])){
                    $request = $request->withAttribute('caso', 'alumno');
                }
                break;
            case 'profesor':
                if(isset($ArrayDeParametros['email']) && isset($ArrayDeParametros['materias'])){
                    $request = $request->withAttribute('caso', 'profesor');
                }
                break;
        }
        if($request->getAttribute("caso")){
            return $next($request, $response);
        }
        return $response->withJson(array("estado" => "error".$payload->tipo), 200);
    }

    public static function ValidarEntradaObtener($request, $response, $next){
        $ArrayDeParametros = $request->getParsedBody();
        $datos = $request->getHeaderLine('token');
        $payload = AutentificadorJWT::ObtenerPayload($datos);
        switch($payload->tipo){
            case 'admin':
                $request = $request->withAttribute('caso', 'admin');
                break;
            case 'alumno':
                $request = $request->withAttribute('caso', 'alumno');
                break;
            case 'profesor':
                $request = $request->withAttribute('caso', 'profesor');
                break;
        }
        if($request->getAttribute("caso")){
            return $next($request, $response);
        }
        return $response->withJson(array("estado" => "error".$payload->tipo), 200);
    }

    public static function ValidarEntradaMateria($request, $response, $next){
        $ArrayDeParametros = $request->getParsedBody();
        if(isset($ArrayDeParametros['nombre']) && isset($ArrayDeParametros['cuatrimestre']) && isset($ArrayDeParametros['cupos'])
            && ($ArrayDeParametros['cuatrimestre']=='1' || $ArrayDeParametros['cuatrimestre']=='2' &&
            $ArrayDeParametros['cuatrimestre']=='3' || $ArrayDeParametros['cuatrimestre']=='4'
            ) && (int)$ArrayDeParametros['cupos']>0){
            return $next($request, $response);
        }else{
            return $response->withJson(array("estado" => "Error", "mensaje" => "Faltan datos"), 200);
        }
        return $response;
    }

    public static function ValidarToken($request, $response, $next){
        $datos = $request->getHeaderLine('token');
        try{
            AutentificadorJWT::VerificarToken($datos);
            return $next($request, $response);
        } catch(Exception $e){
            $response->getBody()->write($e->getMessage());
        }
        return $response;
    }

    public static function ValidarAdmin($request, $response, $next){
        $datos = $request->getHeaderLine('token');
        $payload = AutentificadorJWT::ObtenerPayload($datos);
        if($payload->tipo == "admin"){
            return $next($request, $response);
        }
        return $response->withJson(array("estado" => "Error", "mensaje" => "No tiene permisos suficientes"), 401);
    }

    public static function ValidarAlumno($request, $response, $next){
        $datos = $request->getHeaderLine('token');
        $payload = AutentificadorJWT::ObtenerPayload($datos);
        if($payload->tipo == "alumno"){
            return $next($request, $response);
        }
        return $response->withJson(array("estado" => "Error", "mensaje" => "No tiene permisos suficientes"), 401);
    }
}