<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Models\Prize;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PrizeController extends Controller
{
    public function index(): JsonResponse
    {
        $prizes = Prize::all();
        return ResponseHelper::Success('Prizes retrieved successfully', $prizes);
    }

    public function store(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp,heic|max:2048',
        ]);

        $validatedData['possibility'] = 0;

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('public/images');
            $validatedData['image'] = env('APP_URL') . Storage::url($path);
        }

        $prize = Prize::create($validatedData);
        return ResponseHelper::Created('Prize created successfully', $prize);
    }

    public function show(Prize $prize): JsonResponse
    {
        return ResponseHelper::Success('Prize retrieved successfully', $prize);
    }

    public function update(Request $request, Prize $prize): JsonResponse
    {
        $validatedData = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp,heic|max:2048',
            'possibility' => 'required|numeric|min:0',
        ]);

        if ($request->hasFile('image')) {
            if ($prize->image) {
                Storage::delete(str_replace(env('APP_URL') . '/storage/', 'public/', $prize->image));
            }
            $path = $request->file('image')->store('public/images');
            $validatedData['image'] = env('APP_URL') . Storage::url($path);
        }

        $totalPossibility = Prize::where('uuid', '!=', $prize->uuid)->sum('possibility') + $validatedData['possibility'];

        if ($totalPossibility > 100) {
            return ResponseHelper::BadRequest('Total possibility cannot exceed 100.');
        }

        $prize->update($validatedData);
        return ResponseHelper::Success('Prize updated successfully', $prize);
    }

    public function destroy(Prize $prize): JsonResponse
    {
        DB::transaction(function () use ($prize) {
            if ($prize->image) {
                Storage::delete(str_replace(env('APP_URL') . '/storage/', 'public/', $prize->image));
            }

            $remainingPrizes = Prize::where('uuid', '!=', $prize->uuid)->get();

            if ($remainingPrizes->isEmpty()) {
                throw new Exception('Cannot delete the last prize.');
            }

            $distributedPossibility = $prize->possibility / $remainingPrizes->where('possibility', '>', 0)->count();

            foreach ($remainingPrizes as $remainingPrize) {
                if ($remainingPrize->possibility > 0) {
                    $remainingPrize->possibility += $distributedPossibility;
                    $remainingPrize->save();
                }
            }

            $prize->delete();
        });

        return ResponseHelper::Success('Prize deleted successfully');
    }

    public function batchUpdate(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'prizes' => 'required|array',
            'prizes.*.uuid' => 'required|exists:prizes,uuid',
            'prizes.*.possibility' => 'required|numeric|min:0',
        ]);

        $totalPossibility = array_sum(array_column($validatedData['prizes'], 'possibility'));

        if ($totalPossibility !== 100) {
            return ResponseHelper::BadRequest('Total possibility must be exactly 100.');
        }

        DB::transaction(function () use ($validatedData) {
            foreach ($validatedData['prizes'] as $prizeData) {
                $prize = Prize::find($prizeData['uuid']);

                if (!$prize) {
                    throw new Exception("Prize with UUID {$prizeData['uuid']} not found.");
                }

                $prize->update(['possibility' => $prizeData['possibility']]);
            }
        });

        return ResponseHelper::Success('Prizes updated successfully');
    }

}
