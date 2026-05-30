<?php

namespace App\Services;

use App\Models\{Series, Distribution, Plan, Stok};

class PdfService
{
    /**
     * Generate a full HTML report for a Seri, then output as PDF using mPDF or plain HTML.
     * We use HTML output (browser print) since mPDF may not be installed.
     */
    public function generateSeriReport(Series $seri): string
    {
        $seri->load(['jadwals.pondok', 'aktivitas.pondok', 'aktivitas.user', 'user']);

        $jadwals = $seri->jadwals()->with('pondok')->orderBy('tanggal')->get();
        $aktivitas = $seri->aktivitas()->with(['pondok', 'user'])->orderBy('tanggal_distribusi')->get();
        $kelompoks = $jadwals->groupBy(fn($j) => $j->pondok->penanggung_jawab ?? 'Lainnya');

        $totalRencanaKarung = $jadwals->sum('jumlah_karung');
        $totalRencanaKg = $jadwals->sum('jumlah_kg');
        $totalRealisasiKarung = $aktivitas->sum('jumlah_karung_distribusi');
        $totalRealisasiKg = $aktivitas->sum('jumlah_kg_distribusi');
        $progress = $totalRencanaKarung > 0
            ? round(($totalRealisasiKarung / $totalRencanaKarung) * 100)
            : 0;

        $generatedAt = now()->isoFormat('dddd, D MMMM Y HH:mm');
        $namaAdmin = $seri->user->name ?? '-';

        ob_start();
        ?>
        <!DOCTYPE html>
        <html lang="id">

        <head>
            <meta charset="UTF-8">
            <title>Laporan <?= e($seri->nama) ?></title>
            <style>
                @import url('https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap');

                * {
                    font-family: 'Nunito', sans-serif;
                    margin: 0;
                    padding: 0;
                    box-sizing: border-box;
                }

                body {
                    background: #f8fdf9;
                    color: #1a1a1a;
                    font-size: 13px;
                }

                /* COVER */
                .cover {
                    background: linear-gradient(135deg, #14532d 0%, #166534 60%, #15803d 100%);
                    color: #fff;
                    padding: 50px 40px;
                    min-height: 220px;
                    display: flex;
                    flex-direction: column;
                    justify-content: center;
                }

                .cover-logo {
                    font-size: 3rem;
                    margin-bottom: 12px;
                }

                .cover-title {
                    font-size: 22px;
                    font-weight: 800;
                    line-height: 1.2;
                }

                .cover-sub {
                    font-size: 14px;
                    opacity: 0.85;
                    margin-top: 6px;
                }

                .cover-meta {
                    display: flex;
                    gap: 24px;
                    margin-top: 20px;
                    flex-wrap: wrap;
                }

                .cover-meta-item {
                    background: rgba(255, 255, 255, 0.15);
                    border-radius: 10px;
                    padding: 10px 16px;
                }

                .cover-meta-item .val {
                    font-size: 20px;
                    font-weight: 800;
                }

                .cover-meta-item .lbl {
                    font-size: 11px;
                    opacity: 0.8;
                }

                /* STATUS BAR */
                .status-bar {
                    background: #dcfce7;
                    padding: 10px 40px;
                    display: flex;
                    gap: 20px;
                    align-items: center;
                    border-bottom: 3px solid #16a34a;
                }

                .status-item {
                    display: flex;
                    align-items: center;
                    gap: 6px;
                    font-size: 12px;
                    font-weight: 700;
                }

                .badge {
                    padding: 3px 10px;
                    border-radius: 20px;
                    font-size: 11px;
                    font-weight: 700;
                }

                .badge-success {
                    background: #16a34a;
                    color: #fff;
                }

                .badge-warning {
                    background: #d97706;
                    color: #fff;
                }

                .badge-primary {
                    background: #2563eb;
                    color: #fff;
                }

                .badge-info {
                    background: #0891b2;
                    color: #fff;
                }

                /* MAIN */
                .main {
                    padding: 24px 40px;
                }

                /* PROGRESS */
                .progress-section {
                    background: #fff;
                    border-radius: 12px;
                    padding: 20px;
                    margin-bottom: 20px;
                    box-shadow: 0 2px 8px rgba(0, 0, 0, .06);
                }

                .progress-title {
                    font-size: 14px;
                    font-weight: 800;
                    color: #14532d;
                    margin-bottom: 12px;
                }

                .progress-bar-wrap {
                    background: #e5e7eb;
                    border-radius: 10px;
                    height: 18px;
                    overflow: hidden;
                }

                .progress-bar-fill {
                    background: linear-gradient(90deg, #16a34a, #22c55e);
                    height: 100%;
                    border-radius: 10px;
                    display: flex;
                    align-items: center;
                    justify-content: flex-end;
                    padding-right: 6px;
                }

                .progress-bar-text {
                    color: #fff;
                    font-size: 10px;
                    font-weight: 800;
                }

                .stats-row {
                    display: flex;
                    gap: 12px;
                    margin-top: 14px;
                }

                .stat-box {
                    flex: 1;
                    text-align: center;
                    padding: 12px;
                    border-radius: 10px;
                }

                .stat-box .n {
                    font-size: 22px;
                    font-weight: 800;
                }

                .stat-box .l {
                    font-size: 10px;
                    font-weight: 600;
                    opacity: .7;
                    margin-top: 2px;
                }

                .green-box {
                    background: #f0fdf4;
                    color: #15803d;
                }

                .blue-box {
                    background: #eff6ff;
                    color: #1d4ed8;
                }

                .amber-box {
                    background: #fffbeb;
                    color: #92400e;
                }

                .red-box {
                    background: #fef2f2;
                    color: #991b1b;
                }

                /* SECTION */
                .section {
                    margin-bottom: 24px;
                }

                .section-title {
                    font-size: 13px;
                    font-weight: 800;
                    color: #14532d;
                    border-left: 4px solid #16a34a;
                    padding-left: 10px;
                    margin-bottom: 12px;
                    display: flex;
                    align-items: center;
                    gap: 8px;
                }

                /* TABLE */
                table {
                    width: 100%;
                    border-collapse: collapse;
                    background: #fff;
                    border-radius: 10px;
                    overflow: hidden;
                    box-shadow: 0 2px 6px rgba(0, 0, 0, .05);
                }

                thead tr {
                    background: linear-gradient(90deg, #14532d, #166534);
                    color: #fff;
                }

                th {
                    padding: 10px 12px;
                    text-align: left;
                    font-size: 11px;
                    font-weight: 700;
                    text-transform: uppercase;
                    letter-spacing: .3px;
                }

                td {
                    padding: 9px 12px;
                    border-bottom: 1px solid #f0fdf4;
                    font-size: 12px;
                    vertical-align: middle;
                }

                tr:last-child td {
                    border-bottom: none;
                }

                tr:nth-child(even) {
                    background: #f8fdf9;
                }

                .group-header {
                    background: #dcfce7 !important;
                }

                .group-header td {
                    font-weight: 800;
                    color: #14532d;
                    font-size: 11px;
                }

                .total-row {
                    background: #f0fdf4 !important;
                }

                .total-row td {
                    font-weight: 800;
                    color: #14532d;
                }

                .grand-total {
                    background: linear-gradient(90deg, #14532d, #166534) !important;
                }

                .grand-total td {
                    color: #fff !important;
                    font-weight: 800;
                }

                .text-center {
                    text-align: center;
                }

                .text-right {
                    text-align: right;
                }

                /* FOTO GRID */
                .foto-grid {
                    display: grid;
                    grid-template-columns: repeat(3, 1fr);
                    gap: 12px;
                }

                .foto-item {
                    border-radius: 10px;
                    overflow: hidden;
                    border: 2px solid #dcfce7;
                }

                .foto-item img {
                    width: 100%;
                    height: 120px;
                    object-fit: cover;
                    display: block;
                }

                .foto-item .foto-cap {
                    padding: 6px 8px;
                    background: #f0fdf4;
                }

                .foto-item .foto-cap .nama {
                    font-size: 11px;
                    font-weight: 700;
                    color: #14532d;
                }

                .foto-item .foto-cap .tgl {
                    font-size: 10px;
                    color: #6b7280;
                }

                /* FOOTER */
                .footer {
                    background: #14532d;
                    color: rgba(255, 255, 255, .7);
                    padding: 16px 40px;
                    font-size: 11px;
                    display: flex;
                    justify-content: space-between;
                    margin-top: 20px;
                }

                /* Print */
                @media print {
                    body {
                        background: #fff;
                    }

                    .no-print {
                        display: none;
                    }

                    .cover {
                        -webkit-print-color-adjust: exact;
                        print-color-adjust: exact;
                    }
                }
            </style>
        </head>

        <body>

            <!-- PRINT BUTTON -->
            <div class="no-print" style="position:fixed;top:16px;right:16px;z-index:999;display:flex;gap:8px">
                <button onclick="window.print()"
                    style="background:#16a34a;color:#fff;border:none;padding:12px 22px;border-radius:10px;font-weight:700;font-size:14px;cursor:pointer;box-shadow:0 4px 12px rgba(22,163,74,.4)">
                    🖨️ Cetak / Simpan PDF
                </button>
                <button onclick="window.close()"
                    style="background:#6b7280;color:#fff;border:none;padding:12px 18px;border-radius:10px;font-weight:700;font-size:14px;cursor:pointer">
                    ✕ Tutup
                </button>
            </div>

            <!-- COVER -->
            <div class="cover">
                <div class="cover-logo">🌾</div>
                <div class="cover-title">Laporan Distribusi Beras</div>
                <div class="cover-sub"><?= e($seri->nama) ?> &nbsp;·&nbsp; <?= e($seri->tanggal_mulai->isoFormat('D MMM')) ?> –
                    <?= e($seri->tanggal_selesai->isoFormat('D MMM Y')) ?>
                </div>
                <div class="cover-meta">
                    <div class="cover-meta-item">
                        <div class="val"><?= $jadwals->count() ?></div>
                        <div class="lbl">Titik Distribusi</div>
                    </div>
                    <div class="cover-meta-item">
                        <div class="val"><?= number_format($totalRencanaKarung) ?></div>
                        <div class="lbl">Total Karung</div>
                    </div>
                    <div class="cover-meta-item">
                        <div class="val"><?= number_format($totalRencanaKg) ?> kg</div>
                        <div class="lbl">Total Beras</div>
                    </div>
                    <div class="cover-meta-item">
                        <div class="val"><?= $progress ?>%</div>
                        <div class="lbl">Progress Realisasi</div>
                    </div>
                </div>
            </div>

            <!-- STATUS BAR -->
            <div class="status-bar">
                <div class="status-item">
                    <span>Status:</span>
                    <span
                        class="badge badge-<?= $seri->status === 'aktif' ? 'success' : ($seri->status === 'selesai' ? 'primary' : 'warning') ?>">
                        <?= ucfirst($seri->status) ?>
                    </span>
                </div>
                <div class="status-item">📅 Dibuat oleh: <?= e($namaAdmin) ?></div>
                <div class="status-item" style="margin-left:auto; font-weight:400; opacity:.7">
                    Dicetak: <?= $generatedAt ?>
                </div>
            </div>

            <div class="main">

                <!-- PROGRESS SECTION -->
                <div class="progress-section">
                    <div class="progress-title">📊 Ringkasan Progres Distribusi</div>
                    <div class="progress-bar-wrap">
                        <div class="progress-bar-fill" style="width:<?= $progress ?>%">
                            <span class="progress-bar-text"><?= $progress ?>%</span>
                        </div>
                    </div>
                    <div class="stats-row">
                        <div class="stat-box green-box">
                            <div class="n"><?= number_format($totalRencanaKarung) ?></div>
                            <div class="l">Rencana (Karung)</div>
                        </div>
                        <div class="stat-box blue-box">
                            <div class="n"><?= number_format($totalRealisasiKarung) ?></div>
                            <div class="l">Realisasi (Karung)</div>
                        </div>
                        <div class="stat-box amber-box">
                            <div class="n"><?= number_format($totalRencanaKarung - $totalRealisasiKarung) ?></div>
                            <div class="l">Sisa (Karung)</div>
                        </div>
                        <div class="stat-box green-box">
                            <div class="n"><?= number_format($totalRencanaKg) ?> kg</div>
                            <div class="l">Total KG</div>
                        </div>
                    </div>
                </div>

                <!-- JADWAL PER KELOMPOK -->
                <div class="section">
                    <div class="section-title">📋 Detail Jadwal Distribusi Per Kelompok</div>
                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Pondok / Yayasan</th>
                                <th>Alamat</th>
                                <th class="text-center">Santri</th>
                                <th class="text-center">Rencana (Krg)</th>
                                <th class="text-center">Rencana (KG)</th>
                                <th class="text-center">Tanggal</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            foreach ($kelompoks as $kelompok => $items):
                                $subtotalKrg = $items->sum('jumlah_karung');
                                $subtotalKg = $items->sum('jumlah_kg');
                                ?>
                                <tr class="group-header">
                                    <td colspan="8">👥 <?= e($kelompok) ?> — <?= $items->count() ?> titik</td>
                                </tr>
                                <?php foreach ($items as $j): ?>
                                    <tr>
                                        <td class="text-center"><?= $no++ ?></td>
                                        <td><strong><?= e($j->pondok->nama) ?></strong></td>
                                        <td><?= e($j->pondok->alamat) ?></td>
                                        <td class="text-center"><?= number_format($j->pondok->jumlah_santri) ?></td>
                                        <td class="text-center"><strong><?= $j->jumlah_karung ?></strong></td>
                                        <td class="text-center"><?= $j->jumlah_kg ?></td>
                                        <td class="text-center"><?= $j->tanggal->isoFormat('D MMM') ?></td>
                                        <td class="text-center">
                                            <span
                                                class="badge badge-<?= $j->status === 'selesai' ? 'success' : ($j->status === 'ditunda' ? 'warning' : 'info') ?>">
                                                <?= ucfirst($j->status) ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <tr class="total-row">
                                    <td colspan="4" class="text-right">Sub Total <?= e($kelompok) ?>:</td>
                                    <td class="text-center"><?= $subtotalKrg ?> karung</td>
                                    <td class="text-center"><?= $subtotalKg ?> kg</td>
                                    <td colspan="2"></td>
                                </tr>
                            <?php endforeach; ?>
                            <tr class="grand-total">
                                <td colspan="4" class="text-right">🌾 GRAND TOTAL</td>
                                <td class="text-center"><?= $totalRencanaKarung ?> karung</td>
                                <td class="text-center"><?= $totalRencanaKg ?> kg</td>
                                <td colspan="2"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- REALISASI -->
                <?php if ($aktivitas->count() > 0): ?>
                    <div class="section">
                        <div class="section-title">✅ Realisasi Penyaluran</div>
                        <table>
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Pondok / Yayasan</th>
                                    <th>Tanggal Realisasi</th>
                                    <th class="text-center">Karung</th>
                                    <th class="text-center">KG</th>
                                    <th>Petugas</th>
                                    <th>Catatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($aktivitas as $i => $a): ?>
                                    <tr>
                                        <td class="text-center"><?= $i + 1 ?></td>
                                        <td><strong><?= e($a->pondok->nama) ?></strong><br><span
                                                style="color:#6b7280;font-size:10px"><?= e($a->pondok->alamat) ?></span></td>
                                        <td><?= $a->tanggal_distribusi->isoFormat('D MMMM Y') ?><?php if ($a->jam_distribusi): ?><br><span
                                                    style="color:#6b7280;font-size:10px"><?= e($a->jam_distribusi) ?></span><?php endif; ?>
                                        </td>
                                        <td class="text-center"><strong><?= $a->jumlah_karung_distribusi ?></strong></td>
                                        <td class="text-center"><?= $a->jumlah_kg_distribusi ?></td>
                                        <td><?= e($a->user->name) ?></td>
                                        <td style="font-size:11px;color:#6b7280"><?= e($a->catatan ?? '-') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                <tr class="grand-total">
                                    <td colspan="3" class="text-right">TOTAL REALISASI</td>
                                    <td class="text-center"><?= $totalRealisasiKarung ?> karung</td>
                                    <td class="text-center"><?= $totalRealisasiKg ?> kg</td>
                                    <td colspan="2"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>

                <!-- FOTO BUKTI -->
                <?php
                $fotoList = $aktivitas->filter(fn($a) => $a->foto_watermark || $a->foto_bukti);
                if ($fotoList->count() > 0):
                    ?>
                    <div class="section">
                        <div class="section-title">📸 Foto Bukti Penyaluran</div>
                        <div class="foto-grid">
                            <?php foreach ($fotoList as $a):
                                $fotoPath = $a->foto_watermark ?? $a->foto_bukti;
                                $fotoUrl = asset($fotoPath);
                                ?>
                                <div class="foto-item">
                                    <img src="<?= e($fotoUrl) ?>" alt="Foto <?= e($a->pondok->nama) ?>">
                                    <div class="foto-cap">
                                        <div class="nama">🕌 <?= e($a->pondok->nama) ?></div>
                                        <div class="tgl">📅 <?= $a->tanggal_distribusi->isoFormat('D MMM Y') ?> ·
                                            <?= $a->jumlah_karung_distribusi ?> karung
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

            </div><!-- /main -->

            <!-- FOOTER -->
            <div class="footer">
                <span>🌾 Sistem Distribusi Beras &nbsp;·&nbsp; <?= e($seri->nama) ?></span>
                <span>Dicetak: <?= $generatedAt ?></span>
            </div>

        </body>

        </html>
        <?php
        return ob_get_clean();
    }
}