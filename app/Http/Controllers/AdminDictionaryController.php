<?php

namespace App\Http\Controllers;

use App\Models\DoctorSpecialization;
use App\Models\HelpTag;
use App\Models\Specialization;
use App\Services\HelpTagSimilarityService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminDictionaryController extends Controller
{
    public function specializations(Request $request)
    {
        $query = Specialization::withCount('doctorSpecializations')
            ->orderBy('name');

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->filled('usage')) {
            if ($request->usage === 'used') {
                $query->has('doctorSpecializations');
            }

            if ($request->usage === 'unused') {
                $query->doesntHave('doctorSpecializations');
            }
        }

        $specializations = $query
            ->paginate(20)
            ->withQueryString();

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

    public function helpTags(Request $request)
    {
        $query = HelpTag::withCount('doctors')
            ->orderBy('tag_name');

        if ($request->filled('tag_name')) {
            $query->where('tag_name', 'like', '%' . $request->tag_name . '%');
        }

        if ($request->filled('usage')) {
            if ($request->usage === 'used') {
                $query->has('doctors');
            }

            if ($request->usage === 'unused') {
                $query->doesntHave('doctors');
            }
        }

        $helpTags = $query
            ->paginate(20)
            ->withQueryString();

        return view('admin.help-tags', [
            'helpTags' => $helpTags,
        ]);
    }

    public function storeHelpTag(Request $request, HelpTagSimilarityService $similarityService)
    {
        $request->merge([
            'tag_name' => trim($request->tag_name),
        ]);

        $request->validate([
            'tag_name' => ['required', 'string', 'max:255'],
        ]);

        $existingTag = HelpTag::where('tag_name', $request->tag_name)->first();

        if ($existingTag) {
            return back()
                ->withInput()
                ->withErrors([
                    'tag_name' => 'Taki tag już istnieje.',
                ]);
        }

        $similarTags = $similarityService->findSimilar($request->tag_name, 0.75);

        if ($similarTags->isNotEmpty()) {
            $suggestions = $similarTags->pluck('tag_name')->take(3)->implode(', ');

            return back()
                ->withInput()
                ->withErrors([
                    'tag_name' => 'Podobny tag już istnieje: ' . $suggestions,
                ]);
        }

        HelpTag::create([
            'tag_name' => $request->tag_name,
        ]);

        return back()->with('success', 'Tag został dodany.');
    }

    public function updateHelpTag(Request $request, HelpTag $helpTag, HelpTagSimilarityService $similarityService)
    {
        $request->merge([
            'tag_name' => trim($request->tag_name),
        ]);

        $existingTag = HelpTag::where('tag_name', $request->tag_name)
            ->where('id', '!=', $helpTag->id)
            ->first();

        if ($existingTag) {
            return back()
                ->withInput()
                ->withErrors([
                    'tag_name' => 'Taki tag już istnieje.',
                ]);
        }

        $request->validate([
            'tag_name' => ['required', 'string', 'max:255'],
        ]);

        $similarTags = $similarityService->findSimilar($request->tag_name, 0.75)
            ->reject(fn (HelpTag $tag) => $tag->id === $helpTag->id);

        if ($similarTags->isNotEmpty()) {
            $suggestions = $similarTags->pluck('tag_name')->take(3)->implode(', ');

            return back()
                ->withInput()
                ->withErrors([
                    'tag_name' => 'Podobny tag już istnieje: ' . $suggestions,
                ]);
        }

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
