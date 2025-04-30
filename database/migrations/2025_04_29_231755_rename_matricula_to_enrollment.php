<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('matricula', 'enrollment');
            // Optionally, if you want to rename curso as well:
            // $table->renameColumn('curso', 'course');
        });
    }
    
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('enrollment', 'matricula');
            // If you renamed curso:
            // $table->renameColumn('course', 'curso');
        });
    }
};
