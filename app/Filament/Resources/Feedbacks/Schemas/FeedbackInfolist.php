<?php

namespace App\Filament\Resources\Feedbacks\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class FeedbackInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('interview.id')
                    ->label('Interview')
                    ->placeholder('-'),
                TextEntry::make('offer.id')
                    ->label('Offer')
                    ->placeholder('-'),
                TextEntry::make('rating'),
                TextEntry::make('comments')
                    ->placeholder('-'),
                TextEntry::make('author.name')
                    ->label('Author'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
