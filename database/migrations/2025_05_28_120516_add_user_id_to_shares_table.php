<?php

use App\Models\Share;
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
        // Clear old shares
        Share::truncate();

        Schema::table('shares', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->after('file_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shares', function (Blueprint $table) {
            Schema::dropIfExists('user_id');
        });
    }
};
