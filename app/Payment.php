<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $guarded = [];

    public function getStatusLabelAttribute()
    {
        if ( $this->status == 0 ) {
            return '<span class="badge badge-secondary">Menunggu Konfirmasi</span>';
        }

        return '<span class="badge badge-success">Diterima</span>';
    }
}
