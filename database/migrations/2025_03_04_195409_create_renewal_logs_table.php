<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('renewal_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->date('old_expiration_date');
            $table->date('new_expiration_date');
            $table->timestamp('renewed_at')->useCurrent();
        });
    }

    public function down()
    {
        Schema::dropIfExists('renewal_logs');
    }
};
