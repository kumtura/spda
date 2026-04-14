# Tentang Desa Text Fix Bugfix Design

## Overview

This bugfix addresses incorrect terminology throughout the `/tentang-desa` pages. The application manages a "desa adat" (traditional village) under Bupda (Badan Usaha Padruwen Desa Adat), but static text incorrectly uses "desa" instead of "desa adat". The fix involves updating text strings in Blade templates, controller success messages, and sidebar menu items to consistently use "Desa Adat" terminology.

The fix is minimal and surgical - only text strings are changed. No business logic, routing, database operations, or functionality is modified.

## Glossary

- **Bug_Condition (C)**: The condition that triggers the bug - when viewing pages/menus that display "desa" instead of "desa adat"
- **Property (P)**: The desired behavior - all user-facing text should consistently use "Desa Adat" terminology
- **Preservation**: All existing functionality (forms, uploads, CRUD operations, routing) must remain unchanged
- **Blade Template**: Laravel view files (.blade.php) that render HTML with embedded PHP
- **TentangDesaController**: The controller in `app/Http/Controllers/Administrator/TentangDesaController.php` that handles tentang-desa pages
- **Sidebar Menu**: The navigation menu in `resources/views/layouts/sidebar.blade.php`

## Bug Details

### Bug Condition

The bug manifests when users view any of the tentang-desa pages or navigation elements. The text displays "desa" or "Badan Usaha Milik Desa" instead of the correct "desa adat" or "BUPDA Desa Adat" terminology.

**Formal Specification:**
```
FUNCTION isBugCondition(input)
  INPUT: input of type PageView
  OUTPUT: boolean
  
  RETURN input.page IN ['sejarah', 'lembaga', 'bupda', 'sidebar_menu']
         AND input.displayedText CONTAINS 'desa' WITHOUT 'desa adat'
         AND input.displayedText NOT IN ['Bendesa', 'BUMDes'] // These are proper nouns
END FUNCTION
```

### Examples

- **Sejarah page tab navigation**: Displays "Sejarah Desa" and "Pengurus Desa" → Should display "Sejarah Desa Adat" and "Pengurus Desa Adat"
- **Sidebar menu**: Displays "Badan Usaha Milik Desa" → Should display "BUPDA Desa Adat"
- **Lembaga page breadcrumb**: Displays "Tentang Desa" → Should display "Tentang Desa Adat"
- **Controller success message**: Displays "Sejarah Desa Adat berhasil diperbarui!" (correct) but inconsistent with other messages that use "desa"

## Expected Behavior

### Preservation Requirements

**Unchanged Behaviors:**
- All form submissions must continue to save data correctly to settings.json
- All file uploads (images, videos, documents) must continue to work
- All CRUD operations (create, read, update, delete) must function identically
- All routing and URL paths must remain unchanged
- All CKEditor functionality must continue to work
- All Alpine.js interactions (modals, tabs) must continue to work
- All CSS classes and styling must remain unchanged

**Scope:**
All inputs that do NOT involve viewing static text labels should be completely unaffected by this fix. This includes:
- Form field values and data processing
- Database operations and file storage
- JavaScript functionality and event handlers
- API endpoints and routing logic

## Hypothesized Root Cause

Based on the bug description, the root cause is straightforward:

1. **Inconsistent Terminology in Templates**: Blade template files contain hardcoded text strings that use "desa" instead of "desa adat"
   - Tab labels in sejarah.blade.php use "Sejarah Desa" and "Pengurus Desa"
   - Breadcrumbs use "Tentang Desa" without "Adat" specification
   - Page descriptions use lowercase "desa adat" inconsistently

2. **Incorrect Sidebar Menu Label**: The sidebar menu uses "Badan Usaha Milik Desa" (generic village business entity) instead of "BUPDA Desa Adat" or "Badan Usaha Padruwen Desa Adat" (traditional village business entity)

3. **Legacy Naming**: The application may have been initially designed for regular villages (desa) and later adapted for traditional villages (desa adat), leaving some text strings unchanged

## Correctness Properties

Property 1: Bug Condition - Consistent Desa Adat Terminology

_For any_ page view where the user navigates to tentang-desa pages (sejarah, lembaga, bupda) or views the sidebar menu, the fixed templates SHALL display "Desa Adat" terminology consistently in all tab labels, breadcrumbs, page titles, and menu items.

**Validates: Requirements 2.1, 2.2, 2.3, 2.4, 2.5**

Property 2: Preservation - Unchanged Functionality

_For any_ user interaction that involves form submissions, file uploads, CRUD operations, or navigation, the fixed code SHALL produce exactly the same behavior as the original code, preserving all business logic, routing, and data processing.

**Validates: Requirements 3.1, 3.2, 3.3, 3.4, 3.5, 3.6**

## Fix Implementation

### Changes Required

All changes are text-only modifications to Blade templates and controller success messages. No logic changes.

**File 1**: `resources/views/admin/pages/tentang_desa/sejarah.blade.php`

**Specific Changes**:
1. **Line 9**: Change page title from `Sejarah & Pengurus Desa Adat` to `Sejarah & Pengurus Desa Adat` (already correct, verify consistency)
2. **Line 10**: Change description from `Kelola konten sejarah, pengurus, dan produk hukum desa adat.` to `Kelola konten sejarah, pengurus, dan produk hukum Desa Adat.` (capitalize "Desa Adat")
3. **Line 22**: Change tab label from `'label'=>'Sejarah Desa'` to `'label'=>'Sejarah Desa Adat'`
4. **Line 23**: Change tab label from `'label'=>'Pengurus Desa'` to `'label'=>'Pengurus Desa Adat'`
5. **Line 38**: Change section heading from `Konten Sejarah Desa Adat` to `Konten Sejarah Desa Adat` (already correct, verify)
6. **Line 42**: Verify "Tentang Desa" in description should be "Tentang Desa Adat"

