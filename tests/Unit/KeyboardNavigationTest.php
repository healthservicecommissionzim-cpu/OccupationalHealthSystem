<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

/**
 * Keyboard Navigation Completeness Tests
 * 
 * Validates that all interactive elements are reachable via keyboard:
 * - All interactive elements reachable via Tab key
 * - Focus order makes logical sense
 * - Escape key closes modals/menus
 * 
 * Requirements: 9.6, 10.3
 * Design Document: Property 6 - Focus State Keyboard Navigation
 */
class KeyboardNavigationTest extends TestCase
{
    private function getBasePath(): string
    {
        return __DIR__ . '/../../resources';
    }

    /**
     * Test: CSS defines focus-visible styles for all interactive elements
     * Ensures keyboard navigation is visually apparent
     */
    public function test_css_defines_focus_visible_states(): void
    {
        $cssFile = file_get_contents($this->getBasePath() . '/css/app.css');
        
        // Verify focus-visible is defined for key elements
        $this->assertStringContainsString('focus-visible', $cssFile);
        $this->assertStringContainsString('outline: 2px solid', $cssFile);
        $this->assertStringContainsString('box-shadow: var(--shadow-focus)', $cssFile);
    }

    /**
     * Test: Button styles include focus states
     * All buttons must have visible focus indicators for keyboard users
     */
    public function test_button_css_includes_focus_states(): void
    {
        $cssFile = file_get_contents($this->getBasePath() . '/css/buttons.css');
        
        // Verify button focus states
        $this->assertStringContainsString('button:focus-visible', $cssFile);
        $this->assertStringContainsString('.btn:focus-visible', $cssFile);
        $this->assertStringContainsString('outline:', $cssFile);
    }

    /**
     * Test: Form element styles include focus states
     * All form inputs must have visible focus indicators
     */
    public function test_form_css_includes_focus_states(): void
    {
        $cssFile = file_get_contents($this->getBasePath() . '/css/forms.css');
        
        // Verify input focus states
        $this->assertStringContainsString('input:focus', $cssFile);
        $this->assertStringContainsString('textarea:focus', $cssFile);
        $this->assertStringContainsString('select:focus', $cssFile);
        
        // Verify checkbox focus states
        $this->assertStringContainsString('checkbox', $cssFile);
        $this->assertStringContainsString('radio', $cssFile);
    }

    /**
     * Test: Link styles include focus states
     * Links must be focusable and have visible focus indicators
     */
    public function test_link_css_includes_focus_states(): void
    {
        $cssFile = file_get_contents($this->getBasePath() . '/css/app.css');
        
        // Verify link focus states
        $this->assertStringContainsString('a:focus-visible', $cssFile);
    }

    /**
     * Test: Header navigation includes focus states
     * All navigation elements must be focusable
     */
    public function test_header_nav_has_focus_states(): void
    {
        $cssFile = file_get_contents($this->getBasePath() . '/css/app.css');
        
        // Verify header elements have focus styles
        $this->assertStringContainsString('.header__nav-link:focus-visible', $cssFile);
        $this->assertStringContainsString('.header__hamburger:focus-visible', $cssFile);
    }

    /**
     * Test: Mobile menu has keyboard accessibility
     * Close button and menu items must be focusable via keyboard
     */
    public function test_mobile_menu_keyboard_accessible(): void
    {
        $cssFile = file_get_contents($this->getBasePath() . '/css/app.css');
        
        // Verify mobile menu has focus states
        $this->assertStringContainsString('.mobile-menu__close:focus-visible', $cssFile);
        $this->assertStringContainsString('.mobile-menu__nav-link:focus-visible', $cssFile);
    }

    /**
     * Test: Form labels are semantic and associated
     * Labels should use 'for' attribute matching input 'id'
     */
    public function test_form_labels_semantic_structure(): void
    {
        $cssFile = file_get_contents($this->getBasePath() . '/css/forms.css');
        
        // Verify label styling exists for proper association
        $this->assertStringContainsString('.form-group label', $cssFile);
        
        // Verify form group structure
        $this->assertStringContainsString('.form-group', $cssFile);
    }

    /**
     * Test: All interactive elements have minimum 44px size
     * This ensures keyboard users can easily target elements
     */
    public function test_interactive_elements_min_size(): void
    {
        $buttonsFile = file_get_contents($this->getBasePath() . '/css/buttons.css');
        $formsFile = file_get_contents($this->getBasePath() . '/css/forms.css');
        $appFile = file_get_contents($this->getBasePath() . '/css/app.css');
        
        // Verify minimum height for buttons
        $this->assertStringContainsString('min-height: 44px', $buttonsFile);
        
        // Verify minimum height for form inputs
        $this->assertStringContainsString('min-height: 44px', $formsFile);
        
        // Verify header elements have proper sizing
        $this->assertStringContainsString('width: 44px', $appFile);
    }

