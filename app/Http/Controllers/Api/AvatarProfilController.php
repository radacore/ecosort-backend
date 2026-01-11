<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AvatarProfil;
use App\Models\Pengguna;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class AvatarProfilController extends Controller
{
    /**
     * Menampilkan daftar semua avatar profil
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $avatars = AvatarProfil::all();
        
        return response()->json([
            'avatars' => $avatars
        ]);
    }

    /**
     * Mengupdate avatar profil pengguna
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateAvatar(Request $request)
    {
        $pengguna = $request->user();
        
        // Validasi input
        $validator = Validator::make($request->all(), [
            'avatar_profil_id' => 'required|exists:avatar_profil,id',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }
        
        // Update avatar profil pengguna
        $pengguna->update([
            'avatar_profil_id' => $request->avatar_profil_id
        ]);
        
        // Load relasi avatarProfil
        $pengguna->load('avatarProfil');
        
        return response()->json([
            'message' => 'Avatar profil berhasil diperbarui',
            'pengguna' => $pengguna
        ]);
    }
    
    /**
     * Upload avatar profil kustom
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadAvatar(Request $request)
    {
        $pengguna = $request->user();
        
        // Validasi input
        $validator = Validator::make($request->all(), [
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }
        
        // Simpan file avatar
        $file = $request->file('avatar');
        $fileName = time() . '_' . $pengguna->id . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('avatars', $fileName, 'public');
        
        // Buat entri avatar profil kustom
        $avatarProfil = AvatarProfil::create([
            'nama' => $pengguna->nama,
            'path' => $path,
        ]);
        
        // Update avatar profil pengguna
        $pengguna->update([
            'avatar_profil_id' => $avatarProfil->id
        ]);
        
        // Load relasi avatarProfil
        $pengguna->load('avatarProfil');
        
        return response()->json([
            'message' => 'Avatar profil berhasil diunggah',
            'pengguna' => $pengguna
        ]);
    }
}
