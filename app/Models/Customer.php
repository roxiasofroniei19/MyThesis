<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Schedule;

class Customer extends Model
{
    use HasFactory;

    protected $table = "clients";
    protected $primaryKey = "ClientId";
    public $timestamps = false;

    public function schedules() {
        return $this->hasMany(Schedule::class);
    }
}
