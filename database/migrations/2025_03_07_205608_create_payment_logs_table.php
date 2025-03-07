<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('payment_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onDelete('cascade'); // Συνδέεται με τον πελάτη
            $table->decimal('amount', 10, 2); // Ποσό πληρωμής
            $table->date('payment_date'); // Ημερομηνία πληρωμής
            $table->string('invoice_number')->nullable(); // Προαιρετικός αριθμός τιμολογίου
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('payment_logs');
    }
};
