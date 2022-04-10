<?php

namespace App\Http\Requests;

use App\Enums\BalanceAs;
use Illuminate\Foundation\Http\FormRequest;

class ConvertBalanceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        /** @var \Bavix\Wallet\Models\Wallet $asWorker */
        $asWorker = $this->user()->getWallet(BalanceAs::Worker->value);

        return [
            'amount' => [
                'required',
                'numeric',
                'min:0.1',
                'max:' . $asWorker->balanceFloat,
            ],
        ];
    }
}
