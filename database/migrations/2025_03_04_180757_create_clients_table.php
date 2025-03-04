<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('domain_name')->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('afm')->unique();
            $table->string('email');
            $table->decimal('hosting_cost', 8, 2);
            $table->date('hosting_start_date');
            $table->date('hosting_expiration_date');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('clients');
    }
};
