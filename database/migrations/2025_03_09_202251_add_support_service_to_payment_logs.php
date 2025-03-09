<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('payment_logs', function (Blueprint $table) {
            $table->boolean('support_service')->default(false)->after('payment_date');
            $table->decimal('support_cost', 10, 2)->nullable()->after('support_service');
        });
    }

    public function down(): void
    {
        Schema::table('payment_logs', function (Blueprint $table) {
            $table->dropColumn(['support_service', 'support_cost']);
        });
    }
};

