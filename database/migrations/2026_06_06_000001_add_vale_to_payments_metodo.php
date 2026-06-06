<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE payments MODIFY metodo ENUM('dinheiro', 'cartao_credito', 'cartao_debito', 'pix', 'vale') NOT NULL DEFAULT 'dinheiro'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE payments MODIFY metodo ENUM('dinheiro', 'cartao_credito', 'cartao_debito', 'pix') NOT NULL DEFAULT 'dinheiro'");
    }
};
