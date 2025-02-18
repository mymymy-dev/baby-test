<?php

declare(strict_types = 1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table): void {
            $table->ulid('id')->primary();
            $table->string('first_name', config('my.user.field.first_name.max_length'));
            $table->string('last_name', config('my.user.field.last_name.max_length'));
            $table->string('name', config('my.user.field.name.max_length'))->nullable();
            $table->string('email', config('my.user.field.email.max_length'));
            $table->dateTimeTz('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->dateTimeTz('created_at')->nullable();
            $table->dateTimeTz('updated_at')->nullable();
            $table->dateTimeTz('deleted_at')->nullable();

            $table->unique('name');
            $table->unique('email');
            $table->index(['last_name', 'first_name']);
            $table->index('email_verified_at');
            $table->index('created_at');
            $table->index('deleted_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
