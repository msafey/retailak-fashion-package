<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $primaryKey = "id";

    protected $table = "branches";

    protected $fillable = ['branch_name', 'warehouse_id', 'district_id', 'status'];



    public function workers()
    {
        return $this->hasMany(AdminUser::class,'branch_id');
    }
}
