<?php

use App\Models\MachineState;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('machine_states', function (Blueprint $table) {
            $table->id();
            $table->integer('balance')->default(0);
            $table->timestamps();
        });

        MachineState::create([
            'balance' => 0
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('machine_states');
    }
};
