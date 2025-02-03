<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class Video extends Model
{
    use SoftDeletes;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    protected $appends = ['formatted_duration'];

    const UNPUBLISHED = 1;
    const PUBLISHED = 2;



    public function getImageUrlAttribute() {
        if ($this->thumbnail->url) {
            $img = env('IMAGE_URL').'/video_thumbnails/'.$this->thumbnail;
        }

        return null;
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function level()
    {
        return $this->belongsTo(Level::class);
    }

    public function package_type(){
        return $this->belongsTo(PackageType::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }

    public function professor()
    {
        return $this->belongsTo(Professor::class);
    }

    public function packageVideo() {
        return $this->hasOne(PackageVideo::class);
    }

    public function packageVideos() {
        return $this->hasMany(PackageVideo::class);
    }

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function packages()
    {
        return $this->belongsToMany(Package::class, 'package_videos');
    }

    public function language()
    {
        return $this->belongsTo(Language::class);
    }

    public function getFormattedDurationAttribute()
    {
        if (!$this->duration) {
            return null;
        }

        $durationInSeconds = $this->duration;
        $h = floor($durationInSeconds / 3600);
        $resetSeconds = $durationInSeconds - $h * 3600;
        $m = floor($resetSeconds / 60);
        $resetSeconds = $resetSeconds - $m * 60;
        $s = round($resetSeconds, 3);
        $h = str_pad($h, 2, '0', STR_PAD_LEFT);
        $m = str_pad($m, 2, '0', STR_PAD_LEFT);
        $s = str_pad($s, 2, '0', STR_PAD_LEFT);

      
            $duration[] = $h;
       

        $duration[] = $m;

        $duration[] = $s;

        return implode(':', $duration);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'published_user_id');
    }

    public function publisher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'published_user_id');
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeOfPublished($query)
    {
        return $query->where('is_published', true);
    }

    /**
     * @param Builder $query
     * @param integer $chapterID
     */
    public function scopeOfChapter($query, $chapterID = null)
    {
        $query->where('chapter_id', $chapterID);
    }

    /**
     * @param Builder $query
     * @param integer $professorID
     */
    public function scopeOfProfessor($query, $professorID = null)
    {
        $query->where('professor_id', $professorID);
    }

    public static function formatDuration($durationInSeconds)
    {
        $h = floor($durationInSeconds / 3600);
        $resetSeconds = $durationInSeconds - $h * 3600;
        $m = floor($resetSeconds / 60);
        $resetSeconds = $resetSeconds - $m * 60;
        $s = round($resetSeconds, 3);
        $h = str_pad($h, 2, '0', STR_PAD_LEFT);
        $m = str_pad($m, 2, '0', STR_PAD_LEFT);
        $s = str_pad($s, 2, '0', STR_PAD_LEFT);

        $duration[] = $h;

        $duration[] = $m;

        $duration[] = $s;

        return implode(':', $duration);
    }

    public static function convertDuration($duration)
    {
        $timeArr = array_reverse(explode(':', $duration));
        $seconds = 0;
        foreach ($timeArr as $key => $value) {
            if ($key > 2)
                break;
            $seconds += pow(60, $key) * $value;
        }
        return $seconds;
    }

    public function getSignedUrl(): string
    {
        if (strpos(strtolower($this->url), 'cloudfront') === false) {
            $secret = config('services.jwp.secret');
            $player = config('services.jwp.player');
            $mediaID = $this->media_id;
            $path = "players/$mediaID-$player.js";
            $expires = round((time() + 3600) / 300) * 300;
            $signature = md5("$path:$expires:$secret");
            
            return "https://cdn.jwplayer.com/$path?exp=$expires&sig=$signature";
        }
        return 'aws-s3';
        
    }
    
}
