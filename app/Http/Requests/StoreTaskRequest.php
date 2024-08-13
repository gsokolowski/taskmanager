<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // allow all users to make this request
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => [
                'sometimes',
                'required',
                'max:255',
            ],
            'is_done' => [
                'sometimes',
                'boolean',
            ],
            'project_id' => [
                'nullable',
                function ($attribute, $value, $fail) { //$attribute as the name of the field, $value as the value of the field

                    $project = \App\Models\Project::where('id', $value)
                        ->where('creator_id', Auth::id())
                        ->first();

                    if (!$project) {
                        $fail('The selected project does not exist or does not belong to you.');
                    }
                },
            ],
        ];
    }
}
