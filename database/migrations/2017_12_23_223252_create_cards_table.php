<?php
use Illuminate\Support\Facades\Schema; use Illuminate\Database\Schema\Blueprint; use Illuminate\Database\Migrations\Migration; class CreateCardsTable extends Migration { public function up() { Schema::create('cards', function (Blueprint $speb36c4) { $speb36c4->increments('id'); $speb36c4->integer('user_id')->index(); $speb36c4->integer('product_id')->index(); $speb36c4->text('card'); $speb36c4->integer('type'); $speb36c4->integer('status')->default(\App\Card::STATUS_NORMAL); $speb36c4->integer('count_sold')->default(0); $speb36c4->integer('count_all')->default(1); $speb36c4->timestamps(); $speb36c4->softDeletes(); }); DB::unprepared('ALTER TABLE `cards` CHANGE COLUMN `created_at` `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP;'); } public function down() { Schema::dropIfExists('cards'); try { DB::unprepared('DROP PROCEDURE `add_cards`;'); } catch (\Exception $spece20f) { } } }