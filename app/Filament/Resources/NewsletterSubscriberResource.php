<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NewsletterSubscriberResource\Pages;
use App\Models\NewsletterSubscriber;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class NewsletterSubscriberResource extends Resource
{
    protected static ?string $model = NewsletterSubscriber::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    protected static ?string $navigationGroup = 'Growth';

    protected static ?string $navigationLabel = 'Newsletter';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('email')->email()->required()->unique(ignoreRecord: true),
            Forms\Components\TextInput::make('name'),
            Forms\Components\Select::make('status')->options(['subscribed' => 'Subscribed', 'unsubscribed' => 'Unsubscribed'])->default('subscribed')->native(false),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('email')->searchable()->weight('bold'),
                Tables\Columns\TextColumn::make('status')->badge()->color(fn ($state) => $state === 'subscribed' ? 'success' : 'gray'),
                Tables\Columns\TextColumn::make('source')->limit(40)->color('gray')->toggleable(),
                Tables\Columns\TextColumn::make('created_at')->since()->sortable(),
            ])
            ->filters([Tables\Filters\SelectFilter::make('status')->options(['subscribed' => 'Subscribed', 'unsubscribed' => 'Unsubscribed'])])
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()])
            ->bulkActions([
                Tables\Actions\BulkAction::make('export')->label('Export CSV')->icon('heroicon-o-arrow-down-tray')
                    ->action(fn ($records) => response()->streamDownload(function () use ($records) {
                        $out = fopen('php://output', 'w');
                        fputcsv($out, ['Email', 'Name', 'Status', 'Subscribed at', 'Unsubscribe link']);
                        foreach ($records as $record) {
                            fputcsv($out, [$record->email, $record->name, $record->status, $record->created_at, route('newsletter.unsubscribe', $record->token)]);
                        }
                        fclose($out);
                    }, 'advertally-newsletter-'.now()->format('Y-m-d').'.csv')),
            ]);
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ManageNewsletterSubscribers::route('/')];
    }
}
