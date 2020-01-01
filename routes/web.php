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

Route::get('/team', function () {
    return view('team');
})->name('team')->middleware(
    [
        'auth',
        'xss',
    ]
);

Route::get('/project', function () {
    return view('project');
})->name('project')->middleware(
    [
        'auth',
        'xss',
    ]
);

Route::get('/task', function () {
    return view('task');
})->name('task')->middleware(
    [
        'auth',
        'xss',
    ]
);

Route::get('/kanban', function () {
    return view('kanban');
})->name('kanban')->middleware(
    [
        'auth',
        'xss',
    ]
);

Route::get('/user', function () {
    return view('user');
})->name('user')->middleware(
    [
        'auth',
        'xss',
    ]
);

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
Route::get('profile/{id}', 'UserController@profile')->name('profile')->middleware(
    [
        'auth',
        'xss',
    ]
);
Route::put('edit-profile', 'UserController@editprofile')->name('update.account')->middleware(
    [
        'auth',
        'xss',
    ]
);

Route::resource('users', 'UserController')->middleware(
    [
        'auth',
        'xss',
    ]
);
Route::put('change-password', 'UserController@updatePassword')->name('update.password');

Route::resource('clients', 'ClientController')->middleware(
    [
        'auth',
        'xss',
    ]
);

