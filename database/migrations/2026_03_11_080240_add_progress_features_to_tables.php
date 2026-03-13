<?php

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
        Schema::table('services', function (Blueprint $table) {
            $table->decimal('washer_wage', 15, 2)->default(0)->after('package_qty');
            $table->decimal('ironer_wage', 15, 2)->default(0)->after('washer_wage');
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->enum('washing_status', ['pending', 'completed'])->default('pending')->after('subtotal');
            $table->enum('ironing_status', ['pending', 'completed'])->default('pending')->after('washing_status');
            
            $table->unsignedBigInteger('washer_id')->nullable()->after('ironing_status');
            $table->decimal('washer_wage_amount', 15, 2)->default(0)->after('washer_id');
            
            $table->unsignedBigInteger('ironer_id')->nullable()->after('washer_wage_amount');
            $table->decimal('ironer_wage_amount', 15, 2)->default(0)->after('ironer_id');

            $table->foreign('washer_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('ironer_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropForeign(['washer_id']);
            $table->dropForeign(['ironer_id']);
            $table->dropColumn(['washing_status', 'ironing_status', 'washer_id', 'washer_wage_amount', 'ironer_id', 'ironer_wage_amount']);
        });

        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn(['washer_wage', 'ironer_wage']);
        });
    }
};
