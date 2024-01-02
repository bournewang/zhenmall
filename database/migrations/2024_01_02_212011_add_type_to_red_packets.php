<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTypeToRedPackets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('red_packets', function (Blueprint $table) {
            //
            $table->enum('type', array_keys(\App\Models\RedPacket::typeOptions()))->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('red_packets', function (Blueprint $table) {
            //
            $table->dropColumn('type');
        });
    }
}
