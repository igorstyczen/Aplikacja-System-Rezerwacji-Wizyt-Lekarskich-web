<?php

namespace App\Http\Controllers;

use App\Models\AvailabilitySlot;
use App\Services\NfzApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NfzComparisonController extends Controller
{
    public function index()
    {
        return view('nfz.comparison', [
            'privateSlot' => null,
            'nfzResult' => null,
            'differenceDays' => null,
            'searched' => false,
        ]);
    }

    public function compare(Request $request, NfzApiService $nfzApiService)
    {
        $request->validate([
            'benefit' => ['required', 'string', 'max:255'],
            'locality' => ['required', 'string', 'max:255'],
            'province' => ['required', 'string', 'max:2'],
            'case' => ['required', 'integer', 'in:1,2'],
        ]);

        $privateSlot = $this->findNearestPrivateSlot(
            $request->benefit,
            $request->locality
        );

        $nfzResult = $nfzApiService->searchQueues(
            benefit: $request->benefit,
            locality: $request->locality,
            province: $request->province,
            case: (int) $request->case
        );

        $differenceDays = null;

        if ($privateSlot && $nfzResult['nearest']) {
            $differenceDays = $nfzApiService->calculateDifferenceInDays(
                $privateSlot->start_time,
                $nfzResult['nearest']['date']
            );
        }

        return view('nfz.comparison', [
            'privateSlot' => $privateSlot,
            'nfzResult' => $nfzResult,
            'differenceDays' => $differenceDays,
            'searched' => true,
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
            ->where('availability_slots.status', 'available')
            ->where('availability_slots.start_time', '>=', now())
            ->where(function ($query) use ($benefit) {
                $query->where('services.name', 'like', '%' . $benefit . '%')
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
