SELECT id_urusan AS idurusan, kode_urusan AS kode, nama_urusan AS nama, 0 AS level
FROM data_sub_keg_bl
WHERE id_skpd = 3047 AND tahun_anggaran = 2026
GROUP BY kode_urusan, nama_urusan

UNION ALL

SELECT id_bidang_urusan AS idbidur, kode_bidang_urusan AS kode, nama_bidang_urusan AS nama, 1 AS level
FROM data_sub_keg_bl
WHERE id_skpd = 3047 AND tahun_anggaran = 2026
GROUP BY kode_bidang_urusan, nama_bidang_urusan

UNION ALL

SELECT id_program AS idprog, kode_program AS kode, nama_program AS nama, 2 AS level
FROM data_sub_keg_bl
WHERE id_skpd = 3047 AND tahun_anggaran = 2026
GROUP BY kode_program, nama_program

UNION ALL

SELECT id_giat AS idgiat, kode_giat AS kode, nama_giat AS nama, 3 AS level
FROM data_sub_keg_bl
WHERE id_skpd = 3047 AND tahun_anggaran = 2026
GROUP BY kode_giat, nama_giat

UNION ALL

SELECT 
id_sub_giat AS idsubkeg,
kode_sub_giat AS kode, 
nama_sub_giat AS nama,
4 AS level
FROM data_sub_keg_bl
WHERE id_skpd = 3047 AND tahun_anggaran = 2026
GROUP BY kode_sub_giat, nama_sub_giat

ORDER BY kode ASC, level ASC;



SELECT
    dskbl.kode_sub_giat AS kode,
    dskbl.nama_sub_giat AS nama,
    dskbl.pagu_n_lalu AS tahunMin1,
    SUM(CASE WHEN drka.jenis_bl IN ('BTL-GAJI','HIBAH','BANSOS','BUNGA') THEN drka.total_harga ELSE 0 END) AS belanja_operasi,
    SUM(CASE WHEN drka.jenis_bl IN ('TANAH','BARJAS-MODAL') THEN drka.total_harga ELSE 0 END) AS belanja_modal,
    SUM(CASE WHEN drka.jenis_bl = 'BTT' THEN drka.total_harga ELSE 0 END) AS belanja_tidak_terduga,
    SUM(CASE WHEN drka.jenis_bl IN ('BANKEU','BANKEU-KHUSUS','BOS') THEN drka.total_harga ELSE 0 END) AS belanja_transfer,
    SUM(drka.total_harga) AS total_keseluruhan,
    dskbl.pagu_n_depan AS tahunPlus1
FROM data_sub_keg_bl s
LEFT JOIN data_rka r ON drka.kode_sbl = dskbl.kode_sbl
WHERE dskbl.id_skpd = 3047
  AND dskbl.tahun_anggaran = 2026
  AND dskbl.kode_bl = "3047.3047.4393.18209"
GROUP BY dskbl.kode_sub_giat, dskbl.nama_sub_giat;