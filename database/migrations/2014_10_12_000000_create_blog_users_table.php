<?php

use Jecharlt\LivewireBlogCMS\Models\BlogUser;
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
        Schema::create('blog_users', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('password');
            $table->timestamps();
        });

        BlogUser::updateOrCreate([
            'username' => env('LIVEWIRE_BLOG_CMS_ADMIN_USERNAME'),
            'password' => bcrypt(env('LIVEWIRE_BLOG_CMS_ADMIN_PASSWORD')),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_users');
    }
};
