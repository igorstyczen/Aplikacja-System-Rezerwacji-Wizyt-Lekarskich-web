<?php

namespace App\Http\Controllers;

use App\Models\DoctorSpecialization;
use App\Models\HelpTag;
use App\Models\Specialization;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminDictionaryController extends Controller
{
    public function specializations()
    {
        $specializations = Specialization::withCount('doctorSpecializations')
            ->orderBy('name')
            ->paginate(20);

        return view('admin.specializations', [
            'specializations' => $specializations,
        ]);
    }

    public function storeSpecialization(Request $request)
    {
        $request->merge([
            'name' => trim($request->name),
        ]);

        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:specializations,name'],
        ]);

        Specialization::create([
            'name' => $request->name,
        ]);

        return back()->with('success', 'Specjalizacja została dodana.');
    }

    public function updateSpecialization(Request $request, Specialization $specialization)
    {
        $request->merge([
            'name' => trim($request->name),
        ]);

        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('specializations', 'name')->ignore($specialization->id),
            ],
        ]);

        $specialization->update([
            'name' => $request->name,
        ]);

        DoctorSpecialization::where('specialization_id', $specialization->id)
            ->update([
                'specialization_name' => $specialization->name,
            ]);

        return back()->with('success', 'Specjalizacja została zaktualizowana.');
    }

    public function deleteSpecialization(Specialization $specialization)
    {
        if ($specialization->doctorSpecializations()->exists()) {
            return back()->withErrors([
                'specialization' => 'Nie można usunąć specjalizacji, która jest przypisana do lekarzy.',
            ]);
        }

        $specialization->delete();

        return back()->with('success', 'Specjalizacja została usunięta.');
    }

    public function helpTags()
    {
        $helpTags = HelpTag::withCount('doctors')
            ->orderBy('tag_name')
            ->paginate(20);

        return view('admin.help-tags', [
            'helpTags' => $helpTags,
        ]);
    }

    public function storeHelpTag(Request $request)
    {
        $request->validate([
            'tag_name' => ['required', 'string', 'max:255', 'unique:help_tags,tag_name'],
        ]);

        HelpTag::create([
            'tag_name' => $request->tag_name,
        ]);

        return back()->with('success', 'Tag został dodany.');
    }

    public function updateHelpTag(Request $request, HelpTag $helpTag)
    {
        $request->validate([
            'tag_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('help_tags', 'tag_name')->ignore($helpTag->id),
            ],
        ]);

        $helpTag->update([
            'tag_name' => $request->tag_name,
        ]);

        return back()->with('success', 'Tag został zaktualizowany.');
    }

    public function deleteHelpTag(HelpTag $helpTag)
    {
        if ($helpTag->doctors()->exists()) {
            return back()->withErrors([
                'tag' => 'Nie można usunąć tagu, który jest przypisany do lekarzy.',
            ]);
        }

        $helpTag->delete();

        return back()->with('success', 'Tag został usunięty.');
    }
}
