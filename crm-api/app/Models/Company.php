<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Company extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'logo',
        'website'
    ];

    public function employees() {

        return $this->hasMany(Employee::class);

    }

}