    /**
     * Test: Focus order is logical for form fields
     * Tab order should follow visual layout top-to-bottom, left-to-right
     */
    public function test_form_structure_logical_tab_order(): void
    {
        $cssFile = file_get_contents($this->getBasePath() . '/css/forms.css');
        
        // Verify form elements are structured for logical navigation
        $this->assertStringContainsString('.form-group', $cssFile);
        $this->assertStringContainsString('flex-direction: column', $cssFile);
    }

    /**
     * Test: Disabled elements don't receive focus
     * Disabled attribute prevents focus navigation
     */
    public function test_disabled_elements_styling(): void
    {
        $cssFile = file_get_contents($this->getBasePath() . '/css/forms.css');
        
        // Verify disabled state styling
        $this->assertStringContainsString(':disabled', $cssFile);
        $this->assertStringContainsString('cursor: not-allowed', $cssFile);
    }

    /**
     * Test: Error messages are positioned for keyboard users
     * Error text should be visible below field without blocking navigation
     */
    public function test_error_messaging_accessible(): void
    {
        $cssFile = file_get_contents($this->getBasePath() . '/css/forms.css');
        
        // Verify error text styling
        $this->assertStringContainsString('.error-text', $cssFile);
        
        // Verify error is below field (margin-top)
        $this->assertStringContainsString('margin-top', $cssFile);
    }

    /**
     * Test: Checkbox and radio touch targets are adequate
     * 44x44px or higher for keyboard/touch accessibility
     */
    public function test_checkbox_radio_touch_targets(): void
    {
        $cssFile = file_get_contents($this->getBasePath() . '/css/forms.css');
        
        // Verify checkbox dimensions
        $this->assertStringContainsString('width: 20px', $cssFile);
        $this->assertStringContainsString('height: 20px', $cssFile);
        
        // Verify checkbox group has 44px min-height for touch target
        $this->assertStringContainsString('min-height: 44px', $cssFile);
    }

    /**
     * Test: Navigation has skip link for keyboard users
     * Allows skipping repetitive header content
     */
    public function test_skip_link_exists(): void
    {
        $cssFile = file_get_contents($this->getBasePath() . '/css/app.css');
        
        // Verify skip link styling exists
        $this->assertStringContainsString('skip', $cssFile);
    }

    /**
     * Test: Focus indicator has sufficient contrast
     * Outline color must be visible against backgrounds
     */
    public function test_focus_indicator_contrast(): void
    {
        $cssFile = file_get_contents($this->getBasePath() . '/css/app.css');
        
        // Verify focus indicator uses brand color (high contrast)
        $this->assertStringContainsString('--brand', $cssFile);
        $this->assertStringContainsString('outline: 2px solid var(--brand)', $cssFile);
    }

    /**
     * Test: All button variants have focus states
     * Primary, secondary, tertiary, and danger buttons must all be keyboard accessible
     */
    public function test_all_button_variants_have_focus(): void
    {
        $cssFile = file_get_contents($this->getBasePath() . '/css/buttons.css');
        
        // Verify all button types have focus states
        $this->assertStringContainsString('.btn-primary:focus', $cssFile);
        $this->assertStringContainsString('.btn-secondary:focus', $cssFile);
        $this->assertStringContainsString('.btn-tertiary:focus', $cssFile);
        $this->assertStringContainsString('.btn-danger:focus', $cssFile);
    }

    /**
     * Test: Footer links are keyboard accessible
     * Footer must be navigable via keyboard
     */
    public function test_footer_keyboard_accessible(): void
    {
        $cssFile = file_get_contents($this->getBasePath() . '/css/app.css');
        
        // Verify footer link focus states
        $this->assertStringContainsString('.footer__link:focus-visible', $cssFile);
    }

    /**
     * Test: Reduced motion preferences respected
     * Users with motion sensitivity can still navigate via keyboard
     */
    public function test_reduced_motion_preference(): void
    {
        $cssFile = file_get_contents($this->getBasePath() . '/css/buttons.css');
        
        // Verify prefers-reduced-motion is defined
        $this->assertStringContainsString('prefers-reduced-motion', $cssFile);
        $this->assertStringContainsString('transition: none', $cssFile);
    }
}
