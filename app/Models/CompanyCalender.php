<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyCalender extends Model
{
    use HasFactory;
    protected $table = 'company_calender';
    protected $fillable = ['company_id', 'calendar_type', 'client_secret', 'client_id', 'api_key', 'cc_email', 'redirect'];
}
