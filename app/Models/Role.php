<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    protected $fillable = ['name', 'guard_name', 'label', 'department_id'];

    public function hospitalDepartment()
    {
        return $this->belongsTo(HospitalDepartment::class, 'department_id');
    }
}
