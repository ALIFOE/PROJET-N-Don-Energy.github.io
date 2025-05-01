<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_message_id',
        'file_name',
        'file_path',
        'mime_type',
        'size'
    ];

    public function message()
    {
        return $this->belongsTo(AdminMessage::class);
    }
}
