# Design Document: Tentang Desa Page Redesign

## Overview

This design document specifies the technical implementation for redesigning the public /tentang-desa page. The redesign focuses on four key improvements:

1. **Remove Statistics Cards**: Eliminate the four statistics cards (Banjar, Pura, Tamiu, Usaha) to create a cleaner, more focused page
2. **Redesign Bendesa Section**: Transform the Bendesa section from a compact card layout to a side-by-side layout with photo on the left (50%) and information on the right (50%)
3. **Add Header Image Gallery**: Implement a manageable image gallery at the top of the page with admin upload capabilities and automatic carousel functionality
4. **Create Pura Section with Donation Links**: Add a new section displaying all active temples with donation buttons linking to their respective donation pages

The implementation leverages the existing Laravel + Blade + Alpine.js + Tailwind CSS stack and maintains consistency with the current mobile-first design approach.

## Architecture

### System Components

```
┌─────────────────────────────────────────────────────────────┐
│                    Public Front-End Layer                    │
│  ┌────────────────────────────────────────────────────────┐ │
│  │  tentang_desa.blade.php (View)                         │ │
│  │  - Header Gallery Carousel (new)                       │ │
│  │  - Bendesa Section (redesigned)                        │ │
│  │  - Pura Section (new)                                  │ │
│  │  - Existing Tabs (Sejarah, Lembaga, BUPDA, etc.)      │ │
│  └────────────────────────────────────────────────────────┘ │
└─────────────────────────────────────────────────────────────┘
                            ↕
┌─────────────────────────────────────────────────────────────┐
│                    Controller Layer                          │
│  ┌────────────────────────────────────────────────────────┐ │
│  │  LandingController::tentangDesa()                      │ │
│  │  - Fetch gallery images from settings.json            │ │
│  │  - Fetch Pura data from tb_pura                        │ │
│  │  - Pass data to view                                   │ │
│  └────────────────────────────────────────────────────────┘ │
│  ┌────────────────────────────────────────────────────────┐ │
│  │  TentangDesaController (Admin)                         │ │
│  │  - galleryStore() (new)                                │ │
│  │  - galleryDelete() (new)                               │ │
│  └────────────────────────────────────────────────────────┘ │
└─────────────────────────────────────────────────────────────┘
                            ↕
┌─────────────────────────────────────────────────────────────┐
│                    Data Layer                                │
│  ┌────────────────────────────────────────────────────────┐ │
│  │  settings.json                                         │ │
│  │  - gallery_desa: ["img1.jpg", "img2.jpg", ...]        │ │
│  │  - bendesa_nama, bendesa_foto, bendesa_sambutan, etc. │ │
│  └────────────────────────────────────────────────────────┘ │
│  ┌────────────────────────────────────────────────────────┐ │
│  │  tb_pura (Database)                                    │ │
│  │  - id_pura, nama_pura, lokasi, gambar_pura, aktif     │ │
│  └────────────────────────────────────────────────────────┘ │
└─────────────────────────────────────────────────────────────┘
```

### Data Flow

**Public Page Rendering:**
1. User navigates to `/tentang-desa`
2. `LandingController::tentangDesa()` fetches:
   - Gallery images from `settings.json['gallery_desa']`
   - Bendesa data from `settings.json`
   - Active Pura records from `tb_pura` WHERE `aktif = '1'` ORDER BY `nama_pura`
3. Controller passes data to `tentang_desa.blade.php`
4. View renders with Alpine.js for interactivity

**Admin Gallery Management:**
1. Admin uploads image via form in `sejarah.blade.php`
2. `TentangDesaController::galleryStore()` validates and stores image
3. Image filename appended to `settings.json['gallery_desa']` array
4. Admin deletes image via form
5. `TentangDesaController::galleryDelete()` removes filename from array

## Components and Interfaces

### 1. Header Image Gallery Component

**Location:** Top of page, below hero header

