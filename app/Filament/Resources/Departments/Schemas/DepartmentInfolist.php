<?php

namespace App\Filament\Resources\Departments\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class DepartmentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name'),
                TextEntry::make('description')
                    ->placeholder('-'),
                TextEntry::make('parent.name')
                    ->label('Parent Department')
                    ->placeholder('Root Department'),
                TextEntry::make('level'),
                TextEntry::make('children_count')
                    ->label('Sub-departments')
                    ->state(fn ($record) => $record->children()->count()),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
