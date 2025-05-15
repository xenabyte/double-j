<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('title')->nullable();
            $table->string('othernames')->nullable();
            $table->string('last_name')->nullable();
            $table->date('dob')->nullable();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('gender')->nullable();
            $table->string('image')->nullable();
            $table->string('cv')->nullable();
            $table->string('cover_letter')->nullable();
            $table->string('upload_folder')->nullable();
            $table->unsignedBigInteger('job_posting_id')->nullable();
            $table->unsignedBigInteger('client_id')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('employees');
    }
}
