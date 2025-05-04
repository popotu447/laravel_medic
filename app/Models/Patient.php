<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    /** @use HasFactory<\Database\Factories\PatientFactory> */
    use HasFactory;

    protected $fillable = ['firstName', 'lastName', 'birthDate', 'gender'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function files()
    {
        return $this->hasMany(File::class);
    }

}
