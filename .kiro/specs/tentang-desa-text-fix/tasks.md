# Implementation Plan

- [x] 1. Write bug condition exploration test
  - **Property 1: Bug Condition** - Incorrect Desa Terminology Display
  - **CRITICAL**: This test MUST FAIL on unfixed code - failure confirms the bug exists
  - **DO NOT attempt to fix the test or the code when it fails**
  - **NOTE**: This test encodes the expected behavior - it will validate the fix when it passes after implementation
  - **GOAL**: Surface counterexamples that demonstrate the bug exists
  - **Manual Testing Approach**: Since this is a UI text bug, manual inspection is most appropriate
  - Navigate to `/administrator/tentang-desa/sejarah` and verify tabs show "Sejarah Desa" and "Pengurus Desa" (incorrect)
  - Navigate to `/administrator/tentang-desa/lembaga` and verify breadcrumb shows "Tentang Desa" (incorrect)
  - Navigate to `/administrator/tentang-desa/bupda` and verify breadcrumb shows "Tentang Desa" (incorrect)
  - View sidebar menu and verify it shows "Badan Usaha Milik Desa" (incorrect)
  - Run test on UNFIXED code
  - **EXPECTED OUTCOME**: Test FAILS (this is correct - it proves the bug exists)
  - Document counterexamples found:
    - Tab labels display "Sejarah Desa" instead of "Sejarah Desa Adat"
    - Tab labels display "Pengurus Desa" instead of "Pengurus Desa Adat"
    - Breadcrumbs display "Tentang Desa" instead of "Tentang Desa Adat"
    - Sidebar menu displays "Badan Usaha Milik Desa" instead of "BUPDA Desa Adat"
  - Mark task complete when test is written, run, and failure is documented
  - _Requirements: 1.1, 1.2, 1.3, 1.4, 1.5_

- [x] 2. Write preservation property tests (BEFORE implementing fix)
  - **Property 2: Preservation** - Unchanged Functionality
  - **IMPORTANT**: Follow observation-first methodology
  - Observe behavior on UNFIXED code for non-text-display interactions
  - Test that sejarah form submission saves data correctly to settings.json
  - Test that video upload on sejarah page works correctly
  - Test that lembaga CRUD operations (create, edit, delete) work correctly
  - Test that BUPDA tab navigation (Informasi, Struktur, Tim, Program, Dokumentasi) works correctly
  - Test that sidebar navigation routes to correct pages
  - Test that CKEditor functionality works correctly
  - Run tests on UNFIXED code
  - **EXPECTED OUTCOME**: Tests PASS (this confirms baseline behavior to preserve)
  - Mark task complete when tests are written, run, and passing on unfixed code
  - _Requirements: 3.1, 3.2, 3.3, 3.4, 3.5, 3.6_

- [x] 3. Fix for incorrect desa terminology

  - [x] 3.1 Update sejarah.blade.php text strings
    - Change line 10 description: "desa adat" → "Desa Adat" (capitalize)
    - Change line 22 tab label: "Sejarah Desa" → "Sejarah Desa Adat"
    - Change line 23 tab label: "Pengurus Desa" → "Pengurus Desa Adat"
    - Verify line 38 section heading uses "Desa Adat" consistently
    - _Bug_Condition: isBugCondition(input) where input.page = 'sejarah' AND input.displayedText CONTAINS 'desa' WITHOUT 'desa adat'_
    - _Expected_Behavior: All tab labels and descriptions display "Desa Adat" terminology_
    - _Preservation: All form submissions, CKEditor functionality, and video uploads continue to work_
    - _Requirements: 2.1, 3.1, 3.4_

  - [x] 3.2 Update lembaga.blade.php text strings
    - Change line 8 breadcrumb: "Tentang Desa" → "Tentang Desa Adat"
    - Change line 10 description: "desa adat" → "Desa Adat" (capitalize)
    - _Bug_Condition: isBugCondition(input) where input.page = 'lembaga' AND input.displayedText = 'Tentang Desa'_
    - _Expected_Behavior: Breadcrumb and description display "Desa Adat" terminology_
    - _Preservation: All lembaga CRUD operations continue to work_
    - _Requirements: 2.3, 2.4, 3.1, 3.5_

  - [x] 3.3 Update bupda.blade.php text strings
    - Change line 15 breadcrumb: "Tentang Desa" → "Tentang Desa Adat"
    - _Bug_Condition: isBugCondition(input) where input.page = 'bupda' AND input.displayedText = 'Tentang Desa'_
    - _Expected_Behavior: Breadcrumb displays "Desa Adat" terminology_
    - _Preservation: All BUPDA tab navigation and functionality continue to work_
    - _Requirements: 2.3, 3.1, 3.3_

  - [x] 3.4 Update sidebar.blade.php menu item
    - Change line 97 menu item: "Badan Usaha Milik Desa" → "BUPDA Desa Adat"
    - _Bug_Condition: isBugCondition(input) where input.page = 'sidebar_menu' AND input.displayedText = 'Badan Usaha Milik Desa'_
    - _Expected_Behavior: Sidebar menu displays "BUPDA Desa Adat"_
    - _Preservation: All sidebar navigation and routing continue to work_
    - _Requirements: 2.2, 3.1, 3.6_

  - [x] 3.5 Review TentangDesaController.php success messages
    - Review line 127 and all success messages for consistent "Desa Adat" capitalization
    - Update any messages that use lowercase "desa adat" to "Desa Adat"
    - _Bug_Condition: isBugCondition(input) where input.displayedText is a success message with inconsistent terminology_
    - _Expected_Behavior: All success messages use consistent "Desa Adat" terminology_
    - _Preservation: All controller logic and data processing continue to work_
    - _Requirements: 2.5, 3.1, 3.2_

  - [x] 3.6 Verify bug condition exploration test now passes
    - **Property 1: Expected Behavior** - Correct Desa Adat Terminology Display
    - **IMPORTANT**: Re-run the SAME test from task 1 - do NOT write a new test
    - The test from task 1 encodes the expected behavior
    - When this test passes, it confirms the expected behavior is satisfied
    - Navigate to all tentang-desa pages and verify correct "Desa Adat" terminology
    - Verify sidebar menu displays "BUPDA Desa Adat"
    - **EXPECTED OUTCOME**: Test PASSES (confirms bug is fixed)
    - _Requirements: 2.1, 2.2, 2.3, 2.4, 2.5_

  - [x] 3.7 Verify preservation tests still pass
    - **Property 2: Preservation** - Unchanged Functionality
    - **IMPORTANT**: Re-run the SAME tests from task 2 - do NOT write new tests
    - Run all preservation tests from step 2
    - Verify sejarah form submission still works
    - Verify video upload still works
    - Verify lembaga CRUD still works
    - Verify BUPDA tab navigation still works
    - Verify sidebar navigation still works
    - Verify CKEditor still works
    - **EXPECTED OUTCOME**: Tests PASS (confirms no regressions)
    - Confirm all tests still pass after fix (no regressions)
    - _Requirements: 3.1, 3.2, 3.3, 3.4, 3.5, 3.6_

- [x] 4. Checkpoint - Ensure all tests pass
  - Ensure all tests pass, ask the user if questions arise.
