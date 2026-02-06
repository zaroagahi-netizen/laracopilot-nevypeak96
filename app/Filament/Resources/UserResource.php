<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;

/**
 * ZARO User Resource
 * 
 * Filament admin panelinde kullanıcı yönetimi
 * - Quick create modal desteği
 * - 18+ yaş doğrulaması
 * - Maksimum 6 çocuk sınırı
 * - KVKK onay takibi
 */
class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'CRM';

    protected static ?string $navigationLabel = 'Kullanıcılar';

    protected static ?string $modelLabel = 'Kullanıcı';

    protected static ?string $pluralModelLabel = 'Kullanıcılar';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Temel Bilgiler')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Ad Soyad')
                            ->required()
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('email')
                            ->label('E-posta')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('phone')
                            ->label('Telefon')
                            ->tel()
                            ->maxLength(255),
                        
                        Forms\Components\DatePicker::make('birth_date')
                            ->label('Doğum Tarihi')
                            ->required()
                            ->maxDate(now()->subYears(18))
                            ->helperText('Kullanıcı en az 18 yaşında olmalıdır'),
                        
                        Forms\Components\Select::make('relation_type')
                            ->label('İlişki Türü')
                            ->options([
                                'Anne' => 'Anne',
                                'Baba' => 'Baba',
                                'Öğretmen' => 'Öğretmen',
                            ])
                            ->required()
                            ->default('Anne'),
                        
                        Forms\Components\Toggle::make('kvkk_accepted')
                            ->label('KVKK Onayı')
                            ->required()
                            ->helperText('KVKK aydınlatma metni okundu ve onaylandı'),
                    ])
                    ->columns(2),
                
                Forms\Components\Section::make('Güvenlik')
                    ->schema([
                        Forms\Components\TextInput::make('password')
                            ->label('Şifre')
                            ->password()
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $context): bool => $context === 'create')
                            ->maxLength(255),
                    ])
                    ->columns(1),
                
                Forms\Components\Section::make('Çocuk Bilgileri')
                    ->schema([
                        Forms\Components\Repeater::make('children_data')
                            ->label('Çocuklar')
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('Çocuk Adı')
                                    ->required()
                                    ->maxLength(255),
                                
                                Forms\Components\DatePicker::make('birth_date')
                                    ->label('Doğum Tarihi')
                                    ->required()
                                    ->maxDate(now()),
                                
                                Forms\Components\Select::make('gender')
                                    ->label('Cinsiyet')
                                    ->options([
                                        'Kız' => 'Kız',
                                        'Erkek' => 'Erkek',
                                    ])
                                    ->required(),
                            ])
                            ->columns(3)
                            ->maxItems(6)
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['name'] ?? null)
                            ->helperText('Maksimum 6 çocuk eklenebilir'),
                    ])
                    ->collapsed(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Ad Soyad')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('email')
                    ->label('E-posta')
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                
                Tables\Columns\TextColumn::make('relation_type')
                    ->label('İlişki')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Anne' => 'success',
                        'Baba' => 'info',
                        'Öğretmen' => 'warning',
                        default => 'gray',
                    })
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('children_data')
                    ->label('Çocuk Sayısı')
                    ->getStateUsing(fn (User $record): int => $record->getChildrenCount())
                    ->badge()
                    ->color('primary'),
                
                Tables\Columns\IconColumn::make('kvkk_accepted')
                    ->label('KVKK')
                    ->boolean()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Kayıt Tarihi')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('relation_type')
                    ->label('İlişki Türü')
                    ->options([
                        'Anne' => 'Anne',
                        'Baba' => 'Baba',
                        'Öğretmen' => 'Öğretmen',
                    ]),
                
                Tables\Filters\TernaryFilter::make('kvkk_accepted')
                    ->label('KVKK Onayı'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Düzenle'),
                Tables\Actions\DeleteAction::make()
                    ->label('Sil'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Seçilenleri Sil'),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
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