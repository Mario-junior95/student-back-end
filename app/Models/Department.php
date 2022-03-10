<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $table = "departments";

    protected $guarded= [];

    public function childs() {
        return $this->hasMany(Department::class,'parent_id','id')->with('childs') ;
    }
}
