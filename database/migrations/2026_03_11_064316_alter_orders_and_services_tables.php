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
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['service_id']);
            $table->dropColumn(['service_id', 'quantity']);
            $table->string('customer_name')->nullable()->after('user_id'); // For walk-in customers
        });

        Schema::table('services', function (Blueprint $table) {
            $table->enum('pricing_model', ['item', 'package'])->default('item')->after('unit');
            $table->integer('package_qty')->nullable()->after('pricing_model');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn(['pricing_model', 'package_qty']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('service_id')->nullable()->constrained()->onDelete('cascade');
            $table->decimal('quantity', 8, 2)->nullable();
            $table->dropColumn('customer_name');
        });
    }
};
