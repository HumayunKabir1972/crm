<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DealResource\Pages;
use App\Filament\Resources\DealResource\RelationManagers;
use App\Models\Deal;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DealResource extends Resource
{
    protected static ?string $model = Deal::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\Select::make('customer_id')
                    ->relationship('customer', 'first_name')
                    ->required(),
                Forms\Components\Select::make('lead_id')
                    ->relationship('lead', 'first_name')
                    ->nullable(),
                Forms\Components\TextInput::make('amount')
                    ->numeric()
                    ->required()
                    ->prefix('$'),
                Forms\Components\TextInput::make('currency')
                    ->maxLength(255)
                    ->default('USD'),
                Forms\Components\Select::make('stage')
                    ->options([
                        'prospecting' => 'Prospecting',
                        'qualification' => 'Qualification',
                        'proposal' => 'Proposal',
                        'negotiation' => 'Negotiation',
                        'won' => 'Won',
                        'lost' => 'Lost',
                    ])
                    ->required()
                    ->default('prospecting'),
                Forms\Components\TextInput::make('probability')
                    ->numeric()
                    ->required()
                    ->minValue(0)
                    ->maxValue(100)
                    ->suffix('%'),
                Forms\Components\DatePicker::make('expected_close_date')
                    ->required(),
                Forms\Components\DatePicker::make('actual_close_date'),
                Forms\Components\Select::make('status')
                    ->options([
                        'open' => 'Open',
                        'won' => 'Won',
                        'lost' => 'Lost',
                        'closed' => 'Closed',
                    ])
                    ->required()
                    ->default('open'),
                Forms\Components\TextInput::make('lost_reason')
                    ->maxLength(255),
                Forms\Components\Select::make('assigned_to')
                    ->relationship('assignedUser', 'name')
                    ->required(),
                Forms\Components\Select::make('created_by')
                    ->relationship('creator', 'name')
                    ->required(),
                Forms\Components\TextInput::make('pipeline')
                    ->maxLength(255),
                Forms\Components\Select::make('priority')
                    ->options([
                        'low' => 'Low',
                        'medium' => 'Medium',
                        'high' => 'High',
                        'urgent' => 'Urgent',
                    ])
                    ->default('medium'),
                Forms\Components\TextInput::make('source')
                    ->maxLength(255),
                Forms\Components\TextInput::make('campaign')
                    ->maxLength(255),
                Forms\Components\Textarea::make('notes')
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\KeyValue::make('products'),
                Forms\Components\TextInput::make('discount')
                    ->numeric()
                    ->prefix('$'),
                Forms\Components\TextInput::make('tax')
                    ->numeric()
                    ->prefix('$'),
                Forms\Components\TextInput::make('total_amount')
                    ->numeric()
                    ->prefix('$'),
                Forms\Components\TextInput::make('weighted_amount')
                    ->numeric()
                    ->prefix('$'),
                Forms\Components\TextInput::make('days_to_close')
                    ->numeric(),
                Forms\Components\KeyValue::make('custom_fields'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('deal_code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('customer.first_name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('lead.first_name')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('amount')
                    ->money()
                    ->sortable(),
                Tables\Columns\TextColumn::make('currency')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('stage')
                    ->searchable(),
                Tables\Columns\TextColumn::make('probability')
                    ->numeric()
                    ->sortable()
                    ->suffix('%'),
                Tables\Columns\TextColumn::make('expected_close_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('actual_close_date')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('status')
                    ->searchable(),
                Tables\Columns\TextColumn::make('lost_reason')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('assignedUser.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('creator.name')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('pipeline')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('priority')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('source')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('campaign')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('discount')
                    ->money()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('tax')
                    ->money()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('total_amount')
                    ->money()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('weighted_amount')
                    ->money()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('days_to_close')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
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
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
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
            'index' => Pages\ListDeals::route('/'),
            'create' => Pages\CreateDeal::route('/create'),
            'edit' => Pages\EditDeal::route('/{record}/edit'),
            'view' => Pages\ViewDeal::route('/{record}'),
        ];
    }

    public static function getModelLabel(): string
    {
        return 'Deal';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Deals';
    }
}
