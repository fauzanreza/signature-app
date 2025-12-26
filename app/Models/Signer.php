<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Signer extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'role'];

    public function documents()
    {
        return $this->hasMany(Document::class);
    }
}
