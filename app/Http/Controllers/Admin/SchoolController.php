<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Committee;
use App\Models\School;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Mail\SchoolInviteMail;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class SchoolController extends Controller
{
    public function index()
    {
        $schools = School::with(['principal', 'vicePrincipal', 'secretary', 'committee'])
            ->orderBy('name')
            ->get();

        return view('admin.schools.index', compact('schools'));
    }

    public function create()
    {
        return view('admin.schools.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'alternative_name' => ['nullable', 'string', 'max:150'],
            'slug' => ['required', 'string', 'max:160', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/', 'unique:schools,slug'],
            'roll_number' => ['nullable', 'string', 'max:20', 'unique:schools,roll_number'],
            'school_type' => ['required', 'in:primary,secondary'],
            'town' => ['nullable', 'string', 'max:100'],
            'website_url' => ['nullable', 'url', 'max:255'],
            'school_phone' => ['nullable', 'string', 'max:40'],
            'principal_name' => ['nullable', 'string', 'max:150'],
            'principal_email' => ['nullable', 'email', 'max:150'],
            'vice_principal_name' => ['nullable', 'string', 'max:150'],
            'vice_principal_email' => ['nullable', 'email', 'max:150'],
            'secretary_name' => ['nullable', 'string', 'max:150'],
            'secretary_email' => ['nullable', 'email', 'max:150'],
            'support_status' => ['required', 'in:undecided,supporting'],
            'status' => ['required', 'in:inactive,active'],
            'notes' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($validated) {
            $school = School::create($validated + ['area_id' => Area::defaultId()]);

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
        });

        return redirect()->route('admin.schools.index')
            ->with('success', 'School added.');
    }

    public function edit(School $school)
    {
        return view('admin.schools.edit', compact('school'));
    }

    public function update(Request $request, School $school)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'alternative_name' => ['nullable', 'string', 'max:150'],
            'slug' => ['required', 'string', 'max:160', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/'],
            'roll_number' => ['nullable', 'string', 'max:20', Rule::unique('schools', 'roll_number')->ignore($school->id)],
            'school_type' => ['required', 'in:primary,secondary'],
            'town' => ['nullable', 'string', 'max:100'],
            'website_url' => ['nullable', 'url', 'max:255'],
            'school_phone' => ['nullable', 'string', 'max:40'],
            'principal_name' => ['nullable', 'string', 'max:150'],
            'principal_email' => ['nullable', 'email', 'max:150'],
            'vice_principal_name' => ['nullable', 'string', 'max:150'],
            'vice_principal_email' => ['nullable', 'email', 'max:150'],
            'secretary_name' => ['nullable', 'string', 'max:150'],
            'secretary_email' => ['nullable', 'email', 'max:150'],
            'support_status' => ['required', 'in:undecided,supporting'],
            'status' => ['required', 'in:inactive,active'],
            'classes_confirmed' => ['nullable', 'boolean'],
            'notes' => ['nullable', 'string'],
        ]);

        $validated['classes_confirmed'] = $request->boolean('classes_confirmed');

        DB::transaction(function () use ($school, $validated) {
            $school->update($validated);

            $committee = Committee::where('school_id', $school->id)
                ->where('committee_type', 'school')
                ->first();

            if ($committee) {
                $committee->update([
                    'name' => $school->name . ' Committee',
                    'slug' => $school->slug . '-committee',
                    'town' => $school->town,
                ]);
            }
        });

        return redirect()->route('admin.schools.edit', $school)
            ->with('success', 'School updated.');
    }

    public function destroy(School $school)
    {
        DB::transaction(function () use ($school) {
            Committee::where('school_id', $school->id)
                ->where('committee_type', 'school')
                ->delete();

            $school->delete();
        });

        return redirect()->route('admin.schools.index')
            ->with('success', 'School deleted.');
    }

    public function sendInvite(School $school)
    {
        if (! $school->principal_email) {
            return back()->with('error', 'Enter a principal email address before sending the school invite.');
        }

        $user = User::where('email', $school->principal_email)->first();

        if ($user && ((int) $user->is_admin === 1 || $user->user_type === 'admin')) {
            return back()->with('error', 'That email belongs to an admin account. Use a different principal email address, or change that account\'s role first if this was intentional.');
        }

        if (! $user) {
            $user = User::create([
                'name' => $school->name . ' School User',
                'email' => $school->principal_email,
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

        $mail = Mail::to($school->principal_email);

        if ($school->secretary_email && $school->secretary_email !== $school->principal_email) {
            $mail->cc($school->secretary_email);
        }

        $mail->send(new SchoolInviteMail($school));

        $status = Password::sendResetLink([
            'email' => $school->principal_email,
        ]);

        if ($status !== Password::RESET_LINK_SENT) {
            return back()->with('error', __($status));
        }

        return back()->with('success', 'School invite sent to the principal email address.');
    }
}
