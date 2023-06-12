<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Invoice;
use Illuminate\Http\Request;

class CustomerReportController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('reports.customers_report', compact('categories'));
    }

    public function search_customers(Request $request)
    {

        // في حالة البحث بدون التاريخ

        if ($request->Section && $request->product && $request->start_at == '' && $request->end_at == '') {
            $invoices = Invoice::select('*')->where('category_id', '=', $request->Section)->where('product', '=', $request->product)->get();
            $categories = Category::all();
            return view('reports.customers_report', compact('categories'))->withDetails($invoices);
        }


        // في حالة البحث بتاريخ

        else {

            $start_at = date($request->start_at);
            $end_at = date($request->end_at);

            $invoices = Invoice::whereBetween('invoice_Date', [$start_at, $end_at])->where('category_id', '=', $request->Section)->where('product', '=', $request->product)->get();
            $categories = Category::all();
            return view('reports.customers_report', compact('categories'))->withDetails($invoices);
        }
    }
}
