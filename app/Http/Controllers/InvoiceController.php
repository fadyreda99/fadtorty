<?php

namespace App\Http\Controllers;

use App\Exports\InvoicesExport;
use App\Models\Category;
use App\Models\Invoice;
use App\Models\InvoicesAttachments;
use App\Models\InvoicesDetails;
use App\Models\Product;
use App\Models\User;
use App\Notifications\AddInvoiceDB;
use App\Notifications\AddInvoices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:الفواتير', ['only' => ['invoices', 'index']]);
        $this->middleware('permission:اضافة فاتورة', ['only' => ['invoices', 'create']]);
        $this->middleware('permission:تعديل الفاتورة', ['only' => ['invoices', 'update']]);
        $this->middleware('permission:حذف الفاتورة', ['only' => ['invoices', 'destroy']]);
    }

    public function index()
    {
        $invoices = Invoice::all();
        return view('invoices.invoices', compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        $products = Product::all();
        return view('invoices.add_invoice', compact('categories', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        invoice::create([
            'invoice_number' => $request->invoice_number,
            'invoice_date' => $request->invoice_Date,
            'due_date' => $request->Due_date,
            'product' => $request->product,
            'category_id' => $request->category,
            'amount_collection' => $request->Amount_collection,
            'amount_commission' => $request->Amount_Commission,
            'discount' => $request->Discount,
            'value_vAT' => $request->Value_VAT,
            'rate_vAT' => $request->Rate_VAT,
            'total' => $request->Total,
            'status' => 'غير مدفوعة',
            'value_status' => 2,
            'note' => $request->note,
        ]);

        //get id of last insert from invoices table
        $invoice_id = invoice::latest()->first()->id;
        InvoicesDetails::create([
            'id_invoice' => $invoice_id,
            'invoice_number' => $request->invoice_number,
            'product' => $request->product,
            'category' => $request->category,
            'Status' => 'غير مدفوعة',
            'value_status' => 2,
            'note' => $request->note,
            'user' => (Auth::user()->name),
        ]);

        if ($request->hasFile('pic')) {
            $invoice_id = Invoice::latest()->first()->id;
            $image = $request->file('pic');
            //            $file_name = $image->getClientOriginalName();
            //change file name
            $file_change_name = $image->hashName();
            $invoice_number = $request->invoice_number;
            $attachments = new InvoicesAttachments();
            $attachments->file_name = $file_change_name;
            $attachments->invoice_number = $invoice_number;
            $attachments->Created_by = Auth::user()->name;
            $attachments->invoice_id = $invoice_id;
            $attachments->save();

            // move pic
            $imageName = $request->pic->hashName();
            $request->pic->move(public_path('Attachments/' . $invoice_number), $imageName);
        }
        //send notification email
        $user = User::first();
        Notification::send($user, new AddInvoices($invoice_id));

        ///////////////////////////send notification to database
        //send notification to all users and admins
        //        $user = User::get();

        //send notification to same user who add the invoice
        //        $user = User::find(Auth::user()->id);

        //send notification to all users else the user who added invoice
        //        $user = User::where('id' , '!=' , Auth::user()->id)->get();

        //send notification to all admins only we did codition in blade
        $user = User::get();
        $invoices = invoice::latest()->first();
        Notification::send($user, new AddInvoiceDB($invoices));

        session()->flash('Add', 'تم اضافة الفاتورة بنجاح');
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $invoice = Invoice::where('id', $id)->first();
        return view('invoices.status_update', compact('invoice'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $invoice = Invoice::where('id', $id)->first();
        $categories = Category::all();

        return view('invoices.edit_invoice', compact('invoice', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $invoices = Invoice::findOrFail($request->invoice_id);
        $invoiceDetails = InvoicesDetails::where('id_invoice', $request->invoice_id)->first();
        $invoices->update([
            'invoice_date' => $request->invoice_Date,
            'due_date' => $request->Due_date,
            'product' => $request->product,
            'category_id' => $request->category,
            'amount_collection' => $request->Amount_collection,
            'amount_commission' => $request->Amount_Commission,
            'discount' => $request->Discount,
            'value_vat' => $request->Value_VAT,
            'rate_vat' => $request->Rate_VAT,
            'total' => $request->Total,
            'note' => $request->note
        ]);

        $invoiceDetails->update([
            'product' => $request->product,
            'category' => $request->category,
            'note' => $request->note,
            'user' => (Auth::user()->name)
        ]);

        session()->flash('edit', 'تم تعديل الفاتورة بنجاح');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $id = $request->invoice_id;
        $invoice = Invoice::where('id', $id)->first();
        $attachments = InvoicesAttachments::where('invoice_id', $id)->first();
        $id_page = $request->id_page;

        if ($id_page != 2) {
            //delete dir
            if (!empty($attachments->invoice_number)) {
                Storage::disk('public_uploads')->deleteDirectory($attachments->invoice_number);
            }

            $invoice->forceDelete();
            session()->flash('delete_invoice');
            return redirect('/invoices');
        } else {
            $invoice->Delete();
            session()->flash('archive_invoice');
            return redirect('/invoices');
        }
        //to delete all files into the dir (bs bst5dm get msh first 3shan a loop 3lehomo )
        //        foreach ($attachments as $attachment){
        //            if (!empty($attachment->invoice_number)){
        //                Storage::disk('public_uploads')->delete($attachment->invoice_number.'/'.$attachment->file_name);
        //            }
        //        }

        //soft delete to archive it
        //        $invoice->Delete();
    }

    public function getproducts($id)
    {
        $products = DB::table("products")->where("category_id", $id)->pluck("Product_name", "id");
        return json_encode($products);
    }

    public function status_update($id, Request $request)
    {
        $invoice = Invoice::findOrFail($id);
        if ($request->Status === 'مدفوعة') {
            $invoice->update([
                'value_status' => 1,
                'status' => $request->Status,
                'payment_date' => $request->Payment_Date,
            ]);
            InvoicesDetails::create([
                'id_invoice' => $request->invoice_id,
                'invoice_number' => $request->invoice_number,
                'product' => $request->product,
                'category' => $request->Section,
                'status' => $request->Status,
                'value_status' => 1,
                'note' => $request->note,
                'payment_date' => $request->Payment_Date,
                'user' => (Auth::user()->name),
            ]);
        } else {
            $invoice->update([
                'value_status' => 3,
                'status' => $request->Status,
                'payment_date' => $request->Payment_Date,
            ]);
            InvoicesDetails::create([
                'id_invoice' => $request->invoice_id,
                'invoice_number' => $request->invoice_number,
                'product' => $request->product,
                'category' => $request->Section,
                'status' => $request->Status,
                'value_status' => 3,
                'note' => $request->note,
                'payment_date' => $request->Payment_Date,
                'user' => (Auth::user()->name),
            ]);
        }

        session()->flash('Status_Update');
        return redirect('/invoices');
    }

    public function invoice_paid()
    {
        $invoices = Invoice::where('value_status', 1)->get();
        return view('invoices.invoices_paid', compact('invoices'));
    }

    public function invoice_unpaid()
    {
        $invoices = Invoice::where('value_status', 2)->get();
        return view('invoices.invoices_unpaid', compact('invoices'));
    }

    public function invoice_partial()
    {
        $invoices = Invoice::where('value_status', 3)->get();
        return view('invoices.invoices_partial', compact('invoices'));
    }

    public function print_invoice($id)
    {
        $printed = Invoice::where('id', $id)->first();
        return view('invoices.print_invoice', compact('printed'));
    }

    public function export()
    {
        return Excel::download(new InvoicesExport(), 'invoices.xlsx');
    }

    public function markAsReadAll()
    {
        $userUnreadNotification = auth()->user()->unreadNotifications;
        if ($userUnreadNotification) {
            $userUnreadNotification->markAsRead();
            
            return back();
        }
    }
}
