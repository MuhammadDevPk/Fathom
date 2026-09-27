<?php

namespace App\Http\Requests;

use App\Models\Meeting;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreHighlightRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        /** @var Meeting|null $meeting */
        $meeting = $this->route('meeting');
        $maxDuration = ($meeting instanceof Meeting && $meeting->duration_seconds > 0)
            ? $meeting->duration_seconds
            : null;

        $timestampRules = ['required', 'integer', 'min:0'];
        if ($maxDuration !== null) {
            $timestampRules[] = "max:{$maxDuration}";
        }

        return [
            'note' => ['required', 'string', 'max:2000'],
            'label' => ['nullable', 'string', 'max:100'],
            'timestamp_seconds' => $timestampRules,
        ];
    }

    /**
     * Get custom error messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'note.required' => 'A note is required for the highlight.',
            'timestamp_seconds.max' => 'The highlight timestamp cannot exceed the meeting duration.',
        ];
    }
}
