<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SchoolRegistrationRequest;
use Illuminate\Http\Request;
use App\Models\Area;
use App\Models\Committee;
use App\Models\School;
use App\Models\SchoolClass;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Mail\SchoolCreatedMail;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class SchoolRegistrationRequestController extends Controller
{
    public function index()
    {
        $requests = SchoolRegistrationRequest::orderByRaw("
                CASE request_status
                    WHEN 'pending' THEN 1
                    WHEN 'approved' THEN 2
                    WHEN 'rejected' THEN 3
                    ELSE 4
                END
            ")
            ->orderByDesc('created_at')
            ->get();

        return view('admin.school-requests.index', compact('requests'));
    }

    public function show(SchoolRegistrationRequest $registrationRequest)
    {
        return view('admin.school-requests.show', compact('registrationRequest'));
    }

    public function updateStatus(Request $request, SchoolRegistrationRequest $registrationRequest)
    {
        $validated = $request->validate([
            'request_status' => ['required', 'in:pending,approved,rejected'],
        ]);

        $registrationRequest->update([
            'request_status' => $validated['request_status'],
        ]);

        return redirect()
            ->route('admin.school-requests.show', $registrationRequest)
            ->with('success', 'Request status updated.');
    }

    public function createSchool(Request $request, SchoolRegistrationRequest $registrationRequest)
    {
        if ($registrationRequest->created_school_id) {
            return redirect()
                ->route('admin.school-requests.show', $registrationRequest)
                ->with('error', 'A school has already been created from this request.');
        }

        $validated = $request->validate([
            'slug' => ['required', 'string', 'max:160', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/', 'unique:schools,slug'],
        ]);

        DB::transaction(function () use ($registrationRequest, $validated, &$school) {
            $school = School::create([
                'area_id' => Area::defaultId(),
                'name' => $registrationRequest->school_name,
                'slug' => $validated['slug'],
                'school_type' => $registrationRequest->school_type,
                'town' => $registrationRequest->town,
                'website_url' => $registrationRequest->website_url,
                'support_status' => 'undecided',
                'status' => 'inactive',
                'notes' => $registrationRequest->notes,
            ]);

            $user = User::where('email', $registrationRequest->contact_email)->first();

            if (! $user) {
                $user = User::create([
                    'name' => $registrationRequest->contact_name,
                    'email' => $registrationRequest->contact_email,
                    'password' => Hash::make(Str::random(32)),
                    'is_admin' => 0,
                    'user_type' => 'school',
                    'school_id' => $school->id,
                    'person_id' => null,
                ]);
            } else {
                $user->update([
                    'user_type' => 'school',
                    'school_id' => $school->id,
                ]);
            }

            $defaultClasses = [];

            if ($school->school_type === 'primary') {
                $defaultClasses = [
                    ['class_level' => 'junior_infants', 'class_stream' => null, 'display_name' => 'Junior Infants', 'sort_order' => 10],
                    ['class_level' => 'senior_infants', 'class_stream' => null, 'display_name' => 'Senior Infants', 'sort_order' => 20],
                    ['class_level' => '1st_class', 'class_stream' => null, 'display_name' => '1st Class', 'sort_order' => 30],
                    ['class_level' => '2nd_class', 'class_stream' => null, 'display_name' => '2nd Class', 'sort_order' => 40],
                    ['class_level' => '3rd_class', 'class_stream' => null, 'display_name' => '3rd Class', 'sort_order' => 50],
                    ['class_level' => '4th_class', 'class_stream' => null, 'display_name' => '4th Class', 'sort_order' => 60],
                    ['class_level' => '5th_class', 'class_stream' => null, 'display_name' => '5th Class', 'sort_order' => 70],
                    ['class_level' => '6th_class', 'class_stream' => null, 'display_name' => '6th Class', 'sort_order' => 80],
                ];
            }

            if ($school->school_type === 'secondary') {
                $defaultClasses = [
                    ['class_level' => '1st_year', 'class_stream' => null, 'display_name' => '1st Year', 'sort_order' => 90],
                ];
            }

            foreach ($defaultClasses as $classData) {
                SchoolClass::create([
                    'school_id' => $school->id,
                    'class_level' => $classData['class_level'],
                    'class_stream' => $classData['class_stream'],
                    'display_name' => $classData['display_name'],
                    'sort_order' => $classData['sort_order'],
                    'total_pupils' => null,
                    'is_active' => 1,
                ]);
            }

            $parentCommittee = Committee::where('slug', 'east-cork-reclaim-childhood')->first();

            Committee::create([
                'area_id' => Area::defaultId(),
                'name' => $school->name . ' Committee',
                'slug' => $school->slug . '-committee',
                'committee_type' => 'school',
                'parent_committee_id' => $parentCommittee?->id,
                'school_id' => $school->id,
                'town' => $school->town,
                'primary_contact_person_id' => null,
                'status' => 'active',
            ]);

            $registrationRequest->update([
                'request_status' => 'approved',
                'created_school_id' => $school->id,
            ]);
        });

        Mail::to($registrationRequest->contact_email)
            ->send(new SchoolCreatedMail($school, $registrationRequest));

        return to_route('admin.schools.edit', $school)
            ->with('success', 'School created from request.');
    }
}
