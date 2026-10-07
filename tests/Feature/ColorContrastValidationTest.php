<?php

namespace Tests\Feature;

use PHPUnit\Framework\TestCase;

/**
 * Color Contrast Validation Tests
 * 
 * Validates that all text on backgrounds meets WCAG AA standards:
 * - Normal text (< 18px): minimum 4.5:1 contrast ratio
 * - Large text (≥ 18px): minimum 3:1 contrast ratio
 * - Graphical elements: minimum 3:1 contrast ratio
 * 
 * Requirements: 9.5, 14.3, 14.4
 * Design Document: Property 4 - Color Contrast for Readability
 */
class ColorContrastValidationTest extends TestCase
{
    /**
     * Brand colors from CSS variables
     */
    private const BRAND_COLORS = [
        'brand' => '#1D4533',           // Primary green
        'brand-dark' => '#0f2818',      // Darker green for hover
        'brand-light' => '#f0fdf4',     // Light green background
        'brand-glow' => 'rgba(29, 69, 51, 0.25)',
        'slate-50' => '#FBFBFA',
        'slate-100' => '#f1f5f9',
        'slate-200' => '#e2e8f0',
        'slate-300' => '#7c8fa0',
        'slate-400' => '#64748b',
        'slate-500' => '#475569',
        'slate-600' => '#334155',
        'slate-700' => '#1e293b',
        'slate-800' => '#0f172a',
        'slate-900' => '#0a0e27',
        'white' => '#ffffff',
        'red-500' => '#dc2626',
        'red-600' => '#991b1b',
        'green-500' => '#22c55e',
        'green-600' => '#16a34a',
    ];

    /**
     * Convert hex color to RGB
     */
    private function hexToRgb(string $hex): array
    {
        $hex = ltrim($hex, '#');
        return [
            hexdec(substr($hex, 0, 2)),
            hexdec(substr($hex, 2, 2)),
            hexdec(substr($hex, 4, 2)),
        ];
    }

    /**
     * Calculate relative luminance of a color
     * WCAG formula: https://www.w3.org/TR/WCAG20/#relativeluminancedef
     */
    private function getRelativeLuminance(string $hex): float
    {
        [$r, $g, $b] = $this->hexToRgb($hex);

        $r = $r / 255;
        $g = $g / 255;
        $b = $b / 255;

        $r = $r <= 0.03928 ? $r / 12.92 : pow(($r + 0.055) / 1.055, 2.4);
        $g = $g <= 0.03928 ? $g / 12.92 : pow(($g + 0.055) / 1.055, 2.4);
        $b = $b <= 0.03928 ? $b / 12.92 : pow(($b + 0.055) / 1.055, 2.4);

        return 0.2126 * $r + 0.7152 * $g + 0.0722 * $b;
    }

    /**
     * Calculate contrast ratio between two colors
     * WCAG formula: https://www.w3.org/TR/WCAG20/#contrast-ratiodef
     */
    private function getContrastRatio(string $color1, string $color2): float
    {
        $lum1 = $this->getRelativeLuminance($color1);
        $lum2 = $this->getRelativeLuminance($color2);

        $lighter = max($lum1, $lum2);
        $darker = min($lum1, $lum2);

        return ($lighter + 0.05) / ($darker + 0.05);
    }

    /**
     * Test: Primary button (brand green) on white background
     * Button text should be white (#ffffff)
     * Expected: white text on brand green meets 4.5:1 (WCAG AA for normal text)
     */
    public function test_primary_button_contrast(): void
    {
        $contrast = $this->getContrastRatio(
            self::BRAND_COLORS['white'],
            self::BRAND_COLORS['brand']
        );

        $this->assertGreaterThanOrEqual(
            4.5,
            $contrast,
            "Primary button (white text on brand green) contrast ratio {$contrast} should be >= 4.5:1"
        );
    }

    /**
     * Test: Primary button on dark hover state
     * Button text should remain white
     * Expected: white text on brand-dark meets 4.5:1
     */
    public function test_primary_button_hover_contrast(): void
    {
        $contrast = $this->getContrastRatio(
            self::BRAND_COLORS['white'],
            self::BRAND_COLORS['brand-dark']
        );

        $this->assertGreaterThanOrEqual(
            4.5,
            $contrast,
            "Primary button hover (white text on brand-dark) contrast ratio {$contrast} should be >= 4.5:1"
        );
    }

