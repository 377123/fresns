<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jevan Tang
 * Released under the Apache-2.0 License.
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    /**
     * Run fresns migrations.
     */
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedTinyInteger('type');
            $table->unsignedBigInteger('user_id');
            $table->text('content')->nullable();
            $table->unsignedTinyInteger('is_markdown')->default(0);
            $table->unsignedTinyInteger('is_multilingual')->default(0);
            $table->unsignedTinyInteger('is_mention')->default(0);
            $table->unsignedTinyInteger('is_access_plugin')->default(0);
            $table->string('plugin_fskey', 64)->nullable();
            $table->unsignedBigInteger('action_user_id')->nullable();
            $table->unsignedSmallInteger('action_type')->nullable();
            $table->unsignedTinyInteger('action_object')->nullable();
            $table->unsignedBigInteger('action_id')->nullable();
            $table->unsignedBigInteger('action_content_id')->nullable();
            $table->unsignedTinyInteger('is_read')->default(0);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
            $table->softDeletes();
        });
    }

    /**
     * Reverse fresns migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
}
