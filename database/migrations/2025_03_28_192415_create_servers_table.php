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
        Schema::create('servers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('ip_address');
            $table->string('cpu')->nullable();
            $table->unsignedInteger('memory_gb')->nullable(); 
            $table->unsignedInteger('disk_gb')->nullable();   
            $table->string('type')->nullable();               
            $table->string('hosting_company')->nullable();   
            $table->decimal('monthly_cost', 10, 2)->nullable(); 
            $table->text('notes')->nullable();             
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servers');
    }
};
