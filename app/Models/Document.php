<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Document extends Model
{
    protected $fillable = ['original_filename', 'original_path','signed_filename', 'signed_path', 'short_url', 'total_signer', 'signed', 'sender_name', 'sender_email', 'sender_ip', 'last_signed_time'];

    public function signers()
    {
        return $this->hasMany(Signer::class);
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->toDateTimeString();
    }
}
