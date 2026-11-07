<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PageController as AdminPageController;
use App\Http\Controllers\Admin\SectionController;
use App\Http\Controllers\Admin\ElementController;
use App\Http\Controllers\Admin\SiteSettingController;
use App\Http\Controllers\Admin\ExpeditionController;
use App\Http\Controllers\EnquiryController;
use App\Http\Controllers\SitemapController;

// Public static & core pages
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
Route::get('/robots.txt', [SitemapController::class, 'robots'])->name('robots');

// Enquiry routes
Route::post('/enquiry', [EnquiryController::class, 'store'])->name('enquiry.store');
Route::get('/enquiry/success/{reference}', [EnquiryController::class, 'success'])->name('enquiry.success');

// Dynamic detail pages
Route::get('/destinations/{slug}', [PageController::class, 'destinationDetail'])->name('destinations.show');
Route::get('/packages/{slug}', [PageController::class, 'packageDetail'])->name('packages.show');
Route::get('/experiences/{slug}', [PageController::class, 'experienceDetail'])->name('experiences.show');
Route::get('/travel-guide/{slug}', [PageController::class, 'articleDetail'])->name('articles.show');

// Authentication routes
require __DIR__.'/auth.php';

// Admin dashboard & management routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::post('pages/reorder', [AdminPageController::class, 'reorder'])->name('pages.reorder');
    Route::post('pages/{page}/duplicate', [AdminPageController::class, 'duplicate'])->name('pages.duplicate');
    Route::resource('pages', AdminPageController::class);
    
    Route::prefix('pages/{page}')->name('pages.')->group(function () {
        Route::resource('sections', SectionController::class)->except(['show']);
        Route::post('sections/reorder', [SectionController::class, 'reorder'])->name('sections.reorder');
        Route::post('sections/{section}/content', [SectionController::class, 'updateContent'])->name('sections.content');
        
        Route::prefix('sections/{section}')->name('sections.')->group(function () {
            Route::resource('elements', ElementController::class)->except(['show']);
            Route::post('elements/reorder', [ElementController::class, 'reorder'])->name('elements.reorder');
        });
    });
    
    Route::resource('site-settings', SiteSettingController::class)->except(['show']);
    Route::resource('expeditions', ExpeditionController::class);
    Route::post('expeditions/reorder', [ExpeditionController::class, 'reorder'])->name('expeditions.reorder');
    
    // Dynamic Content CRUD Modules
    Route::resource('destinations', \App\Http\Controllers\Admin\DestinationController::class);
    Route::resource('experiences', \App\Http\Controllers\Admin\ExperienceController::class);
    Route::resource('blog', \App\Http\Controllers\Admin\BlogPostController::class);
    Route::resource('testimonials', \App\Http\Controllers\Admin\TestimonialController::class);
    Route::resource('gallery', \App\Http\Controllers\Admin\GalleryController::class);
    Route::resource('faqs', \App\Http\Controllers\Admin\FAQController::class);

    Route::get('enquiries', [App\Http\Controllers\Admin\EnquiryController::class, 'index'])->name('enquiries.index');
    Route::get('enquiries/{enquiry}', [App\Http\Controllers\Admin\EnquiryController::class, 'show'])->name('enquiries.show');
    Route::patch('enquiries/{enquiry}/status', [App\Http\Controllers\Admin\EnquiryController::class, 'updateStatus'])->name('enquiries.status');
    Route::post('enquiries/{enquiry}/auto-reply', [App\Http\Controllers\Admin\EnquiryController::class, 'sendAutoReply'])->name('enquiries.auto-reply');
    Route::post('enquiries/{enquiry}/notify-team', [App\Http\Controllers\Admin\EnquiryController::class, 'sendTeamNotification'])->name('enquiries.notify-team');

    // Media Upload for Visual CMS
    Route::post('media/upload', [App\Http\Controllers\Admin\MediaController::class, 'upload'])->name('media.upload');
});

// CMS Dynamic Pages (Catch-all for CMS page slugs, placed last so it doesn't conflict)
Route::get('/{slug}', [PageController::class, 'render'])->where('slug', '^[a-zA-Z0-9_\-]+$')->name('page.render');
