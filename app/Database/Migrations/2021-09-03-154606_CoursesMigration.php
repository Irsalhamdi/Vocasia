<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CoursesMigration extends Migration
{
	public function up()
	{
		$this->forge->addField([
			'id_course' => [
				'type' => 'INT',
				'constraint' => 5,
				'unsigned' => true,
				'auto_increment' => true
			],
			'id_user' => [
				'type' => 'INT',
				'constraint' => 5,
				'unsigned' => true
			],
			'id_category' => [
				'type' => 'INT',
				'constraint' => 5,
				'unsigned' => true
			],
			'description' => [
				'type' => 'VARCHAR',
				'constraint' => '255',
			],
			'short_description' => [
				'type' => 'VARCHAR',
				'constraint' => '255',
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
			'status' => [
				'type' => 'ENUM',
				'constraint' => ['pending', 'active', 'prepublish'],
				'default' => 'prepublish'
			]
		]);
		$this->forge->addPrimaryKey('id_course');
		$this->forge->createTable('courses');
	}

	public function down()
	{
		$this->forge->dropTable('courses');
	}
}
