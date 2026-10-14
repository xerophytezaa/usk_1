<?php

namespace App\Http\Controllers;

use App\Models\Peserta;
use App\Models\Skema;
use Illuminate\Http\Request;

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
            'skema_id'     => 'required|exists:skemas,id',
            'nik'          => 'required|numeric|digits:16|unique:pesertas,nik',
            'nama_lengkap' => 'required|string|max:255',
            'email'        => 'required|email|max:255',
            'no_hp'        => 'required|numeric',
            'alamat'       => 'required|string',
        ], [
            'nik.unique'  => 'NIK ini sudah terdaftar sebagai peserta.',
            'nik.digits'  => 'NIK harus berjumlah tepat 16 digit.',
            'nik.numeric' => 'NIK harus berupa angka.',
        ]);

        Peserta::create($validated);

        return redirect()->route('register.index')
            ->with('success', 'Pendaftaran berhasil! Data Anda telah tersimpan di sistem.');
    }
}