<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class StrongPassword implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Minimum 8 characters
        if (strlen($value) < 8) {
            $fail('The :attribute must be at least 8 characters.');
            return;
        }

        // Maximum 64 characters (prevent long password attacks)
        if (strlen($value) > 64) {
            $fail('The :attribute must not exceed 64 characters.');
            return;
        }

        // Must contain at least one uppercase letter
        if (!preg_match('/[A-Z]/', $value)) {
            $fail('The :attribute must contain at least one uppercase letter.');
            return;
        }

        // Must contain at least one lowercase letter
        if (!preg_match('/[a-z]/', $value)) {
            $fail('The :attribute must contain at least one lowercase letter.');
            return;
        }

        // Must contain at least one number
        if (!preg_match('/[0-9]/', $value)) {
            $fail('The :attribute must contain at least one number.');
            return;
        }

        // Must contain at least one special character
        if (!preg_match('/[^A-Za-z0-9]/', $value)) {
            $fail('The :attribute must contain at least one special character.');
            return;
        }

        // Check against common passwords
        $commonPasswords = [
            'password', 'password123', '12345678', 'qwerty', 'abc123', 
            'monkey', '1234567', 'letmein', 'trustno1', 'dragon',
            'baseball', 'iloveyou', 'master', 'sunshine', 'ashley',
            'bailey', 'passw0rd', 'shadow', '123123', '654321'
        ];

        if (in_array(strtolower($value), $commonPasswords)) {
            $fail('The :attribute is too common. Please choose a stronger password.');
            return;
        }

        // Check for sequential characters
        if (preg_match('/(?:abc|bcd|cde|def|efg|fgh|ghi|hij|ijk|jkl|klm|lmn|mno|nop|opq|pqr|qrs|rst|stu|tuv|uvw|vwx|wxy|xyz|012|123|234|345|456|567|678|789)/i', $value)) {
            $fail('The :attribute contains sequential characters. Please choose a stronger password.');
            return;
        }

        // Check for repeated characters (e.g., "aaa", "111")
        if (preg_match('/(.)\1{2,}/', $value)) {
            $fail('The :attribute contains too many repeated characters. Please choose a stronger password.');
            return;
        }
    }
}
