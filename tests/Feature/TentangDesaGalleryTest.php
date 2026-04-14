<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class TentangDesaGalleryTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        
        // Create test settings file
        $settingsPath = storage_path('app/settings.json');
        if (!File::exists(dirname($settingsPath))) {
            File::makeDirectory(dirname($settingsPath), 0777, true);
        }
        File::put($settingsPath, json_encode([]));
    }

    protected function tearDown(): void
    {
        // Clean up test files
        $galleryPath = public_path('storage/tentang_desa/gallery');
        if (File::exists($galleryPath)) {
            File::deleteDirectory($galleryPath);
        }
        
        parent::tearDown();
    }

    /** @test */
    public function it_can_upload_gallery_image_with_valid_file()
    {
        // Create a fake image
        $file = UploadedFile::fake()->image('test-gallery.jpg', 1200, 600)->size(2048);

        // Attempt to upload
        $response = $this->post(route('tentang_desa.gallery.store'), [
            'gallery_image' => $file,
        ]);

        // Assert redirect back with success message
        $response->assertRedirect();
        $response->assertSessionHas('success', 'Gambar gallery berhasil diunggah!');

        // Assert file was stored
        $galleryPath = public_path('storage/tentang_desa/gallery');
        $this->assertTrue(File::exists($galleryPath));
        
        // Assert filename was added to settings.json
        $settings = json_decode(File::get(storage_path('app/settings.json')), true);
        $this->assertArrayHasKey('gallery_desa', $settings);
        $this->assertCount(1, $settings['gallery_desa']);
        $this->assertStringContainsString('gallery_', $settings['gallery_desa'][0]);
    }

    /** @test */
    public function it_validates_image_is_required()
    {
        $response = $this->post(route('tentang_desa.gallery.store'), [
            'gallery_image' => null,
        ]);

        $response->assertSessionHasErrors('gallery_image');
    }

    /** @test */
    public function it_validates_image_format()
    {
        $file = UploadedFile::fake()->create('document.pdf', 1024);

        $response = $this->post(route('tentang_desa.gallery.store'), [
            'gallery_image' => $file,
        ]);

        $response->assertSessionHasErrors('gallery_image');
    }

    /** @test */
    public function it_validates_image_size()
    {
        // Create a file larger than 5MB (5120KB)
        $file = UploadedFile::fake()->image('large-image.jpg')->size(6000);

        $response = $this->post(route('tentang_desa.gallery.store'), [
            'gallery_image' => $file,
        ]);

        $response->assertSessionHasErrors('gallery_image');
    }

    /** @test */
    public function it_appends_multiple_images_to_gallery_array()
    {
        // Upload first image
        $file1 = UploadedFile::fake()->image('gallery1.jpg')->size(1024);
        $this->post(route('tentang_desa.gallery.store'), [
            'gallery_image' => $file1,
        ]);

        // Upload second image
        $file2 = UploadedFile::fake()->image('gallery2.jpg')->size(1024);
        $this->post(route('tentang_desa.gallery.store'), [
            'gallery_image' => $file2,
        ]);

        // Assert both filenames are in settings.json
        $settings = json_decode(File::get(storage_path('app/settings.json')), true);
        $this->assertArrayHasKey('gallery_desa', $settings);
        $this->assertCount(2, $settings['gallery_desa']);
    }

    /** @test */
    public function it_generates_unique_filename_with_timestamp()
    {
        $file = UploadedFile::fake()->image('test.jpg')->size(1024);

        $this->post(route('tentang_desa.gallery.store'), [
            'gallery_image' => $file,
        ]);

        $settings = json_decode(File::get(storage_path('app/settings.json')), true);
        $filename = $settings['gallery_desa'][0];

        // Assert filename starts with 'gallery_' and contains timestamp
        $this->assertStringStartsWith('gallery_', $filename);
        $this->assertStringContainsString((string)time(), $filename);
    }
}
