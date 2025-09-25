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
        /*Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });*/
		
		Schema::create('users', function (Blueprint $newtable) {
 		  $newtable->id();		 
		  $newtable->string('email')->unique();
		  $newtable->string('username',100)->unique();
          $newtable->string('password',50);
		  $newtable->string('remember_token',100);         	  
		  $newtable->timestamps();
	    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
