<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('routing', 'Home::routing', []);
$routes->get('test', 'Home::test', []);

$routes->get('foto', 'Home::pegawai_foto', []);
$routes->get('crypto', 'Home::crypto_test', []);
$routes->get('ngemail', 'Home::ngemail', []);
$routes->post('ngemail', 'Home::ngemail', []);
$routes->get('file/viewer', 'Home::view_file', []);
$routes->get('file/download', 'Home::download', []);
$routes->get('file/deleted', 'Home::delete_file', []);
$routes->get('file/upload', 'Home::do_upload_file', ['filter'=>'acl']);
$routes->post('file/upload', 'Home::do_upload_file', []);
$routes->post('api/file/upload', 'Home::do_upload_file', []);
$routes->get('api/unit_kerja', 'Home::unit_kerja', []);
$routes->get('api/jabatan', 'Home::jabatan', []);
$routes->get('api/referensi', 'Home::referensi', []);
$routes->get('api/perguruan_tinggi', 'Home::perguruan_tinggi', []);
$routes->get('api/gugus_tugas', 'Home::gugus_tugas', []);
$routes->get('api/wilayah', 'Home::wilayah', []);
$routes->get('service/maps', 'Home::maps', []);
$routes->get('api/search_place', 'Home::place_in_google_maps', []);
$routes->get('api/get_place', 'Home::place_by_latlong_in_google_maps', []);
$routes->get('api/check_location_in_radius_absen', 'Presensi::check_location_in_radius_absen', []);
// api perlu login
$routes->get('api/pegawai', 'Home::pegawai', ['filter'=>'acl_api']);
$routes->get('api/member', 'Home::member_ekstern', ['filter'=>'acl_api']);


// PERSURATAN
$routes->get('persuratan/beranda', 'Persuratan::index', ['filter'=>'acl']);
$routes->get('persuratan/compose', 'Persuratan::compose', ['filter'=>'acl']);
$routes->post('persuratan/compose', 'Persuratan::compose', ['filter'=>'acl']);
$routes->get('persuratan/detail', 'Persuratan::detail', ['filter'=>'acl']);
$routes->get('persuratan/hapus', 'Persuratan::hapus', ['filter'=>'acl']);
$routes->get('persuratan/inbox', 'Persuratan::inbox', ['filter'=>'acl']);
$routes->post('api/persuratan/inbox', 'Persuratan::inbox', ['filter'=>'acl_api']);
$routes->get('persuratan/sent', 'Persuratan::sent', ['filter'=>'acl']);
$routes->post('api/persuratan/sent', 'Persuratan::sent', ['filter'=>'acl_api']);
$routes->get('persuratan/draft', 'Persuratan::draft', ['filter'=>'acl']);
$routes->post('api/persuratan/draft', 'Persuratan::draft', ['filter'=>'acl_api']);
$routes->get('persuratan/review', 'Persuratan::review', ['filter'=>'acl']);
$routes->post('api/persuratan/review', 'Persuratan::review', ['filter'=>'acl_api']);
$routes->get('persuratan/register', 'Persuratan::register', ['filter'=>'acl']);
$routes->post('api/persuratan/register', 'Persuratan::register', ['filter'=>'acl_api']);
$routes->get('persuratan/register/form', 'Persuratan::register_form', ['filter'=>'acl']);
$routes->post('persuratan/register/form', 'Persuratan::register_form', ['filter'=>'acl']);
$routes->get('persuratan/teruskan', 'Persuratan::teruskan_form', ['filter'=>'acl']);
$routes->post('persuratan/teruskan', 'Persuratan::teruskan_form', ['filter'=>'acl']);

