<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AvatarProfil extends Model
{
    use HasFactory;

    protected $table = 'avatar_profil';

    protected $fillable = [
        'nama',
        'path',
    ];

    public function penggunas()
    {
        return $this->hasMany(Pengguna::class, 'avatar_profil_id');
    }
}
