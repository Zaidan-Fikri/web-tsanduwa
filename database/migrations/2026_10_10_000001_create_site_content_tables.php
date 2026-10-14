<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Pengaturan umum (kontak, sambutan, identitas, visi, sejarah, SPMB, dll.)
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key', 100)->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // Beranda
        Schema::create('hero_slides', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->string('button_text', 50)->nullable();
            $table->string('button_url')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('agendas', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->string('category', 30)->default('kegiatan');
            $table->timestamps();
        });

        Schema::create('facilities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        // Profil
        Schema::create('missions', function (Blueprint $table) {
            $table->id();
            $table->text('content');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('org_structures', function (Blueprint $table) {
            $table->id();
            $table->string('position');
            $table->string('name');
            $table->unsignedTinyInteger('level')->default(2);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('work_units', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 60)->unique();
            $table->string('position');
            $table->string('field_title');
            $table->string('person_name');
            $table->string('photo')->nullable();
            $table->string('icon', 60)->default('fa-briefcase');
            $table->text('description')->nullable();
            $table->json('programs')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('staff', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('position');
            $table->string('type', 20)->default('guru');
            $table->string('photo')->nullable();
            $table->boolean('show_on_home')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        // Program Madrasah
        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 60)->unique();
            $table->string('menu_name');
            $table->string('title');
            $table->string('icon', 60)->default('fa-star');
            $table->text('description')->nullable();
            $table->json('activities')->nullable();
            $table->json('infos')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        // Ekstrakurikuler
        Schema::create('extracurriculars', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category', 30);
            $table->string('icon', 60)->default('fa-star');
            $table->text('description')->nullable();
            $table->string('schedule', 100)->nullable();
            $table->string('image')->nullable();
            $table->boolean('show_on_home')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        // Informasi
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('category', 30)->default('kegiatan');
            $table->string('image')->nullable();
            $table->text('excerpt')->nullable();
            $table->longText('content');
            $table->date('published_at');
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });

        Schema::create('achievements', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('winner');
            $table->string('rank', 50);
            $table->string('level', 30);
            $table->string('category', 50);
            $table->string('icon', 60)->default('fa-trophy');
            $table->unsignedSmallInteger('year');
            $table->string('image')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('show_on_home')->default(false);
            $table->timestamps();
        });

        Schema::create('galleries', function (Blueprint $table) {
            $table->id();
            $table->string('type', 10)->default('foto');
            $table->string('title');
            $table->string('image')->nullable();
            $table->string('youtube_id', 20)->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        // SPMB
        Schema::create('spmb_steps', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('icon', 60)->default('fa-file-invoice');
            $table->string('badge', 60)->nullable();
            $table->boolean('is_highlight')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('spmb_requirements', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('is_required')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        foreach ([
            'spmb_requirements', 'spmb_steps', 'galleries', 'achievements', 'posts',
            'extracurriculars', 'programs', 'staff', 'work_units', 'org_structures',
            'missions', 'facilities', 'agendas', 'hero_slides', 'settings',
        ] as $table) {
            Schema::dropIfExists($table);
        }
    }
};
