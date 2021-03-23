<?php

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

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'v1'], function () use ($router) {
    $router->post('candidates', ['uses' => 'CandidateController@create']);

    $router->get('candidates/{id}', ['uses' => 'CandidateController@showOneCandidate']);

    $router->get('candidates',  ['uses' => 'CandidateController@showAllCandidates']);
  
    $router->get('candidates/search', ['uses' => 'CandidateController@showSearchCandidate']);
  
    $router->delete('candidates/{id}', ['uses' => 'CandidateController@delete']);
  
    $router->put('candidates/{id}', ['uses' => 'CandidateController@update']);
});