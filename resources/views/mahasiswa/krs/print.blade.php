@php
    $mahasiswa = $krs['mahasiswa'] ?? [];
    $prodi = $mahasiswa['prodi'] ?? [];
    $kaprodi = $prodi['kaprodi'] ?? null;
    $dosenWali = $mahasiswa['dosen_wali'] ?? ($mahasiswa['dosenWali'] ?? null);
    $semester = $krs['semester'] ?? [];
    $tahunAkademik = $semester['tahun_akademik'] ?? ($semester['tahunAkademik'] ?? []);
    $details = $krs['details'] ?? [];
    $semesterKe = $krs['semester_ke'] ?? null;
    $angkaKeRomawi = [
        1 => 'I',
        2 => 'II',
        3 => 'III',
        4 => 'IV',
        5 => 'V',
        6 => 'VI',
        7 => 'VII',
        8 => 'VIII',
        9 => 'IX',
        10 => 'X',
        11 => 'XI',
        12 => 'XII',
        13 => 'XIII',
        14 => 'XIV',
    ];

    $namaSemester = strtoupper((string) ($semester['nama_semester'] ?? ''));
    $tahunAkademikLabel = trim((string) ($tahunAkademik['tahun_akademik'] ?? ''));
    $tahunAkademikDisplay = trim(($namaSemester ? 'SEMESTER ' . $namaSemester . ' ' : '') . $tahunAkademikLabel);
    $semesterLabel = $tahunAkademikDisplay;
    $tanggalCetak = 'Mojokerto, ' . \Carbon\Carbon::now()->locale('id')->translatedFormat('j F Y');
    $tanggalApproval = !empty($krs['tanggal_approval'])
        ? 'Mojokerto, ' . \Carbon\Carbon::parse($krs['tanggal_approval'])->locale('id')->translatedFormat('j F Y')
        : $tanggalCetak;
    $jabatanKaprodi = 'Ketua Program Studi';
    $semesterKeLabel =
        $semesterKe && isset($angkaKeRomawi[(int) $semesterKe])
            ? $angkaKeRomawi[(int) $semesterKe]
            : ($semesterKe ?:
            '-');

    if (!empty($prodi['nama_prodi'])) {
        $jabatanKaprodi .= ' ' . $prodi['nama_prodi'];
    }
