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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('custom_id')->unique();
            $table->datetime('date');
            $table->datetime('due_date');
            $table->foreignId('bill_from')->nullable()->constrained('billing_categories')->cascadeOnDelete();
            $table->foreignId('bill_to')->nullable()->constrained('clients')->cascadeOnDelete();
            $table->string('bill_from_name')->nullable();
            $table->string('bill_from_email')->nullable();
            $table->text('bill_from_description')->nullable();
            $table->string('bill_to_name')->nullable();
            $table->string('bill_to_email')->nullable();
            $table->text('bill_to_description')->nullable();
            $table->string('status');
            $table->float('tax_rate')->default(0);
            $table->float('tax_value')->default(0);
            $table->float('total_amount')->default(0);
            $table->boolean('recurring')->default(false);
            $table->boolean('late_fees')->default(false);
            $table->boolean('notes')->default(false);
            $table->text('invoice_notes')->nullable();
            $table->json('attachments')->nullable();
            $table->float('discount_value')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
