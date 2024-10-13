<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('patient_forms', function (Blueprint $table) {
            $table->id();
            $table->string('patientName');
            $table->integer('age');
            $table->text('address');
            $table->integer('phoneNumber');
            $table->text('chronicDiseases');
            $table->text('bloodType');
            $table->boolean('isSmoking');
            $table->integer('invoiceId');
            $table->string('state')->default('waiting');
            $table->text('diagnosis')->nullable();
            $table->double('invoice')->nullable();
            $table->text('rejectReason')->nullable();
            $table->text('prescription')->nullable();
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreignId('department_id')->references('id')->on('departments')->onDelete('cascade');
            $table->foreignId('doctor_id')->references('id')->on('doctors')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_forms');
    }
};
