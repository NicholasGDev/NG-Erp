<?php

declare(strict_types=1);

namespace App\Http\Requests\Stock;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePhysicalInventoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status'      => 'sometimes|string|in:in_progress,adjusted,cancelled',
            'finished_at' => 'nullable|date',
            'notes'       => 'nullable|string',
        ];
    }
}
