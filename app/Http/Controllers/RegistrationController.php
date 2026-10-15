<?php

namespace App\Http\Controllers;

use App\Models\Peserta;
use App\Models\Skema;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegistrationController extends Controller
{
    public function index()
    {
        $skemas = Skema::all();
        return view('landing', compact('skemas'));
    }

    public function create()
    {
        $skemas = Skema::all();
        return view('register', compact('skemas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'skema_id' => 'required|exists:skemas,id',
            'nik' => 'required|numeric|digits:16|unique:pesertas,nik',
            'nama_lengkap' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'no_hp' => 'required|numeric',
            'alamat' => 'required|string',
            'password' => 'required|min:8',
            'file_ktp' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'file_ijazah' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'file_sertifikat' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        // Upload berkas
        $ktpPath = $request->file('file_ktp')->store('peserta-ktp', 'public');
        $ijazahPath = $request->file('file_ijazah')->store('peserta-ijazah', 'public');
        $sertifikatPath = $request->hasFile('file_sertifikat')
            ? $request->file('file_sertifikat')->store('peserta-sertifikat', 'public')
            : null;

        // 1. Buat User
        $user = User::create([
            'name' => $validated['nama_lengkap'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $user->assignRole('peserta');

        // 2. Buat Record Peserta (is_active default false)
        Peserta::create([
            'user_id' => $user->id,
            'skema_id' => $validated['skema_id'],
            'nik' => $validated['nik'],
            'nama_lengkap' => $validated['nama_lengkap'],
            'email' => $validated['email'],
            'no_hp' => $validated['no_hp'],
            'alamat' => $validated['alamat'],
            'file_ktp' => $ktpPath,
            'file_ijazah' => $ijazahPath,
            'file_sertifikat' => $sertifikatPath,
            'status' => 'pending',
            'is_active' => false, // Belum diaktifkan admin
        ]);

        return redirect()->route('landing')
            ->with('success', 'Pendaftaran berhasil! Data & berkas Anda sedang ditinjau oleh Admin. Silakan tunggu verifikasi untuk bisa login.');
    }
}