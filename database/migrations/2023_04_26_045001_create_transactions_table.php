<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_type_id')->constrained();
            $table->foreignId('financial_year_id')->constrained();
            $table->foreignId('office_id')->constrained();
            $table->foreignId('fund_id')->constrained();
            $table->string('file_number')->nullable();
            $table->bigInteger('amount_in_cents');
            $table->foreignId('approver_id')->constrained();
            $table->datetime('approved_at');
            $table->boolean('incurred')->nullable();
            $table->string('item')->nullable();
            $table->string('vendor_name')->nullable();
            $table->string('gem_contract_number')->nullable();
            $table->string('gem_non_availability_certificate_number')->nullable();
            $table->string('not_gem_remarks')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->boolean('is_deficit')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
