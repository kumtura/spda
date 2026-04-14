# Implementation Plan: Tentang Desa Page Redesign

## Overview

This implementation plan converts the design into discrete coding tasks for redesigning the /tentang-desa public page. The implementation will remove statistics cards, redesign the Bendesa section with a side-by-side layout, add a manageable header image gallery with carousel functionality, and create a new Pura section with donation links. All tasks build incrementally using the existing Laravel + Blade + Alpine.js + Tailwind CSS stack.

## Tasks

- [x] 1. Set up gallery storage structure and routes
  - Create the directory `public/storage/tentang_desa/gallery` if it doesn't exist
  - Add two new routes in `routes/web.php` for gallery management: `galleryStore` and `galleryDelete`
  - Ensure routes are protected with admin middleware
  - _Requirements: 3.3, 3.6_

- [ ] 2. Implement gallery management controller methods
  - [x] 2.1 Implement `galleryStore()` method in `TentangDesaController`
    - Add file validation (image, mimes:jpeg,png,jpg, max:5120)
    - Generate unique filename with timestamp and random string
    - Store uploaded file in `public/storage/tentang_desa/gallery`
    - Append filename to `settings.json['gallery_desa']` array
    - Return success message
    - _Requirements: 3.2, 3.3, 3.4, 3.5, 3.6_
  
  - [x] 2.2 Implement `galleryDelete()` method in `TentangDesaController`
    - Validate filename parameter
    - Remove filename from `settings.json['gallery_desa']` array
    - Delete physical file from storage
    - Return success message
    - _Requirements: 3.7, 3.8_

- [x] 3. Update LandingController to fetch gallery and Pura data
  - Modify `tentangDesa()` method to fetch `gallery_desa` from settings.json
  - Add query to fetch active Pura records WHERE `aktif = '1'` ORDER BY `nama_pura`
  - Pass `$gallery` and `$puraList` variables to the view
  - _Requirements: 4.1, 5.1, 5.8, 5.9_

- [ ] 4. Create admin gallery management interface
  - [x] 4.1 Add gallery management section to `sejarah.blade.php`
    - Add section header "Header Image Gallery"
    - Display existing gallery images in a responsive grid (2 columns mobile, 3 columns desktop)
    - Add hover overlay with delete button for each image
    - Add upload form with file input accepting jpeg,png,jpg
    - Add validation message display for file size and format
    - Style with Tailwind CSS matching existing admin interface
    - _Requirements: 3.1, 3.2, 3.7_
  
  - [ ]* 4.2 Write integration tests for gallery management
    - Test gallery image upload with valid file
    - Test gallery image upload with invalid format
    - Test gallery image upload exceeding size limit
    - Test gallery image deletion
    - _Requirements: 3.2, 3.4, 3.5, 3.7_

- [x] 5. Remove statistics cards from public page
  - Open `tentang_desa.blade.php` view file
  - Remove the statistics cards section displaying Banjar, Pura, Tamiu, and Usaha counts
  - Remove the grid container that held the statistics cards
  - _Requirements: 1.1, 1.2, 1.3, 1.4, 1.5_

- [ ] 6. Implement header gallery carousel on public page
  - [x] 6.1 Create gallery carousel component in `tentang_desa.blade.php`
    - Add Alpine.js data with `currentSlide` and `totalSlides` state
    - Create image container with responsive heights (200px mobile, 400px desktop)
    - Implement foreach loop to display gallery images with transitions
    - Add left/right navigation buttons with click handlers
    - Add indicator dots at bottom with click handlers
    - Add auto-advance script with 5-second interval
    - Hide entire section when gallery is empty
    - _Requirements: 4.1, 4.2, 4.3, 4.4, 4.5, 4.6, 4.7, 4.8_
  
  - [ ]* 6.2 Write integration tests for gallery display
    - Test gallery display with 0 images (section hidden)
    - Test gallery display with 1 image (no carousel controls)
    - Test gallery display with multiple images (carousel active)
    - _Requirements: 4.2, 4.3, 4.8_

- [x] 7. Checkpoint - Verify gallery functionality
  - Ensure all tests pass, ask the user if questions arise.

