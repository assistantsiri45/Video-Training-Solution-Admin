<?php

namespace App\Models;

use App\PackageFeature;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;

class Package extends Model
{
    use SoftDeletes;

    const VALIDITY_IN_MONTHS = 12;


    protected $guarded = ['id'];

    protected $dates = ['expire_at','prebook_launch_date', 'attempt'];

    protected $casts = [
        'price' => 'double',
        'discounted_price' => 'double',
        'discounted_price_expire_at' => 'datetime:Y-m-d',
        'special_price' => 'double',
        'special_price_active_from' => 'datetime:Y-m-d',
        'special_price_expire_at' => 'datetime:Y-m-d',
        'is_special_price_expired',
        'selling_price',
        'is_mini' => 'boolean',
    ];

    protected $appends = [
        'total_duration_formatted',
        'image_url',
        'formatted_attempt',
        'professors',
        'strike_prices',
        'selling_prices',
        'selling_price'
    ];

    const TYPE_CHAPTER_LEVEL = 1;
    const TYPE_SUBJECT_LEVEL = 2;
    const TYPE_CUSTOMIZED = 3;

    const TYPE_MINI = 'mini';
    const TYPE_CRASH = 'crash';

    const PREBOOK = 1;
    const NOT_PREBOOK = 0;

    const TYPE_CHAPTER_LEVEL_VALUE = 'Chapter Level';
    const TYPE_SUBJECT_LEVEL_VALUE = 'Subject Level';
    const TYPE_CUSTOMIZED_VALUE = 'Customized';

    const CATEGORY_VIDEO_ONLY = 1;

    const CATEGORY_VIDEO_ONLY_VALUE = 'video_only';

    /**
     * Set package's category
     *
     * @param string $value
     * @return void
     */

    public function getProfessorsAttribute()
    {
        $packageIDs = [];

        if ($this->type == 2) {
            $packageIDs = SubjectPackage::where('package_id', $this->id)->get()->pluck('chapter_package_id');
        } else {
            $packageIDs[] = $this->id;
        }

        $professorIDs = PackageVideo::whereIn('package_id', $packageIDs)->with('video')->get()->pluck('video.professor_id')->unique();

        $professors = Professor::whereIn('id', $professorIDs)->get();

        return $professors;
    }

    public function setCategoryAttribute($value)
    {
        if ($value == self::CATEGORY_VIDEO_ONLY_VALUE) {
            $this->attributes['category'] = self::CATEGORY_VIDEO_ONLY;
        }
    }
    public function getSpecialPriceAttribute($value) {
        if ($this->special_price_active_from <= Carbon::today() && $this->special_price_expire_at >= Carbon::today()) {
            return $value;
        }

        return 0;
    }

    public function getDiscountedPriceAttribute($value) {
        if (Carbon::parse($this->discounted_price_expire_at)->endOfDay()->isPast()) {
            return 0;
        }

        return $value;
    }

    public function getSellingPriceAttribute() {
        if ($this->is_prebook && !$this->is_prebook_package_launched) {
            return $this->booking_amount;
        }

        if (! empty($this->special_price) && $this->special_price_active_from <= Carbon::today() && $this->special_price_expire_at >= Carbon::today()) return $this->special_price;
        if (! empty($this->discounted_price) && $this->discounted_price_expire_at >= Carbon::today()) return $this->discounted_price;
        return $this->price;
    }
    public function getIsPrebookPackageLaunchedAttribute()
    {
        
        if (! $this->is_prebook) {
            return false;
        }

        return Carbon::parse($this->prebook_launch_date)->startOfDay()->isPast();
    }
    public function getSellingPricesAttribute() {
        if ($this->is_prebook) {
            return $this->booking_amount;
        }

        if (! empty($this->special_price) && $this->special_price_active_from <= Carbon::today() && $this->special_price_expire_at >= Carbon::today()) return $this->special_price;
        if (! empty($this->discounted_price) && $this->discounted_price_expire_at >= Carbon::today()) return $this->discounted_price;
        return $this->price;
    }

    public function getPrebookSellingPriceAttribute()
    {
        if ($this->is_prebook) {
            if (! empty($this->special_price) && $this->special_price_active_from <= Carbon::today() && $this->special_price_expire_at >= Carbon::today()) return $this->special_price;
            if (! empty($this->discounted_price) && $this->discounted_price_expire_at >= Carbon::today()) return $this->discounted_price;
            return $this->price;
        }

        return null;
    }

    public function getStrikePricesAttribute() {
        $prices = collect([
            $this->price,
            $this->discounted_price,
            $this->special_price,
            $this->booking_amount
        ])->filter();

        $prices->pop();

        return $prices->all();
    }

    public function getFormattedAttemptAttribute()
    {
        if ($this->attempt) {
            return date( 'm-Y', strtotime($this->attempt));
        }

        return null;
    }

    public function course() {
        return $this->belongsTo(Course::class);
    }

    public function level() {
        return $this->belongsTo(Level::class);
    }

    public function subject() {
        return $this->belongsTo(Subject::class);
    }

    public function chapter() {
        return $this->belongsTo(Chapter::class);
    }

    public function language() {
        return $this->belongsTo(Language::class);
    }

    public function user() {
        return $this->belongsTo(User::class, 'approved_user_id');
    }

    public function student() {
        return $this->belongsTo(Student::class);
    }

