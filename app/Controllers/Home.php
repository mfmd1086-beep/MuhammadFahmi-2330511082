<?php

namespace App\Controllers;

use App\Models\BiodataModel;
use App\Models\PendidikanModel;
use App\Models\PengalamanModel;
use App\Models\KeahlianModel;
use App\Models\PortofolioModel;

class Home extends BaseController
{
    public function index()
    {
        helper('url'); // supaya base_url() bisa dipakai di view
        try {
            $biodataModel     = new BiodataModel();
            $pendidikanModel  = new PendidikanModel();
            $pengalamanModel  = new PengalamanModel();
            $keahlianModel    = new KeahlianModel();
            $portofolioModel  = new PortofolioModel();

            // Asumsi biodata kamu id = 1
            $biodata = $biodataModel->find(1);

            // Jaga-jaga kalau di hosting nanti row biodata-nya kepencet kehapus
            if (!$biodata) {
                throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Biodata tidak ditemukan');
            }

            $data = [
                'biodata'    => $biodata,
                'pendidikan' => $pendidikanModel->where('biodata_id', 1)
                                                ->orderBy('tahun_mulai', 'DESC')
                                                ->findAll(),
                'pengalaman' => $pengalamanModel->where('biodata_id', 1)
                                                ->orderBy('tahun_mulai', 'DESC')
                                                ->findAll(),
                'keahlian'   => $keahlianModel->where('biodata_id', 1)
                                              ->orderBy('id', 'ASC')
                                              ->findAll(),
                'portofolio' => $portofolioModel->where('biodata_id', 1)
                                                ->orderBy('id', 'ASC')
                                                ->findAll(),
            ];

            return view('welcome_message', $data);
        } catch (\Exception $e) {
            // Jika ada masalah koneksi DB atau query, tampilkan halaman bantuan yang lebih jelas.
            $data = ['error' => $e->getMessage(), 'db' => env('database.default.database') ?? 'fahnicv'];
            return view('db_unavailable', $data);
        }
    }
}
