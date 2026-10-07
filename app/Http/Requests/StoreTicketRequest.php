<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTicketRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Only authenticated users can create tickets
        return $this->user() !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|min:3|max:255',
            'category' => 'required|string|in:System Issue,Feature Request,Bug Report,Account Issue,Other',
            'description' => 'required|string|min:10|max:5000',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Ticket title is required.',
            'title.min' => 'Ticket title must be at least 3 characters long.',
            'title.max' => 'Ticket title cannot exceed 255 characters.',
            'category.required' => 'Ticket category is required.',
            'category.in' => 'Selected category is invalid.',
            'description.required' => 'Ticket description is required.',
            'description.min' => 'Ticket description must be at least 10 characters long.',
            'description.max' => 'Ticket description cannot exceed 5000 characters.',
        ];
    }
}
