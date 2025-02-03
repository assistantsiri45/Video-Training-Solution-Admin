<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use App\Models\Package;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Builder;

class FeedbackController extends Controller
{
    public function index(Builder  $builder)
    {
        if (request()->ajax()) {
            $query = Package::query();

            return DataTables::of($query)
                ->filter(function ($query) {
                    $query->where('is_approved', true);
                    $query->latest();

                    if (request()->filled('filter.search')) {
                        $query->where('name', 'like', '%' . request()->input('filter.search') . '%');
                    }
                })
                ->addColumn('1_star', function ($query) {
                    return $query->orderItems()->where('rating', 1)->count();
                })
                ->addColumn('2_star', function ($query) {
                    return $query->orderItems()->where('rating', 2)->count();
                })
                ->addColumn('3_star', function ($query) {
                    return $query->orderItems()->where('rating', 3)->count();
                })
                ->addColumn('4_star', function ($query) {
                    return $query->orderItems()->where('rating', 4)->count();
                })
                ->addColumn('5_star', function ($query) {
                    return $query->orderItems()->where('rating', 5)->count();
                })
                ->addColumn('average', function ($query) {
                    $star1 = $query->orderItems()->where('rating', 1)->count();
                    $star2 = $query->orderItems()->where('rating', 2)->count();
                    $star3 = $query->orderItems()->where('rating', 3)->count();
                    $star4 = $query->orderItems()->where('rating', 4)->count();
                    $star5 = $query->orderItems()->where('rating', 5)->count();

                    $numerator = ($star1 * 1) + ($star2 * 2) + ($star3 * 3) + ($star4 * 4) + ($star5 * 5);
                    $denominator = ($star1 + $star2 + $star3 + $star4 + $star5);

                    if ($numerator > 0) {
                        return $numerator / $denominator;
                    }

                    return  0;
                })
                ->addColumn('action', 'pages.feedback.action')
                ->rawColumns(['action'])
                ->make(true);
        }

        $table = $builder->columns([
            ['data' => 'name', 'name' => 'name', 'title' => 'Package'],
            ['data' => '1_star', 'name' => '1_star', 'title' => '1 Star'],
            ['data' => '2_star', 'name' => '2_star', 'title' => '2 Star'],
            ['data' => '3_star', 'name' => '3_star', 'title' => '3 Star'],
            ['data' => '4_star', 'name' => '4_star', 'title' => '4 Star'],
            ['data' => '5_star', 'name' => '5_star', 'title' => '5 Star'],
            ['data' => 'average', 'name' => 'average', 'title' => 'Average'],
            ['data' => 'action', 'name' => 'action', 'title' => '']
        ])->parameters([
            'searching' => false,
            'ordering' => false
        ]);

        return view('pages.feedback.index', compact('table'));
    }

    public function show($id, Builder  $builder)
    {
        $package = Package::query()
            ->findOrFail($id);

        if (request()->ajax()) {
            $query = OrderItem::query();
            $query->whereHas('package', function ($query) use ($package) {
                $query->where('package_id', $package->id);
            });
            $query->with('user');

            return DataTables::of($query)
                ->filter(function ($query) {
                    $query->whereNotNull('reviewed_at');
                    $query->orderByDesc('reviewed_at');

                    if (request()->filled('filter.search')) {
                        $query->whereHas('user', function ($query) {
                            $query->where('name', 'like', '%' . request()->input('filter.search') . '%');
                        });
                    }
                })
                ->editColumn('reviewed_at', function ($query) {
                    if ($query->reviewed_at) {
                        return Carbon::parse($query->reviewed_at)->toDayDateTimeString();
                    }
                })
                ->make(true);
        }

        $table = $builder->columns([
            ['data' => 'user.name', 'name' => 'user.name', 'title' => 'Student', 'defaultContent' => ''],
            ['data' => 'rating', 'name' => 'rating', 'title' => 'Rating'],
            ['data' => 'review', 'name' => 'review', 'title' => 'Review'],
            ['data' => 'reviewed_at', 'name' => 'reviewed_at', 'title' => 'Reviewed At']
        ])->parameters([
            'searching' => false,
            'ordering' => false
        ]);

        return view('pages.feedback.show', compact('table', 'package'));
    }
}
