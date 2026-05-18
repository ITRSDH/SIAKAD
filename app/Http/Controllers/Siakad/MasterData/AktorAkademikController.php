<?php

namespace App\Http\Controllers\Siakad\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class AktorAkademikController extends Controller
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
            $dosenResponse = Http::withToken($this->apiToken)->get($this->apiUrl . 'dosen');
            $prodiResponse = Http::withToken($this->apiToken)->get($this->apiUrl . 'prodi');
            $pembimbingResponse = Http::withToken($this->apiToken)->get($this->apiUrl . 'dosen-wali');
            $usersResponse = Http::withToken($this->apiToken)->get($this->apiUrl . 'users');

            if (!$dosenResponse->successful() || !$prodiResponse->successful() || !$pembimbingResponse->successful() || !$usersResponse->successful()) {
                return back()->with('error', 'Gagal mengambil data aktor akademik dari API.');
            }

            $dosen = $dosenResponse->json('data.dosen', []);
            $prodi = $prodiResponse->json('data.prodi', []);
            $pembimbingAkademik = $pembimbingResponse->json('data', []);
            $users = $usersResponse->json('data.users', []);

            $baakUsers = array_values(array_filter($users, function ($user) {
                $roles = $user['roles'] ?? $user['role'] ?? [];

                foreach ($roles as $role) {
                    $roleName = is_array($role) ? ($role['name'] ?? null) : $role;
                    if ($roleName === 'baak') {
                        return true;
                    }
                }

                return false;
            }));

            $kaprodiAssigned = array_values(array_filter($prodi, fn($item) => !empty($item['id_kaprodi'] ?? null)));
            $totalMahasiswaBimbingan = array_sum(array_map(function ($item) {
                return (int) (
                    $item['jumlah_mahasiswa_bimbingan']
                    ?? $item['jumlah_mahasiswa']
                    ?? $item['mahasiswa_count']
                    ?? 0
                );
            }, $pembimbingAkademik));

            $summary = [
                [
                    'title' => 'Dosen',
                    'count' => count($dosen),
                    'description' => 'Data dosen aktif yang menjadi basis pengajaran dan penugasan akademik.',
                    'action_label' => 'Kelola Dosen',
                    'action_url' => route('dosen.index'),
                    'icon' => 'fas fa-chalkboard-teacher',
                    'theme' => 'primary',
                ],
                [
                    'title' => 'Ketua Program Studi',
                    'count' => count($kaprodiAssigned),
                    'description' => 'Program studi yang sudah memiliki kaprodi terpasang.',
                    'action_label' => 'Kelola Kaprodi',
                    'action_url' => route('aktor-akademik.kaprodi'),
                    'icon' => 'fas fa-user-graduate',
                    'theme' => 'success',
                ],
                [
                    'title' => 'Pembimbing Akademik',
                    'count' => count($pembimbingAkademik),
                    'description' => "Dosen pembimbing akademik dengan total {$totalMahasiswaBimbingan} mahasiswa bimbingan.",
                    'action_label' => 'Kelola Pembimbing',
                    'action_url' => route('aktor-akademik.pembimbing-akademik'),
                    'icon' => 'fas fa-user-friends',
                    'theme' => 'warning',
                ],
                [
                    'title' => 'BAAK',
                    'count' => count($baakUsers),
                    'description' => 'User dengan role BAAK untuk operasional akademik dan administrasi kampus.',
                    'action_label' => 'Buka Workspace',
                    'action_url' => route('workspace.baak'),
                    'icon' => 'fas fa-briefcase',
                    'theme' => 'info',
                ],
            ];

            return view('masterdata.aktor_akademik.index', compact(
                'summary',
                'dosen',
                'prodi',
                'pembimbingAkademik',
                'baakUsers',
                'kaprodiAssigned',
                'totalMahasiswaBimbingan'
            ));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function kaprodi()
    {
        try {
            $response = Http::withToken($this->apiToken)->get($this->apiUrl . 'prodi');
            if (!$response->successful()) {
                return back()->with('error', 'Gagal mengambil data kaprodi dari API.');
            }

            $apiData = $response->json('data', []);
            $prodi = $apiData['prodi'] ?? [];
            $dosenList = $apiData['dosen_list'] ?? [];

            return view('masterdata.prodi.index', [
                'prodi' => $prodi,
                'dosenList' => $dosenList,
                'pageTitle' => 'Ketua Program Studi',
                'pageHeading' => 'Ketua Program Studi',
                'pageCrumbLabel' => 'Daftar Kaprodi',
                'pageDescription' => 'Kelola penetapan kaprodi pada setiap program studi melalui tab kaprodi.',
            ]);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function pembimbingAkademik()
    {
        return app(DosenWaliController::class)->index('Pembimbing Akademik', 'Kelola dosen pembimbing akademik dan sebaran mahasiswa bimbingan.');
    }

    public function baak()
    {
        return app(\App\Http\Controllers\ManagementPengguna\UserController::class)->index(
            roleFilter: 'baak',
            pageTitle: 'BAAK',
            pageHeading: 'User BAAK',
            pageDescription: 'Kelola akun operasional BAAK yang menangani administrasi dan layanan akademik.'
        );
    }
}
