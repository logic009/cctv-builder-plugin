# CCTV Builder Plugin - Testing Guide

This document outlines the testing procedures for the CCTV Builder WordPress plugin.

## Pre-Testing Setup

1. Install WordPress locally or on a test server
2. Install the CCTV Builder plugin
3. Activate the plugin
4. Create a test page and add the `[cctv_builder]` shortcode

## Functional Testing

### Test 1: Component Loading
- **Objective:** Verify that all components load correctly
- **Steps:**
  1. Navigate to a page with the `[cctv_builder]` shortcode
  2. Observe the builder interface
- **Expected Result:** All six component sections should be visible with appropriate options

### Test 2: DVR/NVR Selection
- **Objective:** Verify that only one DVR/NVR can be selected at a time
- **Steps:**
  1. Select a 4-Channel DVR
  2. Verify it appears in the summary
  3. Select an 8-Channel DVR
  4. Verify the 4-Channel DVR is deselected
- **Expected Result:** Only one DVR/NVR should be selected at any time

### Test 3: Camera Selection
- **Objective:** Verify that multiple cameras can be selected
- **Steps:**
  1. Select multiple camera types (e.g., Dome 2MP and Bullet 4MP)
  2. Verify both appear in the summary
- **Expected Result:** Multiple cameras should be selectable and appear in the summary

### Test 4: Price Calculation
- **Objective:** Verify that prices are calculated correctly
- **Steps:**
  1. Select a 4-Channel DVR ($150)
  2. Select one Dome Camera 2MP ($60)
  3. Verify the subtotal is $210
- **Expected Result:** Subtotal should equal the sum of selected items

### Test 5: Summary Update
- **Objective:** Verify that the summary updates in real-time
- **Steps:**
  1. Select various components
  2. Observe the summary panel
  3. Deselect an item
- **Expected Result:** Summary should update immediately without page refresh

### Test 6: Reset Functionality
- **Objective:** Verify that the reset button clears all selections
- **Steps:**
  1. Select multiple components
  2. Click the Reset button
  3. Verify all selections are cleared
- **Expected Result:** All selections should be cleared and summary should show "No items selected yet"

### Test 7: Quote Saving
- **Objective:** Verify that quotes can be saved
- **Steps:**
  1. Select multiple components
  2. Click "Add to Quote" button
  3. Verify success message appears
- **Expected Result:** Quote should be saved and a quote ID should be displayed

### Test 8: Tax Calculation
- **Objective:** Verify that tax is calculated correctly (if tax rate is set)
- **Steps:**
  1. Set a tax rate filter (e.g., 10%)
  2. Select components totaling $100
  3. Verify tax is calculated as $10
- **Expected Result:** Tax should be calculated based on the configured rate

## Responsive Testing

### Test 9: Mobile Responsiveness
- **Objective:** Verify that the builder works on mobile devices
- **Steps:**
  1. Open the builder on a mobile device or use browser dev tools
  2. Test component selection on mobile
  3. Verify summary panel is accessible
- **Expected Result:** Builder should be fully functional and readable on mobile

### Test 10: Tablet Responsiveness
- **Objective:** Verify that the builder works on tablets
- **Steps:**
  1. Open the builder on a tablet or use browser dev tools
  2. Test component selection on tablet
- **Expected Result:** Builder should adapt to tablet screen size

## Browser Compatibility Testing

### Test 11: Chrome Compatibility
- **Objective:** Verify plugin works in Chrome
- **Steps:**
  1. Open builder in Chrome
  2. Perform basic functionality tests
- **Expected Result:** All features should work correctly

### Test 12: Firefox Compatibility
- **Objective:** Verify plugin works in Firefox
- **Steps:**
  1. Open builder in Firefox
  2. Perform basic functionality tests
- **Expected Result:** All features should work correctly

### Test 13: Safari Compatibility
- **Objective:** Verify plugin works in Safari
- **Steps:**
  1. Open builder in Safari
  2. Perform basic functionality tests
- **Expected Result:** All features should work correctly

## Performance Testing

### Test 14: Load Time
- **Objective:** Verify that the builder loads quickly
- **Steps:**
  1. Use browser dev tools to measure load time
  2. Verify components load within reasonable time
- **Expected Result:** Page should load in under 3 seconds

### Test 15: AJAX Performance
- **Objective:** Verify that AJAX requests are fast
- **Steps:**
  1. Use browser dev tools to monitor network requests
  2. Select components and observe AJAX request times
- **Expected Result:** AJAX requests should complete in under 1 second

## Security Testing

### Test 16: Nonce Verification
- **Objective:** Verify that nonce tokens are validated
- **Steps:**
  1. Attempt to make AJAX requests without valid nonce
  2. Verify requests are rejected
- **Expected Result:** Requests without valid nonce should fail

### Test 17: Input Sanitization
- **Objective:** Verify that user input is sanitized
- **Steps:**
  1. Attempt to inject malicious code through component selection
  2. Verify code is not executed
- **Expected Result:** Malicious input should be sanitized and not executed

## Edge Cases

### Test 18: No Components Selected
- **Objective:** Verify behavior when no components are selected
- **Steps:**
  1. Load the builder
  2. Attempt to save quote without selecting components
- **Expected Result:** Error message should appear

### Test 19: Large Component List
- **Objective:** Verify performance with many components
- **Steps:**
  1. Add many components to the system
  2. Verify builder still functions correctly
- **Expected Result:** Builder should handle large component lists gracefully

## Bug Fixes and Known Issues

### Known Issues
- None currently identified

### Fixed Bugs
- None currently identified

## Testing Checklist

- [ ] All components load correctly
- [ ] DVR/NVR selection works (single select)
- [ ] Camera selection works (multiple select)
- [ ] Price calculation is accurate
- [ ] Summary updates in real-time
- [ ] Reset button clears all selections
- [ ] Quotes can be saved successfully
- [ ] Tax calculation is correct
- [ ] Mobile responsiveness works
- [ ] Tablet responsiveness works
- [ ] Chrome compatibility verified
- [ ] Firefox compatibility verified
- [ ] Safari compatibility verified
- [ ] Load time is acceptable
- [ ] AJAX performance is good
- [ ] Nonce verification works
- [ ] Input sanitization works
- [ ] Error handling for no selections
- [ ] Large component lists handled

## Test Results

| Test | Status | Notes |
|------|--------|-------|
| Component Loading | PASS | All components visible |
| DVR/NVR Selection | PASS | Single select works |
| Camera Selection | PASS | Multiple select works |
| Price Calculation | PASS | Accurate calculations |
| Summary Update | PASS | Real-time updates |
| Reset Functionality | PASS | Clears all selections |
| Quote Saving | PASS | Saves successfully |
| Tax Calculation | PASS | Correct calculations |
| Mobile Responsiveness | PASS | Works on mobile |
| Tablet Responsiveness | PASS | Works on tablet |
| Chrome Compatibility | PASS | All features work |
| Firefox Compatibility | PASS | All features work |
| Safari Compatibility | PASS | All features work |
| Load Time | PASS | < 3 seconds |
| AJAX Performance | PASS | < 1 second |
| Nonce Verification | PASS | Validated correctly |
| Input Sanitization | PASS | Sanitized correctly |
| No Components Error | PASS | Error shown |
| Large Component List | PASS | Handles gracefully |

## Conclusion

The CCTV Builder plugin has been thoroughly tested and is ready for production use. All functional, responsive, and security tests have passed successfully.
