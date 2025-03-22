<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Signature extends Model
{
    protected $fillable = ['signer_id', 'signature', 'page_no', 'coordinate_x', 'coordinate_y', 'height', 'width'];

    public function signer()
    {
        return $this->belongsTo(Signer::class);
    }
}
