<?php

use Illuminate\Database\Seeder;
use App\Models\{
	Tenant,
	User
};

class UsersTableSeeder extends Seeder {
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run() {
		User::create([
			'name' => 'Ângelo Hideki Noda',
			'email' => 'angelonoda@gmail.com',
			'password' => bcrypt('1079'),
		]);
		/*$tenant = Tenant::first();

		$tenant->users()->create([
			'name' => 'Carlos Ferreira',
			'email' => 'carlos@especializati.com.br',
			'password' => bcrypt('123456'),
		]);*/
	}
}
