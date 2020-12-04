<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::group(
    [
        'middleware' => [
            'auth',
            'xss',
            'verified'
        ],
    ], function (){

    //Profile
    Route::get('collaborators', 'UserProfileController@collaborators')->name('collaborators');
    Route::get('subscription', 'UserProfileController@subscription')->name('subscription');
    Route::get('checkout', 'UserProfileController@checkout')->name('checkout');

    Route::get('profile/edit', 'UserProfileController@editAuth')->name('profile.edit');
    Route::put('profile', 'UserProfileController@updateAuth')->name('profile.update');
    Route::patch('profile', 'UserProfileController@passwordAuth')->name('profile.password');
    Route::delete('profile', 'UserProfileController@destroyAuth')->name('profile.destroy');

    Route::get('subscribers', 'UsersController@subscribers')->name('subscribers');
});


Route::group(
    [
        'middleware' => [
            'xss'
        ],
    ], function (){

    Auth::routes(['verify' => true]);

    Route::get('profile/{user:handle}', 'UserProfileController@show')->name('profile.show');

    //trigger the scheduler
    Route::get('/hshhdyw7820037lammxh29', 'SchedulerController@run')->name('scheduler.run');
});

Route::group(
    [
        'middleware' => [
            'signed',
            'xss'
        ],
    ], function (){

    //User invite accept
    Route::get('users/invite/{user}/edit', 'UserInviteController@edit')->name('users.invite.edit');
    Route::put('users/invite/{user}', 'UserInviteController@update')->name('users.invite.update');

    Route::get('unsubscribe/{user}', 'UserProfileController@editUnsubscribe')->name('unsubscribe.edit');
    Route::put('unsubscribe/{user}', 'UserProfileController@updateUnsubscribe')->name('unsubscribe.update');

});

