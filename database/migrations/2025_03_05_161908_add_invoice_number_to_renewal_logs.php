<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('renewal_logs', function (Blueprint $table) {
            $table->string('invoice_number')->nullable()->after('new_expiration_date');
        });
    }

    public function down()
    {
        Schema::table('renewal_logs', function (Blueprint $table) {
            $table->dropColumn('invoice_number');
        });
    }
};

