<?php

namespace App\Http\Controllers\ManagementPengguna;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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

                $sidebar = [];

                // Membentuk struktur array bertingkat berdasarkan permission
                foreach ($permissions as $perm) {
                    $name = $perm['name'] ?? $perm['permission'] ?? null;
                    if (!$name) continue;

                    // Ubah "user.create.view" → ["User", "Create", "View"]
                    $keys = array_map(fn($p) => ucfirst(str_replace('-', ' ', $p)), explode('.', $name));

                    $ref = &$sidebar;
                    foreach ($keys as $k) {
                        $ref = &$ref[$k];
                    }
                    $ref[] = $name; // Simpan permission pada level paling bawah
                }

                /**
                 * Build menu:
                 * - Level pertama: ada "menus"
                 * - Level children: langsung array tanpa "menus"
                 */
                function buildMenu($arr, $isRoot = true)
                {
                    $result = $isRoot ? ['menus' => []] : [];

                    foreach ($arr as $key => $val) {
                        $children = array_filter($val, 'is_array');
                        $permissions = array_values(array_filter($val, 'is_string'));

                        $item = [
                            'title'       => $key,
                            'permissions' => $permissions,
                            'children'    => !empty($children) ? buildMenu($children, false) : []
                        ];

                        if ($isRoot) {
                            $result['menus'][] = $item;
                        } else {
                            $result[] = $item;
                        }
                    }

                    return $result;
                }

                $menu = buildMenu($sidebar);


                // dd($menu);

                return view('admin.master.pengguna.setting-user.role.index', compact('roles', 'permissions', 'menu'));
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
            // 1. Update role di API
            $response = Http::withToken($this->apiToken)
                ->put($this->apiUrl . "roles/{$id}", $request->all());

            if ($response->successful()) {

                // 2. Panggil endpoint /me untuk mengambil data terbaru
                $userResponse = Http::withToken($this->apiToken)
                    ->get($this->apiUrl . "auth/me");

                if ($userResponse->successful()) {
                    $apiUser = $userResponse->json()['user'];

                    // 3. Ambil session user saat ini
                    $current = Session::get('user', []);

                    // 4. Update hanya bagian role & permission
                    $current['role'] = $apiUser['role'] ?? [];
                    $current['permission'] = $apiUser['permission'] ?? [];

                    // 5. Simpan kembali ke session
                    Session::put('user', $current);
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Role berhasil diperbarui dan session user (role & permission) disinkron.',
                    'data'    => $response->json(),
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
