<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->post('/usuarios/signup', 'UsuarioController@registrarUsuario');

$router->post('/usuarios/signin', 'UsuarioController@iniciarSesion');

$router->group(['middleware' => ['auth']], function () use ($router) {
    $router->get('/usuarios/perfil', 'UsuarioController@perfil');

    $router->get('/libros', 'LibroController@buscarLibrosDelUsuario');

    $router->get('/libros/{id}', 'LibroController@buscarLibroDelUsuario');

    $router->post('/libros', 'LibroController@guardarLibroDelUsuario');

    $router->post('/libros/{id}', 'LibroController@actualizarLibroDelUsuario');

    $router->delete('/libros/{id}', 'LibroController@eliminarLibroDelUsuario');
});
