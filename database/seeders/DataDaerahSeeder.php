<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataDaerahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('data_daerah')->insert([
            'id_daerah' => 604,
            'kode_prop' => '38',
            'kode_kab' => '03',
            'nama_daerah' => 'Kab. Yahukimo',
            'logo' => 'logo_kab_yahukimo.png',
            'kode_ddn' => '95.03',
            'kode_ddn_2' => '9503',
            'is_pusat' => 0,
            'is_prop' => 0,
            'id_prop' => 590,
            'jqm_code' => null,
            'jqm_path' => null,
            'sub_domain' => 'yahukimokab01#@!A',
            'is_deleted' => 0,
            'is_rekap' => 1,
            'set_zona' => 'wit',
            'set_waktu_zona' => 2,
            'set_gmt_zona' => 9,
            'kode_satker' => 998477,
            'kode_prov_djpk' => '26',
            'kode_kab_djpk' => '13',
            'will_migrated' => 0,
            'jns_pemda' => 2,
            'is_otsus_papua' => 2,
            'is_otsus_aceh' => 0,
            'is_dtpk' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
