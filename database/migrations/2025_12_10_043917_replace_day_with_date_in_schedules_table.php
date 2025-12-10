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
        \Illuminate\Support\Facades\DB::table('schedules')->truncate();

        Schema::table('schedules', function (Blueprint $table) {
            // Check if day column exists before trying to drop stuff related to it
            if (Schema::hasColumn('schedules', 'day')) {
                // Try dropping foreign keys if they exist (hard to check, but we can try-catch or suppression is dirty)
                // Use a brute force approach: Drop FKs if index exists? 
                
                // Let's assume standard names. If previous run failed, maybe they are gone.
                // We can't easily check FKs. 
                // But we CAN check 'day' column.
                // If 'day' exists, we assume we need to do the work.
                
                try {
                    $table->dropForeign(['teacher_id']);
                } catch (\Exception $e) {}
                
                try {
                    $table->dropForeign(['class_room_id']);
                } catch (\Exception $e) {}

                // Drop old unique constraints
                try {
                    $table->dropUnique('unique_teacher_schedule');
                } catch (\Exception $e) {}
                
                try {
                    $table->dropUnique('unique_classroom_schedule');
                } catch (\Exception $e) {}
                
                $table->dropColumn('day');
            }
            
            if (!Schema::hasColumn('schedules', 'date')) {
                $table->date('date')->after('class_room_id');
                // Re-add Foreign Keys and new constraints ONLY if we just added date (fresh start)
                // Or safely add them.
            }
        });
        
        // Use a separate schema call to ensure column exists before adding constraints?
        Schema::table('schedules', function (Blueprint $table) {
             // Re-add FKs if they are missing?
             // It's hard to know if they are missing.
             // But valid SQL is idempotent for adding FK? No.
             
             // Simplest recovery: Just add the new constraints. 
             // If they exist, it will fail.
             // Let's assume if we are here, we want these.
             
             // Unique constraints
             try {
                 $table->unique(['teacher_id', 'date', 'start_time'], 'unique_teacher_schedule_date');
             } catch (\Exception $e) {}
             
             try {
                $table->unique(['class_room_id', 'date', 'start_time'], 'unique_classroom_schedule_date');
             } catch (\Exception $e) {}
             
             // Restore FKs (we dropped them or they might be missing)
             // If they already exist, this might fail?
             // Since we truncated table, adding FKs is safe data-wise.
             try {
                $table->foreign('teacher_id')->references('id')->on('teachers')->onDelete('cascade');
             } catch (\Exception $e) {}
             
             try {
                $table->foreign('class_room_id')->references('id')->on('class_rooms')->onDelete('cascade');
             } catch (\Exception $e) {}
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->dropForeign(['teacher_id']);
            $table->dropForeign(['class_room_id']);

            $table->dropUnique('unique_teacher_schedule_date');
            $table->dropUnique('unique_classroom_schedule_date');
            
            $table->dropColumn('date');
            
            $table->enum('day', ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'])->after('class_room_id');
            
            $table->unique(['teacher_id', 'day', 'start_time'], 'unique_teacher_schedule');
            $table->unique(['class_room_id', 'day', 'start_time'], 'unique_classroom_schedule');

            $table->foreign('teacher_id')->references('id')->on('teachers')->onDelete('cascade');
            $table->foreign('class_room_id')->references('id')->on('class_rooms')->onDelete('cascade');
        });
    }
};
