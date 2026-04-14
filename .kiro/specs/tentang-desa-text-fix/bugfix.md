# Bugfix Requirements Document

## Introduction

The `/tentang-desa` pages (sejarah, lembaga, bupda) currently use incorrect terminology. The application manages a "desa adat" (traditional village) under Bupda (Badan Usaha Padruwen Desa Adat), but static text throughout the interface incorrectly uses "desa" instead of "desa adat". This creates confusion about the nature of the organization and misrepresents the traditional village governance structure.

The bug affects:
- Page titles and breadcrumbs
- Tab labels and navigation items
- Menu items in sidebar and leftmenu
- Section headings and descriptions

This bugfix ensures consistent use of "desa adat" terminology throughout the interface, properly reflecting that this is a traditional village management system, not a regular village (desa) system.

## Bug Analysis

### Current Behavior (Defect)

1.1 WHEN viewing the sejarah page tab navigation THEN the system displays "Sejarah Desa" and "Pengurus Desa" instead of "Sejarah Desa Adat" and "Pengurus Desa Adat"

1.2 WHEN viewing the sidebar menu in resources/views/layouts/sidebar.blade.php THEN the system displays "Badan Usaha Milik Desa" instead of "BUPDA Desa Adat" or "Badan Usaha Padruwen Desa Adat"

1.3 WHEN viewing page breadcrumbs and titles THEN the system displays "Tentang Desa" without specifying "Desa Adat"

1.4 WHEN viewing the lembaga page THEN the system displays "Lembaga Desa Adat" correctly but the page description says "Kelola data lembaga yang ada di desa adat" (lowercase, inconsistent)

1.5 WHEN viewing success messages in TentangDesaController THEN the system displays "Sejarah Desa Adat" correctly in some places but inconsistently uses "desa" vs "desa adat" across different messages

### Expected Behavior (Correct)

2.1 WHEN viewing the sejarah page tab navigation THEN the system SHALL display "Sejarah Desa Adat" and "Pengurus Desa Adat" consistently

2.2 WHEN viewing the sidebar menu in resources/views/layouts/sidebar.blade.php THEN the system SHALL display "BUPDA Desa Adat" or "Badan Usaha Padruwen Desa Adat" instead of "Badan Usaha Milik Desa"

2.3 WHEN viewing page breadcrumbs and titles THEN the system SHALL display "Tentang Desa Adat" to clearly indicate this is about the traditional village

2.4 WHEN viewing the lembaga page THEN the system SHALL display "Lembaga Desa Adat" with consistent capitalization and proper description

2.5 WHEN viewing success messages in TentangDesaController THEN the system SHALL consistently use "Desa Adat" terminology in all user-facing messages

### Unchanged Behavior (Regression Prevention)

3.1 WHEN viewing any page functionality (forms, uploads, CRUD operations) THEN the system SHALL CONTINUE TO function exactly as before with no changes to business logic

3.2 WHEN submitting forms on tentang-desa pages THEN the system SHALL CONTINUE TO save data correctly to settings.json

3.3 WHEN viewing the BUPDA page structure and features THEN the system SHALL CONTINUE TO display all tabs (Informasi, Struktur, Tim, Program, Dokumentasi) and their functionality

3.4 WHEN viewing the sejarah page editor and media uploads THEN the system SHALL CONTINUE TO support CKEditor, video uploads, and file management

3.5 WHEN viewing the lembaga page THEN the system SHALL CONTINUE TO display lembaga cards with logos, descriptions, and gallery previews

3.6 WHEN navigating through menu items THEN the system SHALL CONTINUE TO route to the correct pages without any URL or routing changes
