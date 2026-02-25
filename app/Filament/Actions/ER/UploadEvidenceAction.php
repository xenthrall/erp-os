<?php

namespace App\Filament\Actions\ER;

use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class UploadEvidenceAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'uploadEvidence';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label('Evidencias')
            ->icon('heroicon-o-paper-clip')
            ->color('info')
            ->modalHeading('Gestionar evidencias')
            ->modalSubmitActionLabel('Guardar cambios')
            ->modalWidth('lg')
            ->fillForm(function (Model $record): array {
                return [
                    'files' => $record->attachments()
                        ->pluck('path')
                        ->toArray(),
                ];
            })

            ->schema([
                FileUpload::make('files')
                    ->label('Archivos')
                    ->multiple()
                    ->disk('public')
                    ->directory(fn ($record) => $this->resolveDirectory($record))
                    ->visibility('public')
                    ->preserveFilenames()
                    ->openable()
                    ->downloadable()
                    ->panelLayout('grid')
                    ->imagePreviewHeight('800px')
                    ->maxFiles(6)
                    ->maxSize(10240)
                    ->itemPanelAspectRatio(1) 
            ])

            ->action(function (array $data, Model $record): void {

                if (! $record->exists) {
                    return;
                }

                $existing = $record->attachments()->pluck('path')->toArray();
                $updated = $data['files'] ?? [];

                // Detectar eliminados
                $deleted = array_diff($existing, $updated);

                foreach ($deleted as $path) {
                    Storage::disk('public')->delete($path);

                    $record->attachments()
                        ->where('path', $path)
                        ->delete();
                }

                // Detectar nuevos
                $new = array_diff($updated, $existing);

                foreach ($new as $path) {
                    $record->attachments()->create([
                        'path' => $path,
                    ]);
                }

                $this->success();
            });
    }

    protected function resolveDirectory(?Model $record): string
    {
        if (! $record || ! $record->exists) {
            return 'er-reports/temp';
        }

        return "er-reports/{$record->getKey()}";
    }
}