**Structure:**
```html
<div class="px-4 mt-5" x-data="{ currentSlide: 0, totalSlides: {{ count($gallery) }} }">
    <div class="relative rounded-2xl overflow-hidden shadow-lg">
        <!-- Image Container -->
        <div class="relative h-[200px] md:h-[400px] bg-slate-100">
            @foreach($gallery as $index => $image)
            <div x-show="currentSlide === {{ $index }}" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 class="absolute inset-0">
                <img src="{{ asset('storage/tentang_desa/gallery/' . $image) }}" 
                     class="w-full h-full object-cover" 
                     alt="Gallery {{ $index + 1 }}">
            </div>
            @endforeach
        </div>
        
        <!-- Navigation Controls -->
        <button @click="currentSlide = (currentSlide - 1 + totalSlides) % totalSlides"
                class="absolute left-2 top-1/2 -translate-y-1/2 h-10 w-10 bg-white/80 rounded-full">
            <i class="bi bi-chevron-left"></i>
        </button>
        <button @click="currentSlide = (currentSlide + 1) % totalSlides"
                class="absolute right-2 top-1/2 -translate-y-1/2 h-10 w-10 bg-white/80 rounded-full">
            <i class="bi bi-chevron-right"></i>
        </button>
        
        <!-- Indicators -->
        <div class="absolute bottom-3 left-1/2 -translate-x-1/2 flex gap-2">
            @foreach($gallery as $index => $image)
            <button @click="currentSlide = {{ $index }}"
                    :class="currentSlide === {{ $index }} ? 'bg-white' : 'bg-white/50'"
                    class="h-2 w-2 rounded-full transition-all"></button>
            @endforeach
        </div>
    </div>
</div>

<script>
    // Auto-advance every 5 seconds
    setInterval(() => {
        Alpine.store('gallery').next();
    }, 5000);
</script>
```

**Behavior:**
- Display single image if only one exists
- Display carousel with navigation if multiple images exist
- Auto-advance every 5 seconds
- Manual navigation via left/right buttons
- Indicator dots show current position
- Responsive heights: 200px mobile, 400px desktop
- Hide entire section if gallery is empty

### 2. Redesigned Bendesa Section Component

**Location:** Below header gallery (or below hero if no gallery)

**Structure:**
```html
<div class="px-4 mt-5">
    <div class="bg-gradient-to-br from-[#00a6eb]/5 to-white border border-[#00a6eb]/20 rounded-2xl overflow-hidden shadow-sm">
        <div class="flex flex-col md:flex-row">
            <!-- Photo Section (50% on desktop) -->
            <div class="w-full md:w-1/2 h-64 md:h-96 bg-slate-100 relative overflow-hidden">
                @if(!empty($bendesa['foto']))
                    <img src="{{ asset('storage/tentang_desa/pengurus/' . $bendesa['foto']) }}" 
                         class="w-full h-full object-cover" 
                         alt="{{ $bendesa['nama'] }}">
                @else
                    <div class="w-full h-full flex items-center justify-center">
                        <i class="bi bi-person-fill text-6xl text-slate-300"></i>
                    </div>
                @endif
            </div>
            
            <!-- Information Section (50% on desktop) -->
            <div class="w-full md:w-1/2 p-6 md:p-8 flex flex-col justify-center">
                <p class="text-[9px] font-black text-[#00a6eb] uppercase tracking-widest mb-3">
                    Kata Sambutan
                </p>
                
                @if(!empty($bendesa['nama']))
                <h3 class="text-xl md:text-2xl font-black text-slate-800 leading-tight mb-2">
                    {{ $bendesa['nama'] }}
                </h3>
                <span class="inline-block bg-[#00a6eb] text-white text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wide mb-3">
                    Bendesa Adat
                </span>
                @endif
                
                @if(!empty($bendesa['no_telp']))
                <p class="text-sm text-slate-500 mb-4">
                    <i class="bi bi-telephone mr-2 text-[#00a6eb]"></i>{{ $bendesa['no_telp'] }}
                </p>
                @endif
                
                @if(!empty($bendesa['sambutan']))
                <div class="text-sm text-slate-600 leading-relaxed italic border-l-4 border-[#00a6eb] pl-4">
                    <i class="bi bi-quote text-[#00a6eb] text-2xl mr-1"></i>
                    {!! nl2br(e(strip_tags($bendesa['sambutan']))) !!}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
```

**Responsive Behavior:**
- Desktop (≥768px): Side-by-side layout, 50/50 split
- Mobile (<768px): Stacked vertically, photo on top
- Photo maintains aspect ratio without distortion
- Information section vertically centered on desktop

### 3. Pura Section Component

**Location:** Bottom of page, after all tabs

