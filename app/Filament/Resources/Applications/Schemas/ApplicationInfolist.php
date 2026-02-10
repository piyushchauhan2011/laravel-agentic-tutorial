<?php

namespace App\Filament\Resources\Applications\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ApplicationInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('position.title')
                    ->label('Position'),
                TextEntry::make('candidate.id')
                    ->label('Candidate'),
                TextEntry::make('submitted_by')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('current_stage'),
                TextEntry::make('source')
                    ->placeholder('-'),
                TextEntry::make('status'),
                TextEntry::make('cover_letter')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('applied_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
