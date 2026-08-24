-- base query pendapatan
SELECT LEFT(dp.kode_akun, 6) AS kode_level_6, da.nama_akun AS nama_akun, SUM(dp.total) AS jumlah FROM data_pendapatan AS dp LEFT JOIN akun AS da ON LEFT(dp.kode_akun, 6) COLLATE utf8mb4_unicode_ci = da.kode_akun COLLATE utf8mb4_unicode_ci AND da.level = 6 WHERE dp.id_skpd = 3047 AND dp.tahun_anggaran = 2026 GROUP BY dp.id_skpd, LEFT(dp.kode_akun, 6), da.nama_akun ORDER BY LEFT(dp.kode_akun, 6);

-- base query belanja
SELECT LEFT(dr.kode_akun, 6) AS kode_level_6, da.nama_akun AS nama_akun, SUM(dr.rincian) AS jumlah FROM data_rka AS dr INNER JOIN data_sub_keg_bl AS dskb ON dr.kode_sbl = dskb.kode_sbl LEFT JOIN akun AS da ON LEFT(dr.kode_akun, 6) COLLATE utf8mb4_unicode_ci = da.kode_akun COLLATE utf8mb4_unicode_ci AND da.level = 6 WHERE dskb.id_sub_skpd = 3047 AND dr.tahun_anggaran = 2026 GROUP BY dskb.kode_skpd, dskb.nama_skpd, LEFT(dr.kode_akun, 6), da.nama_akun ORDER BY LEFT(dr.kode_akun, 6);

-- base query pembiayaan
SELECT LEFT(db.kode_akun, 6) AS kode_level_6, da.nama_akun AS nama_akun, SUM(db.total) AS jumlah FROM data_pembiayaan AS db LEFT JOIN akun AS da ON LEFT(db.kode_akun, 6) COLLATE utf8mb4_unicode_ci = da.kode_akun COLLATE utf8mb4_unicode_ci AND da.level = 6 WHERE db.id_skpd = 3047 AND db.tahun_anggaran = 2026 GROUP BY db.id_skpd, LEFT(db.kode_akun, 6), da.nama_akun ORDER BY LEFT(db.kode_akun, 6);


-- revisii

-- pendapatan
SELECT
    da.kode_akun AS kode_level,
    da.nama_akun AS nama_akun,
    SUM(dp.total) AS jumlah
FROM data_pendapatan AS dp
LEFT JOIN akun AS da
    ON LEFT(dp.kode_akun, da.level) COLLATE utf8mb4_unicode_ci
       = da.kode_akun COLLATE utf8mb4_unicode_ci
WHERE dp.id_skpd = 3047
  AND dp.tahun_anggaran = 2026
  AND da.level = 6
GROUP BY
    dp.id_skpd,
    da.kode_akun,
    da.nama_akun,
    da.level
ORDER BY
    da.kode_akun;

-- belanja
SELECT
    da.kode_akun AS kode_level,
    da.nama_akun AS nama_akun,
    SUM(dr.rincian) AS jumlah
FROM data_rka AS dr
INNER JOIN data_sub_keg_bl AS dskb
    ON dr.kode_sbl = dskb.kode_sbl
LEFT JOIN akun AS da
    ON LEFT(dr.kode_akun, da.level) COLLATE utf8mb4_unicode_ci
       = da.kode_akun COLLATE utf8mb4_unicode_ci
WHERE dskb.id_sub_skpd = 3047
  AND dr.tahun_anggaran = 2026
  AND da.level = 6
GROUP BY
    dskb.kode_skpd,
    dskb.nama_skpd,
    da.kode_akun,
    da.nama_akun,
    da.level
ORDER BY
    da.kode_akun;

-- pembiayaan
SELECT
    da.kode_akun AS kode_level,
    da.nama_akun AS nama_akun,
    SUM(db.total) AS jumlah
FROM data_pembiayaan AS db
LEFT JOIN akun AS da
    ON LEFT(db.kode_akun, da.level) COLLATE utf8mb4_unicode_ci
       = da.kode_akun COLLATE utf8mb4_unicode_ci
WHERE db.id_skpd = 3047
  AND db.tahun_anggaran = 2026
  AND da.level = 6
GROUP BY
    db.id_skpd,
    da.kode_akun,
    da.nama_akun,
    da.level
ORDER BY
    da.kode_akun;



lv1&lv2
-- pendapatan
SELECT
    da.kode_akun AS kode_level,
    da.nama_akun AS nama_akun,
FROM data_pendapatan AS dp
LEFT JOIN akun AS da
    ON LEFT(dp.kode_akun, da.level) COLLATE utf8mb4_unicode_ci
       = da.kode_akun COLLATE utf8mb4_unicode_ci
WHERE dp.id_skpd = 3047
  AND dp.tahun_anggaran = 2026
  AND da.level = {jika lv1 maka = 1, jika lv2 maka = 3}
GROUP BY
    dp.id_skpd,
    da.kode_akun,
    da.nama_akun,
    da.level
ORDER BY
    da.kode_akun;


-- belanja
SELECT
    da.kode_akun AS kode_level,
    da.nama_akun AS nama_akun
FROM data_rka AS dr
INNER JOIN data_sub_keg_bl AS dskb
    ON dr.kode_sbl = dskb.kode_sbl
LEFT JOIN akun AS da
    ON LEFT(dr.kode_akun, da.level) COLLATE utf8mb4_unicode_ci
       = da.kode_akun COLLATE utf8mb4_unicode_ci
WHERE dskb.id_sub_skpd = 3047
  AND dr.tahun_anggaran = 2026
  AND da.level = {jika lv1 maka = 1, jika lv2 maka = 3}
GROUP BY
    dskb.kode_skpd,
    dskb.nama_skpd,
    da.kode_akun,
    da.nama_akun,
    da.level
ORDER BY
    da.kode_akun;

-- pembiayaan
SELECT
    da.kode_akun AS kode_level,
    da.nama_akun AS nama_akun,
    SUM(db.total) AS jumlah
FROM data_pembiayaan AS db
LEFT JOIN akun AS da
    ON LEFT(db.kode_akun, da.level) COLLATE utf8mb4_unicode_ci
       = da.kode_akun COLLATE utf8mb4_unicode_ci
WHERE db.id_skpd = 3047
  AND db.tahun_anggaran = 2026
  AND da.level = {jika lv1 maka = 1, jika lv2 maka = 3}
GROUP BY
    db.id_skpd,
    da.kode_akun,
    da.nama_akun,
    da.level
ORDER BY
    da.kode_akun;