$routes->get('persuratan/form/tindaklanjut', 'Persuratan::tindaklanjut_form', ['filter'=>'acl']);
$routes->post('persuratan/form/tindaklanjut', 'Persuratan::tindaklanjut_form', ['filter'=>'acl']);
$routes->get('persuratan/form/tindaklanjut/disposisi', 'Persuratan::tindaklanjut_disposisi_form', ['filter'=>'acl']);
$routes->post('persuratan/form/tindaklanjut/disposisi', 'Persuratan::tindaklanjut_disposisi_form', ['filter'=>'acl']);


// CUTI PEGAWAI
$routes->get('cuti/beranda', 'Cuti::index', ['filter'=>'acl']);
$routes->get('cuti/form', 'Cuti::form', ['filter'=>'acl']);
$routes->post('cuti/form', 'Cuti::form', ['filter'=>'acl']);
$routes->get('cuti/detail', 'Cuti::detail', ['filter'=>'acl']);
$routes->get('cuti/hapus', 'Cuti::hapus', ['filter'=>'acl']);
$routes->get('cuti/riwayat', 'Cuti::riwayat', ['filter'=>'acl']);
$routes->post('api/cuti/riwayat', 'Cuti::riwayat', ['filter'=>'acl_api']);
$routes->get('cuti/kirim/permohonan', 'Cuti::kirim_permohonan', ['filter'=>'acl']);
$routes->get('cuti/permohonan', 'Cuti::permohonan', ['filter'=>'acl']);
$routes->post('api/cuti/permohonan', 'Cuti::permohonan', ['filter'=>'acl_api']);
$routes->get('cuti/form/approval', 'Cuti::form_approval', ['filter'=>'acl']);
$routes->post('cuti/form/approval', 'Cuti::form_approval', ['filter'=>'acl']);
$routes->get('cuti/proses', 'Cuti::proses', ['filter'=>'acl']);
$routes->post('api/cuti/proses', 'Cuti::proses', ['filter'=>'acl_api']);
$routes->get('cuti/form/proses', 'Cuti::form_proses', ['filter'=>'acl']);
$routes->post('cuti/form/proses', 'Cuti::form_proses', ['filter'=>'acl']);
$routes->get('cuti/master/saldo', 'Cuti::master_saldo', ['filter'=>'acl']);
$routes->post('api/cuti/master/saldo', 'Cuti::master_saldo', ['filter'=>'acl_api']);
$routes->get('cuti/master/saldo/form', 'Cuti::master_saldo_form', ['filter'=>'acl']);
$routes->post('cuti/master/saldo/form', 'Cuti::master_saldo_form', ['filter'=>'acl']);
$routes->get('cuti/master/saldo/hapus', 'Cuti::master_saldo_hapus', ['filter'=>'acl']);


// PRESENSI
$routes->get('presensi/index', 'Presensi::index', ['filter'=>'acl']);
$routes->get('api/presensi/check', 'Presensi::check_now', ['filter'=>'acl_api']);
$routes->post('api/presensi/start', 'Presensi::start', ['filter'=>'acl_api']);
$routes->post('api/presensi/stop', 'Presensi::stop', ['filter'=>'acl_api']);
$routes->get('presensi/riwayat/unduh', 'Presensi::riwayat_unduh', ['filter'=>'acl']);
$routes->get('presensi/riwayat', 'Presensi::riwayat', ['filter'=>'acl']);
$routes->post('api/presensi/riwayat', 'Presensi::riwayat', ['filter'=>'acl_api']);
$routes->get('presensi/laporan/kegiatan', 'Presensi::laporan_kegiatan_form', ['filter'=>'acl']);
$routes->post('api/presensi/laporan/kegiatan', 'Presensi::laporan_kegiatan_form', ['filter'=>'acl_api']);
$routes->get('presensi/laporan/kegiatan/view', 'Presensi::laporan_kegiatan_view', ['filter'=>'acl']);
$routes->get('presensi/harian/unduh', 'Presensi::harian_unduh', ['filter'=>'acl']);
$routes->get('presensi/harian', 'Presensi::harian', ['filter'=>'acl']);
$routes->post('api/presensi/harian', 'Presensi::harian', ['filter'=>'acl_api']);
$routes->get('presensi/bulanan/unduh', 'Presensi::bulanan_unduh', ['filter'=>'acl']);
$routes->get('presensi/bulanan', 'Presensi::bulanan', ['filter'=>'acl']);
$routes->post('api/presensi/bulanan', 'Presensi::bulanan', ['filter'=>'acl_api']);
$routes->get('presensi/jam_kerja', 'Presensi::jam_kerja', ['filter'=>'acl']);
$routes->get('presensi/hari_libur', 'Presensi::hari_libur', ['filter'=>'acl']);
$routes->post('api/presensi/hari_libur', 'Presensi::hari_libur', ['filter'=>'acl']);
$routes->get('presensi/hari_libur/form', 'Presensi::hari_libur_form', ['filter'=>'acl']);
$routes->post('presensi/hari_libur/form', 'Presensi::hari_libur_form', ['filter'=>'acl']);
$routes->get('presensi/lokasi', 'Presensi::lokasi', ['filter'=>'acl']);
$routes->post('api/presensi/lokasi', 'Presensi::lokasi', ['filter'=>'acl_api']);


