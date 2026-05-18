@php
    $value = (string) ($value ?? '');
    $label = $label ?? ucfirst(str_replace('_', ' ', $value));
    $toneMap = [
        'draft' => 'secondary',
        'dibuka' => 'success',
        'ditutup' => 'danger',
        'selesai' => 'primary',
        'memenuhi' => 'success',
        'belum_memenuhi' => 'warning',
        'ditetapkan' => 'success',
        'lulus' => 'success',
        'revisi' => 'warning',
        'tidak_lulus' => 'danger',
        'dibatalkan' => 'dark',
        'pengajuan' => 'info',
        'bimbingan' => 'primary',
        'ujian' => 'warning',
        'terdaftar' => 'info',
        'terverifikasi' => 'primary',
        'hadir' => 'success',
        'batal' => 'danger',
        'belum' => 'secondary',
        'tidak_memenuhi' => 'danger',
    ];
    $tone = $tone ?? ($toneMap[$value] ?? 'secondary');
@endphp
<span class="badge bg-{{ $tone }}">{{ $label }}</span>
