<?php

namespace App\Filament\Resources\Tags\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class TagInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name'),
                TextEntry::make('slug'),
                TextEntry::make('type')
                    ->placeholder('-'),
                TextEntry::make('candidates_count')
                    ->label('Candidates')
                    ->state(fn ($record) => $record->candidates()->count()),
                TextEntry::make('positions_count')
                    ->label('Positions')
                    ->state(fn ($record) => $record->positions()->count()),
                TextEntry::make('companies_count')
                    ->label('Companies')
                    ->state(fn ($record) => $record->companies()->count()),
            ]);
    }
}