@endphp
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak KRS</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 12mm;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            color: #111827;
            font-size: 12px;
            background: #eef2f7;
        }

        .toolbar {
            max-width: 900px;
            margin: 16px auto 0;
            display: flex;
            gap: 12px;
            justify-content: flex-end;
        }

        .toolbar button,
        .toolbar a {
            border: 1px solid #cbd5e1;
            background: #ffffff;
            color: #0f172a;
            padding: 10px 14px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 12px;
            cursor: pointer;
        }

        .sheet {
            width: 210mm;
            min-height: 297mm;
            margin: 16px auto 24px;
            background: #ffffff;
            padding: 14mm 14mm 18mm;
            box-shadow: 0 8px 30px rgba(15, 23, 42, 0.08);
        }

        .header {
            display: flex;
            gap: 16px;
            align-items: center;
            border-bottom: 3px solid #166534;
            padding-bottom: 10px;
            margin-bottom: 16px;
        }

        .header img {
            width: 78px;
            height: 78px;
            object-fit: contain;
        }

        .header-text {
            flex: 1;
            text-align: center;
        }

        .header-text h1,
        .header-text h2,
        .header-text p {
            margin: 0;
        }

        .header-text h1 {
            font-size: 18px;
            color: #14532d;
            text-transform: uppercase;
        }

        .header-text h2 {
            font-size: 13px;
            margin-top: 4px;
        }

        .header-text p {
            font-size: 11px;
            margin-top: 3px;
        }

        .doc-title {
            text-align: center;
            margin: 18px 0 14px;
        }

        .doc-title h3 {
            margin: 0;
            font-size: 18px;
            letter-spacing: 0.5px;
        }

        .doc-title p {
            margin: 4px 0 0;
            font-size: 12px;
            white-space: pre-line;
            line-height: 1.35;
        }

        .meta-table,
        .courses-table {
            width: 98%;
            border-collapse: collapse;
        }

        .meta-table td {
            padding: 4px 6px;
            vertical-align: top;
        }

        .meta-table td:first-child,
        .meta-table td:nth-child(3) {
            width: 18%;
        }

        .meta-table td:nth-child(2),
        .meta-table td:nth-child(4) {
            width: 32%;
        }

        .courses-table {
            margin-top: 14px;
        }

        .courses-table th,
        .courses-table td {
            border: 1px solid #1f2937;
            padding: 7px 6px;
            vertical-align: top;
        }

        .courses-table th {
            background: #e5f3e8;
            text-align: center;
            font-size: 11px;
        }

        .text-center {
            text-align: center;
        }

        .signature-top {
            min-height: 52px;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
        }

        .signature-date {
            min-height: 18px;
            font-size: 11px;
            color: #111827;
            margin-bottom: 4px;
        }

        .signature-role {
            min-height: 34px;
            line-height: 1.22;
            font-size: 12px;
            color: #111827;
        }

        .signature-role-underline {
            text-decoration: underline;
            text-underline-offset: 2px;
        }

        .signature-space {
            height: 76px;
        }

        .signature-name {
            font-weight: bold;
            text-decoration: underline;
            text-underline-offset: 2px;
            margin-bottom: 4px;
        }

        .muted {
            color: #475569;
        }

        @media print {
            body {
                background: #ffffff;
            }

            .toolbar {
                display: none;
            }

            .sheet {
                box-shadow: none;
                margin: 0;
                width: auto;
                min-height: auto;
                padding: 0;
            }
        }
    </style>
</head>

