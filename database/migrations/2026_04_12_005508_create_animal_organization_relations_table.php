<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('animal_organization', function (Blueprint $table) {
            $table->foreignUuid('animal_id')
                ->constrained('animals', 'id')
                ->cascadeOnDelete();
            $table->foreignUuid('organization_id')
                ->constrained('organizations', 'id')
                ->cascadeOnDelete();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('animal_organization');
    }
};
