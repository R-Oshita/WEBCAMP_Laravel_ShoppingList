<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShoppingLists extends Model
{
    use HasFactory;

    // task_table参照
    /**
     * 複数代入不可能な属性
     */
    protected $guarded = ['id'];
    
}
