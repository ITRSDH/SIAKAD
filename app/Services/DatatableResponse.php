<?php

namespace App\Services;

use Illuminate\Http\Request;

class DataTableResponse
{
    public static function fromApi(Request $request, array $payload, callable $mapper)
    {
        // Handle berbagai kemungkinan struktur response API
        $data = $payload['data'] ?? $payload;
        $total = $payload['total'] ?? (is_array($data) ? count($data) : 0);
        
        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
            'data' => collect($data)
                ->values()
                ->map(fn ($row, $i) =>
                    $mapper($row, $i, $request)
                )
        ]);
    }

    public static function empty(Request $request)
    {
        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => 0,
            'recordsFiltered' => 0,
            'data' => [],
        ]);
    }
}
