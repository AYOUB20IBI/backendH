<?php

use App\Http\Controllers\Api\LoginController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


// Routes Auth  login

Route::post('login',[LoginController::class , 'login']);

