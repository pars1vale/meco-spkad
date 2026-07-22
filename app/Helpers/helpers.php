<?php

if (! function_exists('rupiah')) {
    function rupiah($angka, int $desimal = 2): string
    {
        return 'Rp '.number_format((float) $angka, $desimal, ',', '.');
    }
}

if (! function_exists('rupiah_bulat')) {
    function rupiah_bulat($angka): string
    {
        return 'Rp '.number_format((float) $angka, 0, ',', '.');
    }
}
