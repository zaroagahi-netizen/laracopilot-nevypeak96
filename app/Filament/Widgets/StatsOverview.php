<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        // Today's orders
        $todayOrders = Order::whereDate('created_at', today())->count();
        $yesterdayOrders = Order::whereDate('created_at', today()->subDay())->count();
        $todayChange = $yesterdayOrders > 0 ? (($todayOrders - $yesterdayOrders) / $yesterdayOrders) * 100 : 0;

        // This month's sales
        $thisMonthSales = Order::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->where('payment_status', 'paid')
            ->sum('total');
        $lastMonthSales = Order::whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->where('payment_status', 'paid')
            ->sum('total');
        $salesChange = $lastMonthSales > 0 ? (($thisMonthSales - $lastMonthSales) / $lastMonthSales) * 100 : 0;

        // Low stock products
        $lowStockCount = Product::where('type', 'physical')
            ->where('stock_quantity', '<', 5)
            ->where('stock_quantity', '>', 0)
            ->count();
        $outOfStockCount = Product::where('type', 'physical')
            ->where('stock_quantity', 0)
            ->count();

        // New users this month
        $newUsersThisMonth = User::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        $newUsersLastMonth = User::whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->count();
        $usersChange = $newUsersLastMonth > 0 ? (($newUsersThisMonth - $newUsersLastMonth) / $newUsersLastMonth) * 100 : 0;

        return [
            Stat::make('Bugün Gelen Siparişler', $todayOrders)
                ->description($todayChange >= 0 ? "+{$todayChange}% dünden" : "{$todayChange}% dünden")
                ->descriptionIcon($todayChange >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($todayChange >= 0 ? 'success' : 'danger')
                ->chart([7, 4, 8, 5, 3, 5, $todayOrders]),

            Stat::make('Bu Ay Toplam Satış', number_format($thisMonthSales, 2) . ' ₺')
                ->description($salesChange >= 0 ? "+" . number_format($salesChange, 1) . "% geçen aydan" : number_format($salesChange, 1) . "% geçen aydan")
                ->descriptionIcon($salesChange >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($salesChange >= 0 ? 'success' : 'warning')
                ->chart([
                    $lastMonthSales > 0 ? $lastMonthSales : 0,
                    $thisMonthSales
                ]),

            Stat::make('Stok Durumu', $lowStockCount . ' düşük / ' . $outOfStockCount . ' tükendi')
                ->description('5 adetten az veya tükenmiş ürünler')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color($outOfStockCount > 0 ? 'danger' : ($lowStockCount > 0 ? 'warning' : 'success')),

            Stat::make('Yeni Üyeler (Bu Ay)', $newUsersThisMonth)
                ->description($usersChange >= 0 ? "+" . number_format($usersChange, 1) . "% geçen aydan" : number_format($usersChange, 1) . "% geçen aydan")
                ->descriptionIcon('heroicon-m-user-group')
                ->color('info')
                ->chart([
                    $newUsersLastMonth > 0 ? $newUsersLastMonth : 0,
                    $newUsersThisMonth
                ]),
        ];
    }
}