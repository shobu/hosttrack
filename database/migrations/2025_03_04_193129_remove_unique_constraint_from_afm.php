<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropUnique(['afm']); // ✅ Αφαιρεί το μοναδικό constraint
        });
    }

    public function down()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->unique('afm'); // ✅ Σε περίπτωση rollback, το ξανακάνει unique
        });
    }
};
