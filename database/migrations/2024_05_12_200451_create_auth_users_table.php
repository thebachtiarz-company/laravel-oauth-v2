<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use TheBachtiarz\OAuth\Interfaces\Models\AuthUserInterface;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(AuthUserInterface::TABLE_NAME, function (Blueprint $table) {
            $table->id();
            $table->string(AuthUserInterface::ATTRIBUTE_CODE)->unique();
            $table->string(AuthUserInterface::ATTRIBUTE_USERNAME)->nullable()->unique();
            $table->string(AuthUserInterface::ATTRIBUTE_EMAIL)->nullable()->unique();
            $table->timestamp(AuthUserInterface::ATTRIBUTE_EMAIL_VERIFIED_AT)->nullable();
            $table->string(AuthUserInterface::ATTRIBUTE_PASSWORD);
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(AuthUserInterface::TABLE_NAME);
    }
};
