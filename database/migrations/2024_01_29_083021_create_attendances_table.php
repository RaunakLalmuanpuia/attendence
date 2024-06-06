<?php

use App\Models\Attendance;
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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\User::class);
            $table->foreignIdFor(\App\Models\Office::class);
            $table->foreignIdFor(\App\Models\Device::class)->nullable();
            $table->dateTime('signin_at');
            $table->dateTime('signout_at')->nullable();
            $table->string('lat');
            $table->string('lng');
            $table->string('signout_lat')->nullable();
            $table->string('signout_lng')->nullable();
            $table->string('type')->default(Attendance::PRESENT);
            $table->string('in_remark')->nullable();
            $table->string('out_remark')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
