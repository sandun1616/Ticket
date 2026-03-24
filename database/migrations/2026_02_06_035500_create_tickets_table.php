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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number')->unique();
            $table->string('username');
            $table->enum('department', [
                'Sales',
                'Production',
                'Production - Finishing',
                'Production - Engineering',
                'Business Compliance - Quality',
                'Business Compliance - Audit',
                'Business Compliance - ISO',
                'R & D',
                'Strategy',
                'Finance',
                'HR - Human Resource',
                'HR - Fire',
                'Security Premises',
                'ICT',
                'Supply Chain - FG Stores',
                'Supply Chain - RM Stores',
                'Supply Chain - Import & Export',
                'Supply Chain - Procurement'
            ]);
            $table->string('device_name');
            $table->text('problem_description');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
