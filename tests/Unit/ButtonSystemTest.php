<?php

namespace Tests\Unit;

use Tests\TestCase;

/**
 * Button System Unit Tests
 * 
 * Tests for responsive button component system validation.
 * Requirements: 1.5, 3.5, 9.1, 14.1, 14.5
 * 
 * These tests verify the CSS button system:
 * - Button minimum size (44px height/width)
 * - Font: Plus Jakarta Sans with weights 600-700
 * - All button states: normal, hover, focus, active, disabled
 * - Responsive sizing across breakpoints
 * - Accessibility features
 */
class ButtonSystemTest extends TestCase
{
    private string $buttonsCss;

    protected function setUp(): void
    {
        parent::setUp();
        $path = base_path('resources/css/buttons.css');
        $this->buttonsCss = file_get_contents($path);
    }

    /**
     * Test: Button CSS file exists and has content
     * Validates: Requirements 1.5, 3.5, 9.1, 14.1, 14.5
     */
    public function test_button_css_file_exists_and_has_content()
    {
        $this->assertNotEmpty($this->buttonsCss, 'Button CSS file should have content');
        $this->assertStringContainsString('.btn', $this->buttonsCss, 'Button CSS should define .btn class');
    }

    /**
     * Test: Primary button class is defined
     * Validates: Requirements 1.5, 14.1
     */
    public function test_primary_button_class_is_defined()
    {
        $this->assertStringContainsString('.btn-primary', $this->buttonsCss);
        $this->assertStringContainsString('var(--brand)', $this->buttonsCss);
    }

    /**
     * Test: Button has minimum 44px height for touch targets
     * Validates: Requirements 1.5, 9.1
     */
    public function test_button_has_minimum_44px_height()
    {
        $this->assertStringContainsString('min-height: 44px', $this->buttonsCss, 
            'Button should have minimum height of 44px for accessibility');
    }

    /**
     * Test: Button uses Plus Jakarta Sans font family
     * Validates: Requirements 14.1
     */
    public function test_button_uses_plus_jakarta_sans_font()
    {
        $this->assertStringContainsString('var(--font-family)', $this->buttonsCss);
        $this->assertStringNotContainsString("'Inter'", $this->buttonsCss, 
            'Button CSS should not reference Inter font');
    }

    /**
     * Test: Button uses correct font weight
     * Validates: Requirements 14.1
     */
    public function test_button_uses_correct_font_weight()
    {
        $this->assertTrue(
            strpos($this->buttonsCss, '--font-weight-semibold') !== false ||
            preg_match('/font-weight:\s*600/i', $this->buttonsCss),
            'Button should use font-weight 600 or semibold'
        );
    }

    /**
     * Test: Secondary button class is defined
     * Validates: Requirements 1.5
     */
    public function test_secondary_button_class_is_defined()
    {
        $this->assertStringContainsString('.btn-secondary', $this->buttonsCss);
    }

    /**
     * Test: Tertiary button class is defined
     * Validates: Requirements 1.5
     */
    public function test_tertiary_button_class_is_defined()
    {
        $this->assertStringContainsString('.btn-tertiary', $this->buttonsCss);
    }

    /**
     * Test: Danger button class is defined with red color
     * Validates: Requirements 1.5
     */
    public function test_danger_button_class_uses_red_color()
    {
        $this->assertStringContainsString('.btn-danger', $this->buttonsCss);
        $this->assertStringContainsString('--red-500', $this->buttonsCss);
    }

    /**
     * Test: Button hover state has scale effect
     * Validates: Requirements 14.5
     */
    public function test_button_hover_has_scale_effect()
    {
        $this->assertTrue(
            preg_match('/scale\s*\(\s*1\.05\s*\)/i', $this->buttonsCss),
            'Button hover state should include scale(1.05) transform'
        );
    }

    /**
     * Test: Button active state scales down
     * Validates: Requirements 14.5
     */
    public function test_button_active_scales_down()
    {
        $this->assertTrue(
            preg_match('/scale\s*\(\s*0\.98\s*\)/i', $this->buttonsCss),
            'Button active state should scale down to 0.98'
        );
    }

