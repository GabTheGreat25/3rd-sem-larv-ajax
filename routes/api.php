<?php

use Illuminate\Http\Request;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::post('login', [LoginController::class, 'login']);
// Route::post('register-auth', [
//     'uses' => 'LoginController@register',
//     'as' => 'user.register',
// ]);

// Route::get('register', [
//     'uses' => 'LoginController@getRegister',
// ]);

Route::middleware('guest')->group(function () {
    Route::redirect('/', 'login');

    Route::post('login-auth', [
    'uses' => 'LoginController@login',
    'as' => 'user.login',
    ]);

    Route::get('login', [
    'uses' => 'LoginController@getLogin',
    ]);
    
    Route::get('logout',[
    'uses' => 'LoginController@logout',
    ]);
});

Route::middleware('auth:api')->group(function () {

    Route::view('/home', 'home');

    Route::middleware('role:admin')->group(function () {
        Route::view('/admin-index', 'admin.index');
        Route::view('/admin-register', 'admin.register');
        Route::get('/admin/all',['uses' => 'adminController@getadminAll','as' => 'admin.getadminall'] );
        Route::resource('admin', 'adminController');
        Route::post('admin/post/{id}','adminController@update');
        Route::patch('/admin/restore/{id}', 'adminController@restore');
    });

    Route::middleware('role:operator')->group(function () {
        Route::view('/operator-index', 'operator.index');
        Route::view('/operator-register', 'operator.register');
        Route::get('/operator/all',['uses' => 'operatorController@getoperator','as' => 'operator.getoperatorall'] );
        Route::resource('operator', 'operatorController');
        Route::post('operator/post/{id}','operatorController@update');
        Route::patch('/operator/restore/{id}', 'operatorController@restore');
    });

    Route::middleware('role:investor')->group(function () {
        Route::view('/investor-index', 'investor.index');
        Route::view('/investor-register', 'investor.register');
        Route::get('/investor/all',['uses' => 'investorController@getinvestorAll','as' => 'investor.getinvestorall'] );
        Route::resource('investor', 'investorController');
        Route::post('investor/post/{id}','investorController@update');
        Route::patch('/investor/restore/{id}', 'investorController@restore');
    });

    Route::middleware('role:investor')->group(function () {
        Route::view('/client-index', 'client.index');
        Route::view('/client-register', 'client.register');
        Route::get('/client/all',['uses' => 'clientController@getclientAll','as' => 'client.getclientall'] );
        Route::resource('client', 'clientController');
        Route::post('client/post/{id}','clientController@update');
        Route::patch('/client/restore/{id}', 'clientController@restore');
    });


    Route::middleware('role:admin,client,operator')->group(function () {
        Route::view('/service-index', 'service.index');
        Route::get('/service/all',['uses' => 'serviceController@getserviceAll','as' => 'service.getserviceall'] );
        Route::resource('service', 'serviceController');
        Route::post('service/post/{id}','serviceController@update');
    });

    Route::middleware('role:admin,client,investor')->group(function () {
        Route::view('/camera-index', 'camera.index');
        Route::get('/camera/all',['uses' => 'cameraController@getcameraAll','as' => 'camera.getcameraall'] );
        Route::resource('camera', 'cameraController');
        Route::post('camera/post/{id}','cameraController@update');

        Route::view('/accessories-index', 'accessories.index');
        Route::get('/accessories/all',['uses' => 'accessoriesController@getaccessoriesAll','as' => 'accessories.getaccessoriesall'] );
        Route::resource('accessories', 'accessoriesController');
        Route::post('accessories/post/{id}','accessoriesController@update');
    });
});


