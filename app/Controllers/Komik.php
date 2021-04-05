<?php

namespace App\Controllers;

use App\Models\KomikModel;

class Komik extends BaseController
{

    protected $komikModel;
    // agar bisa dipakai disini dan kelas turunannya

    public function __construct()
    {
        $this->komikModel = new KomikModel();
    }


    public function index()
    {

        $data = [
            'title' => 'Halaman Komik',
            'komik' => $this->komikModel->getKomik()
        ];


        // echo view('komik/komik', $data);
        return view('komik/komik', $data);
    }


    public function detail($slug)
    {

        $data = [
            'title' => 'Detail Komik',
            'komik' => $this->komikModel->getKomik($slug)
        ];

        // jika komik tidak ada di tabel
        if (empty($data['komik'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Judul komik ' . $slug . ' tidak ditemukan.');
        }

        return view('komik/detail', $data);
    }


    public function create()
    {
        session();
        $data = [
            'title' => 'Form Tambah Data Komik',
            'validation' => \Config\Services::validation()
        ];

        return view('komik/create', $data);
    }


    public function save()
    {
        // untuk mengambil method apapun (post/get) dan mengambil field judul
        // $this->request->getVar('judul');

        // untuk mengambil method apapun (post/get) dan mengambil semua field
        // $this->request->getVar();


        // validasi input
        if (!$this->validate([
            'judul' => [
                'rules' => 'required|is_unique[komik.judul]',
                'errors' => [
                    'required' => '{field} komik harus diisi.',
                    'is_unique' => '{field} komik sudah terdaftar'
                ]
            ],
            'cover' => [
                'rules' => 'max_size[cover,1024]|is_image[cover]|mime_in[cover,image/jpg,image/jpeg,image/png]',
                'errors' => [
                    // 'uploaded' => 'Pilih gambar terlebih dahulu',
                    'max_size' => 'Ukuran gambar terlalu besar',
                    'is_image' => 'Yang anda pilih bukan gambar',
                    'mime_in' => 'Yang anda pilih bukan gambar'
                ]
            ]
        ])) {
            // $validation = \Config\Services::validation();
            // return redirect()->to('/komik/create')->withInput()->with('validation', $validation);
            return redirect()->to('/komik/create')->withInput();
        }

        // // ===== custom upload file name =====
        // // ambil gambar
        // $fileCover = $this->request->getFile('cover');
        // // generate nama cover random
        // $namaCover = $fileCover->getRandomName();
        // // pindahkan ke folder img
        // $fileCover->move('img', $namaCover);

        // // ===== required upload file =====
        // // ambil gambar
        // $fileCover = $this->request->getFile('cover');
        // // pindahkan ke folder img
        // $fileCover->move('img');
        // // ambil nama file cover
        // $namaCover = $fileCover->getName();

        // // ===== set default upload file name =====
        $fileCover = $this->request->getFile('cover');
        if ($fileCover->getError() == 4) {
            $namaCover = 'default.jpg';
        } else {
            // pindahkan ke folder img
            $fileCover->move('img');
            // ambil nama file cover
            $namaCover = $fileCover->getName();
        }

        $slug = url_title($this->request->getVar('judul'), '-', true);

        $this->komikModel->save([
            'judul' => $this->request->getVar('judul'),
            'slug' => $slug,
            'penulis' => $this->request->getVar('penulis'),
            'penerbit' => $this->request->getVar('penerbit'),
            'cover' => $namaCover
        ]);

        session()->setFlashdata('info', 'Data berhasil ditambahkan.');

        return redirect()->to('/komik');
    }


    public function delete($id)
    {
        //  ===== CARA 1 (menghapus value default) =====
        // // cari gambar berdasarkan id
        // $komik = $this->komikModel->find($id);
        // // hapus gambar
        // unlink('img/' . $komik['cover']);


        //  ===== CARA 2 (tidak menghapus value default) =====
        // cari gambar berdasarkan id
        $komik = $this->komikModel->find($id);
        // cek jika file gambarnya default.jpg
        if ($komik['cover'] != 'default.jpg') {
            // hapus gambar
            unlink('img/' . $komik['cover']);
        }

        $this->komikModel->delete($id);
        session()->setFlashdata('info', 'Data berhasil dihapus.');
        return redirect()->to('/komik');
    }


    public function edit($slug)
    {
        session();
        $data = [
            'title' => 'Form Ubah Data Komik',
            'validation' => \Config\Services::validation(),
            'komik' => $this->komikModel->getKomik($slug)
        ];

        return view('komik/edit', $data);
    }


    public function update($id)
    {
        // cek judul
        $komikLama = $this->komikModel->getKomik($this->request->getVar('slug'));
        if ($komikLama['judul'] == $this->request->getVar('judul')) {
            $rule_judul = 'required';
        } else {
            $rule_judul = 'required|is_unique[komik.judul]';
        }

        if (!$this->validate([
            'judul' => [
                'rules' => $rule_judul,
                'errors' => [
                    'required' => '{field} komik harus diisi.',
                    'is_unique' => '{field} komik sudah terdaftar'
                ]
            ],
            'cover' => [
                'rules' => 'max_size[cover,1024]|is_image[cover]|mime_in[cover,image/jpg,image/jpeg,image/png]',
                'errors' => [
                    // 'uploaded' => 'Pilih gambar terlebih dahulu',
                    'max_size' => 'Ukuran gambar terlalu besar',
                    'is_image' => 'Yang anda pilih bukan gambar',
                    'mime_in' => 'Yang anda pilih bukan gambar'
                ]
            ]
        ])) {
            return redirect()->to('/komik/edit/' . $this->request->getVar('slug'))->withInput();
        }

        $fileCover = $this->request->getFile('cover');
        // cek gambar, apakah tetap gambar lama
        if ($fileCover->getError() == 4) {
            $namaCover = $this->request->getVar('coverLama');
        } else {
            $namaCover = $fileCover->getName();
            // pindahkan gambar
            $fileCover->move('img', $namaCover);
            // hapus file lama
            unlink('img/' . $this->request->getVar('coverLama'));
        }

        $slug = url_title($this->request->getVar('judul'), '-', true);

        $this->komikModel->save([
            'id' => $id,
            'judul' => $this->request->getVar('judul'),
            'slug' => $slug,
            'penulis' => $this->request->getVar('penulis'),
            'penerbit' => $this->request->getVar('penerbit'),
            'cover' => $namaCover
        ]);

        session()->setFlashdata('info', 'Data berhasil diubah.');

        return redirect()->to('/komik');
    }
}
