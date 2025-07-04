
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name', 50);
            $table->string('last_name', 50);
            $table->string('email', 50)->unique();
            $table->string('mobile', 20)->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('otp', 10);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};