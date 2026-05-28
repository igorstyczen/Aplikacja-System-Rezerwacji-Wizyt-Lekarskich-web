<?php

namespace App\Http\Controllers;

use App\Services\NfzApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class NfzComparisonController extends Controller
{
    private array $nfzBenefits = [
        'kardiolog' => [
            'label' => 'Kardiolog',
            'nfz_search' => 'kardiologiczna',
            'nfz_display' => 'PORADNIA KARDIOLOGICZNA',
            'private_search' => 'kardiolog',
        ],
        'dermatolog' => [
            'label' => 'Dermatolog',
            'nfz_search' => 'dermatologiczna',
            'nfz_display' => 'PORADNIA DERMATOLOGICZNA',
            'private_search' => 'dermatolog',
        ],
        'okulista' => [
            'label' => 'Okulista',
            'nfz_search' => 'okulistyczna',
            'nfz_display' => 'PORADNIA OKULISTYCZNA',
            'private_search' => 'okulist',
        ],
        'laryngolog' => [
            'label' => 'Laryngolog',
            'nfz_search' => 'laryngologiczna',
            'nfz_display' => 'PORADNIA LARYNGOLOGICZNA',
            'private_search' => 'laryngolog',
        ],
        'neurolog' => [
            'label' => 'Neurolog',
            'nfz_search' => 'neurologiczna',
            'nfz_display' => 'PORADNIA NEUROLOGICZNA',
            'private_search' => 'neurolog',
        ],
        'ortopeda' => [
            'label' => 'Ortopeda',
            'nfz_search' => 'ortopedyczna',
            'nfz_display' => 'PORADNIA ORTOPEDYCZNA',
            'private_search' => 'ortoped',
        ],
        'ginekolog' => [
            'label' => 'Ginekolog',
            'nfz_search' => 'ginekologiczno-położnicza',
            'nfz_display' => 'PORADNIA GINEKOLOGICZNO-POŁOŻNICZA',
            'private_search' => 'ginekolog',
        ],
        'chirurg' => [
            'label' => 'Chirurg ogólny',
            'nfz_search' => 'chirurgii ogólnej',
            'nfz_display' => 'PORADNIA CHIRURGII OGÓLNEJ',
            'private_search' => 'chirurg',
        ],
        'urolog' => [
            'label' => 'Urolog',
            'nfz_search' => 'urologiczna',
            'nfz_display' => 'PORADNIA UROLOGICZNA',
            'private_search' => 'urolog',
        ],
        'endokrynolog' => [
            'label' => 'Endokrynolog',
            'nfz_search' => 'endokrynologiczna',
            'nfz_display' => 'PORADNIA ENDOKRYNOLOGICZNA',
            'private_search' => 'endokrynolog',
        ],
    ];

    public function index()
    {
        return view('nfz.comparison', [
            'benefits' => $this->nfzBenefits,
            'privateSlot' => null,
            'nfzResult' => null,
            'differenceDays' => null,
            'searched' => false,
        ]);
    }

    public function compare(Request $request, NfzApiService $nfzApiService)
    {
        $request->validate([
            'benefit' => [
                'required',
                'string',
                Rule::in(array_keys($this->nfzBenefits)),
            ],
            'locality' => ['required', 'string', 'max:255'],
            'province' => ['required', 'string', 'size:2'],
            'case' => ['required', 'integer', 'in:1,2'],
        ]);

        $selectedBenefit = $this->nfzBenefits[$request->benefit];

        $privateSlot = $this->findNearestPrivateSlot(
            $selectedBenefit['private_search'],
            $request->locality
        );

        $nfzResult = $nfzApiService->searchQueues(
            benefit: $selectedBenefit['nfz_search'],
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
            'benefits' => $this->nfzBenefits,
            'privateSlot' => $privateSlot,
            'nfzResult' => $nfzResult,
            'differenceDays' => $differenceDays,
            'searched' => true,
        ]);
    }

    private function findNearestPrivateSlot(string $benefit, string $locality)
    {
        return DB::table('availability_slots')
            ->join('doctors', 'doctors.id', '=', 'availability_slots.doctor_id')
            ->join('clinics', 'clinics.id', '=', 'availability_slots.clinic_id')
            ->leftJoin('services', function ($join) {
                $join->on('services.doctor_id', '=', 'availability_slots.doctor_id')
                    ->on('services.clinic_id', '=', 'availability_slots.clinic_id');
            })
            ->leftJoin('doctor_specializations', 'doctor_specializations.doctor_id', '=', 'doctors.id')
            ->leftJoin('doctors_help_tags', 'doctors_help_tags.doctor_id', '=', 'doctors.id')
            ->leftJoin('help_tags', 'help_tags.id', '=', 'doctors_help_tags.tag_id')
            ->where('availability_slots.status', 'available')
            ->where('availability_slots.start_time', '>=', now())
            ->where('doctors.is_verified', true)
            ->where('doctors.is_active', true)
            ->where('clinics.city', 'like', '%' . $locality . '%')
            ->where(function ($query) use ($benefit) {
                $query->where('services.name', 'like', '%' . $benefit . '%')
                    ->orWhere('services.description', 'like', '%' . $benefit . '%')
                    ->orWhere('doctor_specializations.specialization_name', 'like', '%' . $benefit . '%')
                    ->orWhere('help_tags.tag_name', 'like', '%' . $benefit . '%')
                    ->orWhere('doctors.first_name', 'like', '%' . $benefit . '%')
                    ->orWhere('doctors.last_name', 'like', '%' . $benefit . '%');
            })
            ->orderBy('availability_slots.start_time')
            ->select([
                'availability_slots.id as slot_id',
                'availability_slots.start_time',
                'doctors.id as doctor_id',
                'doctors.first_name as doctor_first_name',
                'doctors.last_name as doctor_last_name',
                'clinics.name as clinic_name',
                'clinics.city as clinic_city',
                DB::raw('COALESCE(services.name, "Brak przypisanej usługi") as service_name'),
                DB::raw('COALESCE(services.price, 0) as service_price'),
                DB::raw('COALESCE(services.duration_minutes, 0) as service_duration'),
            ])
            ->distinct()
            ->first();
    }
}
