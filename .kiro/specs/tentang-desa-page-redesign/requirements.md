# Requirements Document

## Introduction

This document specifies the requirements for redesigning the public /tentang-desa page. The redesign removes unnecessary statistics cards, enhances the Bendesa section with improved layout, adds a manageable header image gallery, and creates a Pura section with donation links. The goal is to create a welcoming page that showcases the traditional village leadership, visual beauty, and temple information with donation capabilities.

## Glossary

- **Public_Page**: The front-end view accessible to website visitors at /tentang-desa
- **Admin_Panel**: The administrative interface for managing content at /administrator/tentang-desa/sejarah
- **Bendesa**: The traditional village head (Bendesa Adat)
- **Pura**: Hindu temple
- **Settings_File**: The JSON file at storage/app/settings.json containing configuration data
- **Gallery_Field**: The new field "gallery_desa" for storing header images
- **Statistics_Cards**: The four cards showing counts for Banjar, Pura, Tamiu, and Usaha
- **Bendesa_Section**: The section displaying Bendesa photo, name, title, phone, and greeting
- **Header_Gallery**: The image slider/carousel at the top of the page
- **Pura_Section**: The section displaying list of temples with donation links
- **Donation_Link**: A clickable button/link directing to a specific Pura's donation page
- **Responsive_Layout**: A layout that adapts to different screen sizes (mobile, tablet, desktop)

## Requirements

### Requirement 1: Remove Statistics Cards

**User Story:** As a visitor, I want to see a cleaner page without unnecessary statistics, so that I can focus on the important content about the village.

#### Acceptance Criteria

1. THE Public_Page SHALL NOT display the Statistics_Cards for Banjar count
2. THE Public_Page SHALL NOT display the Statistics_Cards for Pura count
3. THE Public_Page SHALL NOT display the Statistics_Cards for Tamiu count
4. THE Public_Page SHALL NOT display the Statistics_Cards for Usaha count
5. THE Public_Page SHALL remove the grid container that previously held the Statistics_Cards

### Requirement 2: Redesign Bendesa Section Layout

**User Story:** As a visitor, I want to see the Bendesa's information in a clear side-by-side layout, so that I can easily read the greeting and see the village leader.

#### Acceptance Criteria

1. THE Bendesa_Section SHALL display the Bendesa photo occupying 50% of the section width on desktop screens
2. THE Bendesa_Section SHALL display the Bendesa information occupying 50% of the section width on desktop screens
3. THE Bendesa_Section SHALL display the Bendesa name from Settings_File field "bendesa_nama"
4. THE Bendesa_Section SHALL display the title "Bendesa Adat" below the name
5. THE Bendesa_Section SHALL display the phone number from Settings_File field "bendesa_no_telp"
6. THE Bendesa_Section SHALL display the greeting message from Settings_File field "bendesa_sambutan"
7. THE Bendesa_Section SHALL display the photo from Settings_File field "bendesa_foto"
8. WHEN the screen width is less than 768 pixels, THE Bendesa_Section SHALL stack the photo and information vertically
9. THE Bendesa_Section SHALL maintain the photo aspect ratio without distortion

### Requirement 3: Add Header Image Gallery Management

**User Story:** As an administrator, I want to upload and manage header images for the tentang-desa page, so that visitors can see beautiful images of the village.

#### Acceptance Criteria

1. THE Admin_Panel SHALL provide a new field labeled "Header Image Gallery" in the Sejarah Desa tab
2. THE Admin_Panel SHALL allow uploading multiple images to the Gallery_Field
3. WHEN an administrator uploads an image, THE Admin_Panel SHALL store the image filename in Settings_File under the key "gallery_desa"
4. THE Admin_Panel SHALL validate uploaded images are in JPEG, PNG, or JPG format
5. THE Admin_Panel SHALL validate uploaded images do not exceed 5MB in size
6. THE Admin_Panel SHALL store uploaded images in the directory "public/storage/tentang_desa/gallery"
7. THE Admin_Panel SHALL allow administrators to delete images from the Gallery_Field
8. WHEN an administrator deletes an image, THE Admin_Panel SHALL remove the filename from Settings_File "gallery_desa" array

