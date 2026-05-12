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
            $table->foreignIdFor(Category::class)->nullable()->constrained();
            $table->foreignIdFor(Company::class)->constrained()->cascadeOnDelete();   
            $table->string('title')->nullable();         
            $table->string('slug')->nullable()->unique();
            $table->text('short_description')->nullable();
            $table->text('long_description')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();           
            $table->dateTime('start_time')->nullable();
            $table->dateTime('end_time')->nullable();
            $table->string('location')->nullable();
            $table->string('city')->nullable();
            $table->string('street')->nullable();
            $table->string('postal_code')->nullable();
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
