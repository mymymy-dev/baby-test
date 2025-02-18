<?php

declare(strict_types = 1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('baby_activities', function (Blueprint $table): void {
            $table->ulid('id')->primary();
            $table->foreignUlid('baby_id')->nullable()->constrained('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('type', 30);
            $table->dateTimeTz('date');
            $table->json('data')->nullable();
            $table->foreignUlid('user_id')->nullable()->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->dateTimeTz('created_at')->nullable();
            $table->dateTimeTz('updated_at')->nullable();
            $table->dateTimeTz('deleted_at')->nullable();

            $table->index(['daleted_at', 'baby_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('baby_activities');
    }
};
