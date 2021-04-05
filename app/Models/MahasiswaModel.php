<?php

namespace App\Models;

use CodeIgniter\Model;

class MahasiswaModel extends Model
{
	protected $table      = 'mahasiswa';
	protected $primaryKey = 'id_mahasiswa';
	protected $useTimeStamp = true;
	protected $allowedFields = ['nama', 'alamat'];


	public function search($keyword)
	{
		// // ===== CARA 1
		// $builder = $this->table('mahasiswa');
		// $builder->like('nama', $keyword);
		// $builder->like('alamat', $keyword);
		// return $builder;

		// ===== CARA 2
		return $this->table('mahasiswa')->like('nama', $keyword)->orLike('alamat', $keyword);
	}
}
