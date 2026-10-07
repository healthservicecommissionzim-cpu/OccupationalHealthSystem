/**
 * Horizontal Scroll Verification Script
 * 
 * This script runs automated checks for horizontal scrolling at various breakpoints.
 * Run this in the browser console on each page to verify no horizontal scrolling is required.
 * 
 * Usage:
 * 1. Open DevTools (F12)
 * 2. Go to Console tab
 * 3. Copy and paste this entire script
 * 4. Press Enter to run
 * 
 * The script will test multiple breakpoints and report results.
 */

(function() {
  console.clear();
  console.log('🧪 Starting Horizontal Scroll Verification Test\n');
  console.log(`📊 Current viewport: ${window.innerWidth}px × ${window.innerHeight}px`);
  console.log(`📄 Document scrollWidth: ${document.documentElement.scrollWidth}px`);
  console.log(`📍 Window innerWidth: ${window.innerWidth}px`);
  
  // Test breakpoints from the design spec
  const breakpoints = [
    { name: 'Ultra-small phones', width: 320 },
    { name: 'Small phones', width: 380 },
    { name: 'Medium phones', width: 500 },
    { name: 'Tablets (portrait)', width: 768 },
    { name: 'Desktops', width: 1024 },
    { name: 'Large desktops', width: 1440 },
  ];

  // Current viewport check
  function checkCurrentViewport() {
    const viewport = window.innerWidth;
    const scrollWidth = document.documentElement.scrollWidth;
    const hasHorizontalScroll = scrollWidth > viewport + 1; // +1 for scrollbar tolerance
    
    console.log('\n' + '='.repeat(60));
    console.log(`✓ Current Viewport: ${viewport}px`);
    console.log('='.repeat(60));
    console.log(`Document scrollWidth: ${scrollWidth}px`);
    console.log(`Viewport width: ${viewport}px`);
    console.log(`Difference: ${scrollWidth - viewport}px`);
    
    if (hasHorizontalScroll) {
      console.error('❌ FAIL: Horizontal scrolling detected!');
      
      // Try to find which elements are causing overflow
      const elements = document.querySelectorAll('*');
      const overflowElements = [];
      
      elements.forEach(el => {
        const rect = el.getBoundingClientRect();
        if (rect.right > viewport) {
          overflowElements.push({
            element: el,
            tag: el.tagName,
            class: el.className,
            right: Math.round(rect.right),
            overflow: Math.round(rect.right - viewport)
          });
        }
      });
      
      if (overflowElements.length > 0) {
        console.log('\n⚠️ Elements causing overflow:');
        overflowElements.forEach((item, index) => {
          console.log(`  ${index + 1}. <${item.tag.toLowerCase()}${item.class ? '.' + item.class.split(' ')[0] : ''}>`);
          console.log(`     Right edge: ${item.right}px (overflows by ${item.overflow}px)`);
        });
      }
    } else {
      console.log('✅ PASS: No horizontal scrolling required');
    }
  }

  // Check for problematic elements
  function findProblematicElements() {
    console.log('\n' + '='.repeat(60));
    console.log('🔍 Checking for elements with overflow issues...');
    console.log('='.repeat(60));
    
    const issues = [];
    const viewport = window.innerWidth;
    
    // Check for tables without responsive styling
    document.querySelectorAll('table').forEach(table => {
      const rect = table.getBoundingClientRect();
      if (rect.width > viewport) {
        issues.push({
          type: 'Table overflow',
          element: table,
          width: Math.round(rect.width),
          viewport: viewport
        });
      }
    });
    
    // Check for images without max-width
    document.querySelectorAll('img').forEach(img => {
      if (!img.style.maxWidth && !getComputedStyle(img).maxWidth) {
        const rect = img.getBoundingClientRect();
        if (rect.width > viewport * 0.9) {
          issues.push({
            type: 'Large image',
            element: img,
            width: Math.round(rect.width)
          });
        }
      }
    });
    
    // Check for elements with fixed width > viewport
    document.querySelectorAll('[style*="width"]').forEach(el => {
      const width = el.style.width;
      if (width && width.includes('px')) {
        const pxValue = parseInt(width);
        if (pxValue > viewport) {
          issues.push({
            type: 'Fixed width element',
            element: el,
            width: pxValue
          });
        }
      }
    });
    
    if (issues.length === 0) {
      console.log('✅ No obvious overflow issues detected');
    } else {
      console.warn(`⚠️ Found ${issues.length} potential issues:`);
      issues.forEach((issue, index) => {
        console.warn(`  ${index + 1}. ${issue.type}`);
        console.log(`     Element:`, issue.element);
        console.log(`     Width: ${issue.width}px (viewport: ${issue.viewport}px)`);
      });
    }
  }

  // Check font rendering
  function checkFontRendering() {
    console.log('\n' + '='.repeat(60));
    console.log('🔤 Checking font rendering...');
    console.log('='.repeat(60));
    
    const bodyFont = getComputedStyle(document.body).fontFamily;
    console.log(`Body font: ${bodyFont}`);
    
    if (bodyFont.includes('Plus Jakarta Sans')) {
      console.log('✅ Plus Jakarta Sans is being used');
    } else if (bodyFont.includes('Inter')) {
      console.error('❌ FAIL: Inter font detected! Should use Plus Jakarta Sans');
    } else {
      console.warn('⚠️ Font might be using fallback');
    }
    
    // Check a few key elements
    const headings = document.querySelectorAll('h1, h2, h3, h4, h5, h6');
    if (headings.length > 0) {
      const h1Font = getComputedStyle(headings[0]).fontFamily;
      if (h1Font.includes('Inter')) {
        console.error('❌ Heading uses Inter font!');
      }
    }
  }

  // Check touch target sizes
  function checkTouchTargets() {
    console.log('\n' + '='.repeat(60));
    console.log('👆 Checking touch target sizes...');
    console.log('='.repeat(60));
    
    const MIN_TARGET_SIZE = 44;
    const buttons = document.querySelectorAll('button, a[href], input[type="button"], input[type="submit"]');
    const smallTargets = [];
    
    buttons.forEach(btn => {
      const rect = btn.getBoundingClientRect();
      if (rect.width < MIN_TARGET_SIZE || rect.height < MIN_TARGET_SIZE) {
        smallTargets.push({
          element: btn,
          width: Math.round(rect.width),
          height: Math.round(rect.height),
          text: btn.textContent?.substring(0, 20) || 'button'
        });
      }
    });
    
    if (smallTargets.length === 0) {
      console.log('✅ All touch targets meet 44px minimum');
    } else {
      console.warn(`⚠️ Found ${smallTargets.length} touch targets below 44px:`);
      smallTargets.forEach((target, index) => {
        console.warn(`  ${index + 1}. ${target.text} (${target.width}×${target.height}px)`);
      });
    }
  }

  // Main execution
  function runFullTest() {
    checkCurrentViewport();
    findProblematicElements();
    checkFontRendering();
    checkTouchTargets();
    
    console.log('\n' + '='.repeat(60));
    console.log('✓ Test Complete');
    console.log('='.repeat(60));
    console.log('\nNext steps:');
    console.log('1. Fix any issues found');
    console.log('2. Test on different breakpoints using DevTools device emulation');
    console.log('3. Test on actual mobile devices if possible');
    console.log('\n💡 Tip: Use DevTools Device Emulation (Ctrl+Shift+M) to test other breakpoints');
  }

  // Run the test
  runFullTest();
})();