    /**
     * Test: Button focus state has outline or shadow
     * Validates: Requirements 9.1, 14.5
     */
    public function test_button_focus_has_outline()
    {
        $this->assertTrue(
            preg_match('/(:focus|:focus-visible)/i', $this->buttonsCss),
            'Button CSS should define :focus or :focus-visible state'
        );
    }

    /**
     * Test: Button disabled state has reduced opacity
     * Validates: Requirements 1.5, 9.1
     */
    public function test_button_disabled_has_reduced_opacity()
    {
        $this->assertTrue(
            preg_match('/(:disabled|disabled\])/i', $this->buttonsCss) &&
            preg_match('/opacity:\s*0\.6/i', $this->buttonsCss),
            'Disabled button should have opacity: 0.6'
        );
    }

    /**
     * Test: Button small size variant exists
     * Validates: Requirements 1.5
     */
    public function test_button_small_size_variant_exists()
    {
        $this->assertStringContainsString('.btn-sm', $this->buttonsCss);
        $this->assertTrue(
            preg_match('/min-height:\s*36px/i', $this->buttonsCss),
            'Small button should have min-height: 36px'
        );
    }

    /**
     * Test: Button large size variant exists
     * Validates: Requirements 1.5
     */
    public function test_button_large_size_variant_exists()
    {
        $this->assertStringContainsString('.btn-lg', $this->buttonsCss);
        $this->assertTrue(
            preg_match('/min-height:\s*48px/i', $this->buttonsCss),
            'Large button should have min-height: 48px'
        );
    }

    /**
     * Test: Button full width class exists
     * Validates: Requirements 1.5
     */
    public function test_button_full_width_class_exists()
    {
        $this->assertStringContainsString('.btn-full', $this->buttonsCss);
        $this->assertTrue(
            preg_match('/width:\s*100%/i', $this->buttonsCss),
            'Full width button should have width: 100%'
        );
    }

    /**
     * Test: Button group class stacks on mobile
     * Validates: Requirements 1.5
     */
    public function test_button_group_stacks_on_mobile()
    {
        $this->assertStringContainsString('.btn-group', $this->buttonsCss);
        $this->assertTrue(
            preg_match('/flex-direction:\s*column/i', $this->buttonsCss),
            'Button group should stack vertically (flex-direction: column) on mobile'
        );
    }

    /**
     * Test: Button group has responsive media query
     * Validates: Requirements 1.5
     */
    public function test_button_group_has_responsive_media_query()
    {
        $this->assertStringContainsString('@media', $this->buttonsCss);
        $this->assertTrue(
            preg_match('/min-width/i', $this->buttonsCss),
            'Button group should have responsive media queries'
        );
    }

    /**
     * Test: Button icon class exists with gap
     * Validates: Requirements 1.5
     */
    public function test_button_icon_class_has_gap()
    {
        $this->assertStringContainsString('.btn-icon', $this->buttonsCss);
        $this->assertTrue(
            preg_match('/gap:/i', $this->buttonsCss),
            'Button with icon should have gap property'
        );
    }

    /**
     * Test: Button loading state exists
     * Validates: Requirements 1.5
     */
    public function test_button_loading_state_exists()
    {
        $this->assertStringContainsString('.loading', $this->buttonsCss);
    }

    /**
     * Test: Button has reduced motion media query
     * Validates: Requirements 9.1
     */
    public function test_button_respects_reduced_motion()
    {
        $this->assertTrue(
            preg_match('/prefers-reduced-motion/i', $this->buttonsCss),
            'Button CSS should respect prefers-reduced-motion media query'
        );
    }

    /**
     * Test: Button has transitions for smooth state changes
     * Validates: Requirements 14.5
     */
    public function test_button_has_smooth_transitions()
    {
        $this->assertTrue(
            preg_match('/(transition|--transition-)/i', $this->buttonsCss),
            'Button should have transition properties for smooth state changes'
        );
    }