Route::group(
    [
        'middleware' => [
            'auth',
            'xss',
            'verified'
        ],
    ], function (){

        //Search
        Route::get('search/{search?}', 'DashboardController@search')->name('search');

        //Dashboard
        Route::get('/', 'DashboardController@index');
        Route::get('home', 'DashboardController@index')->name('home');
        Route::get('oauth/google', 'DashboardController@index');

        //Calendar
        Route::resource('calendar', 'CalendarController');

        //Events
        Route::get('events/{event}/refresh/', 'EventController@refresh')->name('events.refresh');
        Route::resource('events', 'EventController');

        //Events
        Route::resource('notes', 'NotesController');

        //Sharepoint
        Route::get('sharepoint', 'SharepointController@index')->name('sharepoint');

        //User invite
        Route::get('users/invite', 'UserInviteController@create')->name('users.invite.create');
        Route::post('users/invite', 'UserInviteController@store')->name('users.invite.store');

        //Users
        Route::post('users/notifications', 'UsersController@readNotifications')->name('users.notifications');
        Route::get('users/{user}/refresh', 'UsersController@refresh')->name('users.refresh');
        Route::get('users/{user}/verify', 'UsersController@verify')->name('users.verify');
        Route::resource('users', 'UsersController');

        //Clients
        Route::resource('clients', 'ClientsController');

        //Contacts
        Route::resource('contacts', 'ContactsController');

        //Settings
        Route::post('settings/company', 'CompanySettingsController@update')->name('settings.company');

        Route::get('leads/board', 'LeadsController@board')->name('leads.board');
        Route::post('leads/order', 'LeadsController@order')->name('leads.order');
        Route::get('leads/{lead}/refresh', 'LeadsController@refresh')->name('leads.refresh');

        Route::resource('leads', 'LeadsController');

        Route::post('leads/{lead}/file', 'LeadFilesController@store')->name('leads.file.upload');
        Route::get('leads/{lead}/file/{file}', 'LeadFilesController@show')->name('leads.file.download');
        Route::delete('leads/{lead}/file/{file}', 'LeadFilesController@destroy')->name('leads.file.delete');

        //SubscriptionPlans
        Route::post('plans/upgrade', 'SubscriptionPlansController@upgrade')->name('plans.upgrade');
        Route::resource('plans', 'SubscriptionPlansController');

        Route::get('plans/{plan}/subscription', 'SubscriptionsController@create')->name('subscriptions.create');

        Route::delete('subscriptions/{subscription}', 'SubscriptionsController@destroy')->name('subscriptions.destroy');
        // Route::resource('subscriptions', 'SubscriptionsController');


        Route::get('projects/{project}/refresh', 'ProjectsController@refresh')->name('projects.refresh');

        Route::resource('projects', 'ProjectsController');

        Route::get('projects/{project}/milestone', 'ProjectMilestonesController@create')->name('projects.milestone.create');
        Route::post('projects/{project}/milestone', 'ProjectMilestonesController@store')->name('projects.milestone.store');
        Route::get('projects/milestone/{milestone}/edit', 'ProjectMilestonesController@edit')->name('projects.milestone.edit');
        Route::put('projects/milestone/{milestone}', 'ProjectMilestonesController@update')->name('projects.milestone.update');
        Route::delete('projects/milestone/{milestone}', 'ProjectMilestonesController@destroy')->name('projects.milestone.destroy');
        Route::get('projects/milestone/{milestone}/show', 'ProjectMilestonesController@show')->name('projects.milestone.show');

        Route::post('projects/{project}/file', 'ProjectFilesController@store')->name('projects.file.upload');
        Route::get('projects/{project}/file/{file}', 'ProjectFilesController@show')->name('projects.file.download');
        Route::delete('projects/{project}/file/{file}', 'ProjectFilesController@destroy')->name('projects.file.delete');

        Route::put('projects/{project}/status', 'ProjectsController@updateStatus')->name('projects.update.status');//TO DO

        Route::get('projects/{project}/invite', 'ProjectInviteController@create')->name('projects.invite.create');
        Route::post('projects/{project}/invite', 'ProjectInviteController@store')->name('projects.invite.store');

        //Timesheets
        Route::get('timesheets/{timesheet}/refresh', 'TimesheetsController@refresh')->name('timesheets.refresh');
        Route::post('timesheets/timer', 'TimesheetsController@timer')->name('timesheets.timer');
        Route::resource('timesheets', 'TimesheetsController');

        //Tasks
        Route::get('tasks/board/{project?}', 'TasksController@board')->name('tasks.board');
        Route::post('tasks/order', 'TasksController@order')->name('tasks.order');
        Route::get('tasks/{task}/refresh/', 'TasksController@refresh')->name('tasks.refresh');

        Route::resource('tasks', 'TasksController');

        Route::get('tasks/{task}/comment', 'TaskCommentsController@index')->name('tasks.comment.index');
        Route::post('tasks/{task}/comment', 'TaskCommentsController@store')->name('tasks.comment.store');
        Route::put('tasks/{task}/comment/{comment}', 'TaskCommentsController@update')->name('tasks.comment.update');
        Route::delete('tasks/{task}/comment/{comment}', 'TaskCommentsController@destroy')->name('tasks.comment.destroy');

        Route::get('tasks/{task}/file', 'TaskFilesController@index')->name('tasks.file.index');
        Route::post('tasks/{task}/file', 'TaskFilesController@store')->name('tasks.file.upload');
        Route::get('tasks/{task}/file/{file}', 'TaskFilesController@show')->name('tasks.file.download');
        Route::delete('tasks/{task}/file/{file}', 'TaskFilesController@destroy')->name('tasks.file.delete');

        Route::get('tasks/{task}/subtasks', 'TaskChecklistController@index')->name('tasks.subtask.index');
        Route::post('tasks/{task}/subtasks', 'TaskChecklistController@store')->name('tasks.subtask.store');
        Route::put('tasks/{task}/subtasks/{subtask}', 'TaskChecklistController@update')->name('tasks.subtask.update');
        Route::patch('tasks/{task}/subtasks/{subtask}', 'TaskChecklistController@update')->name('tasks.subtask.update');
        Route::delete('tasks/{task}/subtasks/{subtask}', 'TaskChecklistController@destroy')->name('tasks.subtask.destroy');
        Route::post('tasks/{task}/subtasks/order', 'TaskChecklistController@order')->name('tasks.subtask.order');

        //Invoices
        Route::get('invoices/{invoice}/refresh', 'InvoicesController@refresh')->name('invoices.refresh');
        Route::get('invoices/{invoice}/pdf', 'InvoicesController@pdf')->name('invoices.pdf');
        Route::resource('invoices', 'InvoicesController');

        //Invoice items
        Route::get('invoices/{invoice}/items/refresh', 'InvoiceItemsController@refresh')->name('invoices.items.refresh');
        Route::get('invoices/{invoice}/items', 'InvoiceItemsController@create')->name('invoices.items.create');
        Route::post('invoices/{invoice}/items', 'InvoiceItemsController@store')->name('invoices.items.store');
        Route::get('invoices/{invoice}/items/{item}/edit', 'InvoiceItemsController@edit')->name('invoices.items.edit');
        Route::put('invoices/{invoice}/items/{item}', 'InvoiceItemsController@update')->name('invoices.items.update');
        Route::delete('invoices/{invoice}/items/{item}', 'InvoiceItemsController@delete')->name('invoices.items.delete');

        //Invoice payments
        Route::get('invoices/{invoice}/payments', 'InvoicePaymentsController@create')->name('invoices.payments.create');
        Route::post('invoices/{invoice}/payments', 'InvoicePaymentsController@store')->name('invoices.payments.store');
        Route::get('invoices/{invoice}/payments/{payment}/edit', 'InvoicePaymentsController@edit')->name('invoices.payments.edit');
        Route::put('invoices/{invoice}/payments/{payment}', 'InvoicePaymentsController@update')->name('invoices.payments.update');
        Route::delete('invoices/{invoice}/payments/{payment}', 'InvoicePaymentsController@delete')->name('invoices.payments.delete');

        Route::post('stages/order', 'StagesController@order')->name('stages.order');
        Route::resource('stages', 'StagesController');

        //Expenses
        Route::get('expenses/{expense}/attachment/{attachment}', 'ExpensesController@attachment')->name('expenses.attachment');
        Route::resource('expenses', 'ExpensesController');

        Route::resource('articles', 'ArticlesController');


        // Managing Google accounts.
        Route::get('google/oauth', 'GoogleAccountController@store')->name('google.store');
        Route::delete('google/{googleAccount}', 'GoogleAccountController@destroy')->name('google.destroy');
});

Route::get('wiki/{user:handle}/{categories?}', 'WikiController@index')->where('categories','^[a-zA-Z0-9-_\/]+$')->name('wiki.index')->middleware('xss');
