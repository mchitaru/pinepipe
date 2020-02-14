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
Auth::routes(['verify' => true]);

// Route::put('change-password', 'UsersController@updatePassword')->name('update.password');
Route::patch('profile', 'UserProfileController@password')->name('profile.password');

Route::group(
    [
        'middleware' => [
            'auth',
            'xss',
            'verified'
        ],
    ], function (){

        //Search
        Route::get('search/{search?}', 'ProjectsController@search')->name('search');

        //Dashboard
        Route::get('/', 'DashboardController@index');
        Route::get('home', 'DashboardController@index')->name('home');

        //Calendar
        Route::resource('calendar', 'CalendarController');

        //Events
        Route::resource('events', 'EventController');

        //Sharepoint
        Route::get('sharepoint', 'SharepointController@index')->name('sharepoint');

        //Users
        Route::post('users/notifications', 'UsersController@readNotifications')->name('users.notifications');
        Route::resource('users', 'UsersController');
        
        Route::resource('roles', 'UserRolesController');
        Route::resource('permissions', 'PermissionsController');

        //Clients
        Route::resource('clients', 'ClientsController');

        //Contacts
        Route::resource('contacts', 'ContactsController');

        //Profile
        Route::get('profile', 'UserProfileController@show')->name('profile.show');
        Route::put('profile', 'UserProfileController@update')->name('profile.update');

        //Settings
        Route::post('settings/email', 'SystemSettingsController@updateEmail')->name('settings.email');
        Route::post('settings/company', 'SystemSettingsController@updateCompany')->name('settings.company');
        Route::post('settings/stripe', 'SystemSettingsController@updateStripe')->name('settings.stripe');
        Route::post('settings/system', 'SystemSettingsController@updateSystem')->name('settings.system');

        //Leads
        Route::resource('leadstages', 'LeadStagesController');
        Route::post(
            '/leadstages/order', [
                                   'as' => 'leadstages.order',
                                   'uses' => 'LeadStagesController@order',
                               ]
        );

        Route::resource('leadsources', 'LeadSourcesController');

        Route::get('leads/board', 'LeadsController@board')->name('leads.board');
        Route::post('leads/order', 'LeadsController@order')->name('leads.order');

        Route::resource('leads', 'LeadsController');

        //PaymentPlans
        Route::resource('plans', 'PaymentPlansController');

        //Projects
        Route::resource('projectstages', 'ProjectStagesController');
        Route::post(
            '/projectstages/order', [
                                      'as' => 'projectstages.order',
                                      'uses' => 'ProjectStagesController@order',
                                  ]
        );
    
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

        Route::get('projects/{project}/permission', 'ProjectPermissionsController@create')->name('projects.permissions.create');
        Route::put('projects/{project}/permission', 'ProjectPermissionsController@store')->name('projects.permissions.store');

        Route::get('projects/{project}/timesheet', 'TimesheetsController@create')->name('projects.timesheet.create');
        Route::post('projects/{project}/timesheet', 'TimesheetsController@store')->name('projects.timesheet.store');
        Route::get('projects/{project}/timesheet/{timesheet}/edit', 'TimesheetsController@edit')->name('projects.timesheet.edit');
        Route::put('projects/{project}/timesheet/{timesheet}/update', 'TimesheetsController@update')->name('projects.timesheet.update');
        Route::delete('projects/{project}/timesheet/{timesheet}/destroy', 'TimesheetsController@destroy')->name('projects.timesheet.destroy');    

        //Tasks
        Route::get('projects/{project}/board', 'TasksController@board')->name('projects.task.board');
        Route::get('projects/{project}/task', 'TasksController@create')->name('projects.task.create');
        Route::post('projects/{project}/task', 'TasksController@store')->name('projects.task.store');
        Route::post('tasks/{task}/refresh/', 'TasksController@refresh')->name('tasks.refresh');
        Route::get('tasks/{task}', 'TasksController@show')->name('tasks.show');
        Route::get('tasks/{task}/edit', 'TasksController@edit')->name('tasks.edit');
        Route::put('tasks/{task}', 'TasksController@update')->name('tasks.update');
        Route::patch('tasks/{task}', 'TasksController@update')->name('tasks.update');
        Route::delete('tasks/{task}', 'TasksController@destroy')->name('tasks.destroy');
        Route::post('tasks/order', 'TasksController@order')->name('tasks.order');

        Route::get('tasks/{task}/comment', 'TaskCommentsController@index')->name('tasks.comment.index');
        Route::post('tasks/{task}/comment', 'TaskCommentsController@store')->name('tasks.comment.store');
        Route::put('tasks/{task}/comment/{comment}', 'TaskCommentsController@update')->name('tasks.comment.update');
        Route::delete('tasks/{task}/comment/{comment}', 'TaskCommentsController@destroy')->name('tasks.comment.destroy');

        Route::get('tasks/{task}/file', 'TaskFilesController@store')->name('tasks.file.index');
        Route::post('tasks/{task}/file', 'TaskFilesController@store')->name('tasks.file.upload');
        Route::get('tasks/{task}/file/{file}', 'TaskFilesController@show')->name('tasks.file.download');
        Route::delete('tasks/{task}/file/{file}', 'TaskFilesController@destroy')->name('tasks.file.delete');

        Route::get('tasks/{task}/checklist', 'TaskChecklistController@store')->name('tasks.checklist.index');
        Route::post('tasks/{task}/checklist', 'TaskChecklistController@store')->name('tasks.checklist.store');
        Route::put('tasks/{task}/checklist/{checklist}', 'TaskChecklistController@update')->name('tasks.checklist.update');
        Route::delete('tasks/{task}/checklist/{checklist}', 'TaskChecklistController@destroy')->name('tasks.checklist.destroy');
        Route::post('tasks/{task}/checklist/order', 'TaskChecklistController@order')->name('tasks.checklist.order');

        //Invoices
        Route::get('projects/{project}/invoice', 'InvoicesController@create')->name('projects.invoice.create');
        Route::resource('invoices', 'InvoicesController');

        //Invoice products
        Route::get('invoices/{invoice}/products', 'InvoiceProductsController@create')->name('invoices.products.create');
        Route::get('invoices/{invoice}/products/{product}', 'InvoiceProductsController@edit')->name('invoices.products.edit');
        Route::post('invoices/{invoice}/products', 'InvoiceProductsController@store')->name('invoices.products.store');
        Route::put('invoices/{invoice}/products/{product}', 'InvoiceProductsController@update')->name('invoices.products.update');
        Route::delete('invoices/{invoice}/products/{product}', 'InvoiceProductsController@delete')->name('invoices.products.delete');

        Route::post('invoices/milestone/task', 'InvoicesController@milestoneTask')->name('invoices.milestone.task');

        //Invoice payments
        Route::get('invoices/payments', 'InvoicePaymentsController@index')->name('invoices.payments.index');
        Route::get('invoices/{invoice}/payments', 'InvoicePaymentsController@create')->name('invoices.payments.create');
        Route::post('invoices/{invoice}/payments', 'InvoicePaymentsController@store')->name('invoices.payments.store');

        Route::resource('productunits', 'ProductUnitsController');
        Route::resource('expensescategory', 'ExpenseCategoriesController');
        Route::resource('taxes', 'TaxesController');
        Route::resource('products', 'ProductsController');

        //Expenses
        Route::get('projects/{project}/expense', 'ExpensesController@create')->name('projects.expense.create');
        Route::resource('expenses', 'ExpensesController');

        //Payments
        Route::resource('payments', 'PaymentTypesController');
        Route::get('orders', 'StripePaymentsController@index')->name('order.index');

        Route::get('stripe/{code}', 'StripePaymentsController@stripe')->name('stripe');
        Route::post('stripe', 'StripePaymentsController@stripePost')->name('stripe.post');
    
        //Notes
        // Route::resource('notes', 'NotesController');
});