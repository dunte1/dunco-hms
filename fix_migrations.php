<?php
$dir = '/home/duncoweb/hmse.duncowebsolutions.co.ke';
require $dir . '/vendor/autoload.php';
$app = require_once $dir . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Mark all old pending migrations as ran (tables already exist from batch 1)
$migrationsToMark = [
    '2025_10_28_211721_create_marketing_campaigns_table',
    '2025_10_28_211739_create_social_accounts_table',
    '2025_10_28_211746_create_scheduled_posts_table',
    '2025_10_28_211757_create_comment_replies_table',
    '2025_10_28_211816_create_graphic_assets_table',
    '2025_10_28_211823_create_marketing_analytics_table',
    '2025_10_28_211830_create_seo_records_table',
    '2025_11_01_013430_create_job_categories_table',
    '2025_11_01_013437_create_job_postings_table',
    '2025_11_01_013444_create_job_applications_table',
    '2025_11_01_013449_create_training_programs_table',
    '2025_11_01_013454_create_training_enrollments_table',
    '2025_11_01_013459_create_hr_announcements_table',
    '2025_11_01_013504_create_shifts_table',
    '2025_11_01_013515_create_employee_shifts_table',
    '2025_11_01_013520_create_public_holidays_table',
    '2025_11_01_013623_update_leave_requests_add_leave_type_id',
    '2025_11_01_113502_create_report_templates_table',
    '2025_11_01_115218_create_e_prescription_templates_table',
    '2025_11_01_115328_add_digital_signature_to_prescriptions_table',
    '2025_11_01_181023_add_status_field_to_users_table',
];

foreach ($migrationsToMark as $migration) {
    $exists = DB::table('migrations')->where('migration', $migration)->exists();
    if (!$exists) {
        DB::table('migrations')->insert([
            'migration' => $migration,
            'batch' => 10,
        ]);
        echo "Marked: $migration\n";
    } else {
        echo "Already exists: $migration\n";
    }
}

echo "\nDone! Now run: php artisan migrate --force\n";
