<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model {
    use HasFactory;
    
    protected $fillable = [
        'user_id', 'signer_id', 'file_name', 'document_number', 'file_path', 'role',
        'page_number', 'qr_x', 'qr_y', 'qr_width', 'qr_height',
        'qr_data', 'status'
    ];
    
    protected $casts = [
        'qr_x' => 'float',
        'qr_y' => 'float',
        'qr_width' => 'float',
        'qr_height' => 'float',
    ];
    
    public function user() {
        return $this->belongsTo(User::class);
    }

    public function signer() {
        return $this->belongsTo(Signer::class);
    }
}