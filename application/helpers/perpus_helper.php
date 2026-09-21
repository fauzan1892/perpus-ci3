<?php defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('perpus_tanggal_valid'))
{
    /**
     * Parse a date-only value using the application's timezone.
     */
    function perpus_tanggal_valid($date)
    {
        if ($date instanceof DateTimeInterface)
        {
            return DateTimeImmutable::createFromInterface($date)->setTime(0, 0, 0);
        }

        $date = trim((string) $date);
        $parsed = DateTimeImmutable::createFromFormat(
            '!Y-m-d',
            $date,
            new DateTimeZone(date_default_timezone_get())
        );
        $errors = DateTimeImmutable::getLastErrors();

        if ($parsed === false || ($errors !== false && ($errors['warning_count'] > 0 || $errors['error_count'] > 0)))
        {
            return null;
        }

        return $parsed;
    }
}

if (!function_exists('perpus_tanggal_jatuh_tempo'))
{
    function perpus_tanggal_jatuh_tempo($tanggal_pinjam, $lama_pinjam)
    {
        $tanggal = perpus_tanggal_valid($tanggal_pinjam);
        $lama_pinjam = filter_var($lama_pinjam, FILTER_VALIDATE_INT);

        if ($tanggal === null || $lama_pinjam === false || $lama_pinjam < 1)
        {
            return false;
        }

        return $tanggal->modify('+'.$lama_pinjam.' days')->format('Y-m-d');
    }
}

if (!function_exists('perpus_hari_terlambat'))
{
    function perpus_hari_terlambat($tanggal_jatuh_tempo, $tanggal_acuan = null)
    {
        $jatuh_tempo = perpus_tanggal_valid($tanggal_jatuh_tempo);
        $acuan = $tanggal_acuan === null
            ? new DateTimeImmutable('today', new DateTimeZone(date_default_timezone_get()))
            : perpus_tanggal_valid($tanggal_acuan);

        if ($jatuh_tempo === null || $acuan === null || $acuan <= $jatuh_tempo)
        {
            return 0;
        }

        return (int) $jatuh_tempo->diff($acuan)->days;
    }
}

if (!function_exists('perpus_hitung_denda'))
{
    function perpus_hitung_denda($hari_terlambat, $jumlah_buku, $harga_per_hari)
    {
        return max(0, (int) $hari_terlambat)
            * max(0, (int) $jumlah_buku)
            * max(0, (float) $harga_per_hari);
    }
}

if (!function_exists('perpus_buku_sampul_url'))
{
    function perpus_buku_sampul_url($filename)
    {
        $filename = basename(trim((string) $filename));
        $relative_path = 'assets/image/buku/'.$filename;

        if ($filename !== '' && $filename !== '0' && is_file(FCPATH.$relative_path))
        {
            return base_url($relative_path);
        }

        return base_url('assets/image/default-book.svg');
    }
}

if (!function_exists('perpus_user_foto_url'))
{
    function perpus_user_foto_url($filename)
    {
        $filename = basename(trim((string) $filename));
        $relative_path = 'assets/image/'.$filename;

        if ($filename !== '' && is_file(FCPATH.$relative_path))
        {
            return base_url($relative_path);
        }

        return base_url('assets/image/default-user.svg');
    }
}
