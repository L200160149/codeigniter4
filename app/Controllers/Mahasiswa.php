<?php

namespace App\Controllers;

use App\Controllers\BaseController;

use App\Models\MahasiswaModel;

class Mahasiswa extends BaseController
{
	protected $mahasiswaModel;
	// agar bisa dipakai disini dan kelas turunannya

	public function __construct()
	{
		$this->mahasiswaModel = new MahasiswaModel();
	}


	public function index()
	{
		// pagination
		$currentPage = $this->request->getVar('page_mahasiswa') ? $this->request->getVar('page_mahasiswa') : 1;
		// searching
		$keyword = $this->request->getVar('keyword');
		if ($keyword) {
			$mahasiswa = $this->mahasiswaModel->search($keyword);
		} else {
			$mahasiswa = $this->mahasiswaModel;
		}

		$data = [
			'title' => 'Halaman Mahasiswa',
			// 'mahasiswa' => $this->mahasiswaModel->findAll()
			// 'mahasiswa' => $this->mahasiswaModel->paginate(10, 'mahasiswa'),
			'mahasiswa' => $mahasiswa->paginate(10, 'mahasiswa'),
			'pager' => $this->mahasiswaModel->pager,
			'currentPage' => $currentPage
		];


		// echo view('komik/komik', $data);
		return view('mahasiswa/index', $data);
	}
}
