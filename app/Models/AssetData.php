<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AssetData extends Model
{
    use HasFactory, Uuid;
    protected $guarded = [];
    public function satuan_asset()
    {
        return $this->belongsTo(SatuanAsset::class, 'id_satuan_asset', 'id');
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'id_vendor', 'id');
    }

    public function lokasi()
    {
        return $this->belongsTo(Lokasi::class, 'id_lokasi', 'id');
    }

    public function kelas_asset()
    {
        return $this->belongsTo(KelasAsset::class, 'id_kelas_asset', 'id');
    }

    public function kategori_asset()
    {
        return $this->belongsTo(KategoriAsset::class, 'id_kategori_asset', 'id');
    }

    public function image()
    {
        return $this->morphMany(AssetImage::class, 'imageable');
    }

    public function detail_service()
    {
        return $this->hasMany(DetailService::class, 'id_asset_data', 'id');
    }
}
