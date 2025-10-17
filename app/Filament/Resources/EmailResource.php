<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmailResource\Pages;
use App\Filament\Resources\EmailResource\RelationManagers;
use App\Models\Email;
use App\Models\Customer;
use App\Models\Lead;
use App\Models\Deal;
use App\Models\Company;
use App\Models\Contact;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EmailResource extends Resource
{
    protected static ?string $model = Email::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                Forms\Components\TextInput::make('from_email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('to_email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('subject')
                    ->required()
                    ->maxLength(255),
                Forms\Components\RichEditor::make('body')
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\KeyValue::make('cc'),
                Forms\Components\KeyValue::make('bcc'),
                Forms\Components\MorphToSelect::make('emailable')
                    ->types([
                        Forms\Components\MorphToSelect\Type::make(\App\Models\Customer::class)
                            ->titleAttribute('first_name'),
                        Forms\Components\MorphToSelect\Type::make(\App\Models\Lead::class)
                            ->titleAttribute('first_name'),
                        Forms\Components\MorphToSelect\Type::make(\App\Models\Deal::class)
                            ->titleAttribute('title'),
                        Forms\Components\MorphToSelect\Type::make(\App\Models\Company::class)
                            ->titleAttribute('name'),
                        Forms\Components\MorphToSelect\Type::make(\App\Models\Contact::class)
                            ->titleAttribute('first_name'),
                    ]),
                Forms\Components\Select::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'sent' => 'Sent',
                        'failed' => 'Failed',
                        'bounced' => 'Bounced',
                    ])
                    ->required()
                    ->default('draft'),
                Forms\Components\DateTimePicker::make('sent_at'),
                Forms\Components\DateTimePicker::make('opened_at'),
                Forms\Components\KeyValue::make('attachments'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('from_email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('to_email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('subject')
                    ->searchable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('emailable_type')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('status')
                    ->searchable(),
                Tables\Columns\TextColumn::make('sent_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('opened_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmails::route('/'),
            'create' => Pages\CreateEmail::route('/create'),
            'edit' => Pages\EditEmail::route('/{record}/edit'),
            'view' => Pages\ViewEmail::route('/{record}'),
        ];
    }

    public static function getModelLabel(): string
    {
        return 'Email';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Emails';
    }
}