**Structure:**
```html
<div class="px-4 mt-8 mb-6">
    <div class="flex items-center gap-2 mb-4">
        <div class="h-8 w-8 bg-[#00a6eb]/10 rounded-lg flex items-center justify-center">
            <i class="bi bi-building text-[#00a6eb]"></i>
        </div>
        <h2 class="text-base font-black text-slate-800 uppercase tracking-widest">
            Pura di Desa Adat
        </h2>
    </div>
    
    @if(count($puraList) > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($puraList as $pura)
        <div class="bg-white border border-slate-100 rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-shadow">
            <!-- Pura Image -->
            <div class="h-40 bg-slate-100 overflow-hidden relative">
                @if(!empty($pura->gambar_pura))
                    <img src="{{ asset('storage/pura/' . $pura->gambar_pura) }}" 
                         class="w-full h-full object-cover" 
                         alt="{{ $pura->nama_pura }}">
                @else
                    <div class="w-full h-full flex items-center justify-center">
                        <i class="bi bi-building text-4xl text-slate-300"></i>
                    </div>
                @endif
                <!-- Temple Icon Overlay -->
                <div class="absolute top-3 right-3 h-10 w-10 bg-white/90 rounded-full flex items-center justify-center shadow-lg">
                    <span class="text-xl">🕉️</span>
                </div>
            </div>
            
            <!-- Pura Info -->
            <div class="p-4">
                <h3 class="text-sm font-black text-slate-800 leading-tight mb-2">
                    {{ $pura->nama_pura }}
                </h3>
                @if(!empty($pura->lokasi))
                <p class="text-xs text-slate-500 mb-3">
                    <i class="bi bi-geo-alt mr-1 text-[#00a6eb]"></i>{{ $pura->lokasi }}
                </p>
                @endif
                
                <!-- Donation Button -->
                <a href="{{ route('public.punia.pura', ['id' => $pura->id_pura]) }}" 
                   class="block w-full bg-[#00a6eb] hover:bg-[#0090d0] text-white text-center text-sm font-bold py-2.5 rounded-xl transition-colors">
                    <i class="bi bi-heart-fill mr-1"></i>Donasi
                </a>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="bg-slate-50 border border-dashed border-slate-200 rounded-2xl p-8 text-center">
        <i class="bi bi-building text-3xl text-slate-300 block mb-2"></i>
        <p class="text-sm font-bold text-slate-400">Belum ada data pura</p>
    </div>
    @endif
</div>
```

**Responsive Grid:**
- Mobile (<768px): 1 column
- Tablet (768px-1023px): 2 columns
- Desktop (≥1024px): 3 columns

**Card Features:**
- Temple image with fallback icon
- Om symbol (🕉️) overlay badge
- Temple name and location
- Prominent donation button
- Hover effect for interactivity

### 4. Admin Gallery Management Interface

**Location:** `sejarah.blade.php` - New section in Sejarah tab

**Structure:**
```html
<div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
    <h3 class="text-sm font-black text-slate-700 flex items-center gap-2 mb-5 pb-3 border-b border-slate-100">
        <i class="bi bi-images text-primary-light"></i> Header Image Gallery
    </h3>
    
    <!-- Existing Images -->
    @php $gallery = $settings['gallery_desa'] ?? []; @endphp
    @if(count($gallery) > 0)
    <div class="grid grid-cols-2 md:grid-cols-3 gap-3 mb-5">
        @foreach($gallery as $index => $image)
        <div class="relative group rounded-xl overflow-hidden border border-slate-200">
            <img src="{{ asset('storage/tentang_desa/gallery/' . $image) }}" 
                 class="w-full h-32 object-cover" 
                 alt="Gallery {{ $index + 1 }}">
            <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                <form action="{{ url('administrator/tentang-desa/sejarah/gallery/delete') }}" 
                      method="POST" 
                      onsubmit="return confirm('Hapus gambar ini?')">
                    @csrf
                    <input type="hidden" name="filename" value="{{ $image }}">
                    <button type="submit" 
                            class="h-10 w-10 bg-rose-500 hover:bg-rose-600 text-white rounded-full transition-colors">
                        <i class="bi bi-trash3"></i>
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
    @endif
    
    <!-- Upload Form -->
    <form action="{{ url('administrator/tentang-desa/sejarah/gallery/store') }}" 
          method="POST" 
          enctype="multipart/form-data" 
          class="space-y-3">
        @csrf
        <div>
            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">
                Upload Gambar Gallery <span class="text-rose-500">*</span>
            </label>
            <input type="file" 
                   name="gallery_image" 
                   required 
                   accept="image/jpeg,image/png,image/jpg"
                   class="block w-full text-sm text-slate-500 border border-slate-200 rounded-xl cursor-pointer bg-slate-50 file:mr-4 file:py-2.5 file:px-4 file:rounded-l-xl file:border-0 file:text-xs file:font-semibold file:bg-primary-light file:text-white hover:file:bg-primary-dark">
            <p class="text-[10px] text-slate-400 mt-1">Format: JPG, PNG. Maks 5MB. Rekomendasi: 1200x600px (landscape)</p>
        </div>
        <div class="flex justify-end">
            <button type="submit" 
                    class="bg-primary-light hover:bg-primary-dark text-white px-6 py-2.5 rounded-xl font-bold text-sm transition-all">
                <i class="bi bi-cloud-arrow-up mr-1"></i>Upload Gambar
            </button>
        </div>
    </form>
</div>
```

