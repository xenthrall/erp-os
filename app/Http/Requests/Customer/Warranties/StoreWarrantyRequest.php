<?php

namespace App\Http\Requests\Customer\Warranties;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Collection;
use Illuminate\Validation\Validator;

class StoreWarrantyRequest extends FormRequest
{
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
        return [
            'shipping_city' => ['required', 'string', 'max:100'],
            'shipping_address' => ['required', 'string', 'max:255'],
            'damage_date' => ['required', 'date', 'before_or_equal:today'],
            'purchase_date' => ['required', 'date', 'before_or_equal:today'],
            'invoice_number' => ['required', 'string', 'max:50'],
            'internal_code' => ['nullable', 'string', 'max:50'],
            'model' => ['required', 'string', 'max:100'],
            'quantity' => ['required', 'integer', 'min:1', 'max:1000'],
            'failure_description' => ['required', 'string', 'min:10'],
            'attachments' => ['nullable', 'array', 'max:5'],
            'attachments.*' => [
                'file',
                'max:51200', // 50MB por archivo (en KB)
                'mimetypes:image/jpeg,image/png,image/webp,image/gif,application/pdf,video/mp4,video/quicktime,video/webm,video/x-msvideo,video/x-matroska',
            ],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            /** @var Collection<int, \Illuminate\Http\UploadedFile> $files */
            $files = collect($this->file('attachments', []));

            $totalBytes = $files->sum(static fn ($file): int => $file->getSize() ?? 0);
            $maxBytes = 50 * 1024 * 1024;

            if ($totalBytes > $maxBytes) {
                $validator->errors()->add(
                    'attachments',
                    'El peso total combinado de las evidencias no debe superar 50MB.',
                );
            }
        });
    }
}