### Requirement 4: Display Header Image Gallery on Public Page

**User Story:** As a visitor, I want to see a beautiful image gallery at the top of the page, so that I can appreciate the visual beauty of the village.

#### Acceptance Criteria

1. THE Public_Page SHALL display the Header_Gallery at the top of the page below the hero header
2. WHEN the Gallery_Field contains one image, THE Public_Page SHALL display that single image
3. WHEN the Gallery_Field contains multiple images, THE Public_Page SHALL display the images as a slider or carousel
4. THE Header_Gallery SHALL automatically transition between images every 5 seconds
5. THE Header_Gallery SHALL provide navigation controls for manual image browsing
6. THE Header_Gallery SHALL display images with a minimum height of 200 pixels on mobile devices
7. THE Header_Gallery SHALL display images with a minimum height of 400 pixels on desktop devices
8. WHEN the Gallery_Field is empty, THE Public_Page SHALL NOT display the Header_Gallery section

### Requirement 5: Create Pura Section with Donation Links

**User Story:** As a visitor, I want to see a list of temples with donation options, so that I can learn about the temples and make donations.

#### Acceptance Criteria

1. THE Public_Page SHALL display the Pura_Section at the bottom of the page
2. THE Pura_Section SHALL display a temple icon (🕉️ or Hindu temple icon) for each Pura
3. THE Pura_Section SHALL display the Pura name from the database field "nama_pura"
4. THE Pura_Section SHALL display the Pura image from the database field "gambar_pura"
5. THE Pura_Section SHALL display the Pura location from the database field "lokasi"
6. THE Pura_Section SHALL display a Donation_Link labeled "Donasi" for each Pura
7. WHEN a visitor clicks a Donation_Link, THE Public_Page SHALL navigate to the donation page for that specific Pura
8. THE Pura_Section SHALL retrieve Pura data from the "tb_pura" table WHERE "aktif" equals '1'
9. THE Pura_Section SHALL order Pura entries alphabetically by "nama_pura"
10. WHEN no active Pura exists, THE Pura_Section SHALL display a message "Belum ada data pura"

### Requirement 6: Ensure Responsive Design

**User Story:** As a mobile visitor, I want the page to look good on my phone, so that I can easily read and navigate the content.

#### Acceptance Criteria

1. THE Public_Page SHALL display the Responsive_Layout that adapts to screen widths from 320 pixels to 1920 pixels
2. WHEN the screen width is less than 768 pixels, THE Public_Page SHALL display the Bendesa_Section in a single column layout
3. WHEN the screen width is less than 768 pixels, THE Pura_Section SHALL display one Pura card per row
4. WHEN the screen width is 768 pixels or greater, THE Pura_Section SHALL display two Pura cards per row
5. WHEN the screen width is 1024 pixels or greater, THE Pura_Section SHALL display three Pura cards per row
6. THE Public_Page SHALL ensure all text remains readable at minimum font size of 12 pixels on mobile devices
7. THE Public_Page SHALL ensure all interactive elements have a minimum touch target size of 44 pixels on mobile devices

### Requirement 7: Maintain Existing Functionality

**User Story:** As a visitor, I want all existing features to continue working, so that I can access all the information I need.

#### Acceptance Criteria

1. THE Public_Page SHALL continue to display the Sejarah (history) content in the Sejarah tab
2. THE Public_Page SHALL continue to display the Lembaga (organizations) content in the Lembaga tab
3. THE Public_Page SHALL continue to display the BUPDA content in the BUPDA tab
4. THE Public_Page SHALL continue to display the Produk Hukum (legal documents) in the Hukum tab
5. THE Public_Page SHALL continue to display the Banjar list in the Banjar & Pura tab
6. THE Public_Page SHALL maintain the tab navigation functionality
7. THE Public_Page SHALL maintain the hero header with breadcrumb navigation
8. THE Public_Page SHALL maintain the foto struktur desa (organizational structure photo) display
