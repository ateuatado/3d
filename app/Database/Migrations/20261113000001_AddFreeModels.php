<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

final class AddFreeModels extends Migration
{
    public function up(): void
    {
        $this->forge->addColumn('models', [
            'is_free' => ['type' => 'TINYINT', 'constraint' => 1, 'unsigned' => true, 'default' => 0, 'after' => 'price'],
        ]);
    }

    public function down(): void
    {
        $this->forge->dropColumn('models', 'is_free');
    }
}
