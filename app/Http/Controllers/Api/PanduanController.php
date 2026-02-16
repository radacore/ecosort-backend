<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Panduan;
use Illuminate\Http\JsonResponse;

class PanduanController extends Controller
{
    /**
     * Return list of active panduan items, ordered by urutan.
     */
    public function index(): JsonResponse
    {
        $panduans = Panduan::active()
            ->ordered()
            ->get(['id', 'judul', 'ikon', 'deskripsi', 'konten', 'urutan']);

        return response()->json([
            'status' => 'success',
            'data' => $panduans,
        ]);
    }
}