**File 2**: `resources/views/admin/pages/tentang_desa/lembaga.blade.php`

**Specific Changes**:
1. **Line 8**: Change breadcrumb from `Tentang Desa` to `Tentang Desa Adat`
2. **Line 10**: Change description from `Kelola data lembaga yang ada di desa adat.` to `Kelola data lembaga yang ada di Desa Adat.` (capitalize "Desa Adat")

**File 3**: `resources/views/admin/pages/tentang_desa/bupda.blade.php`

**Specific Changes**:
1. **Line 15**: Change breadcrumb from `Tentang Desa` to `Tentang Desa Adat`

**File 4**: `resources/views/layouts/sidebar.blade.php`

**Specific Changes**:
1. **Line 97**: Change menu item from `Badan Usaha Milik Desa` to `BUPDA Desa Adat`

**File 5**: `app/Http/Controllers/Administrator/TentangDesaController.php`

**Specific Changes**:
1. **Line 127**: Verify success message uses "Struktur Desa Adat" (check if it says "struktur desa" instead)
2. Review all success messages to ensure consistent capitalization of "Desa Adat"

## Testing Strategy

### Validation Approach

The testing strategy follows a two-phase approach: first, surface counterexamples that demonstrate the bug on unfixed code by viewing pages and capturing screenshots, then verify the fix displays correct terminology and preserves all existing functionality.

### Exploratory Bug Condition Checking

**Goal**: Surface counterexamples that demonstrate the bug BEFORE implementing the fix. Confirm the incorrect text is displayed in the specified locations.

**Test Plan**: Manually navigate to each tentang-desa page and the sidebar menu. Capture screenshots or inspect the rendered HTML to verify the incorrect text strings are present. Document the exact locations and current text.

**Test Cases**:
1. **Sejarah Page Tab Labels**: Navigate to `/administrator/tentang-desa/sejarah` and verify tabs show "Sejarah Desa" and "Pengurus Desa" (will fail on unfixed code)
2. **Lembaga Page Breadcrumb**: Navigate to `/administrator/tentang-desa/lembaga` and verify breadcrumb shows "Tentang Desa" (will fail on unfixed code)
3. **BUPDA Page Breadcrumb**: Navigate to `/administrator/tentang-desa/bupda` and verify breadcrumb shows "Tentang Desa" (will fail on unfixed code)
4. **Sidebar Menu Item**: View the sidebar menu and verify it shows "Badan Usaha Milik Desa" (will fail on unfixed code)

**Expected Counterexamples**:
- Tab labels display "Sejarah Desa" instead of "Sejarah Desa Adat"
- Breadcrumbs display "Tentang Desa" instead of "Tentang Desa Adat"
- Sidebar menu displays "Badan Usaha Milik Desa" instead of "BUPDA Desa Adat"
- Possible causes: hardcoded text strings in Blade templates, inconsistent terminology from initial development

### Fix Checking

**Goal**: Verify that for all page views where the bug condition holds, the fixed templates display the correct "Desa Adat" terminology.

**Pseudocode:**
```
FOR ALL pageView WHERE isBugCondition(pageView) DO
  renderedHTML := renderPage_fixed(pageView)
  ASSERT renderedHTML CONTAINS 'Desa Adat' terminology
  ASSERT renderedHTML NOT CONTAINS incorrect 'desa' terminology
END FOR
```

### Preservation Checking

**Goal**: Verify that for all user interactions where the bug condition does NOT hold (form submissions, file uploads, CRUD operations), the fixed code produces the same result as the original code.

**Pseudocode:**
```
FOR ALL userAction WHERE NOT isBugCondition(userAction) DO
  ASSERT handleAction_original(userAction) = handleAction_fixed(userAction)
END FOR
```

**Testing Approach**: Manual testing is recommended for preservation checking because:
- The changes are text-only and do not affect logic
- Visual inspection can quickly confirm functionality is unchanged
- The scope is limited to a few specific pages

**Test Plan**: After applying the fix, test all major functionality on the tentang-desa pages to ensure nothing broke.

**Test Cases**:
1. **Sejarah Form Submission**: Submit the sejarah form with CKEditor content and verify it saves correctly
2. **Video Upload**: Upload a video on the sejarah page and verify it appears in the list
3. **Lembaga CRUD**: Create, edit, and delete a lembaga entry and verify all operations work
4. **BUPDA Tab Navigation**: Click through all BUPDA tabs (Informasi, Struktur, Tim, Program, Dokumentasi) and verify they display correctly
5. **Sidebar Navigation**: Click the sidebar menu items and verify they navigate to the correct pages

### Unit Tests

- Test that sejarah page renders with correct tab labels "Sejarah Desa Adat" and "Pengurus Desa Adat"
- Test that lembaga page renders with correct breadcrumb "Tentang Desa Adat"
- Test that bupda page renders with correct breadcrumb "Tentang Desa Adat"
- Test that sidebar menu renders with correct label "BUPDA Desa Adat"

### Property-Based Tests

Property-based testing is not applicable for this bugfix because:
- The changes are purely cosmetic text updates
- There is no algorithmic logic to test with random inputs
- The bug condition is deterministic (specific text strings in specific locations)

Manual testing and visual inspection are sufficient for this fix.

### Integration Tests

- Test full navigation flow: Dashboard → Sidebar → Sejarah page → Verify correct text and functionality
- Test full navigation flow: Dashboard → Sidebar → Lembaga page → Verify correct text and functionality
- Test full navigation flow: Dashboard → Sidebar → BUPDA page → Verify correct text and functionality
- Test that all forms continue to submit successfully after text changes
- Test that all file uploads continue to work after text changes
