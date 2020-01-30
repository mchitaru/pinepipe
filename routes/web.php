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
Route::get('searchJson/{search?}', 'ProjectsController@getSearchJson')->name('search.json')->middleware(['auth','xss']);

Auth::routes();

Route::group(
    [
        'middleware' => [
            'auth',
            'xss',
        ],
    ], function (){

        //Workspace
        Route::get('/', 'WorkspaceController@index')->name('home');
        Route::get('/home', 'WorkspaceController@index')->name('home');
}
);

Route::put('change-password', 'UsersController@updatePassword')->name('update.password');

Route::group(
    [
        'middleware' => [
            'auth',
            'xss',
        ],
    ], function (){

        //Users
        Route::resource('users', 'UsersController');
        Route::get('profile/{profile}', 'UsersController@profile')->name('profile');
        Route::put('edit-profile', 'UsersController@editprofile')->name('update.account');
        Route::resource('roles', 'UserRolesController');
        Route::resource('permissions', 'PermissionsController');

        //Clients
        Route::resource('clients', 'ClientsController');
        Route::resource('contacts', 'ContactsController');
}
);


Route::group(
    [
        'middleware' => [
            'auth',
            'xss',
        ],
    ], function (){

        //Language
        Route::get('change-language/{lang}', 'LanguagesController@changeLanquage')->name('change.language');
        Route::get('manage-language/{lang}', 'LanguagesController@manageLanguage')->name('manage.language');
        Route::post('store-language-data/{lang}', 'LanguagesController@storeLanguageData')->name('store.language.data');
        Route::get('create-language', 'LanguagesController@createLanguage')->name('create.language');
        Route::post('store-language', 'LanguagesController@storeLanguage')->name('store.language');
}
);

Route::group(
    [
        'middleware' => [
            'auth',
            'xss',
        ],
    ], function (){

        //System
        Route::resource('systems', 'SystemController');
        Route::post('email-settings', 'SystemController@saveEmailSettings')->name('email.settings');
        Route::post('company-settings', 'SystemController@saveCompanySettings')->name('company.settings');
        Route::post('stripe-settings', 'SystemController@saveStripeSettings')->name('stripe.settings');
        Route::post('system-settings', 'SystemController@saveSystemSettings')->name('system.settings');
        Route::get('company-setting', 'SystemController@companyIndex')->name('company.setting');
}
);

Route::group(
    [
        'middleware' => [
            'auth',
            'xss',
        ],
    ], function (){
    
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
        Route::resource('leads', 'LeadsController');
}
);

Route::resource('productunits', 'ProductUnitsController')->middleware(
    [
        'auth',
        'xss',
    ]
);
Route::resource('expensescategory', 'ExpenseCategoriesController')->middleware(
    [
        'auth',
        'xss',
    ]
);

Route::group(
    [
        'middleware' => [
            'auth',
            'xss',
        ],
    ], function (){

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
        Route::get('projects/file/{file}', 'ProjectFilesController@show')->name('projects.file.download');
        Route::delete('projects/file/{file}', 'ProjectFilesController@destroy')->name('projects.file.delete');
        
        Route::get('projects/{project}/task', 'TasksController@create')->name('projects.task.create');
        Route::get('projects/{project}/board', 'TasksController@board')->name('projects.task.board');

        Route::put('projects/{project}/status', 'ProjectsController@updateStatus')->name('projects.update.status');//TO DO
        
        Route::get('project-invite/{project}', 'ProjectsController@userInvite')->name('project.invite');
        Route::post('invite/{project}', 'ProjectsController@Invite')->name('invite');

        Route::get('projects/{project}/client/{client}/permission', 'ProjectsController@clientPermission')->name('projects.client.permission');
        Route::put('projects/{project}/client/{client}/permission', 'ProjectsController@storeClientPermission')->name('projects.client.permission.store');


        Route::get('projects/{project}/invoice', 'InvoicesController@create')->name('projects.invoice.create');
        Route::get('projects/{project}/expense', 'ExpensesController@create')->name('projects.expense.create');

        Route::get('projects/{project}/timesheet', 'TimesheetsController@create')->name('projects.timesheet.create');
        Route::post('projects/{project}/timesheet', 'TimesheetsController@store')->name('projects.timesheet.store');
        Route::get('projects/{project}/timesheet/{timesheet}/edit', 'TimesheetsController@edit')->name('projects.timesheet.edit');
        Route::put('projects/{project}/timesheet/{timesheet}/update', 'TimesheetsController@update')->name('projects.timesheet.update');
        Route::delete('projects/{project}/timesheet/{timesheet}/destroy', 'TimesheetsController@destroy')->name('projects.timesheet.destroy');    
}
);

Route::group(
    [
        'middleware' => [
            'auth',
            'xss',
        ],
    ], function (){

        //Tasks
        Route::resource('tasks', 'TasksController');

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
}
);

Route::resource('calendar', 'CalendarController')->middleware(
    [
        'auth',
        'xss',
    ]
);

Route::group(
    [
        'middleware' => [
            'auth',
            'xss',
        ],
    ], function (){

        //Invoices
        Route::resource('invoices', 'InvoicesController');

        Route::get('invoices/{invoice}/products', 'InvoicesController@productAdd')->name('invoices.products.add');
        Route::get('invoices/{invoice}/products/{product}', 'InvoicesController@productEdit')->name('invoices.products.edit');
        Route::post('invoices/{invoice}/products', 'InvoicesController@productStore')->name('invoices.products.store');
        Route::put('invoices/{invoice}/products/{product}', 'InvoicesController@productUpdate')->name('invoices.products.update');
        Route::delete('invoices/{invoice}/products/{product}', 'InvoicesController@productDelete')->name('invoices.products.delete');
        Route::post('invoices/milestone/task', 'InvoicesController@milestoneTask')->name('invoices.milestone.task');

        Route::get('invoices-payments', 'InvoicesController@payments')->name('invoices.payments');
        Route::get('invoices/{invoice}/payments', 'InvoicesController@paymentAdd')->name('invoices.payments.create');
        Route::post('invoices/{invoice}/payments', 'InvoicesController@paymentStore')->name('invoices.payments.store');


}
);
Route::resource('taxes', 'TaxesController');
Route::resource('plans', 'PaymentPlansController')->middleware(
    [
        'auth',
        'xss',
    ]
);;


Route::resource('products', 'ProductsController')->middleware(
    [
        'auth',
        'xss',
    ]
);
Route::resource('expenses', 'ExpensesController')->middleware(
    [
        'auth',
        'xss',
    ]
);

Route::resource('payments', 'PaymentsController')->middleware(
    [
        'auth',
        'xss',
    ]
);
Route::resource('notes', 'NotesController')->middleware(
    [
        'auth',
        'xss',
    ]
);


Route::group(
    [
        'middleware' => [
            'auth',
            'xss',
        ],
    ], function (){

    Route::get('/orders', 'StripePaymentsController@index')->name('order.index');
    Route::get('/stripe/{code}', 'StripePaymentsController@stripe')->name('stripe');
    Route::post('/stripe', 'StripePaymentsController@stripePost')->name('stripe.post');

}
);

