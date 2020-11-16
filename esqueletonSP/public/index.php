<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Slim\Routing\RouteCollectorProxy;
use Slim\Factory\AppFactory;

require_once __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

$app->setBasePath("/esqueletonSP/public");

//1
$app->group('/users', function (RouteCollectorProxy $group) {

  $group->post('', \UsuarioApi::class . ':CargarUno')->add(\MWValidaciones::class . ':ValidarEntrada');
  
});
//2
$app->group('/login', function(RouteCollectorProxy $group){
  $group->post('', \UsuarioApi::class . ':Login')->add(\MWValidaciones::class . ':ValidarCredenciales');
});
//4
$app->group('/inscripcion', function(RouteCollectorProxy $group){
  $group->post('/{idMateria}', \MateriaApi::class . ':Inscribir')->add(\MWValidaciones::class . ':ValidarAlumno');
})->add(\MWValidaciones::class . ':ValidarToken');

//3
$app->group('/materia', function(RouteCollectorProxy $group){
  $group->post('', \MateriaApi::class .':CargarUno')->add(\MWValidaciones::class . ':ValidarEntradaMateria')->add(\MWValidaciones::class . ':ValidarAdmin');
})->add(\MWValidaciones::class . ':ValidarToken');


//7
$app->group('/materias', function(RouteCollectorProxy $group){
  $group->get('', \MateriaApi::class . ':ObtenerMaterias')->add(\MWValidaciones::class . ':ValidarEntradaObtener');
})->add(\MWValidaciones::class . ':ValidarToken');
$app->run();
