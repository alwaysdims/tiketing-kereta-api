<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class JadwalKereta extends Model
{
    use HasFactory;

    protected $table = 'jadwal_kereta';

    protected $fillable = [
        'id_kereta', // Changed from id_gerbong to id_kereta
        'id_stasiun_asal',
        'id_stasiun_tujuan',
        'jam_keberangkatan',
        'jam_kedatangan',
        'harga',
    ];

    protected $dates = ['jam_keberangkatan', 'jam_kedatangan'];

    // Accessor for is_expired
    public function getIsExpiredAttribute()
    {
        return Carbon::now()->greaterThan($this->jam_keberangkatan);
    }

    // Optional: Scope to filter out expired schedules
    public function scopeNotExpired($query)
    {
        return $query->where('jam_keberangkatan', '>=', Carbon::now());
    }

    public function stasiunAsal()
    {
        return $this->belongsTo(Stasiun::class, 'id_stasiun_asal');
    }

    public function stasiunTujuan()
    {
        return $this->belongsTo(Stasiun::class, 'id_stasiun_tujuan');
    }

    public function pemesanan()
    {
        return $this->hasMany(Pemesanan::class, 'id_jadwal');
    }

    public function kereta()
    {
        return $this->belongsTo(Kereta::class, 'id_kereta'); // Renamed from jadwal() to kereta()
    }
}
