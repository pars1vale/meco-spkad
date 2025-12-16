<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DataKelurahanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        $data = [
            ['id_lurah' => 71334, 'tahun' => 2023, 'id_prop' => 440, 'id_kab_kota' => 604, 'id_camat' => 5940, 'kode_lurah' => '91.13.09.2001', 'lurah_teks' => 'Dekai', 'kode_ddn' => '91.13.09.2001', 'kode_ddn_2' => '9113092001', 'is_desa' => 1, 'is_locked' => 0],
            ['id_lurah' => 71335, 'tahun' => 2023, 'id_prop' => 440, 'id_kab_kota' => 604, 'id_camat' => 5940, 'kode_lurah' => '91.13.09.2002', 'lurah_teks' => 'Kuaserama', 'kode_ddn' => '91.13.09.2002', 'kode_ddn_2' => '9113092002', 'is_desa' => 1, 'is_locked' => 0],
            ['id_lurah' => 71336, 'tahun' => 2023, 'id_prop' => 440, 'id_kab_kota' => 604, 'id_camat' => 5940, 'kode_lurah' => '91.13.09.2003', 'lurah_teks' => 'Maruku', 'kode_ddn' => '91.13.09.2003', 'kode_ddn_2' => '9113092003', 'is_desa' => 1, 'is_locked' => 0],
            ['id_lurah' => 71337, 'tahun' => 2023, 'id_prop' => 440, 'id_kab_kota' => 604, 'id_camat' => 5940, 'kode_lurah' => '91.13.09.2004', 'lurah_teks' => 'Massi', 'kode_ddn' => '91.13.09.2004', 'kode_ddn_2' => '9113092004', 'is_desa' => 1, 'is_locked' => 0],
            ['id_lurah' => 71338, 'tahun' => 2023, 'id_prop' => 440, 'id_kab_kota' => 604, 'id_camat' => 5940, 'kode_lurah' => '91.13.09.2005', 'lurah_teks' => 'Keikey', 'kode_ddn' => '91.13.09.2005', 'kode_ddn_2' => '9113092005', 'is_desa' => 1, 'is_locked' => 0],
            ['id_lurah' => 71339, 'tahun' => 2023, 'id_prop' => 440, 'id_kab_kota' => 604, 'id_camat' => 5940, 'kode_lurah' => '91.13.09.2006', 'lurah_teks' => 'Kuari', 'kode_ddn' => '91.13.09.2006', 'kode_ddn_2' => '9113092006', 'is_desa' => 1, 'is_locked' => 0],
            ['id_lurah' => 71340, 'tahun' => 2023, 'id_prop' => 440, 'id_kab_kota' => 604, 'id_camat' => 5940, 'kode_lurah' => '91.13.09.2007', 'lurah_teks' => 'Muara', 'kode_ddn' => '91.13.09.2007', 'kode_ddn_2' => '9113092007', 'is_desa' => 1, 'is_locked' => 0],
            ['id_lurah' => 71341, 'tahun' => 2023, 'id_prop' => 440, 'id_kab_kota' => 604, 'id_camat' => 5940, 'kode_lurah' => '91.13.09.2008', 'lurah_teks' => 'Kiribun', 'kode_ddn' => '91.13.09.2008', 'kode_ddn_2' => '9113092008', 'is_desa' => 1, 'is_locked' => 0],
            ['id_lurah' => 71342, 'tahun' => 2023, 'id_prop' => 440, 'id_kab_kota' => 604, 'id_camat' => 5940, 'kode_lurah' => '91.13.09.2009', 'lurah_teks' => 'Kokamu', 'kode_ddn' => '91.13.09.2009', 'kode_ddn_2' => '9113092009', 'is_desa' => 1, 'is_locked' => 0],
            ['id_lurah' => 71343, 'tahun' => 2023, 'id_prop' => 440, 'id_kab_kota' => 604, 'id_camat' => 5940, 'kode_lurah' => '91.13.09.2010', 'lurah_teks' => 'Tomon I', 'kode_ddn' => '91.13.09.2010', 'kode_ddn_2' => '9113092010', 'is_desa' => 1, 'is_locked' => 0],
            ['id_lurah' => 71344, 'tahun' => 2023, 'id_prop' => 440, 'id_kab_kota' => 604, 'id_camat' => 5940, 'kode_lurah' => '91.13.09.2011', 'lurah_teks' => 'Sokamu', 'kode_ddn' => '91.13.09.2011', 'kode_ddn_2' => '9113092011', 'is_desa' => 1, 'is_locked' => 0],
            ['id_lurah' => 71345, 'tahun' => 2023, 'id_prop' => 440, 'id_kab_kota' => 604, 'id_camat' => 5940, 'kode_lurah' => '91.13.09.2012', 'lurah_teks' => 'Tomon II', 'kode_ddn' => '91.13.09.2012', 'kode_ddn_2' => '9113092012', 'is_desa' => 1, 'is_locked' => 0],
        ];

        // Tambahkan timestamps ke setiap data
        foreach ($data as &$row) {
            $row['created_at'] = $now;
            $row['updated_at'] = $now;
        }

        // Insert data dalam batch
        DB::table('data_kelurahan')->insert($data);

        $this->command->info('Data kelurahan berhasil di-seed! Total: ' . count($data) . ' records');
    }
}
