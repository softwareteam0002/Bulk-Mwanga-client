<?php


use DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs;

 Breadcrumbs::for('organization', function ($trail) {

    $trail->push('Organization', route('organization'));

 });

Breadcrumbs::for('organization-create', function ($trail) {

    $trail->parent('organization');
    $trail->push('Create', url('organization/create'));
    $trail->push('Details', url('organization/details-management'));

});

Breadcrumbs::for('organization-details', function ($trail) {

    $trail->parent('organization');
    $trail->push('Details', url('organization/details-management'));

});


Breadcrumbs::for('organization-users', function ($trail) {

    $trail->parent('organization');
    $trail->push('Users', url('organization/users'));

});

Breadcrumbs::for('roles',function ($trail){

    $trail->push('Roles',url('roles'));

});

Breadcrumbs::for('roles-create',function ($trail){

    $trail->parent('roles');
    $trail->push('Create',url('roles/create'));

});

Breadcrumbs::for('roles-view',function ($trail){

    $trail->parent('roles');
    $trail->push('View',url('roles/view/1'));

});

Breadcrumbs::for('withdrawal-fees',function ($trail){

    $trail->push('Withdrawal fees',url('withdrawal-fees'));

});

Breadcrumbs::for('withdrawal-fees-create',function ($trail){

    $trail->parent('withdrawal-fees');
    $trail->push('Add new withdrawal fee',url('withdrawal-fees/create'));

});

Breadcrumbs::for('withdrawal-fees-update',function ($trail){
    $trail->parent('withdrawal-fees');
    $trail->push('Edit',url('withdrawal-fees/edit'));

});

Breadcrumbs::for('transaction-charges',function ($trail){

    $trail->push('Transaction Charges',url('transaction-charges'));

});

Breadcrumbs::for('transaction-charges-create',function ($trail){

    $trail->parent('transaction-charges');
    $trail->push('Add new transaction Charges',url('transaction-charges/create'));

});

Breadcrumbs::for('transaction-charges-update',function ($trail){
    $trail->parent('transaction-charges');
    $trail->push('Edit',url('transaction-charges/edit'));

});

Breadcrumbs::for('users',function ($trail){

    $trail->push('Users',url('users'));

});


Breadcrumbs::for('user-create',function ($trail){

    $trail->parent('users');
    $trail->push('Create',url('users/create'));

});

Breadcrumbs::for('user-edit',function ($trail){

    $trail->parent('users');
    $trail->push('Edit',url('users/2/edit'));

});

Breadcrumbs::for('user-view',function ($trail){

    $trail->parent('users');
    $trail->push('View',url('users/view/2'));

});



Breadcrumbs::for('disbursement',function ($trail){

    $trail->push('Disbursement',url('disbursement/payments'));

});

Breadcrumbs::for('disbursement-create',function ($trail){

    $trail->parent('disbursement');
    $trail->push('Create',url('disbursement/create'));

});

Breadcrumbs::for('disbursement-view',function ($trail){

    $trail->parent('disbursement');
    $trail->push('View',url('disbursement/view'));

});

Breadcrumbs::for('disbursement-progress',function ($trail){

    $trail->parent('disbursement');
    $trail->push('Progress',url('disbursement/progress'));

});

Breadcrumbs::for('batches-for-verification',function ($trail){
    $trail->push('Home',url('/'));
    $trail->push('Verification');

});


Breadcrumbs::for('reports',function ($trail){

    $trail->push('Reports',url('reports'));

});
//
Breadcrumbs::for('reports-per-batch',function ($trail){

    $trail->parent('reports');
    $trail->push('Disbursement Per Batch',url('reports'));

});

Breadcrumbs::for('reports-per-inbatch',function ($trail){

    $trail->parent('reports');
    $trail->push('View All',url('reports'));

});

Breadcrumbs::for('initiator',function ($trail){

    $trail->push('Initiator',url('organization/initiator'));

});

Breadcrumbs::for('initiator-create',function ($trail){

    $trail->parent('initiator');

    $trail->push('Create',url('organization/initiator/create'));

});


