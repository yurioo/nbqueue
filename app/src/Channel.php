<?php
namespace App\src;

use Illuminate\Database\Eloquent\Model;

class Channel extends Model 
{
    protected $fillable = [
        'provider',
        'provider_id',
        'active',
    ];
}
?>