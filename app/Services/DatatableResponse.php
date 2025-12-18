<?php

namespace App\Services;

use Illuminate\Http\Request;

class DataTableResponse
{
    public static function fromApi(Request $request, array $payload, callable $mapper)
    {
        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $payload['total'] ?? 0,
            'recordsFiltered' => $payload['total'] ?? 0,
            'data' => collect($payload['data'] ?? [])
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
