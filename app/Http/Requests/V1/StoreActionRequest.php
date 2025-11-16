<?php

namespace App\Http\Requests\V1;

use App\Enums\ActionRequestStatus;
use App\Enums\ActionRequestType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreActionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'created_by_id'  => 'required|integer|exists:users,id',
            'assigned_to_id' => 'required|integer|exists:dentists,id',
            // Use array_column instead of array_map with a closure
            'type' => ['required', Rule::in(array_column(ActionRequestType::cases(), 'value'))],
            'status' => ['required', Rule::in(array_column(ActionRequestStatus::cases(), 'value'))],
            'payload' => 'required|array',
        ];
    }
}


