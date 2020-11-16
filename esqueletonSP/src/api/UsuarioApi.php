<?php


class UsuarioApi extends Usuario
{

    public static function CargarUno($request, $response)
    {
        $ArrayDeParametros = $request->getParsedBody();
        $email = $ArrayDeParametros['email'];
        $nombre = $ArrayDeParametros['nombre'];
        $clave = $ArrayDeParametros['clave'];
        $tipo = $ArrayDeParametros['tipo'];

        $usuario = new Usuario();
        $usuario->nombre = $nombre;
        $usuario->clave = $clave;
        $usuario->tipo = $tipo;
        $datosOk = $usuario->validarInfo;
        if ($datosOk) {
            $legajo = $usuario->InsertarUsuario();
            $respuesta = array("estado" => "Ok", "Mensaje" => "se guardo el usuario");
            return $response->withJson($respuesta, 200);
        }

        $respuesta = array("estado" => "NO OK", "Mensaje" => "Datos invalidos");
        return $response->withJson($respuesta, "");
    }

    public function Login($request, $response, $args)
    {
        $ArrayDeParametros = $request->getParsedBody();

    
        $usuario1 = Usuario::TraerUnUsuarioPorEmail($ArrayDeParametros['email'])  ?? null;
        $usuario2 = Usuario::TraerUnUsuarioPorNombre($ArrayDeParametros['nombre']) ?? null;
        if($usuario1 != null)
        {
            $usuario= $usuario1;
        }elseif($usuario2 != null)
        {
            $usuario= $usuario2;
        }

        if ($usuario1 || $usuario2) {
            if ($usuario->clave == $ArrayDeParametros['clave']) {
                $token = AutentificadorJWT::CrearToken($usuario->nombre, $usuario->tipo, $usuario->clave);
                $respuesta = array("estado" => "Ok", "token" => $token);
                $codigo = 200;
            } else {
                $respuesta = array("estado" => "Error", "Mensaje" => "Clave incorrecta");
                $codigo = 401;
            }
        } 
        return $response->withJson($respuesta, $codigo);
    }


    public function validarInfo()
    {
        $existeUserConMail = null;
        $existeUserConMail = Usuario::TraerUnUsuarioPorEmail($this->email);
        $existeUserConNombre = null;
        $existeUserConNombre = Usuario::TraerUnUsuarioPorEmail($this->nombre);


        if (
            $existeUserConMail != null && $existeUserConNombre != null
            &&  count_chars($this->clave) < 4 && ($this->tipo != 'alumno' || $this->tipo != 'profesor' || $this->tipo != 'admin')
        ) {
            return true;
        }
        return false;
    }
}
