<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Specialty;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email:filter|unique:users,email',
            'password' => 'required|min:8',
            'specialty_id' => 'required|exists:specialties,id',
            'appointment_duration' => 'required|integer|min:15'
        ]);

        try {
            DB::beginTransaction();

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'doctor',
            ]);

            Doctor::create([
                'user_id' => $user->id,
                'specialty_id' => $request->specialty_id,
                'appointment_duration' => $request->appointment_duration,
                'bio' => $request->bio,
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'doctor added successfully');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', 'something went wrong' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $doctor = Doctor::query()->findOrFail($id);
        $user = $doctor->user;
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email:filter|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8',
            'specialty_id' => 'required|exists:specialties,id',
            'appointment_duration' => 'required|integer|min:15'
        ]);

        try {
            DB::beginTransaction();

            $userData = [
                'name' => $request->name,
                'email' => $request->email
            ];

            if ($request->filled('password'))
                $userData['password'] = Hash::make($request->password);

            $user->update($userData);

            $doctor->update([
                'specialty_id' => $request->specialty_id,
                'appointment_duration' => $request->appointment_duration,
                'bio' => $request->bio,
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'doctor updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'something went wrong' . $e->getMessage());
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
            return redirect()->back()->with('error', 'Error deleting doctor: ' . $e->getMessage());
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
