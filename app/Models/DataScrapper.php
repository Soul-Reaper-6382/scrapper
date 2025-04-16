<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataScrapper extends Model
{
    use HasFactory;
    protected $table = 'datascrapper';
    protected $fillable = ['userid', 'data', 'url', 'table_id', 'table_class', 'headers', 'type'];
}