// KEPEGAWAIAN
// $routes->get('kepegawaian/profile', 'Kepegawaian::profile', ['filter'=>'acl']);
$routes->get('kepegawaian', 'Kepegawaian::index', ['filter'=>'acl']);
$routes->get('kepegawaian/profile', 'Kepegawaian::profile', ['filter'=>'acl']);
$routes->get('kepegawaian/hak_keuangan', 'Kepegawaian::skp_client', ['filter'=>'acl']);
$routes->post('api/kepegawaian/hak_keuangan', 'Kepegawaian::skp_client', ['filter'=>'acl_api']);
$routes->get('kepegawaian/bukti_potong_pajak', 'Kepegawaian::bpp_client', ['filter'=>'acl']);
$routes->post('api/kepegawaian/bukti_potong_pajak', 'Kepegawaian::bpp_client', ['filter'=>'acl_api']);
$routes->get('kepegawaian/aktif', 'Kepegawaian::list_aktif', ['filter'=>'acl']);
$routes->post('api/kepegawaian/aktif', 'Kepegawaian::list_aktif', ['filter'=>'acl_api']);
$routes->get('kepegawaian/form', 'Kepegawaian::form', ['filter'=>'acl']);
$routes->post('kepegawaian/form', 'Kepegawaian::form', ['filter'=>'acl']);
$routes->get('kepegawaian/alamat', 'Kepegawaian::alamat', ['filter'=>'acl']);
$routes->get('kepegawaian/alamat/form', 'Kepegawaian::alamat_form', ['filter'=>'acl']);
$routes->post('kepegawaian/alamat/form', 'Kepegawaian::alamat_form', ['filter'=>'acl']);
$routes->get('kepegawaian/sk', 'Kepegawaian::sk', ['filter'=>'acl']);
$routes->get('kepegawaian/sk/form', 'Kepegawaian::sk_form', ['filter'=>'acl']);
$routes->post('kepegawaian/sk/form', 'Kepegawaian::sk_form', ['filter'=>'acl']);
$routes->get('kepegawaian/fasilitas', 'Kepegawaian::fasilitas', ['filter'=>'acl']);
$routes->get('kepegawaian/fasilitas/form', 'Kepegawaian::fasilitas_form', ['filter'=>'acl']);
$routes->post('kepegawaian/fasilitas/form', 'Kepegawaian::fasilitas_form', ['filter'=>'acl']);
$routes->get('kepegawaian/files', 'Kepegawaian::files', ['filter'=>'acl']);
$routes->get('kepegawaian/qrcode', 'Kepegawaian::qrcode_hash', ['filter'=>'acl']);
$routes->post('api/kepegawaian/qrcode', 'Kepegawaian::qrcode_hash', ['filter'=>'acl_api']);
$routes->get('api/kepegawaian/idcard', 'Kepegawaian::idcard', ['filter'=>'acl_api']);
$routes->get('kepegawaian/user/form', 'Kepegawaian::user_form', ['filter'=>'acl']);
$routes->post('kepegawaian/user/form', 'Kepegawaian::user_form', ['filter'=>'acl']);
$routes->get('kepegawaian/non_aktif', 'Kepegawaian::list_non_aktif', ['filter'=>'acl']);
$routes->post('api/kepegawaian/non_aktif', 'Kepegawaian::list_non_aktif', ['filter'=>'acl_api']);
$routes->get('kepegawaian/ulang_tahun', 'Kepegawaian::ulang_tahun', ['filter'=>'acl']);
$routes->post('api/kepegawaian/ulang_tahun', 'Kepegawaian::ulang_tahun', ['filter'=>'acl_api']);
$routes->get('kepegawaian/download/foto', 'Kepegawaian::download_foto', ['filter'=>'acl']);
$routes->get('kepegawaian/download/data', 'Kepegawaian::download_data', ['filter'=>'acl']);
$routes->get('kepegawaian/tim', 'Kepegawaian::list_tim', ['filter'=>'acl']);
$routes->post('api/kepegawaian/tim', 'Kepegawaian::list_tim', ['filter'=>'acl_api']);
$routes->get('kepegawaian/tim/form', 'Kepegawaian::sk_tim_form', ['filter'=>'acl']);
$routes->post('kepegawaian/tim/form', 'Kepegawaian::sk_tim_form', ['filter'=>'acl']);
$routes->get('kepegawaian/tim/detail', 'Kepegawaian::sk_tim_detail', ['filter'=>'acl']);
$routes->get('api/kepegawaian/tim/detail/deleted', 'Kepegawaian::sk_tim_detail_store', ['filter'=>'acl_api']);
$routes->post('api/kepegawaian/tim/detail/save', 'Kepegawaian::sk_tim_detail_store', ['filter'=>'acl_api']);
$routes->get('kepegawaian/unit', 'Kepegawaian::unit', ['filter'=>'acl']);
$routes->post('api/kepegawaian/unit', 'Kepegawaian::unit', ['filter'=>'acl_api']);
$routes->get('kepegawaian/unit/form', 'Kepegawaian::unit_form', ['filter'=>'acl']);
$routes->post('kepegawaian/unit/form', 'Kepegawaian::unit_form', ['filter'=>'acl']);
$routes->get('kepegawaian/jabatan', 'Kepegawaian::jabatan', ['filter'=>'acl']);
$routes->post('api/kepegawaian/jabatan', 'Kepegawaian::jabatan', ['filter'=>'acl_api']);
$routes->get('kepegawaian/jabatan/form', 'Kepegawaian::jabatan_form', ['filter'=>'acl']);
$routes->post('kepegawaian/jabatan/form', 'Kepegawaian::jabatan_form', ['filter'=>'acl']);
$routes->get('kepegawaian/gugus_tugas', 'Kepegawaian::gugustugas', ['filter'=>'acl']);
$routes->post('api/kepegawaian/gugus_tugas', 'Kepegawaian::gugustugas', ['filter'=>'acl_api']);
$routes->get('kepegawaian/gugus_tugas/form', 'Kepegawaian::gugustugas_form', ['filter'=>'acl']);
$routes->post('kepegawaian/gugus_tugas/form', 'Kepegawaian::gugustugas_form', ['filter'=>'acl']);
$routes->get('kepegawaian/perguruan_tinggi', 'Kepegawaian::pt', ['filter'=>'acl']);
$routes->post('api/kepegawaian/perguruan_tinggi', 'Kepegawaian::pt', ['filter'=>'acl_api']);
$routes->get('kepegawaian/perguruan_tinggi/form', 'Kepegawaian::pt_form', ['filter'=>'acl']);
$routes->post('kepegawaian/perguruan_tinggi/form', 'Kepegawaian::pt_form', ['filter'=>'acl']);
$routes->get('kepegawaian/skp_master', 'Kepegawaian::skp_master', ['filter'=>'acl']);
$routes->post('api/kepegawaian/skp_master', 'Kepegawaian::skp_master', ['filter'=>'acl_api']);
$routes->post('api/kepegawaian/skp_master/generate', 'Kepegawaian::skp_master_generate', ['filter'=>'acl_api']);
$routes->post('api/kepegawaian/skp_master/tte', 'Kepegawaian::skp_master_tte', ['filter'=>'acl_api']);
$routes->get('kepegawaian/bpp_master', 'Kepegawaian::bpp_master', ['filter'=>'acl']);
$routes->post('api/kepegawaian/bpp_master', 'Kepegawaian::bpp_master', ['filter'=>'acl_api']);

