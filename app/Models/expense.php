<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class expense extends Model
{
    use HasFactory;

    protected $table = 'expenses'; // nama table
    protected $primaryKey = 'id'; // primary key dari table tersebut

    protected $fillable = [
        'balance',
        'title',
        'amount',
        'category',
        'type',
        'description',
    ]; // field yang boleh diisi
}
