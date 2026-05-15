<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\AvailabilitySlot;
use App\Models\Clinic;
use App\Models\Doctor;
use App\Models\DoctorSpecialization;
use App\Models\HelpTag;
use App\Models\Patient;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Admin Systemu',
            'email' => 'admin@test.pl',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $doctorUser1 = User::create([
            'name' => 'Jan Kowalski',
            'email' => 'doktor1@test.pl',
            'password' => Hash::make('password'),
            'role' => 'doctor',
        ]);

        $doctorUser2 = User::create([
            'name' => 'Anna Nowak',
            'email' => 'doktor2@test.pl',
            'password' => Hash::make('password'),
            'role' => 'doctor',
        ]);

        $patientUser1 = User::create([
            'name' => 'Piotr Pacjent',
            'email' => 'pacjent1@test.pl',
            'password' => Hash::make('password'),
            'role' => 'patient',
        ]);

        $doctor1 = Doctor::create([
            'user_id' => $doctorUser1->id,
            'first_name' => 'Jan',
            'last_name' => 'Kowalski',
            'photo_url' => null,
            'bio' => 'Lekarz rodzinny z wieloletnim doświadczeniem.',
            'is_verified' => true,
            'is_for_adults' => true,
            'is_for_children' => true,
        ]);

        $doctor2 = Doctor::create([
            'user_id' => $doctorUser2->id,
            'first_name' => 'Anna',
            'last_name' => 'Nowak',
            'photo_url' => null,
            'bio' => 'Specjalistka dermatologii i konsultacji skórnych.',
            'is_verified' => true,
            'is_for_adults' => true,
            'is_for_children' => false,
        ]);

        Patient::create([
            'user_id' => $patientUser1->id,
            'first_name' => 'Piotr',
            'last_name' => 'Pacjent',
            'pesel' => '99010112345',
            'phone' => '500600700',
        ]);

        // Lekarz też może być pacjentem
        Patient::create([
            'user_id' => $doctorUser1->id,
            'first_name' => 'Jan',
            'last_name' => 'Kowalski',
            'pesel' => null,
            'phone' => '501501501',
        ]);

        DoctorSpecialization::create([
            'doctor_id' => $doctor1->id,
            'specialization_name' => 'Lekarz rodzinny',
        ]);

        DoctorSpecialization::create([
            'doctor_id' => $doctor2->id,
            'specialization_name' => 'Dermatolog',
        ]);

        $tagHeadache = HelpTag::create(['tag_name' => 'ból głowy']);
        $tagSkin = HelpTag::create(['tag_name' => 'problemy skórne']);
        $tagChild = HelpTag::create(['tag_name' => 'dziecko']);
        $tagRecipe = HelpTag::create(['tag_name' => 'recepta']);

        $doctor1->helpTags()->attach([
            $tagHeadache->id,
            $tagChild->id,
            $tagRecipe->id,
        ]);

        $doctor2->helpTags()->attach([
            $tagSkin->id,
            $tagRecipe->id,
        ]);

        $clinic1 = Clinic::create([
            'doctor_id' => $doctor1->id,
            'name' => 'Centrum Medyczne Zdrowie',
            'address' => 'ul. Medyczna 10',
            'city' => 'Rzeszów',
            'details' => 'Gabinet numer 12, pierwsze piętro.',
        ]);

        $clinic2 = Clinic::create([
            'doctor_id' => $doctor2->id,
            'name' => 'Klinika Dermatologiczna SkinMed',
            'address' => 'ul. Skórna 5',
            'city' => 'Rzeszów',
            'details' => 'Wejście od parkingu.',
        ]);

        $service1 = Service::create([
            'doctor_id' => $doctor1->id,
            'clinic_id' => $clinic1->id,
            'name' => 'Konsultacja lekarska',
            'description' => 'Podstawowa konsultacja u lekarza rodzinnego.',
            'price' => 150.00,
            'duration_minutes' => 30,
        ]);

        $service2 = Service::create([
            'doctor_id' => $doctor2->id,
            'clinic_id' => $clinic2->id,
            'name' => 'Konsultacja dermatologiczna',
            'description' => 'Ocena zmian skórnych i dobór leczenia.',
            'price' => 200.00,
            'duration_minutes' => 30,
        ]);

        AvailabilitySlot::create([
            'doctor_id' => $doctor1->id,
            'clinic_id' => $clinic1->id,
            'start_time' => now()->addDay()->setTime(9, 0),
            'end_time' => now()->addDay()->setTime(9, 30),
            'is_recurring' => false,
            'recurrence_rule' => null,
            'status' => 'available',
        ]);

        AvailabilitySlot::create([
            'doctor_id' => $doctor1->id,
            'clinic_id' => $clinic1->id,
            'start_time' => now()->addDay()->setTime(10, 0),
            'end_time' => now()->addDay()->setTime(10, 30),
            'is_recurring' => false,
            'recurrence_rule' => null,
            'status' => 'available',
        ]);

        AvailabilitySlot::create([
            'doctor_id' => $doctor2->id,
            'clinic_id' => $clinic2->id,
            'start_time' => now()->addDays(2)->setTime(12, 0),
            'end_time' => now()->addDays(2)->setTime(12, 30),
            'is_recurring' => false,
            'recurrence_rule' => null,
            'status' => 'available',
        ]);
    }
}
