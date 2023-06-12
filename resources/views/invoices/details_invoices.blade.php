@extends('layouts.master')

@section('css')
    <!---Internal  Prism css-->
    <link href="{{ URL::asset('assets/plugins/prism/prism.css') }}" rel="stylesheet">
    <!---Internal Input tags css-->
    <link href="{{ URL::asset('assets/plugins/inputtags/inputtags.css') }}" rel="stylesheet">
    <!--- Custom-scroll -->
    <link href="{{ URL::asset('assets/plugins/custom-scroll/jquery.mCustomScrollbar.css') }}" rel="stylesheet">
@endsection

@section('title')
    تفاصيل فاتورة
@stop

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">قائمة الفواتير</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/
                    تفاصيل الفاتورة</span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection

@section('content')

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session()->has('delete'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>{{ session()->get('delete') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if (session()->has('Add'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>{{ session()->get('Add') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="d-md-flex mt-5">
        <div class="">
            <div class="panel panel-primary tabs-style-4">
                <div class="tab-menu-heading">
                    <div class="tabs-menu ">
                        <!-- Tabs -->
                        <ul class="nav panel-tabs">
                            <li class=""><a href="#tab21" class="active" data-toggle="tab">تفاصيل الفاتورة</a></li>
                            <li><a href="#tab22" data-toggle="tab">حالات الدفع</a></li>
                            <li><a href="#tab23" data-toggle="tab"> المرفقات</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="tabs-style-4" style="width: 100%;">
            <div class="panel-body tabs-menu-body">
                <div class="tab-content">
                    <div class="tab-pane active" id="tab21">
                        <div class="table-responsive mt-15">
                            <table class="table table-striped" style="text-align:center">
                                <tbody>
                                    <tr>
                                        <th scope="row">رقم الفاتورة</th>
                                        <td>{{ $invoice->invoice_number }}</td>
                                        <th scope="row">تاريخ الاصدار</th>
                                        <td>{{ $invoice->invoice_date }}</td>
                                        <th scope="row">تاريخ الاستحقاق</th>
                                        <td>{{ $invoice->due_date }}</td>
                                        <th scope="row">القسم</th>
                                        <td>{{ $invoice->category->category_name }}</td>
                                    </tr>

                                    <tr>
                                        <th scope="row">المنتج</th>
                                        <td>{{ $invoice->product }}</td>
                                        <th scope="row">مبلغ التحصيل</th>
                                        <td>{{ $invoice->amount_collection }}</td>
                                        <th scope="row">مبلغ العمولة</th>
                                        <td>{{ $invoice->amount_commission }}</td>
                                        <th scope="row">الخصم</th>
                                        <td>{{ $invoice->discount }}</td>
                                    </tr>

                                    <tr>
                                        <th scope="row">نسبة الضريبة</th>
                                        <td>{{ $invoice->rate_vat }}</td>
                                        <th scope="row">قيمة الضريبة</th>
                                        <td>{{ $invoice->value_vat }}</td>
                                        <th scope="row">الاجمالي مع الضريبة</th>
                                        <td>{{ $invoice->total }}</td>
                                        <th scope="row">الحالة الحالية</th>

                                        @if ($invoice->value_status == 1)
                                            <td><span class="badge badge-pill badge-success">{{ $invoice->status }}</span>
                                            </td>
                                        @elseif($invoice->value_status == 2)
                                            <td><span class="badge badge-pill badge-danger">{{ $invoice->status }}</span>
                                            </td>
                                        @else
                                            <td><span class="badge badge-pill badge-warning">{{ $invoice->status }}</span>
                                            </td>
                                        @endif
                                    </tr>

                                    <tr>
                                        <th scope="row">ملاحظات</th>
                                        <td>{{ $invoice->note }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="tab-pane" id="tab22">
                        <div class="table-responsive mt-15">
                            <table class="table center-aligned-table mb-0 table-hover" style="text-align:center">
                                <thead>
                                    <tr class="text-dark">
                                        <th>#</th>
                                        <th>رقم الفاتورة</th>
                                        <th>نوع المنتج</th>
                                        <th>القسم</th>
                                        <th>حالة الدفع</th>
                                        <th>تاريخ الدفع </th>
                                        <th>ملاحظات</th>
                                        <th>تاريخ الاضافة </th>
                                        <th>المستخدم</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 0; ?>
                                    @foreach ($invoiceDetails as $detail)
                                        <?php $i += 10; ?>
                                        <tr>
                                            <td>{{ $i }}</td>
                                            <td>{{ $detail->invoice_number }}</td>
                                            <td>{{ $detail->product }}</td>
                                            <td>{{ $invoice->category->category_name }}</td>
                                            @if ($detail->value_status == 1)
                                                <td><span
                                                        class="badge badge-pill badge-success">{{ $detail->status }}</span>
                                                </td>
                                            @elseif($detail->value_status == 2)
                                                <td><span
                                                        class="badge badge-pill badge-danger">{{ $detail->status }}</span>
                                                </td>
                                            @else
                                                <td><span
                                                        class="badge badge-pill badge-warning">{{ $detail->status }}</span>
                                                </td>
                                            @endif
                                            <td>{{ $detail->payment_date }}</td>
                                            <td>{{ $detail->note }}</td>
                                            <td>{{ $detail->created_at }}</td>
                                            <td>{{ $detail->user }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="tab-pane" id="tab23">

                        @can('اضافة مرفق')
                            <div class="card card-statistics">
                                <div class="card-body">
                                    <p class="text-danger">* صيغة المرفق pdf, jpeg ,.jpg , png </p>
                                    <h5 class="card-title">اضافة مرفقات</h5>
                                    <form method="post" action="{{ route('InvoicesAttachments.store') }}"
                                        enctype="multipart/form-data">
                                        {{ csrf_field() }}

                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="customFile" name="file_name">
                                            <input type="hidden" id="customFile" name="invoice_number"
                                                value="{{ $invoice->invoice_number }}">
                                            <input type="hidden" id="invoice_id" name="invoice_id"
                                                value="{{ $invoice->id }}">
                                            <label class="custom-file-label" for="customFile">حدد
                                                المرفق</label>
                                        </div><br><br>
                                        <button type="submit" class="btn btn-primary btn-xl "
                                            name="uploadedFile">تاكيد</button>
                                    </form>
                                </div>
                            </div>
                        @endcan
                        <br>

                        <div class="table-responsive mt-15">
                            <table class="table center-aligned-table mb-0 table table-hover" style="text-align:center">
                                <thead>
                                    <tr class="text-dark">
                                        <th scope="col">#</th>
                                        <th scope="col">اسم الملف</th>
                                        <th scope="col">قام بالاضافة</th>
                                        <th scope="col">تاريخ الاضافة</th>
                                        <th scope="col">العمليات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 0; ?>
                                    @foreach ($invoiceAttachment as $attachment)
                                        <?php $i++; ?>
                                        <tr>
                                            <td>{{ $i }}</td>
                                            <td>{{ $attachment->file_name }}</td>
                                            <td>{{ $attachment->created_by }}</td>
                                            <td>{{ $attachment->created_at }}</td>
                                            <td colspan="2">

                                                <a class="btn btn-outline-success btn-sm"
                                                    href="{{ url('view_file') }}/{{ $invoice->invoice_number }}/{{ $attachment->file_name }}"
                                                    role="button" target="_blank"><i class="fas fa-eye"></i>&nbsp;
                                                    عرض</a>

                                                <a class="btn btn-outline-info btn-sm"
                                                    href="{{ url('download') }}/{{ $invoice->invoice_number }}/{{ $attachment->file_name }}"
                                                    role="button"><i class="fas fa-download"></i>&nbsp;
                                                    تحميل</a>

                                                @can('حذف المرفق')
                                                    <button class="btn btn-outline-danger btn-sm" data-toggle="modal"
                                                        data-file_name="{{ $attachment->file_name }}"
                                                        data-invoice_number="{{ $attachment->invoice_number }}"
                                                        data-id_file="{{ $attachment->id }}"
                                                        data-target="#delete_file">حذف</button>
                                                @endcan

                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- delete -->
        <div class="modal fade" id="delete_file" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">حذف المرفق</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('delete_file') }}" method="post">
                        {{ csrf_field() }}
                        <div class="modal-body">
                            <p class="text-center">
                            <h6 style="color:red"> هل انت متاكد من عملية حذف المرفق ؟</h6>
                            </p>

                            <input type="hidden" name="id_file" id="id_file" value="">
                            <input type="hidden" name="file_name" id="file_name" value="">
                            <input type="hidden" name="invoice_number" id="invoice_number" value="">

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">الغاء</button>
                            <button type="submit" class="btn btn-danger">تاكيد</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <!--Internal  Datepicker js -->
    <script src="{{ URL::asset('assets/plugins/jquery-ui/ui/widgets/datepicker.js') }}"></script>
    <!-- Internal Select2 js-->
    <script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <!-- Internal Jquery.mCustomScrollbar js-->
    <script src="{{ URL::asset('assets/plugins/custom-scroll/jquery.mCustomScrollbar.concat.min.js') }}"></script>
    <!-- Internal Input tags js-->
    <script src="{{ URL::asset('assets/plugins/inputtags/inputtags.js') }}"></script>
    <!--- Tabs JS-->
    <script src="{{ URL::asset('assets/plugins/tabs/jquery.multipurpose_tabcontent.js') }}"></script>
    <script src="{{ URL::asset('assets/js/tabs.js') }}"></script>
    <!--Internal  Clipboard js-->
    <script src="{{ URL::asset('assets/plugins/clipboard/clipboard.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/clipboard/clipboard.js') }}"></script>
    <!-- Internal Prism js-->
    <script src="{{ URL::asset('assets/plugins/prism/prism.js') }}"></script>

    <script>
        $('#delete_file').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget)
            var id_file = button.data('id_file')
            var file_name = button.data('file_name')
            var invoice_number = button.data('invoice_number')
            var modal = $(this)
            modal.find('.modal-body #id_file').val(id_file);
            modal.find('.modal-body #file_name').val(file_name);
            modal.find('.modal-body #invoice_number').val(invoice_number);
        })
    </script>
@endsection
