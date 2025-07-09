<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Doctor;

class DoctorController extends Controller
{
    public function index()
    {
        $doctors = Doctor::all();
        return view('admin.doctor.index', compact('doctors'));
    }

    public function create()
    {
        return view('admin.doctor.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'specialist' => 'required|string',
        ]);

        Doctor::create($request->only('name', 'specialist'));

        // PERUBAHAN: Menggunakan nama route singular 'admin.doctor.index'
        return redirect()->route('admin.doctor.index')->with('success', 'Dokter berhasil ditambahkan');
    }

    public function edit($id)
    {
        $doctor = Doctor::findOrFail($id);
        return view('admin.doctor.edit', compact('doctor'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string',
            'specialist' => 'required|string',
        ]);

        $doctor = Doctor::findOrFail($id);
        $doctor->update($request->only('name', 'specialist'));

        // PERUBAHAN: Menggunakan nama route singular 'admin.doctor.index'
        return redirect()->route('admin.doctor.index')->with('success', 'Data dokter berhasil diupdate');
    }

    public function destroy($id)
    {
        $doctor = Doctor::findOrFail($id);
        $doctor->delete();

        // PERUBAHAN: Menggunakan nama route singular 'admin.doctor.index'
        return redirect()->route('admin.doctor.index')->with('success', 'Data dokter berhasil dihapus');
    }
}