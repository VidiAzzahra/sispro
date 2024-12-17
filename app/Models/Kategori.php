<?php

namespace App\Models;

use Ramsey\Uuid\Uuid;
use App\Models\Barang;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $guarded = ['id'];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = Uuid::uuid4()->toString();
        });
    }

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public function barangs()
    {
        return $this->hasMany(Barang::class);
    }

}
