<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    protected $fillable = ['name', 'parent_id', 'slug'];

    public function parent()
    {
        return $this->belongsTo(Category::class);
    }

    public function scopeGetParent($query)
    {
        // SEMUA QUERY YANG PAKE LOCAL SCOPE INI NANTI OTOMATIS DITAMBAHKAN KONDISI whereNul('parent_id')
        return $query->whereNull('parent_id');
    }

    // MUTATOR
    // Modifikasi data sebelum data disimpan kedalam database
    public function setSlugAttribute($value)
    {
        $this->attributes['slug'] = Str::slug($value);
    }
    // ACCESSOR
    public function getNameAttribute($value)
    {
        return ucfirst($value);
    }

    public function child()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function product()
    {
        return $this->hasMany(Product::class);
    }
}
