<?php

use Illuminate\Http\Request;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\ProposalsControllers\ProposalController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//Courses
Route::get('get-courses', [CourseController::class, 'getCourses']);
Route::get('course/{id}', [CourseController::class, 'getCourse']);
Route::get('requisites/{id}', [CourseController::class, 'getRequisites']);
Route::get('code-to-course/{code}', [CourseController::class, 'codeToCourse']);

//Proposals
Route::post('save-proposal', [ProposalController::class, 'save']);
Route::get('get-proposals-basic-info', [ProposalController::class, 'getProposalsBasicInfo']);
Route::get('get-proposals-by-course/{id}', [ProposalController::class, 'getProposalsByCourseId']);
Route::get('proposal/{id}', [ProposalController::class, 'getProposal']);
Route::get('get-crosslisted/{id}', [ProposalController::class, 'checkIfCrosslisted']);

//Authentication
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout']);