<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
    'title',
    'description',
    'ad_url',
    'banner_image',
    'duration_seconds',
    'is_active',
    ];

    public function getEmbedUrlAttribute()
    {
        $url = $this->ad_url;

        // Convert standard YouTube links to embed
        if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i', $url, $matches)) {
            return "https://www.youtube.com/embed/{$matches[1]}?autoplay=1&controls=0&rel=0";
        }

        // Convert Vimeo links
        if (preg_match('/vimeo\.com\/(\d+)/i', $url, $matches)) {
            return "https://player.vimeo.com/video/{$matches[1]}?autoplay=1";
        }

        return $url;
    }
}
