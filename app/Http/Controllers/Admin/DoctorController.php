<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Specialty;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class DoctorController extends Controller
{
    public function index()
    {
        $doctors = Doctor::with(['user', 'specialty' => function($query) {
            $query->withTrashed();
        }])->get();

        $specialties = Specialty::orderBy('name', 'asc')->get();

        return view('admin.doctors.index', compact('doctors', 'specialties'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email:rfc', 'max:255', 'not_regex:/[\r\n]/', 'unique:users,email'],
            'password' => ['required', Password::defaults()],
            'specialty_id' => ['required', Rule::exists('specialties', 'id')->whereNull('deleted_at')],
            'appointment_duration' => 'required|integer|min:15|max:120|multiple_of:5',
            'bio' => 'nullable|string|max:2000',
        ]);

        try {
            DB::beginTransaction();

            $user = User::create([
                'name' => $validated['name'],
                'email' => strtolower(trim($validated['email'])),
                'password' => Hash::make($validated['password']),
                'role' => 'doctor',
            ]);

            Doctor::create([
                'user_id' => $user->id,
                'specialty_id' => $validated['specialty_id'],
                'appointment_duration' => $validated['appointment_duration'],
                'bio' => $validated['bio'] ?? null,
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'doctor added successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create doctor', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Something went wrong while adding the doctor.');
        }
    }

    public function update(Request $request, $id)
    {
        $doctor = Doctor::query()->findOrFail($id);
        $user = $doctor->user;
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => ['required', 'string', 'email:rfc', 'max:255', 'not_regex:/[\r\n]/', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', Password::defaults()],
            'specialty_id' => ['required', Rule::exists('specialties', 'id')->whereNull('deleted_at')],
            'appointment_duration' => 'required|integer|min:15|max:120|multiple_of:5',
            'bio' => 'nullable|string|max:2000',
        ]);

        try {
            DB::beginTransaction();

            $userData = [
                'name' => $validated['name'],
                'email' => strtolower(trim($validated['email'])),
            ];

            if (!empty($validated['password'])) {
                $userData['password'] = Hash::make($validated['password']);
            }

            $user->update($userData);

            $doctor->update([
                'specialty_id' => $validated['specialty_id'],
                'appointment_duration' => $validated['appointment_duration'],
                'bio' => $validated['bio'] ?? null,
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'doctor updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update doctor', ['doctor_id' => $id, 'error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Something went wrong while updating the doctor.');
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $doctor = Doctor::findOrFail($id);
            $user = $doctor->user;

            $doctor->delete();

            if ($user) {
                $user->delete();
            }

            DB::commit();

            return redirect()->back()->with('success', 'doctor and his user account deleted successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to delete doctor', ['doctor_id' => $id, 'error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Something went wrong while deleting the doctor.');
        }
    }

    public function toggleStatus($id)
    {
        $doctor = Doctor::findOrFail($id);

        $doctor->is_active = !$doctor->is_active;
        $doctor->save();

        $status = $doctor->is_active ? 'activated' : 'deactivated';

        return redirect()->back()->with('success', "doctor account has been $status successfully.");
    }
}
