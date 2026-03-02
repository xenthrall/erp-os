<?php

namespace App\Http\Requests\Customer\Warranties;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Validator;

class UpdateWarrantyRequest extends FormRequest
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
            'attachments_to_delete' => ['nullable', 'array'],
            'attachments_to_delete.*' => ['integer'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            /** @var \App\Models\Warranties\WarrantyRequest|null $warranty */
            $warranty = $this->route('warranty');

            if (! $warranty) {
                return;
            }

            $deleteIds = collect($this->input('attachments_to_delete', []))
                ->filter()
                ->map(static fn ($id): int => (int) $id);

            $existingAttachments = $warranty->attachments;
            $remainingExisting = $existingAttachments->reject(
                static fn ($attachment) => $deleteIds->contains($attachment->id),
            );

            /** @var Collection<int, \Illuminate\Http\UploadedFile> $newFiles */
            $newFiles = collect($this->file('attachments', []));

            $totalCount = $remainingExisting->count() + $newFiles->count();
            if ($totalCount > 5) {
                $validator->errors()->add(
                    'attachments',
                    'Solo puedes tener máximo 5 evidencias por garantía.',
                );
            }

            $existingBytes = $remainingExisting->sum(
                static fn ($attachment): int => $attachment->size ?? Storage::disk('public')->size($attachment->path),
            );

            $newBytes = $newFiles->sum(static fn ($file): int => $file->getSize() ?? 0);

            $totalBytes = $existingBytes + $newBytes;
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
