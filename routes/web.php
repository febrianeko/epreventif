<?php


Route::get('/', function () {
    return view('welcome');
});
//Route::get('/', function () {
//    return view('vendor.adminlte.auth.login');
//});
Route::group(['middleware' => 'auth'], function () {
    //    Route::get('/link1', function ()    {
//        // Uses Auth Middleware
//    });

    //Please do not remove this if you want adminlte:route and adminlte:link commands to works correctly.
    #adminlte_routes

    Route::get('/roles',['as'=>'indexRoles','uses'=>'RolesController@index']);
    Route::post('/roles/pagination',['as'=>'paginationRoles','uses'=>'RolesController@pagination']);
    Route::post('/roles/create',['as'=>'createRole','uses'=>'RolesController@create']);
    Route::get('/roles/all',['as'=>'showAllRoles','uses'=>'RolesController@showAll']);
    Route::post('/roles/update',['as'=>'updateRole','uses'=>'RolesController@update']);
    Route::post('/roles/delete/{id}',['as'=>'deleteRole','uses'=>'RolesController@delete']);

    //router user
    Route::get('/users/{role}',['as'=>'indexUsers','uses'=>'UserController@index']);
    Route::post('/users/pagination',['as'=>'paginationUsers','uses'=>'UserController@pagination']);
    Route::post('/users/create',['as'=>'createUser','uses'=>'UserController@create']);
    Route::get('/users/all',['as'=>'showAllUsers','uses'=>'UserController@showAll']);
    Route::get('/users/read-by-regional-area/{regional}/{area}',['as'=>'readUserByRegionalArea','uses'=>'UserController@readByRegionalArea']);
    Route::post('/users/update',['as'=>'updateUser','uses'=>'UserController@update']);
    Route::post('/users/delete/{id}',['as'=>'deleteUser','uses'=>'UserController@delete']);

    //router regional
    Route::get('/regional',['as'=>'indexRegional','uses'=>'RegionalController@index']);
    Route::post('/regional/pagination',['as'=>'paginationRegional','uses'=>'RegionalController@pagination']);
    Route::post('/regional/create',['as'=>'createRegional','uses'=>'RegionalController@create']);
    Route::get('/regional/all',['as'=>'showAllRegional','uses'=>'RegionalController@showAll']);
    Route::post('/regional/update',['as'=>'updateRegional','uses'=>'RegionalController@update']);
    Route::post('/regional/delete/{id}',['as'=>'deleteRegional','uses'=>'RegionalController@delete']);

    //router area
    Route::get('/area',['as'=>'indexArea','uses'=>'AreaController@index']);
    Route::post('/area/pagination',['as'=>'paginationArea','uses'=>'AreaController@pagination']);
    Route::post('/area/create',['as'=>'createArea','uses'=>'AreaController@create']);
    Route::get('/area/read/{id}',['as'=>'readArea','uses'=>'AreaController@read']);
    Route::get('/area/all',['as'=>'showAllArea','uses'=>'AreaController@showAll']);
    Route::post('/area/update',['as'=>'updateArea','uses'=>'AreaController@update']);
    Route::post('/area/delete/{id}',['as'=>'deleteArea','uses'=>'AreaController@delete']);

    //router sites
    Route::get('/sites',['as'=>'indexSites','uses'=>'SitesController@index']);
    Route::post('/sites/pagination',['as'=>'paginationSites','uses'=>'SitesController@pagination']);
    Route::get('/sites/create',['as'=>'formSite','uses'=>'SitesController@formSites']);
    Route::post('/sites/create',['as'=>'createSite','uses'=>'SitesController@create']);
    Route::get('/sites/all',['as'=>'showAllSites','uses'=>'SitesController@showAll']);
    Route::get('/sites/read-by-regional-area/{regional}/{area}',['as'=>'readSites','uses'=>'SitesController@read']);
    Route::post('/sites/update',['as'=>'updateSite','uses'=>'SitesController@update']);
    Route::post('/sites/delete/{id}',['as'=>'deleteSite','uses'=>'SitesController@delete']);

    //router task
    Route::get('/task',['as'=>'indexTask','uses'=>'TaskController@index']);
    Route::post('/task/pagination',['as'=>'paginationTask','uses'=>'TaskController@pagination']);
    Route::post('/task/create',['as'=>'createTask','uses'=>'TaskController@create']);
    Route::get('/task/read/{id}',['as'=>'readTask','uses'=>'TaskController@read']);
    Route::get('/task/all',['as'=>'showAllTask','uses'=>'TaskController@showAll']);
    Route::post('/task/update',['as'=>'updateTask','uses'=>'TaskController@update']);
    Route::post('/task/delete/{id}',['as'=>'deleteTask','uses'=>'TaskController@delete']);
    Route::get('/task/finish',['as'=>'taskFinish','uses'=>'TaskController@taskFinish']);

});
