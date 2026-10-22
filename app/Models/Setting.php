<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $primaryKey = 'key';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;
    protected $guarded = [];

    public static function ambil(string $key, string $default = ''): string
    {
        return static::find($key)?->value ?? $default;
    }

    public static function simpan(string $key, $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => (string) $value]);
    }

    public static function anggaranAktif(): bool { return static::ambil('anggaran_aktif', '0') === '1'; }
    public static function sisaAnggaran(): int   { return (int) static::ambil('anggaran_total', '0'); }
    public static function maintenance(): bool   { return static::ambil('maintenance', '0') === '1'; }
}
