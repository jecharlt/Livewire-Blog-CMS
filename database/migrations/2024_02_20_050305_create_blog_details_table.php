<?php

use Jecharlt\LivewireBlogCMS\Models\BlogDetails;
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
        Schema::create('blog_details', function (Blueprint $table) {
            $table->id();
            $table->string('title')->unique();
            $table->longText('description')->nullable();
            $table->text('logo_light')->nullable();
            $table->text('logo_dark')->nullable();
            $table->timestamps();
        });

        BlogDetails::updateOrCreate([
           'title' => 'Livewire Blog CMS',
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_details');
    }
};
