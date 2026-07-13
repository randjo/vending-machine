<?php

use App\Domain\Vending\Currency as VendingCurrency;
use App\Models\Currency;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('currency', function (Blueprint $table) {
            $table->id();
            $table->string('sign');
            $table->string('space')->nullable();
            $table->string('position');
            $table->timestamps();
        });

        Currency::create([
            'sign' => 'лв',
            'space' => '',
            'position' => VendingCurrency::CURRENCY_POSITION_AFTER
        ]);
    }


    public function down(): void
    {
        Schema::dropIfExists('currency');
    }
};
