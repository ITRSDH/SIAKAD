<?php

namespace App\Http\Controllers\ManagementPengguna;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class RoleController extends Controller
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
            $response = Http::withToken($this->apiToken)->get($this->apiUrl . 'roles');

            if ($response->successful()) {
                $apiData = $response->json()['data'] ?? [];

                $roles = $apiData['roles'] ?? [];
                $permissions = $apiData['permissions'] ?? [];

                $defaultSidebar = [];
                $sectionBuckets = [];

                $labelize = function (string $value): string {
                    return ucfirst(str_replace('-', ' ', $value));
                };

                $resolvePermissionSection = function (string $permission) use ($labelize): array {
                    $parts = explode('.', $permission);

                    if (($parts[0] ?? null) === 'websitekampus') {
                        return [
                            'section' => 'CMS Kampus',
                            'keys' => array_map($labelize, array_slice($parts, 1)),
                        ];
                    }

                    if (($parts[0] ?? null) === 'pengguna' && ($parts[1] ?? null) === 'setting') {
                        return [
                            'section' => 'Pengaturan Pengguna',
                            'keys' => array_map($labelize, array_slice($parts, 2)),
                        ];
                    }

                    if (($parts[0] ?? null) === 'siakad') {
                        $module = $parts[1] ?? null;

                        if ($module === 'master' && ($parts[2] ?? null) === 'refrensi') {
                            $entity = $parts[3] ?? null;
                            $masterAkademik = ['prodi', 'tahun-akademik', 'semester', 'kurikulum', 'mata-kuliah', 'ruang-kuliah', 'periode-krs'];
                            $capaianPembelajaran = ['profile-lulusan', 'cpl', 'indikator-kinerja', 'pemetaan-plcpl', 'pemetaan-cplmk'];
                            $operasionalAkademik = ['dosen', 'dosen-wali', 'dosen-pengajar-kelas', 'jadwal-kuliah', 'kelas-kuliah', 'mahasiswa', 'mahasiswa-baru'];

                            if (in_array($entity, $masterAkademik, true)) {
                                return [
                                    'section' => 'Master Akademik',
                                    'keys' => array_map($labelize, array_slice($parts, 3)),
                                ];
                            }

                            if (in_array($entity, $capaianPembelajaran, true)) {
                                return [
                                    'section' => 'Capaian Pembelajaran',
                                    'keys' => array_map($labelize, array_slice($parts, 3)),
                                ];
                            }

                            if (in_array($entity, $operasionalAkademik, true)) {
                                return [
                                    'section' => 'Operasional Akademik',
                                    'keys' => array_map($labelize, array_slice($parts, 3)),
                                ];
                            }
                        }

                        if ($module === 'krs') {
                            return [
                                'section' => 'Transaksi Akademik',
                                'keys' => array_map($labelize, array_slice($parts, 2)),
                            ];
                        }

                        if ($module === 'penilaian') {
                            return [
                                'section' => 'Penilaian dan Hasil Studi',
                                'keys' => array_map($labelize, array_slice($parts, 2)),
                            ];
                        }

                        if ($module === 'akademik') {
                            $entity = $parts[2] ?? null;
                            $transaksiAkademik = ['pertemuan-kuliah', 'presensi-kuliah', 'khs', 'transkrip', 'remedial', 'academic-policies'];
                            $akhirStudi = ['tugas-akhir', 'yudisium', 'kelulusan'];
                            $administratif = ['wisuda'];

                            if (in_array($entity, $transaksiAkademik, true)) {
                                return [
                                    'section' => 'Transaksi Akademik',
                                    'keys' => array_map($labelize, array_slice($parts, 2)),
                                ];
                            }

                            if (in_array($entity, $akhirStudi, true)) {
                                return [
                                    'section' => 'Akhir Studi',
                                    'keys' => array_map($labelize, array_slice($parts, 2)),
                                ];
                            }

                            if (in_array($entity, $administratif, true)) {
                                return [
                                    'section' => 'Administratif',
                                    'keys' => array_map($labelize, array_slice($parts, 2)),
                                ];
                            }
                        }
                    }

                    return [
                        'section' => 'Lainnya',
                        'keys' => array_map($labelize, $parts),
                    ];
                };

                $insertIntoTree = function (array &$tree, array $keys, string $permission): void {
                    $ref = &$tree;

                    foreach ($keys as $key) {
                        $ref = &$ref[$key];
                    }

                    $ref[] = $permission;
                };

                $buildMenu = function (array $arr, bool $isRoot = true) use (&$buildMenu): array {
                    $result = $isRoot ? ['menus' => []] : [];

                    foreach ($arr as $key => $val) {
                        $children = array_filter($val, 'is_array');
                        $permissionItems = array_values(array_filter($val, 'is_string'));

                        $item = [
                            'title' => $key,
                            'permissions' => $permissionItems,
                            'children' => !empty($children) ? $buildMenu($children, false) : [],
                        ];

                        if ($isRoot) {
                            $result['menus'][] = $item;
                        } else {
                            $result[] = $item;
                        }
                    }

                    return $result;
                };

                foreach ($permissions as $perm) {
                    $name = $perm['name'] ?? $perm['permission'] ?? null;
                    if (!$name) {
                        continue;
                    }

                    $insertIntoTree($defaultSidebar, array_map($labelize, explode('.', $name)), $name);

                    $resolved = $resolvePermissionSection($name);
                    $sectionName = $resolved['section'];
                    $keys = $resolved['keys'];

                    if (empty($keys)) {
                        $keys = [$labelize($name)];
                    }

                    if (!isset($sectionBuckets[$sectionName])) {
                        $sectionBuckets[$sectionName] = [];
                    }

                    $insertIntoTree($sectionBuckets[$sectionName], $keys, $name);
                }

                $menu = $buildMenu($defaultSidebar);
                $sectionOrder = [
                    'Master Akademik',
                    'Capaian Pembelajaran',
                    'Operasional Akademik',
                    'Transaksi Akademik',
                    'Penilaian dan Hasil Studi',
                    'Akhir Studi',
                    'Administratif',
                    'CMS Kampus',
                    'Pengaturan Pengguna',
                    'Lainnya',
                ];

                $permissionSections = [];
                foreach ($sectionOrder as $sectionName) {
                    if (!isset($sectionBuckets[$sectionName])) {
                        continue;
                    }

                    $permissionSections[] = [
                        'section' => $sectionName,
                        'menus' => $buildMenu($sectionBuckets[$sectionName])['menus'],
                    ];
                }

                return view('auth.pengguna.setting-user.role.index', compact('roles', 'permissions', 'menu', 'permissionSections'));
            }

            return back()->with('error', 'Gagal mengambil data dari API');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $response = Http::withToken($this->apiToken)->post($this->apiUrl . 'roles', $request->all());

            if ($response->successful()) {
                return response()->json($response->json());
            }

            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan data ke API',
                'errors' => $response->json()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $response = Http::withToken($this->apiToken)->get($this->apiUrl . "roles/{$id}");

            if ($response->successful()) {
                return response()->json($response->json());
            }

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data dari API',
                'errors' => $response->json()
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $response = Http::withToken($this->apiToken)
                ->put($this->apiUrl . "roles/{$id}", $request->all());

            if ($response->successful()) {
                $userResponse = Http::withToken($this->apiToken)
                    ->get($this->apiUrl . "auth/me");

                if ($userResponse->successful()) {
                    $apiUser = $userResponse->json()['user'];
                    $current = Session::get('user', []);

                    $current['role'] = $apiUser['role'] ?? [];
                    $current['permission'] = $apiUser['permission'] ?? [];

                    Session::put('user', $current);
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Role berhasil diperbarui dan session user (role & permission) disinkron.',
                    'data' => $response->json(),
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui data di API',
                'errors' => $response->json()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $response = Http::withToken($this->apiToken)->delete($this->apiUrl . "roles/{$id}");

            if ($response->successful()) {
                return response()->json($response->json());
            }

            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data di API',
                'errors' => $response->json()
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
