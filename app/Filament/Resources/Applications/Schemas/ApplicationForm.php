<?php

namespace App\Filament\Resources\Applications\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ApplicationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('position_id')
                    ->relationship('position', 'title')
                    ->required(),
                Select::make('candidate_id')
                    ->relationship('candidate', 'id')
                    ->required(),
                TextInput::make('submitted_by')
                    ->numeric(),
                TextInput::make('current_stage')
                    ->required()
                    ->default('applied'),
                TextInput::make('source'),
                TextInput::make('status')
                    ->required()
                    ->default('active'),
                Textarea::make('cover_letter')
                    ->columnSpanFull(),
                DateTimePicker::make('applied_at'),
            ]);
    }
}