    public function orders(){
        return $this->belongsToMany(Order::class,OrderItem::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function packageVideos()
    {
        return $this->hasMany(PackageVideo::class);
    }

    public function chapterVideos()
    {
        return $this->belongsToMany(Video::class, 'package_videos');
    }

    public function subjectPackage()
    {
        return $this->hasOne(SubjectPackage::class, 'chapter_package_id');
    }

    public function subjectPackages()
    {
        return $this->hasMany(SubjectPackage::class);
    }

    public function features()
    {
        return $this->hasMany(PackageFeature::class);
    }

    public function orderList()
    {
        return OrderItem::with(['order'=>function ($q){

        }]);
    }

    public function studyMaterials()
    {
        return $this->belongsToMany(StudyMaterialV1::class, 'package_study_materials', 'package_id', 'study_material_id');
    }

    public function videos() {
        return Video::select('videos.*')->join('package_videos', function (JoinClause $query) {
            $query->on('package_videos.video_id', '=', 'videos.id');
        })->where('package_videos.package_id', $this->id)->whereNull('videos.deleted_at')->get();
    }

    public function getImageUrlAttribute() {
        if ($this->image) {
            return env('IMAGE_URL').'/packages/'.$this->image;
        }

        return null;
    }

    public function getTotalDurationFormattedAttribute()
    {
        if (!$this->total_duration) {
            return null;
        }

        $durationInSeconds = $this->total_duration;
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

    public function scopeOfCourse($query, $courseId)
    {
        return $query->where('course_id', $courseId);
    }

    public function scopeOfLevel($query, $levelId)
    {
        return $query->where('level_id', $levelId);
    }

    public function scopeOfSubject($query, $subjectId)
    {
        return $query->where('subject_id', $subjectId);
    }

    public function scopeOfChapter($query, $chapterId)
    {
        return $query->where('chapter_id', $chapterId);
    }

    public function scopeSearch($query, $searchText)
    {
        return $query->where('name', 'LIKE', '%'.$searchText.'%');
    }

    public function scopeApproved($query) {
        return $query->where('is_approved', true);
    }

    public function scopeOfNotPreBooked($query)
    {
        if (auth('api')->id()) {
            $prebookedIDs = OrderItem::query()
                ->where('user_id', auth('api')->id())
                ->where('payment_status', OrderItem::PAYMENT_STATUS_PARTIALLY_PAID)
                ->orWhere('payment_status', OrderItem::PAYMENT_STATUS_FULLY_PAID)
                ->where('is_prebook', true)
                ->whereHas('package', function($query) {
                    $query->whereDate('prebook_launch_date', '>', \Illuminate\Support\Carbon::today());
                })
                ->get()->pluck('package_id');

            if ($prebookedIDs) {
                return $query->whereNotIn('id', $prebookedIDs);
            }
        }

        return $query;
    }

    public function scopeOfActive($query, $isActive)
    {
        if (!$isActive) {
            return $query;
        }

        return $query->where(function ($query) {
            $query->where('expiry_type', 1)
                  ->orWhere(function ($query) {
                      $query->where('expiry_type', 2)
                            ->whereDate('expire_at', '>=', \Illuminate\Support\Carbon::today());
                  });
        });
    }

    // public function scopePackageName($query, $id)
    // {
    //     return $query->where('id', $id);
    // }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeOfPublished($query)
    {
        return $query->where('is_approved', true);
    }

    public static function getApplicablePrices($packageID)
    {
        $package = Package::find($packageID);
        $price = $package->price;
        $discountedPrice = null;
        $specialPrice = null;

        if ($package->discounted_price_expire_at && $package->discounted_price_expire_at >= Carbon::today()) {
            $discountedPrice = $package->discounted_price;
        }

        if ($package->special_price_expire_at && $package->special_price_active_from <= Carbon::today() && $package->special_price_expire_at >= Carbon::today()) {
            $specialPrice = $package->special_price;
        }

        return ['price' => $price, 'discounted' => $discountedPrice, 'special' => $specialPrice];
    }

    public function markAsApproved()
    {
        if ($this->is_approved) {
            $this->is_approved = false;
            $this->approved_user_id = null;
        } else {
            $this->is_approved = true;
            $this->approved_user_id = Auth::id();
        }
        $this->published_at = Carbon::now();

        $this->save();
    }

    public function togglePrebook()
    {
        if ($this->is_prebook) {
            $this->is_prebook = false;
        } else {
            $this->is_prebook = true;
        }

        $this->save();
    }

    public static function getFormattedDuration($durationInSeconds)
    {
        $h = floor($durationInSeconds / 3600);
        $resetSeconds = $durationInSeconds - $h * 3600;
        $m = floor($resetSeconds / 60);
        $resetSeconds = $resetSeconds - $m * 60;
        $s = round($resetSeconds, 3);
        $h = str_pad($h, 2, '0', STR_PAD_LEFT);
        $m = str_pad($m, 2, '0', STR_PAD_LEFT);
        $s = str_pad($s, 2, '0', STR_PAD_LEFT);

        if ($h > 0) {
            $duration[] = $h;
        }

        $duration[] = $m;

        $duration[] = $s;

        return implode(':', $duration);
    }

    /***Added BY TE  **/

    public function packagetype(){
        return $this->belongsTo(PackageType::class,'package_type','id');
    }   

    

      public function package_type(){
        return $this->belongsTo(PackageType::class,'package_type','id');
    }   

    /**************TE Ends**************** */
}
