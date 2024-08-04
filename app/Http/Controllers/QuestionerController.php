<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Models\Questioner;
use App\Models\QuestionerUser;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class QuestionerController extends Controller
{
    public function index()
    {
        $questioners = Questioner::all();
        return view('data.formulir', compact('questioners'));
    }

    public function create()
    {
        return view('questioners.create');
    }

    public function store(Request $request)
    {
        $questioner = Questioner::create($request->all());
        return redirect()->route('questioners.index');
    }

    public function show(Questioner $questioner)
    {
        return view('questioners.show', compact('questioner'));
    }

    public function edit(Questioner $questioner)
    {
        return view('questioners.edit', compact('questioner'));
    }

    public function update(Request $request, Questioner $questioner)
    {
        $questioner->update($request->all());
        return redirect()->route('questioners.index');
    }

    public function destroy(Questioner $questioner)
    {
        $questioner->delete();
        return redirect()->route('questioners.index');
    }

    public function showQuestion()
    {
         // Ambil questioner dengan is_active = true
         $activeQuestions = Questioner::where('is_active', true)->get();
         return ResponseHelper::Success('questions retrieved successfully', $activeQuestions);
    }

    public function submitQuestion(Request $request)
    {
        try {
            // Validasi request
            $validatedData = $request->validate([
                'user_id' => 'required|exists:users,id',
                'answers' => 'required|array',
                'answers.*.questioner_id' => 'required|exists:questioners,id',
                'answers.*.answer' => 'required|integer|between:1,5',
            ]);

            // Menyimpan jawaban ke tabel questioner_users
            $responses = [];
            foreach ($validatedData['answers'] as $answerData) {
                $questionerUser = new QuestionerUser();
                $questionerUser->user_id = $validatedData['user_id'];
                $questionerUser->questioner_id = $answerData['questioner_id'];
                $questionerUser->answer = $answerData['answer'];
                $questionerUser->save();
                $responses[] = $questionerUser;
            }

            // Return response JSON dengan pesan sukses
            return ResponseHelper::Created('Answer submitted successfully', ['questionerUser' => $responses]);
        } catch (QueryException $e) {
            // Handle query exception
            return ResponseHelper::InternalServerError($e->getMessage());
        } catch (Exception $e) {
            // Handle general exception
            return ResponseHelper::InternalServerError($e->getMessage());
        }
    }
}

