<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\{Distribution, Plan};
use App\Models\Series;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\JpegEncoder;
use Intervention\Image\ImageManager;

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

            $filename = 'aktivitas_' . time() . '_' . Str::random(8) . '.jpg';

            $manager = new ImageManager(new Driver());

            $image = $manager->decode($file);

            // Resize jika terlalu besar
            $image->scaleDown(width: 1080);

            $path = storage_path('app/public/aktivitas/' . $filename);

            // Simpan JPEG kualitas 70%
            $image->save(
                $path,
                new JpegEncoder(quality: 50)
            );

            $fotoBukti = 'aktivitas/' . $filename;

            // Watermark
            $fotoWatermark = $this->addWatermark(
                $path,
                $jadwal->seri->nama,
                Str::limit($jadwal->pondok->alamat, 30, '...'),
                $jadwal->pondok->nama,
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

        if ($fotoWatermark && Storage::disk('public')->exists($fotoWatermark)) {

            $fullPath = Storage::disk('public')->path($fotoWatermark);

            Http::attach(
                'photo',
                fopen($fullPath, 'r'),
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

    private function addWatermark(
        string $imagePath,
        string $seri,
        string $alamat,
        string $pondok,
        string $tanggal,
        string $petugas
    ): ?string {

        if (!extension_loaded('gd')) {
            return null;
        }

        try {

            $info = getimagesize($imagePath);
            if (!$info) {
                return null;
            }

            $mime = $info['mime'];

            $src = match ($mime) {
                'image/jpeg' => imagecreatefromjpeg($imagePath),
                'image/png' => imagecreatefrompng($imagePath),
                default => null,
            };

            if (!$src) {
                return null;
            }

            $w = imagesx($src);
            $h = imagesy($src);

            // Aktifkan alpha transparency
            imagealphablending($src, true);
            imagesavealpha($src, true);

            $tanggalFormatted = Carbon::parse($tanggal)
                ->locale('id')
                ->translatedFormat('l, d F Y');


            // Ukuran kotak watermark (tetap)
            $boxWidth = 750;
            $boxHeight = 250;
            $padding = 12;

            // Posisi kiri bawah
            $x1 = 10;
            $y1 = $h - $boxHeight - 10;
            $x2 = $x1 + $boxWidth;
            $y2 = $y1 + $boxHeight;

            // Hitam transparan
            $bgColor = imagecolorallocatealpha($src, 0, 0, 0, 60);

            // Putih
            $textColor = imagecolorallocate($src, 255, 255, 255);

            // Kotak watermark
            imagefilledrectangle(
                $src,
                $x1,
                $y1,
                $x2,
                $y2,
                $bgColor
            );

            $font = 5;
            $fontFile = public_path('fonts/Poppins-Regular.ttf');

            // Makin rendah nilai nya, maka makin tingg

            imagettftext(
                $src,
                24,
                0,
                $x1 + $padding,
                $y1 + 30,
                $textColor,
                $fontFile,
                'Distribusi Beras Paskas Banjarmasin'
            );

            imagettftext(
                $src,
                22,
                0,
                $x1 + $padding,
                $y1 + 80,
                $textColor,
                $fontFile,
                "• Seri : $seri
"
            );

            imagettftext(
                $src,
                22,
                0,
                $x1 + $padding,
                $y1 + 110,
                $textColor,
                $fontFile,
                "• Pondok : $pondok"
            );

            imagettftext(
                $src,
                22,
                0,
                $x1 + $padding,
                $y1 + 140,
                $textColor,
                $fontFile,
                "• Alamat : $alamat"
            );

            imagettftext(
                $src,
                22,
                0,
                $x1 + $padding,
                $y1 + 170,
                $textColor,
                $fontFile,
                "• Tanggal : $tanggalFormatted"
            );

            imagettftext(
                $src,
                22,
                0,
                $x1 + $padding,
                $y1 + 200,
                $textColor,
                $fontFile,
                "• Petugas : $petugas"
            );

            imagettftext(
                $src,
                18,
                0,
                $x1 + $padding,
                $y1 + 240,
                $textColor,
                $fontFile,
                'Generated by : MyPaskas'
            );

            $wmFilename = 'wm_' . basename($imagePath);
            $wmPath = dirname($imagePath) . '/' . $wmFilename;

            if ($mime === 'image/jpeg') {
                imagejpeg($src, $wmPath, 70);
            } else {
                imagepng($src, $wmPath);
            }

            imagedestroy($src);

            return 'aktivitas/' . $wmFilename;

        } catch (\Throwable $e) {
            return null;
        }
    }
}