<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->string('document_number')->nullable()->after('file_name');
            $table->unsignedBigInteger('signer_id')->nullable()->after('user_id');
            
            $table->foreign('signer_id')->references('id')->on('signers')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropForeign(['signer_id']);
            $table->dropColumn(['document_number', 'signer_id']);
        });
    }
}
