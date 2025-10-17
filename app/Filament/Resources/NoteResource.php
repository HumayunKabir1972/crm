<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NoteResource\Pages;
use App\Filament\Resources\NoteResource\RelationManagers;
use App\Models\Note;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class NoteResource extends Resource
{
    protected static ?string $model = Note::class;

    protected static ?string $navigationIcon = 'heroicon-o-pencil-square';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                Forms\Components\Textarea::make('content')
                    ->required()
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\MorphToSelect::make('notable')
                    ->types([
                        Forms\Components\MorphToSelect\Type::make(App\Models\Customer::class)
                            ->titleAttribute('first_name'),
                        Forms\Components\MorphToSelect\Type::make(App\Models\Lead::class)
                            ->titleAttribute('first_name'),
                        Forms\Components\MorphToSelect\Type::make(App\Models\Deal::class)
                            ->titleAttribute('title'),
                        Forms\Components\MorphToSelect\Type::make(App\Models\Company::class)
                            ->titleAttribute('name'),
                        Forms\Components\MorphToSelect\Type::make(App\Models\Contact::class)
                            ->titleAttribute('first_name'),
                    ]),
                Forms\Components\Toggle::make('is_pinned')
                    ->default(false),
                Forms\Components\KeyValue::make('attachments'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('content')
                    ->searchable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('notable_type')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('is_pinned')
                    ->boolean(),
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
            'index' => Pages\ListNotes::route('/'),
            'create' => Pages\CreateNote::route('/create'),
            'edit' => Pages\EditNote::route('/{record}/edit'),
            'view' => Pages\ViewNote::route('/{record}'),
        ];
    }

    public static function getModelLabel(): string
    {
        return 'Note';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Notes';
    }
}
