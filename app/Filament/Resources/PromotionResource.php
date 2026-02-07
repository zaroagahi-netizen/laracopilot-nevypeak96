<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PromotionResource\Pages;
use App\Models\Promotion;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PromotionResource extends Resource
{
    protected static ?string $model = Promotion::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';

    protected static ?string $navigationGroup = 'Shop';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationLabel = 'Kuponlar';

    protected static ?string $modelLabel = 'Kupon';

    protected static ?string $pluralModelLabel = 'Kuponlar';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Kupon Bilgileri')
                    ->schema([
                        Forms\Components\TextInput::make('code')
                            ->label('Kupon Kodu')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->alphaNum()
                            ->uppercase()
                            ->helperText('Kupon kodu otomatik olarak büyük harfe çevrilir'),
                        Forms\Components\Textarea::make('description')
                            ->label('Açıklama')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('İndirim Ayarları')
                    ->schema([
                        Forms\Components\Select::make('type')
                            ->label('İndirim Türü')
                            ->options([
                                'percentage' => 'Yüzde İndirim (%)',
                                'fixed' => 'Sabit İndirim (₺)',
                            ])
                            ->required()
                            ->reactive()
                            ->default('percentage'),
                        Forms\Components\TextInput::make('value')
                            ->label('İndirim Değeri')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->suffix(fn (Forms\Get $get) => $get('type') === 'percentage' ? '%' : '₺')
                            ->helperText(fn (Forms\Get $get) => 
                                $get('type') === 'percentage' 
                                    ? 'Örnek: 20 = %20 indirim' 
                                    : 'Örnek: 50 = 50₺ indirim'
                            ),
                        Forms\Components\TextInput::make('min_cart_amount')
                            ->label('Minimum Sepet Tutarı')
                            ->numeric()
                            ->minValue(0)
                            ->default(0)
                            ->prefix('₺')
                            ->helperText('Kuponu kullanmak için gereken minimum sepet tutarı'),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('Kullanım Limitleri')
                    ->schema([
                        Forms\Components\TextInput::make('usage_limit')
                            ->label('Kullanım Limiti')
                            ->numeric()
                            ->minValue(0)
                            ->helperText('Boş bırakılırsa sınırsız kullanım'),
                        Forms\Components\TextInput::make('used_count')
                            ->label('Kullanım Sayısı')
                            ->numeric()
                            ->disabled()
                            ->default(0)
                            ->helperText('Şu ana kadar kaç kez kullanıldı'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Geçerlilik Tarihleri')
                    ->schema([
                        Forms\Components\DateTimePicker::make('valid_from')
                            ->label('Başlangıç Tarihi')
                            ->helperText('Bu tarihten önce kupon kullanılamaz'),
                        Forms\Components\DateTimePicker::make('valid_until')
                            ->label('Bitiş Tarihi')
                            ->helperText('Bu tarihten sonra kupon kullanılamaz'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Durum')
                    ->schema([
                        Forms\Components\Toggle::make('active')
                            ->label('Aktif')
                            ->default(true)
                            ->helperText('Kuponu aktif veya pasif yapın'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Kupon Kodu')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('type')
                    ->label('Tür')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match($state) {
                        'percentage' => 'Yüzde',
                        'fixed' => 'Sabit',
                        default => $state,
                    })
                    ->colors([
                        'success' => 'percentage',
                        'info' => 'fixed',
                    ]),
                Tables\Columns\TextColumn::make('value')
                    ->label('Değer')
                    ->formatStateUsing(fn ($record) => 
                        $record->type === 'percentage' 
                            ? "%{$record->value}" 
                            : "{$record->value}₺"
                    )
                    ->sortable(),
                Tables\Columns\TextColumn::make('min_cart_amount')
                    ->label('Min. Sepet')
                    ->money('TRY')
                    ->sortable(),
                Tables\Columns\TextColumn::make('usage_limit')
                    ->label('Limit')
                    ->formatStateUsing(fn ($state) => $state ?? '∞')
                    ->sortable(),
                Tables\Columns\TextColumn::make('used_count')
                    ->label('Kullanıldı')
                    ->sortable()
                    ->color(fn ($record) => 
                        $record->usage_limit && $record->used_count >= $record->usage_limit 
                            ? 'danger' 
                            : 'success'
                    ),
                Tables\Columns\IconColumn::make('active')
                    ->label('Aktif')
                    ->boolean(),
                Tables\Columns\TextColumn::make('valid_from')
                    ->label('Başlangıç')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('valid_until')
                    ->label('Bitiş')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Oluşturulma')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Tür')
                    ->options([
                        'percentage' => 'Yüzde',
                        'fixed' => 'Sabit',
                    ]),
                Tables\Filters\TernaryFilter::make('active')
                    ->label('Aktif'),
                Tables\Filters\Filter::make('expired')
                    ->label('Süresi Dolmuş')
                    ->query(fn (Builder $query) => $query->where('valid_until', '<', now())),
                Tables\Filters\Filter::make('usage_limit_reached')
                    ->label('Limit Dolmuş')
                    ->query(fn (Builder $query) => $query->whereRaw('used_count >= usage_limit')),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListPromotions::route('/'),
            'create' => Pages\CreatePromotion::route('/create'),
            'edit' => Pages\EditPromotion::route('/{record}/edit'),
        ];
    }
}