<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveColumnFromGiftcardsTableForUpdate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('giftcards', function (Blueprint $table) {
            $table->dropColumn('image');
            $table->dropColumn('category');
            $table->dropColumn('merchant_cudos_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('giftcards', function (Blueprint $table) {
            $table->text('message')->after('company_logo')->nullable();
            $table->text('details_json')->after('message')->nullable();
            $table->string('company_logo')->after('email')->nullable();
        });
    }
}
