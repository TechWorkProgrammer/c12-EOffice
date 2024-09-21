<?php

namespace App\Http\Controllers;

use App\Helpers\AuthHelper;
use App\Helpers\ResponseHelper;
use App\Models\Signature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SignatureController extends Controller
{
    public function index() {
        $userLogin = AuthHelper::getAuthenticatedUser();

        $datas = Signature::where('user_id', $userLogin->uuid)->get();
        return ResponseHelper::Success('data for signatures retrieved successfully', $datas);
    }

    public function show(Signature $signatureId) {
        return ResponseHelper::Success('data for signature retrieved successfully', $signatureId);
    }

    public function store(Request $request) {
        try {
            $userLogin = AuthHelper::getAuthenticatedUser();

            $validatedData = $request->validate([
                'image' => 'required|file',
                'doc_name' => 'required|string|max:255',
                'doc_page' => 'required|string|max:255',
                'doc_ext' => 'required|string|max:255',
            ]);

            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('public/signature');

                $validatedData['image'] = env('APP_URL') . Storage::url($path);
            }

            $validatedData['user_id'] = $userLogin->uuid;

            Signature::create($validatedData);

            return ResponseHelper::Created('signature created successfully');
        } catch (\Exception $e) {
            return ResponseHelper::InternalServerError($e->getMessage());
        }
    }
}
