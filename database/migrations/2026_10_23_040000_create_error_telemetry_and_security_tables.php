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
        // Add threat detection, device, OS, and geo metadata to error_visits
        Schema::table('error_visits', function (Blueprint $table) {
            $table->string('threat_level')->default('safe')->after('user_agent'); // safe, suspicious, critical
            $table->string('threat_category')->nullable()->after('threat_level');
            $table->string('device_type')->nullable()->after('threat_category'); // desktop, mobile, tablet, bot
            $table->string('browser')->nullable()->after('device_type');
            $table->string('os')->nullable()->after('browser');
            $table->string('country')->default('Local / Intranet')->after('os');
            $table->string('country_code')->default('US')->after('country');
        });

        // Incident User Reports table
        Schema::create('error_reports', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_id')->unique(); // e.g. #ERR-84920
            $table->unsignedBigInteger('error_visit_id')->nullable();
            $table->integer('status_code')->default(404);
            $table->string('url');
            $table->text('user_message');
            $table->string('user_email')->nullable();
            $table->json('diagnostic_data')->nullable(); // viewport, referrer, OS, device
            $table->string('status')->default('open'); // open, investigating, resolved
            $table->timestamps();
        });

        // Blocked IPs table for Security Radar
        Schema::create('blocked_ips', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address')->unique();
            $table->string('reason')->nullable();
            $table->string('threat_category')->nullable();
            $table->string('blocked_by')->default('Security Radar');
            $table->timestamp('blocked_at')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blocked_ips');
        Schema::dropIfExists('error_reports');
        Schema::table('error_visits', function (Blueprint $table) {
            $table->dropColumn([
                'threat_level',
                'threat_category',
                'device_type',
                'browser',
                'os',
                'country',
                'country_code'
            ]);
        });
    }
};
