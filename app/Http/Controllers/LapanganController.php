<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Lapangan;
use App\Models\JenisLapangan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LapanganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = Lapangan::with('jenisLapangan');

        $totalLapangan = $query->count();
        $lapangan = $query->latest()->paginate(10);
        
        $view = Auth::user()->role == 'admin' ? 'admin.admindashboard' : 'user.userdashboard';
        $bookingpending = Booking::where('status', 'pending')->count();
        $bookingapproved = Booking::where('status', 'confirmed')->count();
        $totalbooking = Booking::count();
        $bookings = Booking::with(['lapangan.jenisLapangan'])->latest()->paginate(10);

        return view($view, compact('lapangan', 'totalLapangan', 'bookingpending', 'bookingapproved', 'totalbooking', 'bookings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $jenis_lapangan = JenisLapangan::all();

        return view('admin.tambahlapangan', compact('jenis_lapangan'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_lapangan'     => 'required|string|max:255',
            'jenis_lapangan'    => 'required|exists:jenis_lapangans,id',
            'gambar_lapangan'   => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:3072',
            'jam_buka'          => 'required',
            'jam_tutup'         => 'required',
            'harga_sewa'        => 'required|numeric|min:0',
            'deskripsi_lapangan'=> 'nullable|string',
            'status'            => 'nullable|in:Tersedia,Penuh',
        ], [
            'harga_sewa.min'        => 'Harga sewa tidak boleh minus',
            'jenis_lapangan.exists' => 'Jenis lapangan tidak valid',
        ]);

        $imagePath = null;
        if ($request->hasFile('gambar_lapangan')) {
            $file = $request->file('gambar_lapangan');
            $imagePath = $this->handleUploadImage($file);
        }
        
        Lapangan::create([
            'nama_lapangan'      => $request->nama_lapangan,
            'jenis_lapangan'     => $request->jenis_lapangan,
            'gambar_lapangan'    => $imagePath,
            'deskripsi_lapangan' => $request->deskripsi_lapangan,
            'harga_sewa'         => $request->harga_sewa,
            'status'             => $request->status ?? 'Tersedia',
            'jam_buka'           => $request->jam_buka,
            'jam_tutup'          => $request->jam_tutup
        ]);

        return redirect()->route('admin.semua-lapangan')
            ->with('success', 'Lapangan berhasil ditambahkan.');
    }

    public function getAll(Request $request)
    {
        $query = Lapangan::with('jenisLapangan')->latest();
 
        if ($request->filled('search')) {
            $query->where('nama_lapangan', 'like', '%' . $request->search . '%');
        }
 
        if ($request->filled('jenis')) {
            $query->where('jenis_lapangan', $request->jenis);
        }
 
        $jenis_lapangan = JenisLapangan::all();
        $totalLapangan  = Lapangan::count();
 
        if (Auth::user()->role === 'admin') {
            $lapangan = $query->paginate(10)->withQueryString();
            return view('admin.adminsemualapangan', compact('lapangan', 'totalLapangan', 'jenis_lapangan'));
        }
 
        $lapangan = $query->paginate(9)->withQueryString();
        return view('user.temukanlapangan', compact('lapangan', 'jenis_lapangan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Lapangan $lapangan)
    {
        $jenis_lapangan = JenisLapangan::all();

        return view('admin.editlapangan', compact('lapangan', 'jenis_lapangan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Lapangan $lapangan)
    {
        $request->validate([
            'nama_lapangan'      => 'required|string|max:255',
            'jenis_lapangan'     => 'required|exists:jenis_lapangans,id',
            'harga_sewa'         => 'required|numeric|min:0',
            'gambar_lapangan'    => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:3072',
            'jam_buka'           => 'required',
            'jam_tutup'          => 'required',
            'status'             => 'nullable|in:Tersedia,Penuh',
            'deskripsi_lapangan' => 'nullable|string',
        ]);

        $dataUpdate = [
            'nama_lapangan'      => $request->nama_lapangan,
            'jenis_lapangan'     => $request->jenis_lapangan,
            'deskripsi_lapangan' => $request->deskripsi_lapangan,
            'harga_sewa'         => $request->harga_sewa,
            'status'             => $request->status ?? $lapangan->status ?? 'Tersedia',
            'jam_buka'           => $request->jam_buka,
            'jam_tutup'          => $request->jam_tutup,
        ];

        if ($request->hasFile('gambar_lapangan')) {
            if ($lapangan->gambar_lapangan) {
                $this->handleDeleteImage($lapangan->gambar_lapangan);
            }

            $file = $request->file('gambar_lapangan');
            $dataUpdate['gambar_lapangan'] = $this->handleUploadImage($file);
        }

        $lapangan->update($dataUpdate);

        return redirect()->route('admin.semua-lapangan')
            ->with('success', 'Lapangan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lapangan $lapangan)
    {
        if ($lapangan->gambar_lapangan) {
            $this->handleDeleteImage($lapangan->gambar_lapangan);
        }

        $lapangan->delete();

        return redirect()->route('admin.semua-lapangan')
            ->with('success', 'Lapangan berhasil dihapus.');
    }

    public function show(Lapangan $lapangan)
    {
        $lapangan->load('jenisLapangan');

        if (Auth::user()->role === 'admin') {
            $totalBooking = Booking::where('lapangan_id', $lapangan->id)->count();

            $bookingsConfirmed = Booking::where('lapangan_id', $lapangan->id)
                ->whereIn('status', ['confirmed', 'completed'])
                ->get();

            $totalPendapatan = $bookingsConfirmed->sum(function ($b) use ($lapangan) {
                $durasi = Carbon::parse($b->jam_mulai)->diffInHours(Carbon::parse($b->jam_selesai));
                return $lapangan->harga_sewa * $durasi;
            });

            $jamTerpakai = $bookingsConfirmed->sum(function ($b) {
                return Carbon::parse($b->jam_mulai)->diffInHours(Carbon::parse($b->jam_selesai));
            });

            $recentBookings = Booking::where('lapangan_id', $lapangan->id)
                ->with('user')
                ->latest()
                ->take(5)
                ->get();

            return view('admin.detaillapangan', compact(
                'lapangan',
                'totalPendapatan',
                'totalBooking',
                'jamTerpakai',
                'recentBookings'
            ));
        }

        return view('user.userdetaillapangan', compact('lapangan'));
    }

    private function handleUploadImage($file): string
    {
        $supabaseUrl = env('SUPABASE_URL');
        $supabaseKey = env('SUPABASE_API_KEY');
        $bucket = env('SUPABASE_BUCKET', 'lapangan');

        if ($supabaseUrl && $supabaseKey) {
            try {
                $fileName = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                $fileContent = file_get_contents($file->getRealPath());

                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $supabaseKey,
                    'apiKey' => $supabaseKey,
                ])->attach('file', $fileContent, $fileName)
                  ->post("{$supabaseUrl}/storage/v1/object/{$bucket}/{$fileName}");

                if ($response->successful()) {
                    return "{$supabaseUrl}/storage/v1/object/public/{$bucket}/{$fileName}";
                }
            } catch (\Throwable $e) {
                // Fallback to local storage if Supabase fails
            }
        }

        // Simpan ke storage/app/public/lapangans
        $path = $file->store('lapangans', 'public');
        return $path;
    }

    private function handleDeleteImage(string $imagePath): void
    {
        if (str_starts_with($imagePath, 'http://') || str_starts_with($imagePath, 'https://')) {
            $supabaseUrl = env('SUPABASE_URL');
            $supabaseKey = env('SUPABASE_API_KEY');
            $bucket = env('SUPABASE_BUCKET', 'lapangan');

            if ($supabaseUrl && $supabaseKey) {
                try {
                    $baseUrl = "{$supabaseUrl}/storage/v1/object/public/{$bucket}/";
                    $fileName = str_replace($baseUrl, '', $imagePath);
                    if ($fileName && $fileName !== $imagePath) {
                        Http::withHeaders([
                            'Authorization' => 'Bearer ' . $supabaseKey,
                            'apiKey' => $supabaseKey,
                        ])->delete("{$supabaseUrl}/storage/v1/object/{$bucket}/{$fileName}");
                    }
                } catch (\Throwable $e) {
                    // Ignore deletion error
                }
            }
        } else {
            Storage::disk('public')->delete($imagePath);
        }
    }
}