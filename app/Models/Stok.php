<?php

namespace App\Models;

use Ramsey\Uuid\Uuid;
use App\Models\Produk;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Stok extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = Uuid::uuid4()->toString();

            $produk = Produk::find($model->produk_id);

            if ($model->tipe === 'masuk') {
                $produk->stok += $model->stok;
            } elseif ($model->tipe === 'keluar') {
                $produk->stok -= $model->stok;
            }

            $produk->save();
        });
        static::updating(function ($model) {

            $produk = Produk::find($model->produk_id);

            if ($model->tipe === 'masuk') {
                $produk->stok += $model->stok - $model->getOriginal('stok');
            } elseif ($model->tipe === 'keluar') {
                $produk->stok -= $model->stok - $model->getOriginal('stok');
            }

            $produk->save();
        });
        static::deleting(function ($stok) {
            $produk = Produk::find($stok->produk_id);

            if ($produk) {
                if ($stok->tipe === 'masuk') {
                    $produk->stok -= $stok->stok;
                } elseif ($stok->tipe === 'keluar') {
                    $produk->stok += $stok->stok;
                }

                $produk->save();
            }
        });
    }

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }
}
