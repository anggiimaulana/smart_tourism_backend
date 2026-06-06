<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FeedbackController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'feature' => 'required|string|max:50',
            'rating' => 'required|integer',
            'comment' => 'nullable|string',
            'context' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $feedback = Feedback::create([
            'user_id' => $request->user()?->id,
            'feature' => $request->feature,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'context' => $request->context,
        ]);

        return response()->json([
            'message' => 'Feedback submitted successfully',
            'data' => $feedback
        ], 201);
    }
}
