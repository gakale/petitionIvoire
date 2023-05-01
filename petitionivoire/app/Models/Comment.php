<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Petition;
use App\Models\Report;

class Comment extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function petition()
    {
        return $this->belongsTo(Petition::class);
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

}
