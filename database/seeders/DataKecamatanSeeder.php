<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataKecamatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        $data = [
            ['id_camat' => 5934, 'tahun' => 0, 'id_prop' => 440, 'id_kab_kota' => 604, 'kode_camat' => '95.03.01', 'camat_teks' => 'Kurima', 'kode_ddn' => '95.03.01', 'kode_ddn_2' => '950301', 'is_locked' => 0],
            ['id_camat' => 5935, 'tahun' => 0, 'id_prop' => 440, 'id_kab_kota' => 604, 'kode_camat' => '95.03.02', 'camat_teks' => 'Anggruk', 'kode_ddn' => '95.03.02', 'kode_ddn_2' => '950302', 'is_locked' => 0],
            ['id_camat' => 5936, 'tahun' => 0, 'id_prop' => 440, 'id_kab_kota' => 604, 'kode_camat' => '95.03.03', 'camat_teks' => 'Ninia', 'kode_ddn' => '95.03.03', 'kode_ddn_2' => '950303', 'is_locked' => 0],
            ['id_camat' => 5937, 'tahun' => 0, 'id_prop' => 440, 'id_kab_kota' => 604, 'kode_camat' => '95.03.06', 'camat_teks' => 'Silimo', 'kode_ddn' => '95.03.06', 'kode_ddn_2' => '950306', 'is_locked' => 0],
            ['id_camat' => 5938, 'tahun' => 0, 'id_prop' => 440, 'id_kab_kota' => 604, 'kode_camat' => '95.03.07', 'camat_teks' => 'Samenage', 'kode_ddn' => '95.03.07', 'kode_ddn_2' => '950307', 'is_locked' => 0],
            ['id_camat' => 5939, 'tahun' => 0, 'id_prop' => 440, 'id_kab_kota' => 604, 'kode_camat' => '95.03.08', 'camat_teks' => 'Nalca', 'kode_ddn' => '95.03.08', 'kode_ddn_2' => '950308', 'is_locked' => 0],
            ['id_camat' => 5940, 'tahun' => 0, 'id_prop' => 440, 'id_kab_kota' => 604, 'kode_camat' => '95.03.09', 'camat_teks' => 'Dekai', 'kode_ddn' => '95.03.09', 'kode_ddn_2' => '950309', 'is_locked' => 0],
            ['id_camat' => 5941, 'tahun' => 0, 'id_prop' => 440, 'id_kab_kota' => 604, 'kode_camat' => '95.03.10', 'camat_teks' => 'Obio', 'kode_ddn' => '95.03.10', 'kode_ddn_2' => '950310', 'is_locked' => 0],
            ['id_camat' => 5942, 'tahun' => 0, 'id_prop' => 440, 'id_kab_kota' => 604, 'kode_camat' => '95.03.11', 'camat_teks' => 'Suru Suru', 'kode_ddn' => '95.03.11', 'kode_ddn_2' => '950311', 'is_locked' => 0],
            ['id_camat' => 5943, 'tahun' => 0, 'id_prop' => 440, 'id_kab_kota' => 604, 'kode_camat' => '95.03.12', 'camat_teks' => 'Wusama', 'kode_ddn' => '95.03.12', 'kode_ddn_2' => '950312', 'is_locked' => 0],
            ['id_camat' => 5944, 'tahun' => 0, 'id_prop' => 440, 'id_kab_kota' => 604, 'kode_camat' => '95.03.13', 'camat_teks' => 'Amuma', 'kode_ddn' => '95.03.13', 'kode_ddn_2' => '950313', 'is_locked' => 0],
            ['id_camat' => 5945, 'tahun' => 0, 'id_prop' => 440, 'id_kab_kota' => 604, 'kode_camat' => '95.03.14', 'camat_teks' => 'Musaik', 'kode_ddn' => '95.03.14', 'kode_ddn_2' => '950314', 'is_locked' => 0],
            ['id_camat' => 5946, 'tahun' => 0, 'id_prop' => 440, 'id_kab_kota' => 604, 'kode_camat' => '95.03.15', 'camat_teks' => 'Pasema', 'kode_ddn' => '95.03.15', 'kode_ddn_2' => '950315', 'is_locked' => 0],
            ['id_camat' => 5947, 'tahun' => 0, 'id_prop' => 440, 'id_kab_kota' => 604, 'kode_camat' => '95.03.16', 'camat_teks' => 'Hogio', 'kode_ddn' => '95.03.16', 'kode_ddn_2' => '950316', 'is_locked' => 0],
            ['id_camat' => 5948, 'tahun' => 0, 'id_prop' => 440, 'id_kab_kota' => 604, 'kode_camat' => '95.03.17', 'camat_teks' => 'Mugi', 'kode_ddn' => '95.03.17', 'kode_ddn_2' => '950317', 'is_locked' => 0],
            ['id_camat' => 5949, 'tahun' => 0, 'id_prop' => 440, 'id_kab_kota' => 604, 'kode_camat' => '95.03.18', 'camat_teks' => 'Soba', 'kode_ddn' => '95.03.18', 'kode_ddn_2' => '950318', 'is_locked' => 0],
            ['id_camat' => 5950, 'tahun' => 0, 'id_prop' => 440, 'id_kab_kota' => 604, 'kode_camat' => '95.03.19', 'camat_teks' => 'Werima', 'kode_ddn' => '95.03.19', 'kode_ddn_2' => '950319', 'is_locked' => 0],
            ['id_camat' => 5951, 'tahun' => 0, 'id_prop' => 440, 'id_kab_kota' => 604, 'kode_camat' => '95.03.20', 'camat_teks' => 'Tangma', 'kode_ddn' => '95.03.20', 'kode_ddn_2' => '950320', 'is_locked' => 0],
            ['id_camat' => 5952, 'tahun' => 0, 'id_prop' => 440, 'id_kab_kota' => 604, 'kode_camat' => '95.03.21', 'camat_teks' => 'Ukha', 'kode_ddn' => '95.03.21', 'kode_ddn_2' => '950321', 'is_locked' => 0],
            ['id_camat' => 5953, 'tahun' => 0, 'id_prop' => 440, 'id_kab_kota' => 604, 'kode_camat' => '95.03.22', 'camat_teks' => 'Panggema', 'kode_ddn' => '95.03.22', 'kode_ddn_2' => '950322', 'is_locked' => 0],
            ['id_camat' => 5954, 'tahun' => 0, 'id_prop' => 440, 'id_kab_kota' => 604, 'kode_camat' => '95.03.23', 'camat_teks' => 'Kosarek', 'kode_ddn' => '95.03.23', 'kode_ddn_2' => '950323', 'is_locked' => 0],
            ['id_camat' => 5955, 'tahun' => 0, 'id_prop' => 440, 'id_kab_kota' => 604, 'kode_camat' => '95.03.24', 'camat_teks' => 'Nipsan', 'kode_ddn' => '95.03.24', 'kode_ddn_2' => '950324', 'is_locked' => 0],
            ['id_camat' => 5956, 'tahun' => 0, 'id_prop' => 440, 'id_kab_kota' => 604, 'kode_camat' => '95.03.25', 'camat_teks' => 'Ubahak', 'kode_ddn' => '95.03.25', 'kode_ddn_2' => '950325', 'is_locked' => 0],
            ['id_camat' => 5957, 'tahun' => 0, 'id_prop' => 440, 'id_kab_kota' => 604, 'kode_camat' => '95.03.26', 'camat_teks' => 'Pronggoli', 'kode_ddn' => '95.03.26', 'kode_ddn_2' => '950326', 'is_locked' => 0],
            ['id_camat' => 5958, 'tahun' => 0, 'id_prop' => 440, 'id_kab_kota' => 604, 'kode_camat' => '95.03.27', 'camat_teks' => 'Walma', 'kode_ddn' => '95.03.27', 'kode_ddn_2' => '950327', 'is_locked' => 0],
            ['id_camat' => 5959, 'tahun' => 0, 'id_prop' => 440, 'id_kab_kota' => 604, 'kode_camat' => '95.03.28', 'camat_teks' => 'Yahuliambut', 'kode_ddn' => '95.03.28', 'kode_ddn_2' => '950328', 'is_locked' => 0],
            ['id_camat' => 5960, 'tahun' => 0, 'id_prop' => 440, 'id_kab_kota' => 604, 'kode_camat' => '95.03.29', 'camat_teks' => 'Hereapini', 'kode_ddn' => '95.03.29', 'kode_ddn_2' => '950329', 'is_locked' => 0],
            ['id_camat' => 5961, 'tahun' => 0, 'id_prop' => 440, 'id_kab_kota' => 604, 'kode_camat' => '95.03.30', 'camat_teks' => 'Ubalihi', 'kode_ddn' => '95.03.30', 'kode_ddn_2' => '950330', 'is_locked' => 0],
            ['id_camat' => 5962, 'tahun' => 0, 'id_prop' => 440, 'id_kab_kota' => 604, 'kode_camat' => '95.03.31', 'camat_teks' => 'Talambo', 'kode_ddn' => '95.03.31', 'kode_ddn_2' => '950331', 'is_locked' => 0],
            ['id_camat' => 5963, 'tahun' => 0, 'id_prop' => 440, 'id_kab_kota' => 604, 'kode_camat' => '95.03.32', 'camat_teks' => 'Puldama', 'kode_ddn' => '95.03.32', 'kode_ddn_2' => '950332', 'is_locked' => 0],
            ['id_camat' => 5964, 'tahun' => 0, 'id_prop' => 440, 'id_kab_kota' => 604, 'kode_camat' => '95.03.33', 'camat_teks' => 'Endomen', 'kode_ddn' => '95.03.33', 'kode_ddn_2' => '950333', 'is_locked' => 0],
            ['id_camat' => 5965, 'tahun' => 0, 'id_prop' => 440, 'id_kab_kota' => 604, 'kode_camat' => '95.03.34', 'camat_teks' => 'Kona', 'kode_ddn' => '95.03.34', 'kode_ddn_2' => '950334', 'is_locked' => 0],
            ['id_camat' => 5966, 'tahun' => 0, 'id_prop' => 440, 'id_kab_kota' => 604, 'kode_camat' => '95.03.35', 'camat_teks' => 'Dirwemna', 'kode_ddn' => '95.03.35', 'kode_ddn_2' => '950335', 'is_locked' => 0],
            ['id_camat' => 5967, 'tahun' => 0, 'id_prop' => 440, 'id_kab_kota' => 604, 'kode_camat' => '95.03.36', 'camat_teks' => 'Holuon', 'kode_ddn' => '95.03.36', 'kode_ddn_2' => '950336', 'is_locked' => 0],
            ['id_camat' => 5968, 'tahun' => 0, 'id_prop' => 440, 'id_kab_kota' => 604, 'kode_camat' => '95.03.37', 'camat_teks' => 'Lolat', 'kode_ddn' => '95.03.37', 'kode_ddn_2' => '950337', 'is_locked' => 0],
            ['id_camat' => 5969, 'tahun' => 0, 'id_prop' => 440, 'id_kab_kota' => 604, 'kode_camat' => '95.03.38', 'camat_teks' => 'Soloikma', 'kode_ddn' => '95.03.38', 'kode_ddn_2' => '950338', 'is_locked' => 0],
            ['id_camat' => 5970, 'tahun' => 0, 'id_prop' => 440, 'id_kab_kota' => 604, 'kode_camat' => '95.03.39', 'camat_teks' => 'Sela', 'kode_ddn' => '95.03.39', 'kode_ddn_2' => '950339', 'is_locked' => 0],
            ['id_camat' => 5971, 'tahun' => 0, 'id_prop' => 440, 'id_kab_kota' => 604, 'kode_camat' => '95.03.40', 'camat_teks' => 'Korupun', 'kode_ddn' => '95.03.40', 'kode_ddn_2' => '950340', 'is_locked' => 0],
            ['id_camat' => 5972, 'tahun' => 0, 'id_prop' => 440, 'id_kab_kota' => 604, 'kode_camat' => '95.03.41', 'camat_teks' => 'Langda', 'kode_ddn' => '95.03.41', 'kode_ddn_2' => '950341', 'is_locked' => 0],
            ['id_camat' => 5973, 'tahun' => 0, 'id_prop' => 440, 'id_kab_kota' => 604, 'kode_camat' => '95.03.42', 'camat_teks' => 'Bomela', 'kode_ddn' => '95.03.42', 'kode_ddn_2' => '950342', 'is_locked' => 0],
            ['id_camat' => 5974, 'tahun' => 0, 'id_prop' => 440, 'id_kab_kota' => 604, 'kode_camat' => '95.03.43', 'camat_teks' => 'Suntamon', 'kode_ddn' => '95.03.43', 'kode_ddn_2' => '950343', 'is_locked' => 0],
            ['id_camat' => 5975, 'tahun' => 0, 'id_prop' => 440, 'id_kab_kota' => 604, 'kode_camat' => '95.03.44', 'camat_teks' => 'Seredela', 'kode_ddn' => '95.03.44', 'kode_ddn_2' => '950344', 'is_locked' => 0],
            ['id_camat' => 5976, 'tahun' => 0, 'id_prop' => 440, 'id_kab_kota' => 604, 'kode_camat' => '95.03.45', 'camat_teks' => 'Sobaham', 'kode_ddn' => '95.03.45', 'kode_ddn_2' => '950345', 'is_locked' => 0],
            ['id_camat' => 5977, 'tahun' => 0, 'id_prop' => 440, 'id_kab_kota' => 604, 'kode_camat' => '95.03.46', 'camat_teks' => 'Kabianggama', 'kode_ddn' => '95.03.46', 'kode_ddn_2' => '950346', 'is_locked' => 0],
            ['id_camat' => 5978, 'tahun' => 0, 'id_prop' => 440, 'id_kab_kota' => 604, 'kode_camat' => '95.03.47', 'camat_teks' => 'Kwelemdua', 'kode_ddn' => '95.03.47', 'kode_ddn_2' => '950347', 'is_locked' => 0],
            ['id_camat' => 5979, 'tahun' => 0, 'id_prop' => 440, 'id_kab_kota' => 604, 'kode_camat' => '95.03.48', 'camat_teks' => 'Kwikma', 'kode_ddn' => '95.03.48', 'kode_ddn_2' => '950348', 'is_locked' => 0],
            ['id_camat' => 5980, 'tahun' => 0, 'id_prop' => 440, 'id_kab_kota' => 604, 'kode_camat' => '95.03.49', 'camat_teks' => 'Hilipuk', 'kode_ddn' => '95.03.49', 'kode_ddn_2' => '950349', 'is_locked' => 0],
            ['id_camat' => 5981, 'tahun' => 0, 'id_prop' => 440, 'id_kab_kota' => 604, 'kode_camat' => '95.03.50', 'camat_teks' => 'Duram', 'kode_ddn' => '95.03.50', 'kode_ddn_2' => '950350', 'is_locked' => 0],
            ['id_camat' => 5982, 'tahun' => 0, 'id_prop' => 440, 'id_kab_kota' => 604, 'kode_camat' => '95.03.51', 'camat_teks' => 'Yogosem', 'kode_ddn' => '95.03.51', 'kode_ddn_2' => '950351', 'is_locked' => 0],
            ['id_camat' => 5983, 'tahun' => 0, 'id_prop' => 440, 'id_kab_kota' => 604, 'kode_camat' => '95.03.52', 'camat_teks' => 'Kayo', 'kode_ddn' => '95.03.52', 'kode_ddn_2' => '950352', 'is_locked' => 0],
            ['id_camat' => 5984, 'tahun' => 0, 'id_prop' => 440, 'id_kab_kota' => 604, 'kode_camat' => '95.03.53', 'camat_teks' => 'Sumo', 'kode_ddn' => '95.03.53', 'kode_ddn_2' => '950353', 'is_locked' => 0],
        ];

        // Tambahkan timestamps ke setiap data
        foreach ($data as &$row) {
            $row['created_at'] = $now;
            $row['updated_at'] = $now;
        }

        // Insert data dalam batch untuk performa lebih baik
        DB::table('data_kecamatan')->insert($data);
        $this->command->info('Data kecamatan berhasil di-seed! Total: ' . count($data) . ' records');
    }
}
