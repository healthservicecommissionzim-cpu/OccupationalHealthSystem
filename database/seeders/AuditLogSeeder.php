<?php

namespace Database\Seeders;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AuditLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get or create test users
        $superAdmin = User::where('email', 'admin@test.com')->first() 
            ?? User::factory()->create(['email' => 'admin@test.com', 'name' => 'Super Admin']);
        
        $user1 = User::where('email', 'user1@test.com')->first() 
            ?? User::factory()->create(['email' => 'user1@test.com', 'name' => 'Test User 1']);
        
        $user2 = User::where('email', 'user2@test.com')->first() 
            ?? User::factory()->create(['email' => 'user2@test.com', 'name' => 'Test User 2']);

        // 5 Role Assignment Logs
        for ($i = 1; $i <= 5; $i++) {
            AuditLog::create([
                'event_id' => 'evt_' . Str::ulid(),
                'correlation_id' => Str::uuid(),
                'request_id' => 'req_' . Str::ulid(),
                'user_id' => $superAdmin->id,
                'action' => 'ROLE_ASSIGNMENT',
                'component' => 'UserApprovalController@assignRole',
                'affected_entity' => "Role assigned to User $i",
                'http_method' => 'POST',
                'http_code' => 200,
                'status' => 'SUCCESS',
                'resource_type' => 'Role',
                'resource_path' => '/user-approval/role-assignment',
                'auditable_type' => 'App\\Models\\Role',
                'auditable_id' => $i,
                'ip_address' => '127.0.0.1',
                'ip_version' => 'IPv4',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
                'classification' => 'AUDIT_CRITICAL',
                'retention_policy' => 'STANDARD_1_YEAR',
                'timestamp_utc' => now()->subMinutes(60 - $i * 10),
            ]);
        }

        // 5 User Rejection Logs
        for ($i = 1; $i <= 5; $i++) {
            AuditLog::create([
                'event_id' => 'evt_' . Str::ulid(),
                'correlation_id' => Str::uuid(),
                'request_id' => 'req_' . Str::ulid(),
                'user_id' => $superAdmin->id,
                'action' => 'USER_REJECTED',
                'component' => 'UserApprovalController@reject',
                'affected_entity' => "User Rejection $i - Invalid credentials",
                'http_method' => 'POST',
                'http_code' => 200,
                'status' => 'SUCCESS',
                'resource_type' => 'User',
                'resource_path' => '/user-approval/reject',
                'auditable_type' => 'App\\Models\\User',
                'auditable_id' => $user1->id + $i,
                'error_message' => 'Application rejected due to incomplete documentation',
                'ip_address' => '127.0.0.1',
                'ip_version' => 'IPv4',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
                'classification' => 'AUDIT_CRITICAL',
                'retention_policy' => 'STANDARD_1_YEAR',
                'timestamp_utc' => now()->subMinutes(120 - $i * 15),
            ]);
        }

        // 5 User Activity Logs (USER_APPROVED)
        for ($i = 1; $i <= 5; $i++) {
            AuditLog::create([
                'event_id' => 'evt_' . Str::ulid(),
                'correlation_id' => Str::uuid(),
                'request_id' => 'req_' . Str::ulid(),
                'user_id' => $superAdmin->id,
                'action' => 'USER_APPROVED',
                'component' => 'UserApprovalController@approve',
                'affected_entity' => "User Approval $i - Assigned district_hr role",
                'http_method' => 'POST',
                'http_code' => 200,
                'status' => 'SUCCESS',
                'resource_type' => 'User',
                'resource_path' => '/user-approval/approve',
                'auditable_type' => 'App\\Models\\User',
                'auditable_id' => $user2->id + $i,
                'ip_address' => '127.0.0.1',
                'ip_version' => 'IPv4',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
                'classification' => 'AUDIT_CRITICAL',
                'retention_policy' => 'STANDARD_1_YEAR',
                'timestamp_utc' => now()->subMinutes(180 - $i * 20),
            ]);
        }

        // 5 System Access Logs (LOGIN_SUCCESS)
        for ($i = 1; $i <= 5; $i++) {
            AuditLog::create([
                'event_id' => 'evt_' . Str::ulid(),
                'correlation_id' => Str::uuid(),
                'request_id' => 'req_' . Str::ulid(),
                'user_id' => $user1->id + $i,
                'action' => 'LOGIN_SUCCESS',
                'component' => 'LoginController@store',
                'affected_entity' => "User Login Successful $i",
                'http_method' => 'POST',
                'http_code' => 200,
                'status' => 'SUCCESS',
                'resource_type' => 'User',
                'resource_path' => '/login',
                'auditable_type' => 'App\\Models\\User',
                'auditable_id' => $user1->id + $i,
                'ip_address' => '127.0.0.1',
                'ip_version' => 'IPv4',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
                'classification' => 'IMPORTANT',
                'retention_policy' => 'STANDARD_1_YEAR',
                'timestamp_utc' => now()->subMinutes(240 - $i * 25),
            ]);
        }

        // 5 Failed Login Logs (LOGIN_FAILED)
        for ($i = 1; $i <= 5; $i++) {
            AuditLog::create([
                'event_id' => 'evt_' . Str::ulid(),
                'correlation_id' => Str::uuid(),
                'request_id' => 'req_' . Str::ulid(),
                'user_id' => null,
                'action' => 'LOGIN_FAILED',
                'component' => 'LoginController@store',
                'affected_entity' => "testuser$i@example.com",
                'http_method' => 'POST',
                'http_code' => 401,
                'status' => 'FAILED',
                'error_message' => 'Invalid credentials - password mismatch',
                'resource_type' => 'User',
                'resource_path' => '/login',
                'auditable_type' => 'App\\Models\\User',
                'auditable_id' => null,
                'ip_address' => '192.168.1.' . (100 + $i),
                'ip_version' => 'IPv4',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
                'classification' => 'SECURITY_SENSITIVE',
                'retention_policy' => 'EXTENDED_3_YEARS',
                'timestamp_utc' => now()->subMinutes(300 - $i * 30),
                'data_before' => [
                    'attempted_email' => "testuser$i@example.com",
                    'ip_address' => '192.168.1.' . (100 + $i),
                    'ip_suspicious' => false,
                    'timestamp' => now()->subMinutes(300 - $i * 30)->toIso8601String(),
                ],
                'data_after' => [
                    'login_attempt_blocked' => true,
                    'reason' => 'Invalid credentials',
                    'password_hash' => hash('sha256', 'wrongpassword' . $i),
                    'suspicious_activity_detected' => false,
                ],
            ]);
        }

        // 5 Suspicious Failed Login Logs (Questionable times, unusual activities)
        $suspiciousIps = ['203.0.113.45', '198.51.100.23', '192.0.2.88', '203.0.113.99', '198.51.100.45'];
        $suspiciousEmails = ['admin@example.com', 'superadmin@test.com', 'root@system.local', 'system@admin.com', 'administrator@local'];
        
        for ($i = 1; $i <= 5; $i++) {
            AuditLog::create([
                'event_id' => 'evt_' . Str::ulid(),
                'correlation_id' => Str::uuid(),
                'request_id' => 'req_' . Str::ulid(),
                'user_id' => null,
                'action' => 'LOGIN_FAILED',
                'component' => 'LoginController@store',
                'affected_entity' => $suspiciousEmails[$i - 1],
                'http_method' => 'POST',
                'http_code' => 401,
                'status' => 'FAILED',
                'error_message' => 'Invalid credentials - multiple failed attempts detected',
                'resource_type' => 'User',
                'resource_path' => '/login',
                'auditable_type' => 'App\\Models\\User',
                'auditable_id' => null,
                'ip_address' => $suspiciousIps[$i - 1],
                'ip_version' => 'IPv4',
                'hostname' => 'unknown-host-' . $i,
                'geo_location' => ['CN', 'RU', 'KP', 'US-Proxy', 'VPN-Service'][$i - 1],
                'geo_country' => ['China', 'Russia', 'North Korea', 'USA (Suspicious)', 'VPN Network'][$i - 1],
                'geo_city' => ['Beijing', 'Moscow', 'Pyongyang', 'DataCenter', 'Anonymous'][$i - 1],
                'user_agent' => 'curl/7.64.1',
                'classification' => 'SECURITY_SENSITIVE',
                'retention_policy' => 'EXTENDED_3_YEARS',
                'timestamp_utc' => now()->subHours(3)->subMinutes($i * 15), // Off-hours login attempts
                'data_before' => [
                    'attempted_email' => $suspiciousEmails[$i - 1],
                    'ip_address' => $suspiciousIps[$i - 1],
                    'ip_suspicious' => true,
                    'questionable_time' => true,
                    'timestamp' => now()->subHours(3)->subMinutes($i * 15)->toIso8601String(),
                    'attempt_sequence' => "Attempt $i of multiple",
                ],
                'data_after' => [
                    'login_attempt_blocked' => true,
                    'reason' => 'Invalid credentials - suspicious activity detected',
                    'password_hash' => hash('sha256', 'bruteforce' . $i),
                    'suspicious_activity_detected' => true,
                    'threat_level' => 'HIGH',
                    'brute_force_detected' => true,
                    'privilege_escalation_attempt' => $i % 2 === 0,
                    'admin_account_targeted' => true,
                ],
            ]);
        }

        $this->command->info('✓ Created 30 audit log test entries');
        $this->command->info('  - 5 Role Assignment logs');
        $this->command->info('  - 5 User Rejection logs');
        $this->command->info('  - 5 User Activity (Approval) logs');
        $this->command->info('  - 5 System Access (Login Success) logs');
        $this->command->info('  - 5 Failed Login logs (normal attempts)');
        $this->command->info('  - 5 Suspicious Failed Login logs (with IP/activity detection)');
        $this->command->info('');
        $this->command->info('Suspicious Login Features:');
        $this->command->info('  ✓ Username/email captured');
        $this->command->info('  ✓ Password hash (SHA-256) captured');
        $this->command->info('  ✓ Suspicious IP detection');
        $this->command->info('  ✓ Off-hours login attempts');
        $this->command->info('  ✓ Brute force detection');
        $this->command->info('  ✓ Admin account targeting detection');
        $this->command->info('  ✓ Geographic location tracking');
        $this->command->info('  ✓ User agent logging (curl, browser, etc.)');
    }
}
