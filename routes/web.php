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
        Route::get('clients', 'ClientsController@index')->name('clients.index');
        Route::get('clients/create', 'ClientsController@create')->name('clients.create');
        Route::post('clients', 'ClientsController@store')->name('clients.store');
        Route::get('clients/{client}', 'ClientsController@show')->name('clients.show');
        Route::get('clients/{client}/edit', 'ClientsController@edit')->name('clients.edit');
        Route::put('clients/{client}', 'ClientsController@update')->name('clients.update');
        Route::delete('clients/{client}', 'ClientsController@destroy')->name('clients.destroy');

        Route::get('contacts', 'ContactsController@index')->name('contacts.index');
        Route::get('contacts/create', 'ContactsController@create')->name('contacts.create');
        Route::post('contacts', 'ContactsController@store')->name('contacts.store');
        Route::get('contacts/{contact}', 'ContactsController@show')->name('contacts.show');
        Route::get('contacts/{contact}/edit', 'ContactsController@edit')->name('contacts.edit');
        Route::put('contacts/{contact}', 'ContactsController@update')->name('contacts.update');
        Route::delete('contacts/{contact}', 'ContactsController@destroy')->name('contacts.destroy');

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
        Route::post('/leads/order', [
            'as' => 'leads.order',
            'uses' => 'LeadsController@order',
        ]);

        Route::get('leads/create', 'LeadsController@create')->name('leads.create');
        Route::post('leads', 'LeadsController@store')->name('leads.store');
        Route::get('leads/{lead}', 'LeadsController@show')->name('leads.show');
        Route::get('leads/{lead}/edit', 'LeadsController@edit')->name('leads.edit');
        Route::put('leads/{lead}', 'LeadsController@update')->name('leads.update');
        Route::delete('leads/{lead}', 'LeadsController@destroy')->name('leads.destroy');

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
    
        Route::get('projects', 'ProjectsController@index')->name('projects.index');
        Route::get('projects/create', 'ProjectsController@create')->name('projects.create');
        Route::post('projects', 'ProjectsController@store')->name('projects.store');
        Route::get('projects/{project}', 'ProjectsController@show')->name('projects.show');
        Route::get('projects/{project}/edit', 'ProjectsController@edit')->name('projects.edit');
        Route::put('projects/{project}', 'ProjectsController@update')->name('projects.update');
        Route::patch('projects/{project}', 'ProjectsController@update')->name('projects.update');
        Route::delete('projects/{project}', 'ProjectsController@destroy')->name('projects.destroy');

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

        Route::get('projects/{project}/client/{client}/permission', 'ProjectsController@clientPermission')->name('projects.client.permission');
        Route::put('projects/{project}/client/{client}/permission', 'ProjectsController@storeClientPermission')->name('projects.client.permission.store');

        Route::get('projects/{project}/timesheet', 'TimesheetsController@create')->name('projects.timesheet.create');
        Route::post('projects/{project}/timesheet', 'TimesheetsController@store')->name('projects.timesheet.store');
        Route::get('projects/{project}/timesheet/{timesheet}/edit', 'TimesheetsController@edit')->name('projects.timesheet.edit');
        Route::put('projects/{project}/timesheet/{timesheet}/update', 'TimesheetsController@update')->name('projects.timesheet.update');
        Route::delete('projects/{project}/timesheet/{timesheet}/destroy', 'TimesheetsController@destroy')->name('projects.timesheet.destroy');    

        //Tasks
        Route::get('projects/{project}/board', 'TasksController@board')->name('projects.task.board');
        Route::get('projects/{project}/task', 'TasksController@create')->name('projects.task.create');
        Route::post('projects/{project}/task', 'TasksController@store')->name('projects.task.store');
        Route::get('tasks/{task}', 'TasksController@show')->name('tasks.show');
        Route::get('tasks/{task}/edit', 'TasksController@edit')->name('tasks.edit');
        Route::put('tasks/{task}', 'TasksController@update')->name('tasks.update');
        Route::patch('tasks/{task}', 'TasksController@update')->name('tasks.update');
        Route::delete('tasks/{task}', 'TasksController@destroy')->name('tasks.destroy');

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

        //Invoices
        Route::get('projects/{project}/invoice', 'InvoicesController@create')->name('projects.invoice.create');
        Route::get('invoices', 'InvoicesController@index')->name('invoices.index');
        Route::post('invoices', 'InvoicesController@store')->name('invoices.store');
        Route::get('invoices/{invoice}', 'InvoicesController@show')->name('invoices.show');
        Route::get('invoices/{invoice}/edit', 'InvoicesController@edit')->name('invoices.edit');
        Route::put('invoices/{invoice}', 'InvoicesController@update')->name('invoices.update');
        Route::delete('invoices/{invoice}', 'InvoicesController@destroy')->name('invoices.destroy');

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
        Route::get('expenses', 'ExpensesController@index')->name('expenses.index');
        Route::post('expenses', 'ExpensesController@store')->name('expenses.store');
        Route::get('expenses/{expense}', 'ExpensesController@show')->name('expenses.show');
        Route::get('expenses/{expense}/edit', 'ExpensesController@edit')->name('expenses.edit');
        Route::put('expenses/{expense}', 'ExpensesController@update')->name('expenses.update');
        Route::delete('expenses/{expense}', 'ExpensesController@destroy')->name('expenses.destroy');

        //Payments
        Route::resource('payments', 'PaymentTypesController');
        Route::get('orders', 'StripePaymentsController@index')->name('order.index');

        Route::get('stripe/{code}', 'StripePaymentsController@stripe')->name('stripe');
        Route::post('stripe', 'StripePaymentsController@stripePost')->name('stripe.post');
    
        //Notes
        // Route::resource('notes', 'NotesController');
});