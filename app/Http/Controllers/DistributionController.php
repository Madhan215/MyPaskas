<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\{Distribution, Plan};
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class DistributionController extends Controller
{
    public function index()
    {
        $aktivitas = Distribution::with(['pondok', 'seri', 'user'])
            ->latest()
            ->paginate(15);
        return view('distribution.index', compact('aktivitas'));
    }

    public function create(Request $request)
    {
        $jadwalId = $request->get('jadwal_id');
        $jadwal = $jadwalId ? Plan::with('pondok', 'seri')->findOrFail($jadwalId) : null;
        $jadwals = Plan::with(['pondok', 'seri'])
            ->where('status', 'belum')
            ->orderBy('tanggal')
            ->get();
        return view('distribution.form', compact('jadwal', 'jadwals'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jadwal_id'                => 'required|exists:plans,id',
            'tanggal_distribusi'       => 'required|date',
            'jumlah_karung_distribusi' => 'required|integer|min:1',
            'foto_bukti'               => 'nullable|image|mimes:jpg,jpeg,png,heif,heic|max:5120',
        ], [
            'jadwal_id.required'                => 'Jadwal wajib dipilih',
            'tanggal_distribusi.required'       => 'Tanggal realisasi wajib diisi',
            'jumlah_karung_distribusi.required' => 'Jumlah karung wajib diisi',
            'foto_bukti.image'                  => 'File harus berupa foto',
            'foto_bukti.max'                    => 'Ukuran foto maksimal 5MB',
        ]);

        $jadwal = Plan::with('pondok', 'seri')->findOrFail($request->jadwal_id);

        $fotoBukti = null;
        $fotoWatermark = null;

        if ($request->hasFile('foto_bukti')) {
            $file = $request->file('foto_bukti');
            $filename = 'aktivitas_' . time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/aktivitas'), $filename);
            $fotoBukti = 'uploads/aktivitas/' . $filename;

            // Buat watermark menggunakan GD
            $fotoWatermark = $this->addWatermark(
                public_path($fotoBukti),
                $jadwal->pondok->nama,
                $jadwal->pondok->alamat,
                $request->tanggal_distribusi,
                Auth::user()->name
            );
        }

        $aktivitas = Distribution::create([
            'jadwal_id'                => $jadwal->id,
            'pondok_id'                => $jadwal->pondok_id,
            'seri_id'                  => $jadwal->seri_id,
            'tanggal_distribusi'       => $request->tanggal_distribusi,
            'jam_distribusi'           => now()->format('H:i:s'),
            'jumlah_karung_distribusi' => $request->jumlah_karung_distribusi,
            'jumlah_kg_distribusi'     => $request->jumlah_karung_distribusi * 10,
            'catatan'                  => $request->catatan,
            'foto_bukti'               => $fotoBukti,
            'foto_watermark'           => $fotoWatermark,
            'user_id'                  => Auth::id(),
        ]);

        // Update status jadwal
        $jadwal->update(['status' => 'selesai']);

        /*
   |--------------------------------------------------------------------------
   | KIRIM TELEGRAM DENGAN FOTO
   |--------------------------------------------------------------------------
   */

        $caption = "
📦 <b>MyPaskas Real Time - Bot</b>

✅ Distribusi Beras

📚 <b>Seri:</b>
{$jadwal->seri->nama}

🏠 <b>Yayasan:</b> {$jadwal->pondok->nama}

📍 <b>Alamat:</b>
{$jadwal->pondok->alamat}

🌾 <b>Jumlah:</b>
{$request->jumlah_karung_distribusi} Karung

📅 <b>Hari, Tanggal Distribusi:</b>
" . Carbon::parse($request->tanggal_distribusi)
                ->locale('id')
                ->translatedFormat('l, d F Y') . "

🕒 <b>Jam Distribusi:</b>
" . now()->format('H:i:s') . " WITA

📝 <b>Catatan:</b>
<i>" . ($request->catatan ?? '-') . "</i>

👤 <b>Petugas:</b>
" . Auth::user()->name;


        /*
        |--------------------------------------------------------------------------
        | Jika ada foto
        |--------------------------------------------------------------------------
        */

        if ($fotoWatermark && file_exists(public_path($fotoWatermark))) {

            Http::attach(
                'photo',
                fopen(public_path($fotoWatermark), 'r'),
                basename($fotoWatermark)
            )->post(
                    'https://api.telegram.org/bot' . config('services.telegram.bot_token') . '/sendPhoto',
                    [
                        'chat_id'    => config('services.telegram.chat_id'),
                        'caption'    => $caption,
                        'parse_mode' => 'HTML',
                    ]
                );

        } else {

            /*
            |--------------------------------------------------------------------------
            | Jika tidak ada foto
            |--------------------------------------------------------------------------
            */

            Http::post(
                'https://api.telegram.org/bot' . config('services.telegram.bot_token') . '/sendMessage',
                [
                    'chat_id'    => config('services.telegram.chat_id'),
                    'text'       => $caption,
                    'parse_mode' => 'HTML',
                ]
            );


        }

        return redirect()->route('aktivitas.index')->with('success', 'Aktivitas penyaluran berhasil disimpan');
    }

    public function show(Distribution $aktivitas)
    {
        $aktivitas->load(['pondok', 'seri', 'user', 'jadwal']);
        return view('distribution.show', compact('aktivitas'));
    }

    private function addWatermark(string $imagePath, string $pondok, string $alamat, string $tanggal, string $petugas): ?string
    {
        if (!extension_loaded('gd'))
            return null;

        try {
            $info = getimagesize($imagePath);
            if (!$info)
                return null;

            $mime = $info['mime'];
            $src = match ($mime) {
                'image/jpeg' => imagecreatefromjpeg($imagePath),
                'image/png' => imagecreatefrompng($imagePath),
                default => null,
            };
            if (!$src)
                return null;

            $w = imagesx($src);
            $h = imagesy($src);

            // Panel watermark di bawah
            $panelH = 80;
            $dst = imagecreatetruecolor($w, $h + $panelH);

            // Copy gambar asli
            imagecopy($dst, $src, 0, 0, 0, 0, $w, $h);

            // Panel hijau tua
            $bgColor = imagecolorallocate($dst, 22, 101, 52);
            $textColor = imagecolorallocate($dst, 255, 255, 255);
            imagefilledrectangle($dst, 0, $h, $w, $h + $panelH, $bgColor);

            $font = 5; // built-in font
            $tanggalFormatted = date('d/m/Y', strtotime($tanggal));

            imagestring($dst, $font, 10, $h + 8, "Pondok : $pondok", $textColor);
            imagestring($dst, $font, 10, $h + 26, "Alamat : $alamat", $textColor);
            imagestring($dst, $font, 10, $h + 44, "Tanggal: $tanggalFormatted", $textColor);
            imagestring($dst, $font, 10, $h + 62, "Petugas: $petugas", $textColor);

            $wmFilename = 'wm_' . basename($imagePath);
            $wmPath = dirname($imagePath) . '/' . $wmFilename;

            if ($mime === 'image/jpeg') {
                imagejpeg($dst, $wmPath, 90);
            } else {
                imagepng($dst, $wmPath);
            }

            imagedestroy($src);
            imagedestroy($dst);

            return 'uploads/aktivitas/' . $wmFilename;
        } catch (\Throwable $e) {
            return null;
        }
    }
}