<?php

namespace App\Http\Controllers;

use App\Models\Clinic;
use App\Models\Doctor;
use App\Models\DoctorSpecialization;
use App\Models\HelpTag;
use App\Services\HelpTagSimilarityService;
use App\Services\NfzApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index(Request $request, NfzApiService $nfzApiService)
    {
        $query = Doctor::with([
                'specializations',
                'helpTags',
                'clinics',
            ])
            ->where('is_verified', true)
            ->where('is_active', true);

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
            $matchingTagIds = app(HelpTagSimilarityService::class)
                ->findMatchingTagIds($request->tag);

            if (count($matchingTagIds) > 0) {
                $query->whereHas('helpTags', function ($q) use ($matchingTagIds) {
                    $q->whereIn('help_tags.id', $matchingTagIds);
                });
            } else {
                $query->whereRaw('0 = 1');
            }
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

        $nfzSearched = $request->filled('nfz_benefit')
            && $request->filled('nfz_locality')
            && $request->filled('nfz_province');

        $privateSlot = null;
        $nfzResult = null;
        $differenceDays = null;

        if ($nfzSearched) {
            $privateSlot = $this->findNearestPrivateSlot(
                $request->nfz_benefit,
                $request->nfz_locality
            );

            $nfzResult = $nfzApiService->searchQueues(
                benefit: $request->nfz_benefit,
                locality: $request->nfz_locality,
                province: $request->nfz_province,
                case: (int) $request->input('nfz_case', 1)
            );

            if ($privateSlot && $nfzResult['nearest']) {
                $differenceDays = $nfzApiService->calculateDifferenceInDays(
                    $privateSlot->start_time,
                    $nfzResult['nearest']['date']
                );
            }
        }

        return view('home', [
            'doctors' => $doctors,
            'specializations' => $specializations,
            'tags' => $tags,
            'cities' => $cities,
            'nfzSearched' => $nfzSearched,
            'privateSlot' => $privateSlot,
            'nfzResult' => $nfzResult,
            'differenceDays' => $differenceDays,
        ]);
    }

    private function findNearestPrivateSlot(string $benefit, string $locality)
    {
        return DB::table('availability_slots')
            ->join('services', function ($join) {
                $join->on('services.doctor_id', '=', 'availability_slots.doctor_id')
                    ->on('services.clinic_id', '=', 'availability_slots.clinic_id');
            })
            ->join('doctors', 'doctors.id', '=', 'availability_slots.doctor_id')
            ->join('clinics', 'clinics.id', '=', 'availability_slots.clinic_id')
            ->leftJoin('doctor_specializations', 'doctor_specializations.doctor_id', '=', 'doctors.id')
            ->leftJoin('doctors_help_tags', 'doctors_help_tags.doctor_id', '=', 'doctors.id')
            ->leftJoin('help_tags', 'help_tags.id', '=', 'doctors_help_tags.tag_id')
            ->where('availability_slots.status', 'available')
            ->where('availability_slots.start_time', '>=', now())
            ->where('doctors.is_verified', true)
            ->where('doctors.is_active', true)
            ->where(function ($query) use ($benefit) {
                $query->where('services.name', 'like', '%' . $benefit . '%')
                    ->orWhere('services.description', 'like', '%' . $benefit . '%')
                    ->orWhere('doctor_specializations.specialization_name', 'like', '%' . $benefit . '%')
                    ->orWhere('help_tags.tag_name', 'like', '%' . $benefit . '%')
                    ->orWhere('doctors.first_name', 'like', '%' . $benefit . '%')
                    ->orWhere('doctors.last_name', 'like', '%' . $benefit . '%');
            })
            ->where('clinics.city', 'like', '%' . $locality . '%')
            ->orderBy('availability_slots.start_time')
            ->select([
                'availability_slots.id as slot_id',
                'availability_slots.start_time',
                'doctors.id as doctor_id',
                'doctors.first_name as doctor_first_name',
                'doctors.last_name as doctor_last_name',
                'clinics.name as clinic_name',
                'clinics.city as clinic_city',
                'services.name as service_name',
                'services.price as service_price',
                'services.duration_minutes as service_duration',
            ])
            ->first();
    }
}
