<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('renewal_logs', function (Blueprint $table) {
            $table->dropColumn(['amount', 'invoice_number']);
        });
    }
    
    public function down()
    {
        Schema::table('renewal_logs', function (Blueprint $table) {
            $table->decimal('amount', 10, 2)->nullable();
            $table->string('invoice_number')->nullable();
        });
    }
    
};
