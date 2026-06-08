<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $tipos = [
            'bebida' => [
                'Bebidas',
            ],
            'entrada' => [
                'Entradas',
                'Petiscos',
            ],
            'prato_principal' => [
                'Pratos Principais',
                'Grelhados',
                'Massas',
                'Massas e Acompanhos',
            ],
            'acompanhamento' => [
                'Acompanhamentos',
            ],
            'sobremesa' => [
                'Sobremesas',
            ],
        ];

        foreach ($tipos as $tipo => $nomes) {
            DB::table('categories')
                ->whereIn('nome', $nomes)
                ->update(['tipo_principal' => $tipo]);
        }
    }

    public function down(): void
    {
        DB::table('categories')
            ->whereIn('nome', [
                'Bebidas',
                'Entradas',
                'Petiscos',
                'Pratos Principais',
                'Grelhados',
                'Massas',
                'Massas e Acompanhos',
                'Acompanhamentos',
                'Sobremesas',
            ])
            ->update(['tipo_principal' => null]);
    }
};