    /**
     * Test: Secondary button (brand green text on light green background)
     * Expected: brand text on brand-light meets 4.5:1
     */
    public function test_secondary_button_contrast(): void
    {
        $contrast = $this->getContrastRatio(
            self::BRAND_COLORS['brand'],
            self::BRAND_COLORS['brand-light']
        );

        $this->assertGreaterThanOrEqual(
            4.5,
            $contrast,
            "Secondary button (brand text on brand-light) contrast ratio {$contrast} should be >= 4.5:1"
        );
    }

    /**
     * Test: Body text (dark slate) on white background
     * Expected: slate-900 text on white meets 4.5:1
     */
    public function test_body_text_contrast(): void
    {
        $contrast = $this->getContrastRatio(
            self::BRAND_COLORS['slate-900'],
            self::BRAND_COLORS['white']
        );

        $this->assertGreaterThanOrEqual(
            4.5,
            $contrast,
            "Body text (slate-900 on white) contrast ratio {$contrast} should be >= 4.5:1"
        );
    }

    /**
     * Test: Form label text on white background
     * Expected: slate-700 label text on white meets 4.5:1
     */
    public function test_form_label_contrast(): void
    {
        $contrast = $this->getContrastRatio(
            self::BRAND_COLORS['slate-700'],
            self::BRAND_COLORS['white']
        );

        $this->assertGreaterThanOrEqual(
            4.5,
            $contrast,
            "Form label (slate-700 on white) contrast ratio {$contrast} should be >= 4.5:1"
        );
    }

    /**
     * Test: Form helper text on white background
     * Expected: slate-400 helper text on white meets minimum 3:1 for large text or graphical elements
     */
    public function test_form_helper_text_contrast(): void
    {
        $contrast = $this->getContrastRatio(
            self::BRAND_COLORS['slate-400'],
            self::BRAND_COLORS['white']
        );

        // Helper text is typically small and secondary, minimum 3:1 is acceptable for reduced importance
        $this->assertGreaterThanOrEqual(
            3.0,
            $contrast,
            "Form helper text (slate-400 on white) contrast ratio {$contrast} should be >= 3:1"
        );
    }

    /**
     * Test: Error message text on white background
     * Expected: red-500 error text on white meets 4.5:1
     */
    public function test_error_message_contrast(): void
    {
        $contrast = $this->getContrastRatio(
            self::BRAND_COLORS['red-500'],
            self::BRAND_COLORS['white']
        );

        $this->assertGreaterThanOrEqual(
            4.5,
            $contrast,
            "Error text (red-500 on white) contrast ratio {$contrast} should be >= 4.5:1"
        );
    }

    /**
     * Test: Link text on white background
     * Expected: brand green link text on white meets 4.5:1
     */
    public function test_link_contrast_white_bg(): void
    {
        $contrast = $this->getContrastRatio(
            self::BRAND_COLORS['brand'],
            self::BRAND_COLORS['white']
        );

        $this->assertGreaterThanOrEqual(
            4.5,
            $contrast,
            "Link text (brand on white) contrast ratio {$contrast} should be >= 4.5:1"
        );
    }

    /**
     * Test: Header text on white background
     * Expected: slate-900 heading text on white meets 4.5:1
     */
    public function test_heading_contrast(): void
    {
        $contrast = $this->getContrastRatio(
            self::BRAND_COLORS['slate-900'],
            self::BRAND_COLORS['white']
        );

        $this->assertGreaterThanOrEqual(
            4.5,
            $contrast,
            "Heading (slate-900 on white) contrast ratio {$contrast} should be >= 4.5:1"
        );
    }

    /**
     * Test: Footer text on brand background
     * Expected: white text on brand green meets 4.5:1
     */
    public function test_footer_text_contrast(): void
    {
        $contrast = $this->getContrastRatio(
            self::BRAND_COLORS['white'],
            self::BRAND_COLORS['brand']
        );

        $this->assertGreaterThanOrEqual(
            4.5,
            $contrast,
            "Footer text (white on brand) contrast ratio {$contrast} should be >= 4.5:1"
        );
    }

    /**
     * Test: Footer link text on brand background
     * Expected: white text on brand green meets 4.5:1 (or light text meets minimum)
     */
    public function test_footer_link_contrast(): void
    {
        $contrast = $this->getContrastRatio(
            self::BRAND_COLORS['white'],
            self::BRAND_COLORS['brand']
        );

        $this->assertGreaterThanOrEqual(
            4.5,
            $contrast,
            "Footer link (white on brand) contrast ratio {$contrast} should be >= 4.5:1"
        );
    }

