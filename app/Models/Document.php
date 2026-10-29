<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'category',
        'description',
        'file_path',
        'original_filename',
        'is_active',
        'download_count',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'download_count' => 'integer',
        ];
    }
}
