<?php

namespace App\Filament\Resources\Departments\Schemas;

use App\Models\Department;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class DepartmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Textarea::make('description'),
                Select::make('parent_id')
                    ->label('Parent Department')
                    ->options(Department::pluck('name', 'id'))
                    ->searchable()
                    ->nullable(),
                TextInput::make('level')
                    ->numeric()
                    ->default(0),
            ]);
    }
}
