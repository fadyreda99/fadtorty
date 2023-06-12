<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoicesAttachments;
use App\Models\InvoicesDetails;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class InvoicesDetailsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\InvoicesDetails  $invoicesDetails
     * @return \Illuminate\Http\Response
     */
    public function show(InvoicesDetails $invoicesDetails)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\InvoicesDetails  $invoicesDetails
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $invoice = Invoice::where('id', $id)->first();
        $invoiceDetails = InvoicesDetails::where('id_invoice', $id)->get();
        $invoiceAttachment = InvoicesAttachments::where('invoice_id', $id)->get();

        return view('invoices.details_invoices', compact('invoice', 'invoiceDetails', 'invoiceAttachment'));
    }

    public function readNotify(Request $request)
    {
        $notify_id = $request->notify_id;
        $invoice_id = $request->invoice_id;

        $Notification = Auth::user()->Notifications->find($notify_id);
        if ($Notification) {
            $Notification->markAsRead();
        }

        $invoice = Invoice::where('id', $invoice_id)->first();
        $invoiceDetails = InvoicesDetails::where('id_invoice', $invoice_id)->get();
        $invoiceAttachment = InvoicesAttachments::where('invoice_id', $invoice_id)->get();

        return view('invoices.details_invoices', compact('invoice', 'invoiceDetails', 'invoiceAttachment'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\InvoicesDetails  $invoicesDetails
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, InvoicesDetails $invoicesDetails)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\InvoicesDetails  $invoicesDetails
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $invoices = InvoicesAttachments::findOrFail($request->id_file);
        $invoices->delete();
        Storage::disk('public_uploads')->delete($request->invoice_number . '/' . $request->file_name);

        session()->flash('delete', 'تم حذف المرفق بنجاح');
        return back();
    }

    public function open_file($invoice_number, $file_name)
    {
        //        $files = Storage::disk('public_uploads')->getDriver()->get()
        //            ->applyPathPrefix($invoice_number.'/'.$file_name);

        //       $path =  $invoice_number.'/'.$file_name;
        //        $contents = Storage::disk('public_uploads')->get($path);
        //        return $contents;
        ////        $files = Storage::disk('public_uploads')->exists($invoice_number.'/'.$file_name);
        //        return response()->file($contents);
        //        $prefix = Storage::disk('public_uploads')->path($invoice_number.'/'.$file_name);
        //
        $file = Storage::disk('public_uploads')->path($invoice_number . '/' . $file_name);
        return response()->file($file);
        //
    }

    public function get_file($invoice_number, $file_name)
    {
        $file = Storage::disk('public_uploads')->path($invoice_number . '/' . $file_name);
        return response()->download($file);
    }
}
