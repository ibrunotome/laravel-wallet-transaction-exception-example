<?php

namespace App\Http\Controllers;


use App\Actions\ConvertBalanceAction;
use App\Http\Requests\ConvertBalanceRequest;
use Illuminate\Http\JsonResponse;

class ConvertBalanceController
{
    /**
     * @throws \Throwable
     */
    public function __invoke(ConvertBalanceRequest $request, ConvertBalanceAction $action): JsonResponse
    {
        $validated = $request->validated();
        $action->execute($request->user(), $validated['amount']);

        return response()->json(['message' => 'Balance successfully converted!']);
    }
}