Route::resource('roles', 'RoleController')->middleware(
    [
        'auth',
        'xss',
    ]
);
Route::resource('permissions', 'PermissionController')->middleware(
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
    Route::get('change-language/{lang}', 'LanguageController@changeLanquage')->name('change.language');
    Route::get('manage-language/{lang}', 'LanguageController@manageLanguage')->name('manage.language');
    Route::post('store-language-data/{lang}', 'LanguageController@storeLanguageData')->name('store.language.data');
    Route::get('create-language', 'LanguageController@createLanguage')->name('create.language');
    Route::post('store-language', 'LanguageController@storeLanguage')->name('store.language');
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
    Route::resource('leadstages', 'LeadstagesController');
    Route::post(
        '/leadstages/order', [
                               'as' => 'leadstages.order',
                               'uses' => 'LeadstagesController@order',
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
    Route::resource('projectstages', 'ProjectstagesController');
    Route::post(
        '/projectstages/order', [
                                  'as' => 'projectstages.order',
                                  'uses' => 'ProjectstagesController@order',
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
    Route::resource('leadsources', 'LeadsourceController');
}
);
Route::resource('labels', 'LabelsController')->middleware(
    [
        'auth',
        'xss',
    ]
);
Route::resource('productunits', 'ProductunitsController')->middleware(
    [
        'auth',
        'xss',
    ]
);
Route::resource('expensescategory', 'ExpensesCategoryController')->middleware(
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
Route::resource('contacts', 'ContactsController')->middleware(
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
    Route::put('projects/{id}/status', 'ProjectsController@updateStatus')->name('projects.update.status');
    Route::resource('projects', 'ProjectsController');
    Route::get('project-invite/{project_id}', 'ProjectsController@userInvite')->name('project.invite');
    Route::post('invite/{project}', 'ProjectsController@Invite')->name('invite');

    Route::get('projects/{id}/milestone', 'ProjectsController@milestone')->name('project.milestone');
    Route::post('projects/{id}/milestone', 'ProjectsController@milestoneStore')->name('project.milestone.store');
    Route::get('projects/milestone/{id}/edit', 'ProjectsController@milestoneEdit')->name('project.milestone.edit');
    Route::put('projects/milestone/{id}', 'ProjectsController@milestoneUpdate')->name('project.milestone.update');
    Route::delete('projects/milestone/{id}', 'ProjectsController@milestoneDestroy')->name('project.milestone.destroy');
    Route::get('projects/milestone/{id}/show', 'ProjectsController@milestoneShow')->name('project.milestone.show');

    Route::post('projects/{id}/file', 'ProjectsController@fileUpload')->name('project.file.upload');
    Route::get('projects/{id}/file/{fid}', 'ProjectsController@fileDownload')->name('projects.file.download');
    Route::delete('projects/{id}/file/delete/{fid}', 'ProjectsController@fileDelete')->name('projects.file.delete');

    Route::get('projects/{id}/taskboard', 'ProjectsController@taskBoard')->name('project.taskboard');
    Route::get('projects/{id}/taskboard/create', 'ProjectsController@taskCreate')->name('task.create');
    Route::post('projects/{id}/taskboard/store', 'ProjectsController@taskStore')->name('task.store');
    Route::get('projects/taskboard/{id}/edit', 'ProjectsController@taskEdit')->name('task.edit');
    Route::put('projects/taskboard/{id}/update', 'ProjectsController@taskUpdate')->name('task.update');
    Route::delete('projects/taskboard/{id}/delete', 'ProjectsController@taskDestroy')->name('task.destroy');
    Route::get('projects/taskboard/{id}/show', 'ProjectsController@taskShow')->name('task.show');
    Route::post('projects/order', 'ProjectsController@order')->name('taskboard.order');

    Route::post('projects/{id}/taskboard/{tid}/comment', 'ProjectsController@commentStore')->name('comment.store');
    Route::post('projects/taskboard/{id}/file', 'ProjectsController@commentStoreFile')->name('comment.file.store');
    Route::delete('projects/taskboard/comment/{id}', 'ProjectsController@commentDestroy')->name('comment.destroy');
    Route::delete('projects/taskboard/file/{id}', 'ProjectsController@commentDestroyFile')->name('comment.file.destroy');

    Route::post('projects/taskboard/{id}/checklist/store', 'ProjectsController@checkListStore')->name('task.checklist.store');
    Route::put('projects/taskboard/{id}/checklist/{cid}/update', 'ProjectsController@checklistUpdate')->name('task.checklist.update');
    Route::delete('projects/taskboard/{id}/checklist/{cid}', 'ProjectsController@checklistDestroy')->name('task.checklist.destroy');

    Route::get('projects/{id}/client/{cid}/permission', 'ProjectsController@clientPermission')->name('client.permission');
    Route::put('projects/{id}/client/{cid}/permission', 'ProjectsController@storeClientPermission')->name('client.store.permission');
}
);


Route::group(
    [
        'middleware' => [
            'auth',
            'xss',
        ],
    ], function (){
    Route::resource('invoices', 'InvoiceController');

    Route::get('invoices/{id}/products', 'InvoiceController@productAdd')->name('invoices.products.add');
    Route::get('invoices/{id}/products/{pid}', 'InvoiceController@productEdit')->name('invoices.products.edit');
    Route::post('invoices/{id}/products', 'InvoiceController@productStore')->name('invoices.products.store');
    Route::put('invoices/{id}/products/{pid}', 'InvoiceController@productUpdate')->name('invoices.products.update');
    Route::delete('invoices/{id}/products/{pid}', 'InvoiceController@productDelete')->name('invoices.products.delete');
    Route::post('invoices/milestone/task', 'InvoiceController@milestoneTask')->name('invoices.milestone.task');

    Route::get('invoices-payments', 'InvoiceController@payments')->name('invoices.payments');
    Route::get('invoices/{id}/payments', 'InvoiceController@paymentAdd')->name('invoices.payments.create');
    Route::post('invoices/{id}/payments', 'InvoiceController@paymentStore')->name('invoices.payments.store');


}
);
Route::resource('taxes', 'TaxController');
Route::resource('plans', 'PlanController')->middleware(
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
Route::resource('expenses', 'ExpenseController')->middleware(
    [
        'auth',
        'xss',
    ]
);
Route::resource('payments', 'PaymentController')->middleware(
    [
        'auth',
        'xss',
    ]
);
Route::resource('notes', 'NoteController')->middleware(
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

    Route::get('/orders', 'StripePaymentController@index')->name('order.index');
    Route::get('/stripe/{code}', 'StripePaymentController@stripe')->name('stripe');
    Route::post('/stripe', 'StripePaymentController@stripePost')->name('stripe.post');

}
);

