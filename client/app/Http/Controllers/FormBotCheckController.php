<?php

namespace App\Http\Controllers;

use App\Services\FormBotProtection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FormBotCheckController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        if (! FormBotProtection::passes($request, $request->input('before_submit') === '0')) {
            return response()->json([
                'success' => false,
                'message' => 'We could not verify this submission. Please wait a moment and try again.',
            ], 422);
        }

        return response()->json(['success' => true]);
    }
}
