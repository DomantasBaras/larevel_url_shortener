<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPrefixToUrlsTable extends Migration
{
    public function up()
    {
        Schema::table('urls', function (Blueprint $table) {
            $table->string('prefix')->nullable()->after('short_hash');
        });
    }

    public function down()
    {
        Schema::table('urls', function (Blueprint $table) {
            $table->dropColumn('prefix');
        });
    }
}
