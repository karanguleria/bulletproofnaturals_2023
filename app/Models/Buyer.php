<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Buyer extends Model
{
    use HasFactory;
    protected static $unguarded = true;
    protected $table = 'buyer';

    protected $fillable = [
        'email','fname','lname', 'street', 'apartement','city','state','zip','phone'
    ];
    
}
