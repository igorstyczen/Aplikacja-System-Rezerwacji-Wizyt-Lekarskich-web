<?php

namespace App\Http\Controllers;

use App\Models\Clinic;
use App\Models\Doctor;
use App\Models\DoctorSpecialization;
use App\Models\HelpTag;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $query = Doctor::with([
                'specializations',
                'helpTags',
                'clinics',
            ])
            ->where('is_verified', true);

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('specialization')) {
            $query->whereHas('specializations', function ($q) use ($request) {
                $q->where('specialization_name', $request->specialization);
            });
        }

        if ($request->filled('tag')) {
            $query->whereHas('helpTags', function ($q) use ($request) {
                $q->where('tag_name', $request->tag);
            });
        }

        if ($request->filled('city')) {
            $query->whereHas('clinics', function ($q) use ($request) {
                $q->where('city', $request->city);
            });
        }

        if ($request->boolean('for_children')) {
            $query->where('is_for_children', true);
        }

        $doctors = $query->paginate(50)->withQueryString();

        $specializations = DoctorSpecialization::query()
            ->select('specialization_name')
            ->distinct()
            ->orderBy('specialization_name')
            ->pluck('specialization_name');

        $tags = HelpTag::query()
            ->orderBy('tag_name')
            ->pluck('tag_name');

        $cities = Clinic::query()
            ->select('city')
            ->distinct()
            ->orderBy('city')
            ->pluck('city');

        return view('home', compact('doctors', 'specializations', 'tags', 'cities'));
    }
}
