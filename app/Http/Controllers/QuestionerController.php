<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Models\Questioner;
use App\Models\QuestionerUser;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuestionerController extends Controller
{
    public function index(): JsonResponse
    {
        $questioners = Questioner::withCount(['questionerUsers as respondents'])
            ->withAvg('questionerUsers', 'answer')
            ->get()
            ->map(function ($questioner) {
                $questioner->questioner_users_avg_answer = $questioner->questioner_users_avg_answer ?? 0;
                return $questioner;
            });

        return ResponseHelper::Success('Questioners retrieved successfully', $questioners);
    }


    public function store(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'question' => 'required|string',
            'is_active' => 'boolean',
        ]);
        $questioner = Questioner::create($validatedData);
        return ResponseHelper::Created('Questioner created successfully', $questioner);
    }

    public function show(Questioner $questioner): JsonResponse
    {
        $details = $questioner->questionerUsers()->with('user')->get();

        $mappedDetails = $details->map(function ($questionerUser) {
            return [
                'name' => $questionerUser->user->name,
                'answer' => $questionerUser->answer,
            ];
        });

        return ResponseHelper::Success('Detailed questioner responses retrieved successfully', $mappedDetails);
    }

    public function update(Request $request, Questioner $questioner): JsonResponse
    {
        $validatedData = $request->validate([
            'question' => 'sometimes|required|string',
            'is_active' => 'sometimes|boolean',
        ]);
        $questioner->update($validatedData);
        return ResponseHelper::Success('Questioner updated successfully');
    }

    public function getUnansweredQuestions(): JsonResponse
    {
        $user = Auth::guard('api')->user();
        $unansweredQuestions = $user->getUnansweredActiveQuestions();

        return ResponseHelper::Success('Unanswered active questions retrieved successfully.', $unansweredQuestions);
    }

    public function submitAnswer(Request $request): JsonResponse
    {
        $user = Auth::guard('api')->user();

        $validatedData = $request->validate([
            'questioner_id' => 'required|uuid|exists:questioners,uuid',
            'answer' => 'required|integer|min:1|max:5',
        ]);

        if (!empty($user->uuid)) {
            $existingAnswer = QuestionerUser::where('questioner_id', $validatedData['questioner_id'])
                ->where('user_id', $user->uuid)
                ->first();
            if ($existingAnswer) {
                return ResponseHelper::BadRequest('You have already answered this question.');
            }
            QuestionerUser::create([
                'questioner_id' => $validatedData['questioner_id'],
                'user_id' => $user->uuid,
                'answer' => $validatedData['answer'],
            ]);
        }

        $remainingQuestions = $user->getUnansweredActiveQuestions()->count();

        if ($remainingQuestions === 0) {
            try {
                if (!empty($user->uuid)) {
                    User::where('uuid', $user->uuid)->update(['is_verified' => true]);
                }
            } catch (Exception $e) {
                return ResponseHelper::InternalServerError('An error occurred while updating the user: ' . $e->getMessage());
            }
        }

        return ResponseHelper::Success('Answer submitted successfully.', Auth::guard('api')->user());
    }
}

