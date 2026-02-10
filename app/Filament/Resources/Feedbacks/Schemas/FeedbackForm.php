<?php

namespace App\Filament\Resources\Feedbacks\Schemas;

use App\Models\Interview;
use App\Models\Offer;
use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class FeedbackForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('interview_id')
                    ->label('Interview')
                    ->options(Interview::pluck('id', 'id'))
                    ->searchable()
                    ->nullable(),
                Select::make('offer_id')
                    ->label('Offer')
                    ->options(Offer::pluck('id', 'id'))
                    ->searchable()
                    ->nullable(),
                TextInput::make('rating')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(10),
                Textarea::make('comments'),
                Select::make('author_id')
                    ->label('Author')
                    ->options(User::pluck('name', 'id'))
                    ->searchable()
                    ->required(),
            ]);
    }
}
