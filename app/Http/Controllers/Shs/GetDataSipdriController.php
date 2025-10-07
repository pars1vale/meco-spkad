<?php

namespace App\Http\Controllers\Shs;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class GetDataSipdriController extends Controller
{
    public function getStandarHarga()
    {
        $url = "https://sipd-ri.kemendagri.go.id/api/master/d_komponen/listAll";

        $response = Http::withHeaders([
            'x-access-token' => 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJleHAiOjE3NTk0NTQ4MDMsImhhc2hlZCI6dHJ1ZSwiaWRfZGFlcmFoIjo2MDQsImlkX2xldmVsIjo0LCJpZF91c2VyIjoyNDU4OX0.EJNVE8gZBlOdG6dK_g4sjvSWNqEHZOXOxtWOdmUWQII',
            'x-api-key'      => 'ZXlKMGIydGxiaUk2SW1GM01VaFJkRXN6YVRkblluUm9haUlzSW1sa1gyUmhaWEpoYUNJNk5qQTBMQ0owWVdoMWJpSTZNakF5TlN3aWFXUmZZWEJ3SWpveE9EWTJNeXdpYVhOZllYQndJam94TENKelpXTnlaWFJmYTJWNUlqb2lXbGhzUzJWdFJsaFZhbEpLWVcwNWNGWnJXbXRrTVZaeVRWWmFVRlpXV205YVYzQkNUMVZzY0dReWJHcE5iWGh5V1d0T1NrNXJiSE5WYmtwVFZrZDRVbFpXVVhkUFZXeHdaREpzYWsxdGVISlhhMDVLVG10c2MxVnVVbXBTVlhCUFZWWlJkMDlWYkhCa01teG9WakZLY2xOWGNIWmhWbHBJVFZoa1VtRjZSa05WUmxGM1lWZGFVbEJVTUQwaUxDSnpaV04xY21sMGVWOXJaWGtpT2lJMk1EUjhNakF5Tlh4TlZHTXhUMVJSTVUxNlNYZE9kejA5ZkV4dlRHbzVaVE5XYWpaOE1UYzVNeklpTENKMGIydGxibDlyWlhsZk1TSTZJakV3T1RJNFpEVXpOemRrWlRBME1UVTVPR1k0TWprMU9EQTFOak14TVRZNFlqUmpOMlZrTjJVeVlUWmtaR1JsTlRnMFpXSmtOVGM1TW1aaFpXTXhaRGxpWlRabE5XVTVNaUlzSW5SdmEyVnVYMnRsZVY4eUlqb2lPVGd4TmpkaU56VXpNMkV3TXpBeE9HRmxZVFUwTnpFd01URXhPRGMzTldZMk4yUTVaV0UxT0NKOQ==',
        ])->asMultipart()
            ->post($url, [
                'id_daerah' => 604,
                'tahun'     => 2025
            ]);

        if ($response->successful()) {
            $data = $response->json();

            // ambil data yg relevan
            $result = collect($data)->map(function ($item) {
                return [
                    'id_standar_harga'     => $item['id_standar_harga'] ?? null,
                    'id_kel_standar_harga' => $item['id_kel_standar_harga'] ?? null,
                    'kode_kel_standar_harga' => $item['kode_kel_standar_harga'] ?? null,
                    'nama_kel_standar_harga' => $item['nama_kel_standar_harga'] ?? null,
                    'tipe_standar_harga'   => $item['tipe_standar_harga'] ?? null,
                    'id_unik'              => $item['id_unik'] ?? null,
                    'tahun'                => $item['tahun'] ?? null,
                    'id_daerah'            => $item['id_daerah'] ?? null,
                    'kode_standar_harga'   => $item['kode_standar_harga'] ?? null,
                    'nama_standar_harga'   => $item['nama_standar_harga'] ?? null,
                    'satuan'               => $item['satuan'] ?? null,
                    'spek'                 => $item['spek'] ?? null,
                    'harga'                => $item['harga'] ?? null,
                    'is_pdn'               => $item['is_pdn'] ?? null,
                    'nilai_tkdn'           => $item['nilai_tkdn'] ?? null,
                    'is_locked'            => $item['is_locked'] ?? null,
                ];
            });

            return response()->json($result);
        }

        return response()->json([
            'success' => false,
            'status'  => $response->status(),
            'error'   => $response->body(),
        ], $response->status());
    }
}
