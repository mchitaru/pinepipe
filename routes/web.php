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

Route::get('/', 'WorkspaceController@index')->name('home')->middleware(
    [
        'auth',
        'xss',
    ]
);

Route::get('/home', 'WorkspaceController@index')->name('home')->middleware(
    [
        'auth',
        'xss',
    ]
);

Route::resource('users', 'UsersController')->middleware(
    [
        'auth',
        'xss',
    ]
);

Route::get('profile/{id}', 'UsersController@profile')->name('profile')->middleware(
    [
        'auth',
        'xss',
    ]
);
Route::put('edit-profile', 'UsersController@editprofile')->name('update.account')->middleware(
    [
        'auth',
        'xss',
    ]
);

Route::put('change-password', 'UsersController@updatePassword')->name('update.password');


Route::resource('clients', 'ClientsController')->middleware(
    [
        'auth',
        'xss',
    ]
);

Route::resource('contacts', 'ContactsController')->middleware(
    [
        'auth',
        'xss',
    ]
);

Route::resource('roles', 'UserRolesController')->middleware(
    [
        'auth',
        'xss',
    ]
);
Route::resource('permissions', 'PermissionsController')->middleware(
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
    Route::get('change-language/{lang}', 'LanguagesController@changeLanquage')->name('change.language');
    Route::get('manage-language/{lang}', 'LanguagesController@manageLanguage')->name('manage.language');
    Route::post('store-language-data/{lang}', 'LanguagseController@storeLanguageData')->name('store.language.data');
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
    Route::resource('leadstages', 'LeadStagesController');
    Route::post(
        '/leadstages/order', [
                               'as' => 'leadstages.order',
                               'uses' => 'LeadStagesController@order',
                           ]
    );
}
);
Route::group(
    [
        'middleware' => [
            'auth',
            'xss',
        ],
    ], function (){
    Route::resource('projectstages', 'ProjectStagesController');
    Route::post(
        '/projectstages/order', [
                                  'as' => 'projectstages.order',
                                  'uses' => 'ProjectStagesController@order',
                              ]
    );
}
);
Route::group(
    [
        'middleware' => [
            'auth',
            'xss',
        ],
    ], function (){
    Route::resource('leadsources', 'LeadSourcesController');
}
);
Route::resource('labels', 'LabelsController')->middleware(
    [
        'auth',
        'xss',
    ]
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
Route::post(
    '/leads/order', [
                      'as' => 'leads.order',
                      'uses' => 'LeadsController@order',
                  ]
)->middleware(
    [
        'auth',
        'xss',
    ]
);
Route::resource('leads', 'LeadsController')->middleware(
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

    Route::resource('projects', 'ProjectsController');

    Route::get('projects/{id}/milestone', 'ProjectMilestonesController@create')->name('projects.milestone.create');
    Route::post('projects/{id}/milestone', 'ProjectMilestonesController@store')->name('projects.milestone.store');
    Route::get('projects/milestone/{id}/edit', 'ProjectMilestonesController@edit')->name('projects.milestone.edit');
    Route::put('projects/milestone/{id}', 'ProjectMilestonesController@update')->name('projects.milestone.update');
    Route::delete('projects/milestone/{id}', 'ProjectMilestonesController@destroy')->name('projects.milestone.destroy');
    Route::get('projects/milestone/{id}/show', 'ProjectMilestonesController@show')->name('projects.milestone.show');

    Route::post('projects/{id}/file', 'ProjectFilesController@store')->name('projects.file.upload');
    Route::get('projects/{id}/file/{fid}', 'ProjectFilesController@show')->name('projects.file.download');
    Route::delete('projects/{id}/file/delete/{fid}', 'ProjectFilesController@destroy')->name('projects.file.delete');
    
    Route::get('projects/{id}/task', 'ProjectTasksController@create')->name('projects.task.create');
    Route::post('projects/{id}/task', 'ProjectTasksController@store')->name('projects.task.store');

    Route::put('projects/{id}/status', 'ProjectsController@updateStatus')->name('projects.update.status');//TO DO
    
    Route::get('project-invite/{project_id}', 'ProjectsController@userInvite')->name('project.invite');
    Route::post('invite/{project}', 'ProjectsController@Invite')->name('invite');

    Route::get('projects/{id}/client/{cid}/permission', 'ProjectsController@clientPermission')->name('projects.client.permission');
    Route::put('projects/{id}/client/{cid}/permission', 'ProjectsController@storeClientPermission')->name('projects.client.permission.store');
}
);

Route::resource('timesheets', 'TimesheetsController')->middleware(
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
    Route::resource('tasks', 'TasksController');

    Route::post('tasks/{tid}/comment', 'TaskCommentsController@store')->name('tasks.comment.store');
    Route::delete('tasks/comment/{id}', 'TaskCommentsController@destroy')->name('tasks.comment.destroy');

    Route::post('tasks/{id}/file', 'TaskFilesController@store')->name('tasks.file.upload');
    Route::get('tasks/{id}/file/{fid}', 'TaskFilesController@show')->name('tasks.file.download');
    Route::delete('tasks/{id}/file/delete/{fid}', 'TaskFilesController@destroy')->name('tasks.file.delete');

    Route::post('tasks/{id}/checklist/store', 'TaskChecklistController@store')->name('tasks.checklist.store');
    Route::put('tasks/{id}/checklist/{cid}/update', 'TaskChecklistController@update')->name('tasks.checklist.update');
    Route::delete('tasks/{id}/checklist/{cid}', 'TaskChecklistController@destroy')->name('tasks.checklist.destroy');
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
    Route::resource('invoices', 'InvoiceController');

    Route::get('invoices/{id}/products', 'InvoicesController@productAdd')->name('invoices.products.add');
    Route::get('invoices/{id}/products/{pid}', 'InvoicesController@productEdit')->name('invoices.products.edit');
    Route::post('invoices/{id}/products', 'InvoicesController@productStore')->name('invoices.products.store');
    Route::put('invoices/{id}/products/{pid}', 'InvoicesController@productUpdate')->name('invoices.products.update');
    Route::delete('invoices/{id}/products/{pid}', 'InvoicesController@productDelete')->name('invoices.products.delete');
    Route::post('invoices/milestone/task', 'InvoicesController@milestoneTask')->name('invoices.milestone.task');

    Route::get('invoices-payments', 'InvoicesController@payments')->name('invoices.payments');
    Route::get('invoices/{id}/payments', 'InvoicesController@paymentAdd')->name('invoices.payments.create');
    Route::post('invoices/{id}/payments', 'InvoicesController@paymentStore')->name('invoices.payments.store');


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

