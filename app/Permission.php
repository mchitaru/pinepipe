<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission as BasePermission;

use App\Scopes\CompanyTenantScope;

class Permission extends BasePermission
{
}