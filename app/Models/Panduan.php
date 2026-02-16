<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Panduan extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul',
        'ikon',
        'deskripsi',
        'konten',
        'urutan',
        'is_active',
    ];

    protected $casts = [
        'konten' => 'array',
        'is_active' => 'boolean',
        'urutan' => 'integer',
    ];

    /**
     * Scope to filter only active panduan items.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to order by urutan.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('urutan', 'asc');
    }
}
