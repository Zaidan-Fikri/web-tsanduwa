<?php

namespace App\Support\Spp;

/**
 * Kode akses cek tagihan untuk wali murid (dipakai bersama NISN).
 *
 * 6 karakter dari 31 huruf/angka yang tidak mirip satu sama lain (tanpa 0/O, 1/I/L)
 * => ±887 juta kemungkinan per siswa, sehingga tidak bisa ditebak.
 */
class AccessCode
{
    public const LENGTH = 6;

    private const ALPHABET = 'ABCDEFGHJKMNPQRSTUVWXYZ23456789';

    /**
     * Buat kode acak yang belum ada di $taken (kunci = kode).
     *
     * @param  array<string, true>  $taken
     */
    public static function generate(array &$taken = []): string
    {
        do {
            $code = '';
            for ($i = 0; $i < self::LENGTH; $i++) {
                $code .= self::ALPHABET[random_int(0, strlen(self::ALPHABET) - 1)];
            }
        } while (isset($taken[$code]));

        $taken[$code] = true;

        return $code;
    }

    /** Rapikan isian wali murid: "k7x-2mp " => "K7X2MP". */
    public static function normalize(?string $input): string
    {
        return strtoupper(preg_replace('/[^A-Za-z0-9]/', '', (string) $input));
    }

    /** "K7X2MP" => "K7X-2MP" (lebih mudah dibaca/dicatat). */
    public static function format(?string $code): string
    {
        return $code ? substr($code, 0, 3).'-'.substr($code, 3) : '-';
    }
}