## Data Models

### Settings JSON Structure

**File:** `storage/app/settings.json`

**New Field:**
```json
{
  "gallery_desa": [
    "gallery_1234567890.jpg",
    "gallery_1234567891.jpg",
    "gallery_1234567892.jpg"
  ],
  "bendesa_nama": "I Wayan Sudana",
  "bendesa_foto": "bendesa_1234567890.jpg",
  "bendesa_sambutan": "Selamat datang di website Desa Adat...",
  "bendesa_no_telp": "081234567890"
}
```

### Pura Model

**Table:** `tb_pura`

**Relevant Fields:**
- `id_pura` (INT, PRIMARY KEY)
- `nama_pura` (VARCHAR)
- `lokasi` (VARCHAR)
- `gambar_pura` (VARCHAR)
- `aktif` (ENUM: '0', '1')

**Query:**
```php
$puraList = Pura::where('aktif', '1')
    ->orderBy('nama_pura', 'asc')
    ->get();
```

## Error Handling

### File Upload Validation

**Gallery Image Upload:**
```php
$request->validate([
    'gallery_image' => 'required|image|mimes:jpeg,png,jpg|max:5120', // 5MB
]);
```

**Error Scenarios:**
1. **File too large:** Display error message "Ukuran file maksimal 5MB"
2. **Invalid format:** Display error message "Format file harus JPG, PNG, atau JPEG"
3. **Upload failed:** Display error message "Gagal mengunggah gambar. Silakan coba lagi"
4. **Storage permission error:** Log error and display "Terjadi kesalahan sistem"

### Data Retrieval

**Empty Gallery:**
- Condition: `empty($gallery)` or `count($gallery) === 0`
- Action: Hide gallery section entirely (no placeholder)

**Empty Pura List:**
- Condition: `count($puraList) === 0`
- Action: Display empty state message "Belum ada data pura"

**Missing Bendesa Data:**
- Condition: `empty($bendesa['nama'])` and `empty($bendesa['sambutan'])`
- Action: Hide Bendesa section entirely

### Image Display Fallbacks

**Gallery Image Missing:**
```php
@if(file_exists(public_path('storage/tentang_desa/gallery/' . $image)))
    <img src="{{ asset('storage/tentang_desa/gallery/' . $image) }}" ...>
@else
    <div class="w-full h-full bg-slate-200 flex items-center justify-center">
        <i class="bi bi-image text-4xl text-slate-400"></i>
    </div>
@endif
```

**Pura Image Missing:**
- Display placeholder icon (bi-building) in slate-300 color
- Maintain card layout consistency

## Testing Strategy

This feature involves UI rendering, file uploads, and data display. The testing approach focuses on integration tests and manual UI testing rather than property-based testing.

### Integration Tests

**Test Suite:** `tests/Feature/TentangDesaPageTest.php`

1. **Gallery Management Tests:**
   - Test gallery image upload with valid file
   - Test gallery image upload with invalid file (wrong format, too large)
   - Test gallery image deletion
   - Test gallery display with 0, 1, and multiple images

2. **Bendesa Section Tests:**
   - Test Bendesa section display with complete data
   - Test Bendesa section display with partial data
   - Test Bendesa section hidden when no data exists
   - Test responsive layout rendering

3. **Pura Section Tests:**
   - Test Pura list display with active temples
   - Test Pura list empty state
   - Test donation link generation
   - Test Pura ordering (alphabetical)

4. **Statistics Removal Tests:**
   - Verify statistics cards are not rendered
   - Verify grid container is removed

### Manual UI Testing

**Responsive Design Testing:**
- Test on mobile (320px, 375px, 414px widths)
- Test on tablet (768px, 1024px widths)
- Test on desktop (1280px, 1920px widths)
- Verify Bendesa section layout switches at 768px breakpoint
- Verify Pura grid columns adjust at breakpoints

**Gallery Carousel Testing:**
- Verify auto-advance works (5-second interval)
- Verify manual navigation (left/right buttons)
- Verify indicator dots update correctly
- Verify transitions are smooth
- Test with 1, 2, 5, and 10 images

**Admin Interface Testing:**
- Upload various image sizes and formats
- Delete images and verify removal
- Test concurrent uploads
- Verify file storage location

### Browser Compatibility

Test on:
- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Mobile Safari (iOS)
- Chrome Mobile (Android)

### Accessibility Testing

- Verify all images have alt text
- Verify buttons have proper labels
- Verify keyboard navigation works
- Verify touch targets are minimum 44px on mobile
- Verify color contrast meets WCAG AA standards

