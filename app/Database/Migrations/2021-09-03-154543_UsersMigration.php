<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UsersMigration extends Migration
{
	public function up()
	{
		$this->forge->addField([
			'id_user' => [
				'type' => 'INT',
				'constraint' => 5,
				'unsigned' => true,
				'auto_increment' => true
			],
			'email' => [
				'type' => 'VARCHAR',
				'constraint' => '50',
			],
			'first_name' => [
				'type' => 'VARCHAR',
				'constraint' => '50'
			],
			'last_name' => [
				'type' => 'VARCHAR',
				'constraint' => '50'
			],
			'foto_profile' => [
				'type' => 'VARCHAR',
				'constraint' => 50
			],
			'created_at' => [
				'type' => 'DATETIME',
				'default' => null,
				'null' => true
			],
			'updated_at' => [
				'type' => 'DATETIME',
				'default' => null,
				'null' => true
			],
			'is_active' => [
				'type' => 'ENUM',
				'constraint' => ['active', 'not_active'],
				'default' => 'not_active'
			]
		]);
		$this->forge->addPrimaryKey('id_user');
		$this->forge->addUniqueKey('email');
		$this->forge->createTable('users');
	}

	public function down()
	{
		$this->forge->dropTable('users');
	}
}
