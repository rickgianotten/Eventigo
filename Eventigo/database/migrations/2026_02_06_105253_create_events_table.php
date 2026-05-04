<?php


use App\Models\Category;
use App\Models\Company;
use App\Models\User;
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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->enum('status', ['online', 'offline', 'concept'])->default('online');
            $table->foreignIdFor(Category::class)->constrained();
            $table->foreignIdFor(Company::class)->constrained()->cascadeOnDelete();   
            $table->string('title');         
            $table->string('slug')->unique();
            $table->text('short_description');
            $table->text('long_description')->nullable();
            $table->date('start_date');
            $table->date('end_date');           
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->string('location');
            $table->string('city');
            $table->string('street');
            $table->string('postal_code');
            $table->string('image_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
