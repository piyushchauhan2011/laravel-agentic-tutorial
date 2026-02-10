<?php

namespace App\Filament\Resources\Candidates\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CandidateForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('full_name')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                TextInput::make('phone')
                    ->tel(),
                TextInput::make('resume_url')
                    ->url(),
                TextInput::make('current_company'),
                TextInput::make('score')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
