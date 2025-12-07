<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Step 1: Add id_user column to students table (if not exists)
        if (!Schema::hasColumn('students', 'id_user')) {
            Schema::table('students', function (Blueprint $table) {
                $table->foreignId('id_user')->nullable()->after('id')->constrained('users')->onDelete('cascade');
            });
        }

        // Step 2: Migrate existing students to users table
        $students = DB::table('students')->whereNull('id_user')->get();
        
        foreach ($students as $student) {
            // Check if user exists by username (NIS)
            $existingUser = DB::table('users')->where('username', $student->nis)->first();
            
            if (!$existingUser) {
                // Generate dummy email for student: nis@student.school.id
                // Assuming NIS is present, if not use random string
                $nis = $student->nis ?? Str::random(10);
                $email = $nis . '@student.school.id';

                // Handle potential email duplicate (though unlikely with unique NIS)
                $emailExists = DB::table('users')->where('email', $email)->exists();
                if ($emailExists) {
                    $email = $nis . '_' . Str::random(3) . '@student.school.id';
                }

                // Create user for each student
                $userId = DB::table('users')->insertGetId([
                    'name' => $student->name,
                    'username' => $student->nis, // NIS sebagai username
                    'email' => $email, // NEW: Add required email field
                    'password' => Hash::make($student->birth_date ?? '2000-01-01'), 
                    'role' => 'student',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                $userId = $existingUser->id;
            }

            // Update student with id_user
            DB::table('students')
                ->where('id', $student->id)
                ->update(['id_user' => $userId]);
        }

        // Step 3: Make id_user NOT NULL after data migration
        // Only if we are sure all students have users
        $countNull = DB::table('students')->whereNull('id_user')->count();
        if ($countNull == 0) {
            DB::statement('ALTER TABLE students MODIFY id_user BIGINT UNSIGNED NOT NULL');
        }

        // Step 4: Drop name and password columns from students (if exists)
        Schema::table('students', function (Blueprint $table) {
            if (Schema::hasColumn('students', 'name')) {
                $table->dropColumn('name');
            }
            if (Schema::hasColumn('students', 'password')) {
                $table->dropColumn('password');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add back name and password columns
        Schema::table('students', function (Blueprint $table) {
            if (!Schema::hasColumn('students', 'name')) {
                $table->string('name')->nullable()->after('nis');
            }
            if (!Schema::hasColumn('students', 'password')) {
                $table->string('password')->nullable()->after('name');
            }
        });

        // Copy data back from users to students
        $students = DB::table('students')->get();
        foreach ($students as $student) {
            if ($student->id_user) {
                $user = DB::table('users')->where('id', $student->id_user)->first();
                if ($user) {
                    DB::table('students')
                        ->where('id', $student->id)
                        ->update([
                            'name' => $user->name,
                            'password' => $user->password,
                        ]);
                }
            }
        }

        // Change columns to NOT NULL after data restore
        DB::statement('ALTER TABLE students MODIFY name VARCHAR(255) NOT NULL');
        DB::statement('ALTER TABLE students MODIFY password VARCHAR(255) NOT NULL');

        // Drop id_user column
        if (Schema::hasColumn('students', 'id_user')) {
            Schema::table('students', function (Blueprint $table) {
                $table->dropForeign(['id_user']);
                $table->dropColumn('id_user');
            });
        }

        // Optional: We don't delete users to prevent accidental data loss strictly in down
    }
};
