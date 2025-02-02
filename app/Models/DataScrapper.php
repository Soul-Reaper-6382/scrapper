<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataScrapper extends Model
{
    use HasFactory;
    protected $table = 'Datascrapper';
    protected $fillable = ['userid', 'data', 'url'];
}
