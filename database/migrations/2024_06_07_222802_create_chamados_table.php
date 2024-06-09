<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChamadosTable extends Migration
{
    public function up()
    {
        Schema::create('chamados', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id')->comment('Usuario que abriu o chamado');
            $table->string('title');
            $table->text('description');
            $table->tinyText('status')->default('A')->comment('A - Aberto,  EA - Em Atendimento, F- Finalizado');
            $table->timestamps();
        });

        Schema::create('chamado_arquivos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('chamado_id')->comment('Usuario que abriu o chamado');
            $table->string('filename');
            $table->string('file')->comment('Caminho onde esta localizado o arquivo');
            $table->timestamps();
        });

        Schema::create('chamado_respostas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('chamado_id');
            $table->unsignedBigInteger('user_id')->comment('cliente ou colaborador autor da resposta');
            $table->text('content');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('chamados');
    }
}
