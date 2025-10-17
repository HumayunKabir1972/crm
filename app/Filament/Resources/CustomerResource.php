<?php

namespace App\Filament\Resources;

use Filament\Tables\Actions\Action;
use App\Filament\Resources\CustomerResource\Pages;
use App\Models\Customer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CustomerResource extends Resource
{
    protected static ?string $model = \App\Models\Customer::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'CRM';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Basic Information')
                    ->schema([
                        Forms\Components\TextInput::make('first_name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('last_name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        Forms\Components\TextInput::make('phone')
                            ->tel()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('mobile')
                            ->tel()
                            ->maxLength(255),
                        Forms\Components\Select::make('customer_type')
                            ->options([
                                'individual' => 'Individual',
                                'business' => 'Business',
                            ])
                            ->required()
                            ->default('individual'),
                        Forms\Components\Select::make('status')
                            ->options([
                                'active' => 'Active',
                                'inactive' => 'Inactive',
                                'prospect' => 'Prospect',
                            ])
                            ->required()
                            ->default('active'),
                    ])->columns(2),
                // ... rest of your form schema
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(Customer::query()->with(['assignedUser', 'deals', 'contacts']))
            ->columns([
                Tables\Columns\TextColumn::make('customer_code')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('first_name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('last_name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('company_name')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'success' => 'active',
                        'warning' => 'prospect',
                        'danger' => 'inactive',
                    ]),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                        'prospect' => 'Prospect',
                    ]),
            ])
            ->actions([
                Action::make('archive')
                    ->label('Archive')
                    ->icon('heroicon-o-archive-box')
                    ->color('warning')
                    ->action(function (Customer $record) {
                        // This action will be handled by JavaScript, so we just return true
                        // or you can dispatch a browser event here if needed for Livewire
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Archive Customer')
                    ->modalDescription('Are you sure you want to archive this customer?')
                    ->modalSubmitActionLabel('Yes, archive it')
                    ->extraAttributes(function (Customer $record) {
                        return [
                            'x-on:click' => 'archiveCustomer(\'' . $record->id . '\')',
                            'x-data' => '{}',
                            'data-id' => $record->id,
                        ];
                    }),
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(), // Add this if you want view action
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
            // Only include view if you created ViewCustomer page
            // 'view' => Pages\ViewCustomer::route('/{record}'),
        ];
    }
}