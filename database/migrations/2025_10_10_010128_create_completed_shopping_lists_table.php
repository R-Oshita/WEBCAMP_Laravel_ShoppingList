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
        Schema::create('completed_shopping_lists', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->comment('この買うもの名の所有者');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade'); // 外部キー制約
            $table->string('name', 255)->comment('「買うもの」名');
            $table->dateTime('created_at')->useCurrent()->comment('購入日');
            $table->dateTime('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('completed_shopping_lists');
    }
};
