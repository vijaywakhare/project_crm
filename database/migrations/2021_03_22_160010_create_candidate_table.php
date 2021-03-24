<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCandidateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('candidate', function (Blueprint $table) {
            $table->increments('id');
            $table->string('first_name', 40)->nullable();
            $table->string('last_name', 40)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('contact_number', 100)->nullable();
            $table->enum('gender', ['Male', 'Female'])->nullable();
            $table->string('specialization', 200)->nullable();
            $table->integer('work_ex_year')->nullable();
            $table->integer('candidate_dob')->nullable();
            $table->longText('address', 500)->nullable();
            $table->string('resume', 100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('candidate');
    }
}
