<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CategoryMigration extends Migration
{
	public function up()
	{
		$this->forge->addField([
			'id_category' => [
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
				'constraint' => '50',
			],
			'last_name' => [
				'type' => 'VARCHAR',
				'constraint' => '50',
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
		$this->forge->addPrimaryKey('id_category');
		$this->forge->createTable('category');
	}

	public function down()
	{
		$this->forge->dropTable('category');
	}
}
