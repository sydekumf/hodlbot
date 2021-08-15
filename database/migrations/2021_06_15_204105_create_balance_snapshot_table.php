<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateBalanceSnapshotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('balance_snapshot', function (Blueprint $table) {
            $table->id()->unique();
            $table->string('coin');
            $table->double('amount', 16, 8);
            $table->double('usd_value', 16, 8);
            $table->double('daily_gain', 10, 8)->nullable();
            $table->double('total_daily_gain', 10, 8)->nullable();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('balance_snapshot');
    }
}
