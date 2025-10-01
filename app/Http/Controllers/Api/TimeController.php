<?php
/**
 * GB Developer
 *
 * @category GB_Developer
 * @package  GB
 *
 * @copyright Copyright (c) 2025 GB Developer.
 *
 * @author Geovan Brambilla <geovangb@gmail.com>
 */

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Time;
use Illuminate\Http\Request;

class TimeController extends Controller
{
    public function store(Request $request)
    {
        $time = Time::create([
            'nome' => $request->nome
        ]);

        return response()->json([
            'success' => true,
            'time' => $time
        ]);
    }
}
