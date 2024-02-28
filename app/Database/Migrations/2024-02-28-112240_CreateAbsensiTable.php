<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAbsensiTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true
            ],
            'member_id' => [
                'type'          => 'INT',
                'constraint'    => 20,
                'unsigned'      => true,
                'null'          => true
            ],
            'created_at' => [
                'type'       => 'TIMESTAMP',
                'null'       => true,
                'default'    => null
            ],
            'updated_at' => [
                'type'       => 'TIMESTAMP',
                'null'       => true,
                'default'    => null
            ],
            'deleted_at' => [
                'type'       => 'TIMESTAMP',
                'null'       => true,
                'default'    => null
            ],
        ]);

        $this->forge->addPrimaryKey('id');

        $this->forge->addForeignKey('member_id', 'members', 'id', 'CASCADE', 'NO ACTION');

        $this->forge->createTable('absensi', TRUE);
    }

    public function down()
    {
        $this->forge->dropTable('absensi');
    }
}
