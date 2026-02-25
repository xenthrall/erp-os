<?php

namespace App\Filament\Resources\ER\ErReports\Pages;

use App\Filament\Actions\ER\UploadEvidenceAction;
use App\Filament\Actions\ER\AddNoteAction;
use App\Filament\Resources\ER\ErReports\ErReportResource;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;
use Filament\Actions\Action;

class ViewErReport extends Page
{
    use InteractsWithRecord;

    protected static string $resource = ErReportResource::class;

    protected static ?string $title = '';

    protected string $view = 'filament.resources.e-r.er-reports.pages.view-er-report';

    public function mount(int|string $record): void
    {
        $this->record = $this->resolveRecord($record)->load([
            'type', 
            'reporter', 
            'employee', 
            'notes', 
            'attachments'
        ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('back')
                ->label('Regresar')
                ->icon('heroicon-m-arrow-left')
                ->color('gray')
                ->url(fn() => static::getResource()::getUrl('index')),

            UploadEvidenceAction::make('uploadEvidence'),

            AddNoteAction::make('addNote'),
        ];
    }
}
