<?php
namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable {
    use HasFactory;
    
    protected $fillable = [
        'name', 'username', 'email', 'role', 'password',
    ];
    
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    public function documents() {
        return $this->hasMany(Document::class);
    }
    
    public function signer() {
        return $this->hasOne(Signer::class);
    }
}