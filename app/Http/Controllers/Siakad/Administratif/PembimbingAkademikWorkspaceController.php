<?php

namespace App\Http\Controllers\Siakad\Administratif;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class PembimbingAkademikWorkspaceController extends Controller
{
    protected string $apiUrl;
    protected string $apiToken;

    public function __construct()
    {
        $this->apiUrl = config('api.base_url');
        $this->apiToken = session('access_token');
    }

    public function index()
    {
        try {
            $statisticsResponse = Http::withToken($this->apiToken)->get($this->apiUrl . 'krs-dosen/statistics');
            $pendingResponse = Http::withToken($this->apiToken)->get($this->apiUrl . 'krs-dosen/pending');
            $bimbinganResponse = Http::withToken($this->apiToken)->get($this->apiUrl . 'krs-dosen/mahasiswa-bimbingan');

            if (!$statisticsResponse->successful() || !$pendingResponse->successful() || !$bimbinganResponse->successful()) {
                return back()->with('error', 'Gagal mengambil data workspace pembimbing akademik dari API.');
            }

            $statistics = $statisticsResponse->json('data', []);
            $pendingKrs = $pendingResponse->json('data', []);
            $mahasiswaBimbingan = $bimbinganResponse->json('data', []);

            $kpis = [
                [
                    'title' => 'Mahasiswa Bimbingan',
                    'value' => number_format((int) ($statistics['total_mahasiswa_wali'] ?? count($mahasiswaBimbingan))),
                    'description' => 'Jumlah mahasiswa yang berada dalam tanggung jawab bimbingan akademik Anda.',
                    'icon' => 'fas fa-user-friends',
                    'theme' => 'primary',
                ],
                [
                    'title' => 'KRS Pending',
                    'value' => number_format((int) ($statistics['pending_approval'] ?? count($pendingKrs))),
                    'description' => 'Pengajuan KRS yang menunggu review dan keputusan Anda.',
                    'icon' => 'fas fa-hourglass-half',
                    'theme' => 'warning',
                ],
                [
                    'title' => 'Disetujui',
                    'value' => number_format((int) ($statistics['approved_this_semester'] ?? 0)),
                    'description' => 'KRS yang sudah Anda setujui pada semester aktif.',
                    'icon' => 'fas fa-circle-check',
                    'theme' => 'success',
                ],
                [
                    'title' => 'Revisi',
                    'value' => number_format((int) ($statistics['revised_this_semester'] ?? 0)),
                    'description' => 'KRS yang Anda kembalikan untuk diperbaiki mahasiswa.',
                    'icon' => 'fas fa-pen-to-square',
                    'theme' => 'info',
                ],
            ];

            $workflowGroups = [
                [
                    'title' => 'Bimbingan Akademik',
                    'description' => 'Pusat kerja untuk membaca beban bimbingan dan pengajuan KRS mahasiswa.',
                    'links' => [
                        ['label' => 'Workspace Pembimbing', 'route' => route('workspace.pembimbing-akademik')],
                        ['label' => 'KRS Bimbingan', 'route' => route('dosenpa.krs.index')],
                    ],
                ],
                [
                    'title' => 'Operasional Review',
                    'description' => 'Langkah kerja utama saat memeriksa pengajuan KRS mahasiswa bimbingan.',
                    'links' => [
                        ['label' => 'Approval KRS', 'route' => route('dosenpa.krs.index')],
                        ['label' => 'Profil Saya', 'route' => route('profile')],
                    ],
                ],
            ];

            $recentPending = array_slice($pendingKrs, 0, 5);
            $recentBimbingan = array_slice($mahasiswaBimbingan, 0, 5);

            return view('administratif.pembimbing_akademik.index', compact(
                'kpis',
                'statistics',
                'pendingKrs',
                'mahasiswaBimbingan',
                'workflowGroups',
                'recentPending',
                'recentBimbingan'
            ));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
