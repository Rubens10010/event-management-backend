<?php

use App\Http\Controllers\AccessLogController;
use App\Http\Controllers\BlockController;
use App\Http\Controllers\ConfinementController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\EventGatekeeperController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\ExamTextController;
use App\Http\Controllers\InviteeController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MatrixController;
use App\Http\Controllers\MatrixDetailController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\OrganizationManagerController;
use App\Http\Controllers\ParticipantController;
use App\Http\Controllers\ProcessController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\TeamManagerController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\RequireMasterKey;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'message' => 'Welcome to the Exam Generator API',
    ]);
});

//Route::middleware([RequireMasterKey::class])->group(function () {
Route::post('/login', [LoginController::class, 'authenticate'])->name('login');
//});

Route::get('available-teams', [TeamController::class, 'indexOpen']);
Route::post('participants', [ParticipantController::class, 'store']);
Route::get('events/{event}', [EventController::class, 'show']);
Route::get('participants/{participant}', [ParticipantController::class, 'show']);
Route::get('participants/ndoc/{ndoc}', [ParticipantController::class, 'getByNdoc']);
Route::get('participants/{participant}/invitees', [InviteeController::class, 'getFromParticipant']);
Route::apiResource('invitees', InviteeController::class)->except('destroy');
Route::delete('participants/{participant}/invitees/{invitee}', [InviteeController::class, 'destroy']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', [UserController::class, 'show']);

    Route::get('teams/report', [TeamController::class, 'getReport']);

    Route::apiResources([
        'organizations' => OrganizationController::class,
        'access_logs' => AccessLogController::class,
        'organization_managers' => OrganizationManagerController::class,
        'team_managers' => TeamManagerController::class,
        'teams' => TeamController::class
    ]);

    Route::get('controller/participants/validate', [ParticipantController::class, 'validate']);
    Route::get('controller/participants/validate-dni', [ParticipantController::class, 'validateDni']);
    Route::get('controller/participants/validate-invitee', [ParticipantController::class, 'validateInvitee']);
    Route::get('controller/access_logs/exit', [ParticipantController::class, 'logExit']);

    Route::apiResource('events', EventController::class)->except(['show']);
    Route::apiResource('participants', ParticipantController::class)->except(['store', 'show']);

    Route::get('manager/teams', [TeamController::class, 'getTeamsForManager']);
    Route::get('organization/{organizationId}/event/{eventId}/teams', [TeamController::class, 'getTeamsForOrganization']);
    Route::get('teams/{teamId}/managers', [TeamController::class, 'getManagersForTeam']);
    Route::get('event/{eventId}/team/{teamId}/participants', [TeamController::class, 'getParticipantsForEvent']);
    Route::get('teams/{team}/users-available', [TeamController::class, 'getUsersAvailableForTeam']);
    Route::post('teams/{team}/managers', [TeamManagerController::class, 'storeManagerUser']);

    Route::get('event/{event}/estadisticas', [EventController::class, 'getStatisticsForEvent']);
    Route::put('event/{event}/status', [EventController::class, 'updateStatus']);

    Route::post('invitees/{invitee}/ndoc', [InviteeController::class, 'registerNdoc']);

    Route::post('/reset-password', [UserController::class, 'resetPassword']);

    Route::post('/logout', [LoginController::class, 'logout']);
});