    /**
     * Test: Button responsive padding in media queries
     * Validates: Requirements 1.5, 3.5
     */
    public function test_button_responsive_padding_exists()
    {
        $this->assertTrue(
            preg_match('/@media/i', $this->buttonsCss) && 
            preg_match('/padding:/i', $this->buttonsCss),
            'Button should have responsive padding in media queries'
        );
    }

    /**
     * Test: Primary button uses brand dark on hover
     * Validates: Requirements 14.5
     */
    public function test_primary_button_uses_brand_dark_on_hover()
    {
        $this->assertTrue(
            preg_match('/--brand-dark/i', $this->buttonsCss),
            'Primary button hover should use brand-dark color'
        );
    }

    /**
     * Test: Secondary button exists with light background
     * Validates: Requirements 1.5
     */
    public function test_secondary_button_uses_light_background()
    {
        $this->assertTrue(
            preg_match('/--brand-light/i', $this->buttonsCss),
            'Secondary button should use brand-light background'
        );
    }

    /**
     * Test: Tertiary button is text-only style
     * Validates: Requirements 1.5
     */
    public function test_tertiary_button_is_text_only()
    {
        $this->assertTrue(
            preg_match('/transparent/i', $this->buttonsCss),
            'Tertiary button should use transparent background'
        );
    }

    /**
     * Test: Button has high contrast mode support
     * Validates: Requirements 9.1, 14.1
     */
    public function test_button_has_high_contrast_support()
    {
        $this->assertTrue(
            preg_match('/prefers-contrast/i', $this->buttonsCss),
            'Button CSS should have high contrast mode support'
        );
    }

    /**
     * Test: Button has dark mode support
     * Validates: Requirements 9.1
     */
    public function test_button_has_dark_mode_support()
    {
        $this->assertTrue(
            preg_match('/prefers-color-scheme/i', $this->buttonsCss),
            'Button CSS should have dark mode support'
        );
    }

    /**
     * Test: Button CSS has accessibility documentation
     * Validates: Requirements 9.1, 14.1, 14.5
     */
    public function test_button_css_has_accessibility_comments()
    {
        $this->assertTrue(
            preg_match('/(Accessibility|WCAG|focus|keyboard|touch|44px)/i', $this->buttonsCss),
            'Button CSS should have accessibility documentation and best practices'
        );
    }

    /**
     * Test: Button CSS file has proper structure
     * Validates: Requirements 1.5, 3.5, 9.1, 14.1, 14.5
     */
    public function test_button_css_has_complete_structure()
    {
        // Verify key sections exist
        $requiredSections = [
            'BUTTON BASE',
            'PRIMARY BUTTON',
            'SECONDARY BUTTON',
            'SIZE VARIANTS',
            'ACCESSIBILITY',
            'DISABLED STATE'
        ];

        foreach ($requiredSections as $section) {
            $this->assertTrue(
                strpos($this->buttonsCss, $section) !== false,
                "Button CSS should have $section section"
            );
        }
    }

    /**
     * Test: Button shadow properties exist for depth
     * Validates: Requirements 14.5
     */
    public function test_button_has_shadow_properties()
    {
        $this->assertTrue(
            preg_match('/(box-shadow|--shadow)/i', $this->buttonsCss),
            'Button should have shadow properties for visual depth'
        );
    }

    /**
     * Test: Button group has gap property for spacing
     * Validates: Requirements 1.5
     */
    public function test_button_group_has_gap_property()
    {
        $this->assertStringContainsString('gap:', $this->buttonsCss);
    }

    /**
     * Test: Button cursor changes for disabled state
     * Validates: Requirements 9.1
     */
    public function test_disabled_button_has_not_allowed_cursor()
    {
        $this->assertTrue(
            preg_match('/(cursor:\s*not-allowed|not-allowed)/i', $this->buttonsCss),
            'Disabled button should have cursor: not-allowed'
        );
    }

    /**
     * Test: Button uses semantic HTML selectors
     * Validates: Requirements 9.1
     */
    public function test_button_css_uses_semantic_selectors()
    {
        $this->assertTrue(
            preg_match('/(button|input\[type.*submit|\.btn)/i', $this->buttonsCss),
            'Button CSS should use semantic selectors'
        );
    }
}
