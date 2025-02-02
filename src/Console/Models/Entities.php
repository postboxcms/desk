<?php

namespace PostboxCMS\Desk\Console\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entities extends Model
{
    use HasFactory;
    protected $fillable = ['name','description','icon','slug','model'];
    protected $table = "entities";
}