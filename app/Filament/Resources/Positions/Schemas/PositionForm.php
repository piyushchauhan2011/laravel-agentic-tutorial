<?php

namespace App\Filament\Resources\Positions\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class PositionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('company_id')
                    ->relationship('company', 'name')
                    ->required(),
                TextInput::make('title')
                    ->required(),
                TextInput::make('department'),
                TextInput::make('employment_type')
                    ->required()
                    ->default('full_time'),
                TextInput::make('location'),
                Textarea::make('description')
                    ->columnSpanFull(),
                TextInput::make('status')
                    ->required()
                    ->default('draft'),
                DateTimePicker::make('published_at'),
                DateTimePicker::make('closing_at'),
            ]);
    }
}