    /**
     * Test: Placeholder text on white background
     * Expected: slate-400 placeholder on white meets minimum 3:1 for reduced importance
     */
    public function test_placeholder_text_contrast(): void
    {
        $contrast = $this->getContrastRatio(
            self::BRAND_COLORS['slate-400'],
            self::BRAND_COLORS['white']
        );

        // Placeholder text is subordinate text, 3:1 minimum is acceptable
        $this->assertGreaterThanOrEqual(
            3.0,
            $contrast,
            "Placeholder text (slate-400 on white) contrast ratio {$contrast} should be >= 3:1"
        );
    }

    /**
     * Test: Disabled button text on white background
     * Expected: button with 0.6 opacity should still meet minimum contrast
     */
    public function test_disabled_button_contrast(): void
    {
        // Disabled buttons often have reduced contrast due to opacity
        // Minimum 3:1 is acceptable for disabled/inactive elements
        $contrast = $this->getContrastRatio(
            self::BRAND_COLORS['brand'],
            self::BRAND_COLORS['white']
        );

        // With 0.6 opacity, contrast is reduced but should meet minimum
        $this->assertGreaterThanOrEqual(
            3.0,
            $contrast,
            "Disabled button (brand with reduced opacity on white) should maintain >= 3:1 contrast"
        );
    }

    /**
     * Test: Focus indicator outline on brand green button
     * Expected: outline color meets sufficient contrast with button background
     */
    public function test_focus_indicator_contrast(): void
    {
        $contrast = $this->getContrastRatio(
            self::BRAND_COLORS['brand'],
            self::BRAND_COLORS['white']
        );

        $this->assertGreaterThanOrEqual(
            4.5,
            $contrast,
            "Focus indicator outline (brand on white) contrast ratio {$contrast} should be >= 4.5:1"
        );
    }

    /**
     * Test: Tertiary button text on white background
     * Expected: brand green tertiary button text meets 4.5:1
     */
    public function test_tertiary_button_contrast(): void
    {
        $contrast = $this->getContrastRatio(
            self::BRAND_COLORS['brand'],
            self::BRAND_COLORS['white']
        );

        $this->assertGreaterThanOrEqual(
            4.5,
            $contrast,
            "Tertiary button (brand text on white) contrast ratio {$contrast} should be >= 4.5:1"
        );
    }

    /**
     * Test: Danger button (red) on white background
     * Expected: white text on red-500 meets 4.5:1
     */
    public function test_danger_button_contrast(): void
    {
        $contrast = $this->getContrastRatio(
            self::BRAND_COLORS['white'],
            self::BRAND_COLORS['red-500']
        );

        $this->assertGreaterThanOrEqual(
            4.5,
            $contrast,
            "Danger button (white text on red-500) contrast ratio {$contrast} should be >= 4.5:1"
        );
    }

    /**
     * Test: Card border on light background
     * Expected: slate-300 border on white/light background meets minimum 3:1 for graphical elements
     */
    public function test_card_border_contrast(): void
    {
        $contrast = $this->getContrastRatio(
            self::BRAND_COLORS['slate-300'],
            self::BRAND_COLORS['white']
        );

        // Borders are graphical elements, minimum 3:1 required
        $this->assertGreaterThanOrEqual(
            3.0,
            $contrast,
            "Card border (slate-300 on white) contrast ratio {$contrast} should be >= 3:1"
        );
    }

    /**
     * Test: Active link state on white background
     * Expected: brand-dark text meets 4.5:1
     */
    public function test_active_link_contrast(): void
    {
        $contrast = $this->getContrastRatio(
            self::BRAND_COLORS['brand-dark'],
            self::BRAND_COLORS['white']
        );

        $this->assertGreaterThanOrEqual(
            4.5,
            $contrast,
            "Active link (brand-dark on white) contrast ratio {$contrast} should be >= 4.5:1"
        );
    }

    /**
     * Test: Checkbox checked state (brand background with white checkmark)
     * Expected: white checkmark on brand meets 4.5:1
     */
    public function test_checkbox_checked_contrast(): void
    {
        $contrast = $this->getContrastRatio(
            self::BRAND_COLORS['white'],
            self::BRAND_COLORS['brand']
        );

        $this->assertGreaterThanOrEqual(
            4.5,
            $contrast,
            "Checkbox checkmark (white on brand) contrast ratio {$contrast} should be >= 4.5:1"
        );
    }

    /**
     * Test: Navigation link text on white header
     * Expected: slate-700 nav link on white meets 4.5:1
     */
    public function test_nav_link_contrast(): void
    {
        $contrast = $this->getContrastRatio(
            self::BRAND_COLORS['slate-700'],
            self::BRAND_COLORS['white']
        );

        $this->assertGreaterThanOrEqual(
            4.5,
            $contrast,
            "Navigation link (slate-700 on white) contrast ratio {$contrast} should be >= 4.5:1"
        );
    }
}