// administrator
$routes->get('data/pengguna', 'Administrator::pengguna', ['filter'=>'acl']);
$routes->post('api/data/pengguna', 'Administrator::pengguna', ['filter'=>'acl_api']);
$routes->get('data/pengguna/form', 'Administrator::pengguna_form', ['filter'=>'acl']);
$routes->post('data/pengguna/form', 'Administrator::pengguna_form', ['filter'=>'acl']);
$routes->get('data/referensi', 'Administrator::referensi', ['filter'=>'acl']);
$routes->post('api/data/referensi', 'Administrator::referensi', ['filter'=>'acl_api']);
$routes->get('data/referensi/form', 'Administrator::referensi_form', ['filter'=>'acl']);
$routes->post('data/referensi/form', 'Administrator::referensi_form', ['filter'=>'acl']);
$routes->get('data/konfigurasi', 'Administrator::konfigurasi', ['filter'=>'acl']);
$routes->post('api/data/konfigurasi', 'Administrator::konfigurasi', ['filter'=>'acl_api']);
$routes->get('data/konfigurasi/form', 'Administrator::konfigurasi_form', ['filter'=>'acl']);
$routes->post('data/konfigurasi/form', 'Administrator::konfigurasi_form', ['filter'=>'acl']);
$routes->get('app/role/form', 'Administrator::role_form', ['filter'=>'acl']);
$routes->post('app/role/form', 'Administrator::role_form', ['filter'=>'acl']);
$routes->get('api/app/roles', 'Administrator::roles', ['filter'=>'acl_api']);
$routes->post('app/module/form', 'Administrator::module_form', ['filter'=>'acl']);
$routes->get('app/module/form', 'Administrator::module_form', ['filter'=>'acl']);
$routes->get('api/app/modules', 'Administrator::modules', ['filter'=>'acl_api']);
$routes->get('app/form', 'Administrator::app_form', ['filter'=>'acl']);
$routes->post('app/form', 'Administrator::app_form', ['filter'=>'acl']);
$routes->get('app', 'Administrator::index', ['filter'=>'acl']);

// authentication
$routes->post('auth/login', 'Auth::login', ['filter'=>'acl_api']);
$routes->get('auth/login', 'Auth::login', []);
$routes->get('auth/google', 'Auth::googleLogin');
$routes->get('auth/google/callback', 'Auth::googleCallback');
$routes->get('auth/logout', 'Auth::logout', [/*'filter'=>'acl_api'*/]);
$routes->get('auth/otp', 'Auth::otp', []);
$routes->post('auth/otp', 'Auth::otp', []);
$routes->get('auth/totp', 'Auth::totp', ['filter'=>'acl']);
$routes->get('auth/activate2fa', 'Auth::activate2fa', ['filter'=>'acl']);
$routes->post('auth/activate2fa', 'Auth::activate2fa', ['filter'=>'acl_api']);