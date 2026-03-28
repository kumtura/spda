<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Banjar;
use App\Models\Skill_TenagaKerja;
use App\Models\Karyawan;
use App\Models\Usaha;
use App\Models\Detail_Usaha;
use App\Models\Penanggung_Jawab;
use App\Models\Loker;
use App\Models\Jadwal_Interview;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class LokerDummySeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Banjars
        $banjarUbud = Banjar::firstOrCreate(['nama_banjar' => 'Banjar Dinas Ubud Kaja'], ['alamat_banjar' => 'Jl. Raya Ubud, Gianyar', 'aktif' => '1']);
        $banjarCanggu = Banjar::firstOrCreate(['nama_banjar' => 'Banjar Perancak Canggu'], ['alamat_banjar' => 'Jl. Pantai Berawa, Badung', 'aktif' => '1']);
        $banjarSanur = Banjar::firstOrCreate(['nama_banjar' => 'Banjar Sindu Sanur'], ['alamat_banjar' => 'Jl. Danau Tamblingan, Denpasar', 'aktif' => '1']);

        // 2. Seed Skills
        $skills = [
            'Pramusaji (Waiter/Waitress)',
            'Koki Masakan Bali (Cook)',
            'Teknisi Bengkel (Mechanic)',
            'Keamanan (Security/Pecalang)',
            'Staf Administrasi LPD',
            'Tukang Kebun (Gardener)',
            'Housekeeping Villa'
        ];

        foreach ($skills as $skill) {
            Skill_TenagaKerja::firstOrCreate(['nama_skill' => $skill], ['aktif' => '1']);
        }

        // 3. Seed Business Units (Usaha)
        // Unit 1: Warung Babi Guling
        $pj1Id = DB::table('tb_penanggung_jawab')->updateOrInsert(
            ['email' => 'sukarata@gmail.com'],
            [
                'status_penanggung_jawab' => 'Pemilik',
                'nama' => 'I Gede Sukarata',
                'alamat' => 'Ubud, Gianyar',
                'no_wa_pngg' => '081234567890',
                'aktif' => '1'
            ]
        );
        $pj1 = DB::table('tb_penanggung_jawab')->where('email', 'sukarata@gmail.com')->first();

        DB::table('tb_detail_usaha')->updateOrInsert(
            ['email_usaha' => 'ibuoka@warung.com'],
            [
                'nama_usaha' => 'Warung Babi Guling Ibu Oka',
                'id_banjar' => $banjarUbud->id_data_banjar,
                'no_telp' => '0361975432',
                'no_wa' => '081234567891',
                'alamat_banjar' => 'Jl. Suweta No. 2, Ubud',
                'minimal_bayar' => 50000,
                'aktif' => '1',
                'tanggal_daftar' => date('Y-m-d')
            ]
        );
        $detail1 = DB::table('tb_detail_usaha')->where('email_usaha', 'ibuoka@warung.com')->first();

        $user1 = User::updateOrCreate(
            ['email' => 'unit.ubud@spda.com'],
            [
                'name' => 'Warung Ibu Oka',
                'password' => Hash::make('password'),
                'id_level' => 3,
                'no_wa' => '081234567891'
            ]
        );

        DB::table('tb_usaha')->updateOrInsert(
            ['user_id' => $user1->id],
            [
                'id_detail_usaha' => $detail1->id_detail_usaha,
                'id_penanggung_jawab' => $pj1->id_penanggung_jawab,
                'aktif_status' => '1'
            ]
        );
        $usaha1 = DB::table('tb_usaha')->where('user_id', $user1->id)->first();

        // Unit 2: Villa di Canggu
        DB::table('tb_penanggung_jawab')->updateOrInsert(
            ['email' => 'sariani@villa.com'],
            [
                'status_penanggung_jawab' => 'Manager',
                'nama' => 'Ni Wayan Sariani',
                'alamat' => 'Canggu, Badung',
                'no_wa_pngg' => '087865432100',
                'aktif' => '1'
            ]
        );
        $pj2 = DB::table('tb_penanggung_jawab')->where('email', 'sariani@villa.com')->first();

        DB::table('tb_detail_usaha')->updateOrInsert(
            ['email_usaha' => 'info@santivilla.com'],
            [
                'nama_usaha' => 'Santi Villa Canggu',
                'id_banjar' => $banjarCanggu->id_data_banjar,
                'no_telp' => '0361844556',
                'no_wa' => '087865432101',
                'alamat_banjar' => 'Jl. Nelayan No. 15, Canggu',
                'minimal_bayar' => 100000,
                'aktif' => '1',
                'tanggal_daftar' => date('Y-m-d')
            ]
        );
        $detail2 = DB::table('tb_detail_usaha')->where('email_usaha', 'info@santivilla.com')->first();

        $user2 = User::updateOrCreate(
            ['email' => 'unit.canggu@spda.com'],
            [
                'name' => 'Santi Villa',
                'password' => Hash::make('password'),
                'id_level' => 3,
                'no_wa' => '087865432101'
            ]
        );

        DB::table('tb_usaha')->updateOrInsert(
            ['user_id' => $user2->id],
            [
                'id_detail_usaha' => $detail2->id_detail_usaha,
                'id_penanggung_jawab' => $pj2->id_penanggung_jawab,
                'aktif_status' => '1'
            ]
        );
        $usaha2 = DB::table('tb_usaha')->where('user_id', $user2->id)->first();

        // 4. Seed Workforce (Tenaga Kerja)
        $karyawans = [
            ['nama' => 'I Kadek Winarta', 'email' => 'kadek@gmail.com', 'wa' => '085100000001', 'umur' => 24, 'jk' => 1, 'alamat' => 'Desa Sayan, Ubud'],
            ['nama' => 'Ni Made Putriati', 'email' => 'made@gmail.com', 'wa' => '085100000002', 'umur' => 22, 'jk' => 2, 'alamat' => 'Br. Tengah, Sanur'],
            ['nama' => 'I Putu Gede Agus', 'email' => 'putu@gmail.com', 'wa' => '085100000003', 'umur' => 28, 'jk' => 1, 'alamat' => 'Kedonganan, Badung'],
            ['nama' => 'Ni Ketut Suaryani', 'email' => 'ketut@gmail.com', 'wa' => '085100000004', 'umur' => 25, 'jk' => 2, 'alamat' => 'Tegallalang, Gianyar'],
            ['nama' => 'I Wayan Sudarsana', 'email' => 'wayan@gmail.com', 'wa' => '085100000005', 'umur' => 35, 'jk' => 1, 'alamat' => 'Jimbaran, Badung'],
            ['nama' => 'I Gede Budiase', 'email' => 'gede@gmail.com', 'wa' => '085100000006', 'umur' => 30, 'jk' => 1, 'alamat' => 'Kintamani, Bangli'],
            ['nama' => 'Ni Nyoman Rai', 'email' => 'nyoman@gmail.com', 'wa' => '085100000007', 'umur' => 21, 'jk' => 2, 'alamat' => 'Renon, Denpasar'],
        ];

        $k_models = [];
        foreach ($karyawans as $k) {
            $k_models[] = Karyawan::firstOrCreate(
                ['email_karyawan' => $k['email']],
                [
                    'nama' => $k['nama'],
                    'no_wa' => $k['wa'],
                    'umur' => $k['umur'],
                    'jenis_kelamin' => $k['jk'],
                    'alamat' => $k['alamat'],
                    'aktif' => '1',
                    'status' => '0'
                ]
            );
        }

        // 5. Seed Loker
        $loker1 = Loker::firstOrCreate(
            ['judul' => 'Pramusaji Warung Babi Guling', 'id_usaha' => $usaha1->id_usaha],
            [
                'deskripsi' => 'Dibutuhkan pramusaji jujur dan cekatan untuk melayani pelanggan di Warung Ibu Oka Ubud.',
                'status' => 'Buka'
            ]
        );

        $loker2 = Loker::firstOrCreate(
            ['judul' => 'Housekeeping Villa (Canggu)', 'id_usaha' => $usaha2->id_usaha],
            [
                'deskripsi' => 'Dicari staf housekeeping berpengalaman untuk villa di area Canggu. Mengutamakan kerapian dan teliti.',
                'status' => 'Buka'
            ]
        );

        // 6. Seed Interviews (Wawancara)
        if (DB::table('tb_jadwal_interview')->where('id_karyawan', $k_models[0]->id_tenaga_kerja)->count() == 0) {
            DB::table('tb_jadwal_interview')->insert([
                'id_karyawan' => $k_models[0]->id_tenaga_kerja,
                'id_usaha' => $usaha1->id_usaha,
                'tanggal_interview' => date('Y-m-d', strtotime('+2 days')),
                'jam' => '09:00',
                'status_diterima' => '0',
                'aktif' => '1'
            ]);
            $k_models[0]->update(['status' => '1']);
        }

        if (DB::table('tb_jadwal_interview')->where('id_karyawan', $k_models[1]->id_tenaga_kerja)->count() == 0) {
            DB::table('tb_jadwal_interview')->insert([
                'id_karyawan' => $k_models[1]->id_tenaga_kerja,
                'id_usaha' => $usaha2->id_usaha,
                'tanggal_interview' => date('Y-m-d', strtotime('+3 days')),
                'jam' => '10:00',
                'status_diterima' => '0',
                'aktif' => '1'
            ]);
            $k_models[1]->update(['status' => '1']);
        }

        // 7. Seed Active Workforce (Accepted)
        if (DB::table('tb_jadwal_interview')->where('id_karyawan', $k_models[2]->id_tenaga_kerja)->where('status_diterima', '1')->count() == 0) {
            DB::table('tb_jadwal_interview')->insert([
                'id_karyawan' => $k_models[2]->id_tenaga_kerja,
                'id_usaha' => $usaha1->id_usaha,
                'tanggal_interview' => date('Y-m-d', strtotime('-5 days')),
                'jam' => '14:00',
                'status_diterima' => '1',
                'tanggal_diterima' => date('Y-m-d', strtotime('-3 days')),
                'jabatan' => 'Asisten Koki',
                'aktif' => '1'
            ]);
            $k_models[2]->update(['status' => '2']);
        } // 2 = Active Working
    }
}