<body>
    <div class="toolbar">
        <a href="{{ route('krs.index') }}">Kembali</a>
        <button type="button" onclick="window.print()">Cetak / Simpan PDF</button>
    </div>

    <div class="sheet">
        <div class="header">
            <img src="{{ asset('logo.png') }}" alt="Logo Kampus">
            <div class="header-text">
                <h1>Sekolah Tinggi Ilmu Kesehatan Dian Husada</h1>
                <h2>Kartu Rencana Studi Mahasiswa</h2>
                <p>Jl. Raya Teras No. 4 Tambakagung Puri Mojokerto Jawa Timur</p>
                <p>Telp. 0321-327771 | Email admin@dianhusada.ac.id | www.dianhusada.ac.id</p>
            </div>
        </div>

        <div class="doc-title">
            <h3>KRS MAHASISWA</h3>
            {{-- <p>{{ $semesterLabel ?: 'Semester -' }}</p> --}}
        </div>

        <table class="meta-table">
            <tr>
                <td>Nama</td>
                <td>: {{ $mahasiswa['nama_mahasiswa'] ?? '-' }}</td>
                <td>NIM</td>
                <td>: {{ $mahasiswa['nim'] ?? '-' }}</td>
            </tr>
            <tr>
                <td>Program Studi</td>
                <td>: ({{ $prodi['jenjang_pendidikan'] ?? '-' }}) {{ $prodi['nama_prodi'] ?? '-' }}</td>
                <td>Angkatan</td>
                <td>: {{ $mahasiswa['angkatan'] ?? '-' }}</td>
            </tr>
            <tr>
                <td>Tahun Akademik</td>
                <td>: {{ $tahunAkademikDisplay ?: '-' }}</td>
                {{-- <td>Status KRS</td>
                <td>: Disetujui Dosen Wali</td> --}}
                <td>Semester</td>
                <td>: {{ $semesterKeLabel }}</td>
            </tr>
            {{-- <tr>
                <td>Semester</td>
                <td>: {{ $semesterKeLabel }}</td>
                <td></td>
                <td></td>
            </tr> --}}
        </table>

        <table class="courses-table">
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 13%;">Kode MK</th>
                    <th>Mata Kuliah</th>
                    <th style="width: 10%;">Kelas</th>
                    <th style="width: 10%;">SMTR MK</th>
                    <th style="width: 8%;">SKS</th>
                    <th style="width: 12%;">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($details as $index => $detail)
                    @php
                        $kelas = $detail['kelas_kuliah'] ?? ($detail['kelasKuliah'] ?? []);
                        $kurikulumMataKuliah = $kelas['kurikulum_mata_kuliah'] ?? ($kelas['kurikulumMataKuliah'] ?? []);
                        $mataKuliah = $kurikulumMataKuliah['mata_kuliah'] ?? ($kurikulumMataKuliah['mataKuliah'] ?? []);
                        $statusDetail = match (strtolower((string) ($detail['status'] ?? ''))) {
                            'terdaftar' => 'Terima',
                            'lulus' => 'Lulus',
                            'drop' => 'Drop',
                            'tidak_lulus' => 'Tidak Lulus',
                            default => ucfirst(str_replace('_', ' ', (string) ($detail['status'] ?? '-'))),
                        };
                    @endphp
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td class="text-center">{{ $mataKuliah['kode_mk'] ?? '-' }}</td>
                        <td>{{ $mataKuliah['nama_mk'] ?? '-' }}</td>
                        <td class="text-center">{{ $kelas['nama_kelas'] ?? '-' }}</td>
                        <td class="text-center">{{ $kurikulumMataKuliah['semester_ke'] ?? '-' }}</td>
                        <td class="text-center">{{ $mataKuliah['sks'] ?? 0 }}</td>
                        <td class="text-center">{{ $statusDetail }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">Tidak ada mata kuliah pada KRS ini.</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="5" class="text-center">Total SKS</th>
                    <th class="text-center">{{ $krs['total_sks'] ?? 0 }}</th>
                    <th></th>
                </tr>
            </tfoot>
        </table>

        <table class="signature-table" style="width: 100%; margin-top: 36px; border: none;">
            <tr>
                <td style="width: 50%; text-align: center; vertical-align: top; padding: 0 20px;">
                    <div class="signature-top">
                        <div class="signature-date"></div>
                        <div class="signature-role">Dosen Penasehat Akademik</div>
                    </div>
                    <div class="signature-space"></div>
                    <div class="signature-name">
                        {{ $dosenWali['nama_dosen'] ?? ($krs['approved_by_detail']['nama_dosen'] ?? '-') }}</div>
                    <div>NIDN. {{ $dosenWali['nidn'] ?? ($krs['approved_by_detail']['nidn'] ?? '-') }}</div>
                </td>
                <td style="width: 50%; text-align: center; vertical-align: top; padding: 0 20px;">
                    <div class="signature-top">
                        <div class="signature-date">{{ $tanggalApproval }}</div>
                        <div class="signature-role">Mahasiswa</div>
                    </div>
                    <div class="signature-space"></div>
                    <div class="signature-name">{{ $mahasiswa['nama_mahasiswa'] ?? '-' }}</div>
                    <div>NIM. {{ $mahasiswa['nim'] ?? '-' }}</div>
                </td>
            </tr>
        </table>

        <table class="signature-table" style="width: 100%; margin-top: 20px; border: none;">
            <tr>
                <td style="text-align: center; vertical-align: top; padding: 0 20px;">
                    <div class="signature-top">
                        <div class="signature-date">{{ $tanggalApproval }}</div>
                        <div class="signature-role">{{ $jabatanKaprodi }}<br>Sekolah Tinggi
                            Ilmu Kesehatan Dian Husada</div>
                    </div>
                    <div class="signature-space"></div>
                    <div class="signature-name">{{ $kaprodi['nama_dosen'] ?? '-' }}</div>
                    <div>NIDN. {{ $kaprodi['nidn'] ?? '-' }}</div>
                </td>
            </tr>
        </table>
    </div>

    <script>
        window.addEventListener('load', () => {
            window.setTimeout(() => window.print(), 300);
        });
    </script>
</body>

</html>
