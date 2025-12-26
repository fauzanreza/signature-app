<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentsTable extends Migration {
    public function up() {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('file_name');
            $table->string('file_path');
            $table->string('role');
            $table->integer('page_number')->default(1);
            $table->float('qr_x')->default(0);
            $table->float('qr_y')->default(0);
            $table->float('qr_width')->default(100);
            $table->float('qr_height')->default(100);
            $table->text('qr_data')->nullable(); // URL or data for QR
            $table->string('status')->default('pending'); // pending, signed, archived
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
    public function down() {
        Schema::dropIfExists('documents');
    }
}