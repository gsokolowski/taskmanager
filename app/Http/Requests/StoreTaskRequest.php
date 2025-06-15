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
                // Rule no longer can be used because it does not work with custom validation rules
                // Rule::exists('projects', 'id')->where(function ($query) {
                //     $query->where('creator_id', Auth::id());
                // }),

                // so used this instead

                // custom validation rule to check if this specific project id
                // exists and belongs to the logged in user
                function ($attribute, $value, $fail) {
                    //$attribute as the name of the field, $value as the value of the field, here project_id
                    //and fail is response whn failed

                    // $project = \App\Models\Project::where('id', $value)
                    //     ->where('creator_id', Auth::id())
                    //     ->first();


                    // custom validation rule to check if project_id that has been passed to store is the same
                    // as project_id of the user who is a member of. if he/she is not then do not allow to store task
                    // if he is allowed to store that task with user who is a member of that project
                    // Now every creator of the project is also a member of the project as we have asign this in Project observer so
                    // checking if user is a member of the pjoject is enough

                    $project = \App\Models\Project::where('id', $value)
                        ->where(function($query) {
                        $query->whereHas('members', function($q) { //  where mebers pivot table has currnet user id associated with this project
                                    $q->where('user_id', auth()->id());
                                });
                    })
                    ->first();

                    //dd($project);

                    if (!$project) {
                        $fail('You are trying to asign task to project id which does not exist or does not belong to you or you are not a member of this project.');
                    }
                },

            ],
        ];
    }
}