- [ ] 8. Redesign Bendesa section with side-by-side layout
  - [x] 8.1 Implement new Bendesa section layout in `tentang_desa.blade.php`
    - Replace existing Bendesa card with new side-by-side layout
    - Create flex container with responsive behavior (stacked mobile, side-by-side desktop)
    - Add photo section (50% width desktop, full width mobile) with 64/96 height
    - Add information section (50% width desktop, full width mobile) with vertical centering
    - Display Bendesa name from `$bendesa['nama']`
    - Display "Bendesa Adat" title badge
    - Display phone number from `$bendesa['no_telp']` with phone icon
    - Display greeting message from `$bendesa['sambutan']` with quote styling
    - Add fallback icon when photo is missing
    - Hide section when both name and greeting are empty
    - _Requirements: 2.1, 2.2, 2.3, 2.4, 2.5, 2.6, 2.7, 2.8, 2.9_
  
  - [ ]* 8.2 Write integration tests for Bendesa section
    - Test Bendesa section display with complete data
    - Test Bendesa section display with partial data
    - Test Bendesa section hidden when no data exists
    - _Requirements: 2.3, 2.4, 2.5, 2.6, 2.7_

- [ ] 9. Implement Pura section with donation links
  - [ ] 9.1 Create Pura section component in `tentang_desa.blade.php`
    - Add section header with temple icon and "Pura di Desa Adat" title
    - Create responsive grid (1 column mobile, 2 columns tablet, 3 columns desktop)
    - Implement foreach loop for `$puraList`
    - Display Pura image with 40-height container and object-cover
    - Add Om symbol (🕉️) overlay badge in top-right corner
    - Display Pura name from `$pura->nama_pura`
    - Display Pura location from `$pura->lokasi` with location icon
    - Add donation button linking to `route('public.punia.pura', ['id' => $pura->id_pura])`
    - Add fallback icon when Pura image is missing
    - Display empty state message when no active Pura exists
    - _Requirements: 5.1, 5.2, 5.3, 5.4, 5.5, 5.6, 5.7, 5.10_
  
  - [ ]* 9.2 Write integration tests for Pura section
    - Test Pura list display with active temples
    - Test Pura list empty state
    - Test donation link generation
    - Test Pura ordering (alphabetical by nama_pura)
    - _Requirements: 5.7, 5.8, 5.9, 5.10_

- [x] 10. Implement responsive design breakpoints
  - Verify Bendesa section uses `md:flex-row` for side-by-side layout at 768px+
  - Verify Bendesa photo uses `md:w-1/2` for 50% width at 768px+
  - Verify Pura grid uses `md:grid-cols-2` at 768px+ and `lg:grid-cols-3` at 1024px+
  - Verify gallery height uses `md:h-[400px]` at 768px+
  - Verify all touch targets are minimum 44px (h-10 w-10 for buttons)
  - Verify text remains readable with minimum text-sm (14px) on mobile
  - _Requirements: 6.1, 6.2, 6.3, 6.4, 6.5, 6.6, 6.7_

- [x] 11. Checkpoint - Verify all sections render correctly
  - Ensure all tests pass, ask the user if questions arise.

- [x] 12. Verify existing functionality remains intact
  - Verify Sejarah tab content displays correctly
  - Verify Lembaga tab content displays correctly
  - Verify BUPDA tab content displays correctly
  - Verify Produk Hukum tab content displays correctly
  - Verify Banjar & Pura tab content displays correctly
  - Verify tab navigation works correctly
  - Verify hero header with breadcrumb displays correctly
  - Verify foto struktur desa displays correctly
  - _Requirements: 7.1, 7.2, 7.3, 7.4, 7.5, 7.6, 7.7, 7.8_

- [ ] 13. Final integration and testing
  - [x] 13.1 Test complete page flow
    - Test admin uploads multiple gallery images
    - Test public page displays gallery carousel with auto-advance
    - Test Bendesa section displays with side-by-side layout on desktop
    - Test Pura section displays with donation links
    - Test responsive behavior on mobile, tablet, and desktop
    - _Requirements: 3.2, 4.4, 2.1, 5.6, 6.1_
  
  - [ ]* 13.2 Write end-to-end integration tests
    - Test complete workflow from gallery upload to public display
    - Test responsive layout transitions at breakpoints
    - _Requirements: 6.1, 6.2, 6.3, 6.4, 6.5_

- [x] 14. Final checkpoint - Ensure all tests pass
  - Ensure all tests pass, ask the user if questions arise.

## Notes

- Tasks marked with `*` are optional and can be skipped for faster MVP
- Each task references specific requirements for traceability
- The implementation uses existing Laravel + Blade + Alpine.js + Tailwind CSS stack
- All changes maintain mobile-first responsive design principles
- Gallery images are stored in `public/storage/tentang_desa/gallery`
- Settings are managed via `storage/app/settings.json`
- Pura data is fetched from `tb_pura` table with `aktif = '1'` filter
