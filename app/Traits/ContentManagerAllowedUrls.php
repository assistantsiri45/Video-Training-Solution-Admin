<?php

namespace App\Traits;

trait ContentManagerAllowedUrls
{
    protected $contentManagerAllowedUrls = [
        'home',
        'courses',
        'courses/*',
        'levels',
        'levels/*',
        'subjects',
        'subjects/*',
        'chapters',
        'chapters/*',
        'modules',
        'modules/*',
        'study-materials',
        'study-materials/*',
        'videos',
        'videos/*',
        'fetch-unpublished-videos',
        'studio-upload-videos',
        'fetch-published-videos',
        'sections',
        'sections/*',
        'all-packages',
        'all-packages/*',
        'packages',
        'packages/*',
        'package-add-to-archeive',
        'archived-packages',
        'drafted-packages',
        'published-packages',
        'course-levels/*',
        'level-subjects/*',
        'subject-chapters/*',
        'gettypes/*',
        'get-subjects-by-level',
        'chapter-module/*',
        'videos-add-to-archeive',
        'unlink_demo_video',
        'banners',
        'banners/*',
        'validate-phone',
        'professors',
        'professors/*',
        'change-banners-order',
        's3-videos',
        's3-videos/*'
    ];

    protected function contentManagerAllowedUrls()
    {
        return config('app.allowed_urls', $this->contentManagerAllowedUrls);
    }
}
