<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(config('forms.tables.test'), function (Blueprint $table) {
            $table->id();
            $table->string('name',20);
            $table->unsignedInteger('patient_id')->nullable();
            $table->unsignedInteger('room_id')->nullable();
            $table->unsignedInteger('location_id')->nullable();
            $table->unsignedInteger('appointment_id')->nullable();
            $table->unsignedTinyInteger('step')->nullable();
            //$table->timestamp('appointment_at')->nullable();
            //$table->timestamp('onderzoek_at')->nullable();
            //$table->timestamp('analyse_at')->nullable();
            //$table->timestamp('upload_at')->nullable();
            //$table->timestamp('beoordeling_at')->nullable();
            //$table->unsignedInteger('beoordeling_by')->nullable();
            //$table->timestamp('beoordeling2_at')->nullable();
            //$table->timestamp('behandeladvies_at')->nullable();
            //$table->timestamp('controle_at')->nullable();
            //$table->timestamp('geprint_at')->nullable();
            //$table->timestamp('verstuurd_at')->nullable();

            $table->timestamps();
        });
        Schema::create(config('forms.tables.testdetails'), function (Blueprint $table) {
            $table->unsignedBigInteger('test_id');

            $table->json('steps')->nullable();
            $table->json('log')->nullable();
            $table->json('questions')->nullable();
            $table->json('answers')->nullable(); //
            $table->json('auth')->nullable(); //speciale rechten voor betreffende step
            $table->string('error')->nullable(); //fout tekst
            $table->text('exception')->nullable(); //foute opgetreden
            $table->text('request')->nullable(); //aanvraag van huisarts (of andere aanvrager)
            $table->text('result_pdf')->nullable(); //pdf result (print)
            $table->text('result_e')->nullable(); //electronic result
            $table->foreign('test_id')->onDelete('cascade')->references('id')->on(config('forms.tables.test'));
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
        Schema::dropIfExists(config('forms.table_tests_detail_name'));
        Schema::dropIfExists(config('forms.table_tests_name'));
    }
}