## Implementation Notes

### File Storage Structure

```
public/storage/tentang_desa/
├── gallery/
│   ├── gallery_1234567890.jpg
│   ├── gallery_1234567891.jpg
│   └── gallery_1234567892.jpg
├── pengurus/
│   ├── bendesa_1234567890.jpg
│   └── struktur_desa_1234567890.jpg
└── sejarah/
    └── (existing files)
```

### Route Additions

**Admin Routes (web.php):**
```php
Route::post('/administrator/tentang-desa/sejarah/gallery/store', 
    [TentangDesaController::class, 'galleryStore'])->name('admin.tentang-desa.gallery.store');

Route::post('/administrator/tentang-desa/sejarah/gallery/delete', 
    [TentangDesaController::class, 'galleryDelete'])->name('admin.tentang-desa.gallery.delete');
```

### Controller Methods

**TentangDesaController.php:**

```php
public function galleryStore(Request $request)
{
    $request->validate([
        'gallery_image' => 'required|image|mimes:jpeg,png,jpg|max:5120',
    ]);

    $file = $request->file('gallery_image');
    $fileName = 'gallery_' . time() . '_' . str_shuffle('abcde') . '.' . $file->getClientOriginalExtension();
    $dest = public_path('storage/tentang_desa/gallery');
    
    if (!File::isDirectory($dest)) {
        File::makeDirectory($dest, 0777, true, true);
    }
    
    $file->move($dest, $fileName);

    $settings = $this->getSettings();
    $gallery = $settings['gallery_desa'] ?? [];
    $gallery[] = $fileName;
    $settings['gallery_desa'] = $gallery;
    $this->saveSettings($settings);

    return redirect()->back()->with('success', 'Gambar gallery berhasil diunggah!');
}

public function galleryDelete(Request $request)
{
    $request->validate(['filename' => 'required|string']);
    
    $settings = $this->getSettings();
    $gallery = $settings['gallery_desa'] ?? [];
    $gallery = array_values(array_filter($gallery, fn($img) => $img !== $request->filename));
    $settings['gallery_desa'] = $gallery;
    $this->saveSettings($settings);
    
    // Delete physical file
    $filePath = public_path('storage/tentang_desa/gallery/' . $request->filename);
    if (File::exists($filePath)) {
        File::delete($filePath);
    }

    return redirect()->back()->with('success', 'Gambar gallery berhasil dihapus!');
}
```

**LandingController.php:**

```php
public function tentangDesa()
{
    // Existing code...
    
    // Add gallery data
    $settings = $this->getSettings();
    $gallery = $settings['gallery_desa'] ?? [];
    
    // Add Pura list for new section
    $puraList = Pura::where('aktif', '1')
        ->orderBy('nama_pura', 'asc')
        ->get();
    
    return view('front.pages.tentang_desa', compact(
        // existing variables...
        'gallery',
        'puraList'
    ));
}
```

### Performance Considerations

**Image Optimization:**
- Recommend admins upload images at 1200x600px
- Consider adding image compression on upload (optional enhancement)
- Use lazy loading for gallery images: `loading="lazy"`

**Database Query Optimization:**
- Pura query is simple and indexed on `aktif` field
- Consider caching Pura list if it changes infrequently

**Alpine.js State Management:**
- Gallery carousel state is lightweight (single integer)
- No performance concerns with current implementation

### Migration Path

**Deployment Steps:**
1. Create `public/storage/tentang_desa/gallery` directory
2. Deploy updated controller methods
3. Deploy updated view files
4. Test gallery upload functionality
5. Test public page rendering
6. Verify responsive behavior

**Rollback Plan:**
- Revert view files to previous version
- Remove gallery routes
- Gallery images remain in storage (no data loss)

### Future Enhancements

**Potential Improvements:**
1. **Gallery Image Reordering:** Allow admins to drag-and-drop reorder gallery images
2. **Image Captions:** Add optional captions to gallery images
3. **Pura Filtering:** Add filter/search functionality to Pura section
4. **Lazy Loading:** Implement intersection observer for Pura cards
5. **Image Compression:** Automatic image optimization on upload
6. **Gallery Lightbox:** Click to view full-size images in modal

## Summary

This design provides a comprehensive technical specification for redesigning the tentang-desa page with four key improvements: removing statistics cards, redesigning the Bendesa section with a side-by-side layout, adding a manageable header image gallery with carousel functionality, and creating a new Pura section with donation links. The implementation maintains consistency with the existing Laravel + Blade + Alpine.js + Tailwind CSS stack and follows mobile-first responsive design principles.
