<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

final class CreateMarketplaceTables extends Migration
{
    public function up(): void
    {
        $this->createModels();
        $this->createModelFiles();
        $this->createOrders();
        $this->createOrderItems();
        $this->createPayments();
        $this->createDownloadAuthorizations();
        $this->createCustomRequests();
        $this->createAuditEvents();
    }

    public function down(): void
    {
        foreach ([
            'audit_events',
            'custom_requests',
            'download_authorizations',
            'payments',
            'order_items',
            'orders',
            'model_files',
            'models',
        ] as $table) {
            $this->forge->dropTable($table, true);
        }
    }

    private function createModels(): void
    {
        $this->forge->addField([
            'id' => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'title' => ['type' => 'VARCHAR', 'constraint' => 180],
            'slug' => ['type' => 'VARCHAR', 'constraint' => 200],
            'description' => ['type' => 'TEXT'],
            'category' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'tags' => ['type' => 'TEXT', 'null' => true],
            'dimensions' => ['type' => 'VARCHAR', 'constraint' => 120, 'null' => true],
            'unit' => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'intended_use' => ['type' => 'TEXT', 'null' => true],
            'compatibility_notes' => ['type' => 'TEXT', 'null' => true],
            'license_terms' => ['type' => 'TEXT'],
            'price' => ['type' => 'DECIMAL', 'constraint' => '12,2'],
            'currency' => ['type' => 'CHAR', 'constraint' => 3, 'default' => 'BRL'],
            'status' => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'draft'],
            'published_version' => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'created_by' => ['type' => 'BIGINT', 'unsigned' => true, 'null' => true],
            'updated_by' => ['type' => 'BIGINT', 'unsigned' => true, 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('slug', false, true);
        $this->forge->addKey(['status', 'created_at']);
        $this->forge->createTable('models', true);
    }

    private function createModelFiles(): void
    {
        $this->forge->addField([
            'id' => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'model_id' => ['type' => 'BIGINT', 'unsigned' => true],
            'version' => ['type' => 'INT', 'unsigned' => true, 'default' => 1],
            'kind' => ['type' => 'VARCHAR', 'constraint' => 20],
            'original_name' => ['type' => 'VARCHAR', 'constraint' => 255],
            'storage_name' => ['type' => 'VARCHAR', 'constraint' => 255],
            'storage_path' => ['type' => 'VARCHAR', 'constraint' => 500],
            'mime_type' => ['type' => 'VARCHAR', 'constraint' => 150],
            'extension' => ['type' => 'VARCHAR', 'constraint' => 20],
            'size_bytes' => ['type' => 'BIGINT', 'unsigned' => true],
            'sha256' => ['type' => 'CHAR', 'constraint' => 64],
            'status' => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'pending'],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['model_id', 'version']);
        $this->forge->addKey('sha256');
        $this->forge->createTable('model_files', true);
    }

    private function createOrders(): void
    {
        $this->forge->addField([
            'id' => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'public_number' => ['type' => 'VARCHAR', 'constraint' => 40],
            'user_id' => ['type' => 'BIGINT', 'unsigned' => true],
            'status' => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'pending'],
            'subtotal' => ['type' => 'DECIMAL', 'constraint' => '12,2'],
            'discount' => ['type' => 'DECIMAL', 'constraint' => '12,2', 'default' => 0],
            'total' => ['type' => 'DECIMAL', 'constraint' => '12,2'],
            'currency' => ['type' => 'CHAR', 'constraint' => 3, 'default' => 'BRL'],
            'paid_at' => ['type' => 'DATETIME', 'null' => true],
            'cancelled_at' => ['type' => 'DATETIME', 'null' => true],
            'cancellation_reason' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('public_number', false, true);
        $this->forge->addKey(['user_id', 'status']);
        $this->forge->createTable('orders', true);
    }

    private function createOrderItems(): void
    {
        $this->forge->addField([
            'id' => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'order_id' => ['type' => 'BIGINT', 'unsigned' => true],
            'model_id' => ['type' => 'BIGINT', 'unsigned' => true],
            'model_version' => ['type' => 'INT', 'unsigned' => true],
            'description' => ['type' => 'VARCHAR', 'constraint' => 255],
            'quantity' => ['type' => 'INT', 'unsigned' => true, 'default' => 1],
            'unit_price' => ['type' => 'DECIMAL', 'constraint' => '12,2'],
            'total' => ['type' => 'DECIMAL', 'constraint' => '12,2'],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['order_id', 'model_id']);
        $this->forge->createTable('order_items', true);
    }

    private function createPayments(): void
    {
        $this->forge->addField([
            'id' => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'order_id' => ['type' => 'BIGINT', 'unsigned' => true],
            'external_id' => ['type' => 'VARCHAR', 'constraint' => 100],
            'external_status' => ['type' => 'VARCHAR', 'constraint' => 50],
            'method' => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'pix'],
            'amount' => ['type' => 'DECIMAL', 'constraint' => '12,2'],
            'response_summary' => ['type' => 'TEXT', 'null' => true],
            'confirmed_at' => ['type' => 'DATETIME', 'null' => true],
            'refunded_at' => ['type' => 'DATETIME', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('external_id', false, true);
        $this->forge->addKey(['order_id', 'external_status']);
        $this->forge->createTable('payments', true);
    }

    private function createDownloadAuthorizations(): void
    {
        $this->forge->addField([
            'id' => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'token_hash' => ['type' => 'CHAR', 'constraint' => 64],
            'user_id' => ['type' => 'BIGINT', 'unsigned' => true],
            'order_id' => ['type' => 'BIGINT', 'unsigned' => true],
            'order_item_id' => ['type' => 'BIGINT', 'unsigned' => true],
            'model_file_id' => ['type' => 'BIGINT', 'unsigned' => true],
            'expires_at' => ['type' => 'DATETIME', 'null' => true],
            'max_uses' => ['type' => 'INT', 'unsigned' => true, 'default' => 3],
            'used_count' => ['type' => 'INT', 'unsigned' => true, 'default' => 0],
            'revoked_at' => ['type' => 'DATETIME', 'null' => true],
            'last_download_at' => ['type' => 'DATETIME', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('token_hash', false, true);
        $this->forge->addKey(['user_id', 'order_id']);
        $this->forge->createTable('download_authorizations', true);
    }

    private function createCustomRequests(): void
    {
        $this->forge->addField([
            'id' => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'user_id' => ['type' => 'BIGINT', 'unsigned' => true, 'null' => true],
            'name' => ['type' => 'VARCHAR', 'constraint' => 160],
            'email' => ['type' => 'VARCHAR', 'constraint' => 255],
            'phone' => ['type' => 'VARCHAR', 'constraint' => 40],
            'description' => ['type' => 'TEXT'],
            'requirements' => ['type' => 'TEXT', 'null' => true],
            'desired_format' => ['type' => 'VARCHAR', 'constraint' => 80, 'null' => true],
            'status' => ['type' => 'VARCHAR', 'constraint' => 30, 'default' => 'received'],
            'internal_notes' => ['type' => 'TEXT', 'null' => true],
            'assigned_to' => ['type' => 'BIGINT', 'unsigned' => true, 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['status', 'created_at']);
        $this->forge->createTable('custom_requests', true);
    }

    private function createAuditEvents(): void
    {
        $this->forge->addField([
            'id' => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'user_id' => ['type' => 'BIGINT', 'unsigned' => true, 'null' => true],
            'action' => ['type' => 'VARCHAR', 'constraint' => 100],
            'entity' => ['type' => 'VARCHAR', 'constraint' => 100],
            'entity_id' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'result' => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'success'],
            'metadata' => ['type' => 'TEXT', 'null' => true],
            'ip_address' => ['type' => 'VARCHAR', 'constraint' => 45, 'null' => true],
            'correlation_id' => ['type' => 'VARCHAR', 'constraint' => 80, 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['entity', 'entity_id']);
        $this->forge->addKey(['action', 'created_at']);
        $this->forge->createTable('audit_events', true);
    }
}
