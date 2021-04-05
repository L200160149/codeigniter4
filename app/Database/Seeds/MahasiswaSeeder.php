<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class MahasiswaSeeder extends Seeder
{
	public function run()
	{
		for ($i = 1; $i < 100; $i++) {
			$data = [
				'nama' => static::faker()->name,
				'alamat'    => static::faker()->address,
				'created_at'    => Time::now(),
				'updated_at'    => Time::now()
			];
			$this->db->table('mahasiswa')->insert($data);
		}
	}
}
