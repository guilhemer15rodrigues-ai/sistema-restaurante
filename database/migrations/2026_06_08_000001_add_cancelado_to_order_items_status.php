<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE order_items MODIFY COLUMN status ENUM('pendente','em_preparo','pronto','entregue','cancelado') NOT NULL DEFAULT 'pendente'");
    }

    public function down(): void
    {
        DB::statement("UPDATE order_items SET status = 'entregue' WHERE status = 'cancelado'");
        DB::statement("ALTER TABLE order_items MODIFY COLUMN status ENUM('pendente','em_preparo','pronto','entregue') NOT NULL DEFAULT 'pendente'");
    }
};
