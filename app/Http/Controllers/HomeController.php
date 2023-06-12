<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $allInvoices = Invoice::count();
        $notPaid = Invoice::where('value_status', '2')->count();
        $paid = Invoice::where('value_status', '1')->count();
        $partial = Invoice::where('value_status', '3')->count();

        if ($notPaid == 0) {
            $nspainvoices2 = 0;
        } else {
            $nspainvoices2 = round($notPaid / $allInvoices * 100);
        }

        if ($paid == 0) {
            $nspainvoices1 = 0;
        } else {
            $nspainvoices1 = round($paid / $allInvoices * 100);
        }

        if ($partial == 0) {
            $nspainvoices3 = 0;
        } else {
            $nspainvoices3 = round($partial / $allInvoices * 100);
        }

        $barChartjs = app()->chartjs
            ->name('barChartTest')
            ->type('bar')
            ->size(['width' => 400, 'height' => 200])
            ->labels(['الفواتير', 'الفواتير المدفوعة', 'الفواتير الغير مدفوعة', 'الفواتير المدفوعة جزئيا'])
            ->datasets([
                [
                    "label" => "الفواتير",
                    'backgroundColor' => ['black', 'green', 'orange', 'red'],
                    'data' => [100, $nspainvoices1, $nspainvoices3, $nspainvoices2]
                ],
                [
                    "label" => "المدفوعة",
                    'backgroundColor' => ['green'],
                    'data' => [0, 0, 0, 0, 0]
                ],
                [
                    "label" => "المدفوعة جزئيا",
                    'backgroundColor' => ['orange'],
                    'data' => [0, 0, 0, 0, 0]
                ],
                [
                    "label" => "الغير مدفوعة",
                    'backgroundColor' => ['red'],
                    'data' => [0, 0, 0, 0, 0]
                ],
            ])
            ->options([]);

        $pieChartjs = app()->chartjs
            ->name('pieChartTest')
            ->type('pie')
            ->size(['width' => 400, 'height' => 200])
            ->labels(['المدفوعة', 'المدفوعة جزئيا', 'الغير مدفوعة'])
            ->datasets([
                [
                    'backgroundColor' => ['green', 'orange', 'red'],
                    'hoverBackgroundColor' => ['#539165', '#E57C23', '#B31312'],
                    'data' => [$nspainvoices1, $nspainvoices3, $nspainvoices2]
                ]
            ])
            ->options([]);

        return view('dashboard', compact('pieChartjs', 'barChartjs'));
    }
}
