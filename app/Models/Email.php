<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;

class Email extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'type', 'body'];


    public function setBodyAttribute($value)
    {
        $this->attributes['body'] = new HtmlString($value);
    }
    public function getBodyAttribute($value)
    {
        return new HtmlString($value);
    }
}
