<?php

use Illuminate\Http\Request;
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



Route::group(['middleware' => 'auth:api'], function () {
	Route::namespace('Api')->name('api.')->group(function () {
		Route::post('resetevents', 'EventController@resetTodoEvents');
		Route::resource('milestones', 'MilestoneController');
		Route::resource('calendar', 'CalendarController');
		Route::resource('employees', 'EmployeeController');
		Route::resource('holidays', 'HolidayController');
		Route::resource('usersettings', 'UserSettingController');
		Route::resource('events', 'EventController');
		Route::post('changeevents', 'EventController@changeEvents');
		Route::post('availablehours', 'EventController@availebaleUserHours');
		Route::post('checkhours', 'EventController@checkUserHours');
		
		Route::get('user-holidays', 'HolidayController@getUserHolidays');
		Route::get('list-holidays', 'HolidayController@listUserHolidays');
		Route::put('approve-holidays/{id}', 'HolidayController@approve');
		Route::get('unapproved-holidays', 'HolidayController@getUnapprovedHolidays');
		
		//project calendar
		Route::get('projectresources', 'CalendarController@getProjectResources');
		Route::get('projectevents', 'CalendarController@getProjectEvents');
		
		//gantt calendar
		Route::resource('gantt', 'GanttController')->withoutMiddleware(['throttle']);
		Route::post('gantt-dep', 'GanttController@dependencies')->withoutMiddleware(['throttle']);
		Route::get('gantt-dep', 'GanttController@getDependencies')->withoutMiddleware(['throttle']);
		Route::delete('gantt-dep/{id}', 'GanttController@delDependencies')->withoutMiddleware(['throttle']);
		
		Route::post('gantt-resource', 'GanttController@resources')->withoutMiddleware(['throttle']);
		
		//scheduler
		Route::resource('scheduler', 'SchedulerController')->withoutMiddleware(['throttle']);
		Route::get('scheduler-assignments', 'SchedulerController@assignments')->withoutMiddleware(['throttle']);
		
		Route::post('ticketwork', 'WorkController@storeTicketWork');
		Route::get('ticketstatus', 'TicketController@ticketStatus');
		Route::post('ticketstatus', 'TicketController@changeStatus');
		Route::get('worklist/{ticket}', 'TicketController@workList');
		Route::get('ticket/{ticket}', 'TicketController@ticket');
				
		Route::resource('todos', 'TodoController');
		Route::resource('todostatus', 'TodoStatusController');
		Route::resource('projects', 'ProjectController');
		Route::resource('conversation-summaries', 'ConversationSummaryController');
		Route::get('subprojects/{project}', 'ProjectController@getSubProjects');
		
		//statistics dashboard
		Route::get('statistic-project', 'StatisticController@projectStatistic');
		Route::get('statistic-project/{project}', 'StatisticController@projectStatisticSelectet');
		Route::get('statistic-user', 'StatisticController@projectStatisticUser');
		Route::get('statistic-daily', 'StatisticController@projectStatisticDaily');
		
		Route::resource('dashboard', 'DashboardController');
		Route::post('rework', 'DashboardController@storeRework');
		Route::get('user-rework', 'DashboardController@userRework');
		Route::get('user-overtime', 'DashboardController@userOvertime');
		Route::get('todo-overtime', 'DashboardController@overtime');
		Route::post('approved', 'DashboardController@storeApproved');
		Route::get('approval-info/{todo}', 'WorkController@getApprovalInformation');
		
		//todo
		Route::get('mytodo', 'UserTodoController@mytodo');
		Route::get('myopentodo', 'UserTodoController@myopentodo');
		Route::get('mytodowithoutasessment', 'UserTodoController@myTodoWithoutAsessment');
		
	});
    Route::get('/protected-endpoint', 'SecretController@index');
    // more endpoints ...
});


Route::namespace('Api')->name('api.')->group(function () {
	Route::resource('mails', 'MailController');
	Route::resource('user', 'UserController');
	Route::resource('tickets', 'TicketController');	
	Route::resource('categories', 'CategoryController');
	Route::resource('works', 'WorkController');
	Route::resource('descriptions', 'CategoryDescriptionController');
	Route::resource('usertodo', 'UserTodoController');
	Route::resource('todoassessments', 'TodoAssessmentController');
	

	
	Route::post('showusertodo', 'UserTodoController@usertodo');
	Route::post('showuserworks', 'WorkController@userWorks');

	
	Route::get('alldescriptions/{category}', 'CategoryDescriptionController@showAllDescriptions');
	Route::post('search', 'SearchController@search');
	Route::get('planning/{project}', 'ProjectController@planning');
	Route::get('open-todo/{project}', 'TodoController@openTodo');
	Route::get('pdf/{project}', 'ProjectController@showPDF');
	Route::get('project-employee/{project}', 'ProjectController@employeeStatistic');
	Route::get('categorie-milestones/{category}', 'CategoryController@milestones');
	Route::get('categories-done/{project}', 'CategoryController@done');
	


});