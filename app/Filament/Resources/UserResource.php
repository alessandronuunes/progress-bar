<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Livewire\Livewire;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Jobs\RecalculateUserJob;
use Filament\Resources\Resource;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use App\Services\ProgressNotificationService;
use Filament\Forms\Components\DateTimePicker;
use App\Filament\Resources\UserResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\UserResource\RelationManagers;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required(),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required(),
                Forms\Components\DateTimePicker::make('email_verified_at'),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email_verified_at')
                    ->dateTime()
                    ->sortable(),
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
                Tables\Actions\Action::make('recalc')
                    ->modalHeading('Recalcular')
                    ->icon('heroicon-s-chart-bar-square')
                    ->modalWidth('md')
                    ->form([
                        DateTimePicker::make('date_start')
                            ->label('Data de início')
                            ->displayFormat('d/m/Y H:i:s')
                            ->native(false)
                            ->default(now()->subDays(7)->startOfDay()) // Data inicial: 7 dias atrás
                            ->columnSpanFull()
                            ->required(),
                        DateTimePicker::make('date_end')
                            ->label('Data de Fim')
                            ->displayFormat('d/m/Y H:i:s')
                            ->default(now()->endOfDay()) // Data final: hoje às 23:59:59
                            ->native(false)
                            ->columnSpanFull()
                            ->required(),
                    ])
                    ->action(function (array $data, User $record, $livewire): void {
                        $userId = auth()->user()->id;
                        
                        // Inicializa o serviço de notificação de progresso
                        $progressService = app(ProgressNotificationService::class);
                        // Cria uma notificação com progresso inicial 0
                        $notificationId = $progressService->addNotification(
                            "Recalculando T.M.E para {$record->name}",
                            $userId,
                            auth()->user()->name,
                            0
                        );
                        
                        // Dispara o job para processar em background
                        RecalculateUserJob::dispatch(
                            $record, 
                            $data['date_start'], 
                            $data['date_end'], 
                            $userId,
                            $notificationId
                        );
                        // Dispara o evento para o componente Livewire
                        $livewire->dispatch('showProgressBar');
                        // Notifica o usuário que o processo foi iniciado
                        Notification::make()
                            ->title('Recálculo iniciado')
                            ->body('O processo foi enviado para a fila e será executado em segundo plano.')
                            ->success()
                            ->send();
                    }),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
