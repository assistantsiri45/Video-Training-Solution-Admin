@extends('adminlte::page')

@section('title', 'Edit Coupons')

@section('content_header')
    <h1 class="m-0 text-dark">Edit Coupon</h1>
@stop

@section('css')
    <style>
        .sidebar-dark-primary .nav-sidebar > .nav-item > .nav-link.active, .sidebar-light-primary .nav-sidebar > .nav-item > .nav-link.active {
            background-color: #007bff !important;
            color: #fff !important;
        }
        #filter-btn{
            margin-top: 30px;
        }
        #filter-btn2{
            margin-top: 30px;
        }
        #btn-clear{
            margin-top: 30px;
        }
        #students-table_filter{
            display: none;
        }
        .select2-container {
            width: 100% !important;
        }
    </style>

@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-default">
                <form role="form" name="edit_coupon_form" id="edit_coupon_form" method="POST"  action="{{route('coupons.update',$coupon->id)}}" >
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" name="name" class="form-control" @if($coupon->status==2) readonly @endif id="coupon_name" @error('name') is-invalid @enderror value="{{$coupon->name}}" placeholder="Coupon Name">
                                    @error('name')
                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('name') }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="amount">Coupon Amount</label>
                                    <input type="number" min="0"  name="amount" class="form-control"  @if($coupon->status==2) readonly @endif id="amount" @error('amount') is-invalid @enderror value="{{$coupon->amount}}" placeholder="Coupon amount">
                                    @error('amount')
                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('amount') }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="amount_type">Type</label>
                                    <select name="amount_type" id="amount_type" class="form-control select2 select2-hidden-accessible @error('amount_type') is-invalid @enderror" style="width: 100%;" data-select2-id="1" tabindex="-1" aria-hidden="true">
                                        <option></option>
                                        <option data-select2-id="{{ \App\Models\Coupon::FLAT}}" value="{{  \App\Models\Coupon::FLAT }}" @if ($coupon->amount_type == 1) selected @endif>FLAT</option>
                                        <option data-select2-id="{{ \App\Models\Coupon::PERCENTAGE }}" value="{{ \App\Models\Coupon::PERCENTAGE}}" @if ($coupon->amount_type == 2) selected @endif>PERCENTAGE</option>
                                        <!-- <option data-select2-id="{{ \App\Models\Coupon::FIXED_PRICE }}" @if ($coupon->amount_type == \App\Models\Coupon::FIXED_PRICE) selected @endif value="{{ \App\Models\Coupon::FIXED_PRICE }}">{{ \App\Models\Coupon::FIXED_PRICE_TEXT }}</option> -->
                                    </select>
                                    @error('amount_type')
                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('amount_type') }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-4 coupon-per-user-container @if ($coupon->amount_type == \App\Models\Coupon::FIXED_PRICE) d-none @endif">
                                <div class="form-group">
                                    <label for="coupon_per_user">Total no. of coupons per user</label>
                                    <input type="number" min="0"  name="coupon_per_user" class="form-control" id="coupon_per_user" @error('coupon_per_user') is-invalid @enderror value="{{$coupon->coupon_per_user}}" placeholder="Total no. of coupons per user">
                                    @error('coupon_per_user')
                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('coupon_per_user') }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-4 coupon-limit-container @if ($coupon->amount_type == \App\Models\Coupon::FIXED_PRICE) d-none @endif">
                                <div class="form-group">
                                    <label for="total_coupon_limit">Total coupon limit</label>
                                    <input type="number" min="0"  name="total_coupon_limit" class="form-control" id="total_coupon_limit" @error('total_coupon_limit') is-invalid @enderror value="{{$coupon->total_coupon_limit}}" placeholder="Total coupon limit">
                                    @error('total_coupon_limit')
                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('total_coupon_limit') }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="validity">Coupon validity</label>
                                    <input id="validity" name="validity" type="text" class="form-control @error('validity') is-invalid @enderror value="{{$coupon->validity}}">
                                    @error('validity')
                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('validity') }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="min_purchase_amount">Minimum purchase amount</label>
                                    <input id="min_purchase_amount" name="min_purchase_amount" type="number" class="form-control" @error('min_purchase_amount') is-invalid @enderror value="{{$coupon->min_purchase_amount}}">
                                    @error('min_purchase_amount')
                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('min_purchase_amount') }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-4 maximum-discount-container @if ($coupon->amount_type == \App\Models\Coupon::FIXED_PRICE) d-none @endif" @if ($coupon->amount_type == 1) style="display:none ;" @endif>
                                <div class="form-group">
                                    <label for="max_discount_amount">Maximum discount amount</label>
                                    <input id="max_discount_amount" min="0" name="max_discount_amount" type="number" class="form-control" @error('max_discount_amount') is-invalid @enderror value="{{ $coupon->max_discount_amount }}" placeholder="Maximum discount amount">
                                    @error('max_discount_amount')
                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('max_discount_amount') }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <input type="text" hidden id="valid_from" value="{{$coupon->valid_from}}">
                                <input type="text" hidden id="valid_to" value="{{$coupon->valid_to}}">
                            </div>
                            <div class="col-sm-4 coupon-type-container @if ($coupon->amount_type == \App\Models\Coupon::FIXED_PRICE) d-none @endif">
                                <div class="form-group">
                                    <label for="coupon_type">Coupon Type</label>
                                    <div class="checkbox">
                                        <label>
                                            <input name="coupon_type" id="coupon_type" value="{{$coupon->coupon_type}}"  type="checkbox" @if($coupon->coupon_type==2)  checked  @endif>
                                            Private
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 mt-3">
                                <div  id="privateCoupon"  @if($coupon->coupon_type==1) class="collapse" @else class="expand" @endif  >
                                      
{{--                                    <form  id="form_filter_students" name="form_filter_students" class="form-inline mb-0">--}}
                                        <div class="card card-body">
                                        <div class="row">
                                            <div class="col-sm-2">
                                                <div class="form-group">
                                                    <label for="signup_date_range">Signup date range:</label>
                                                    <input type="text" class="form-control pull-right" name="signup_date_range" id="signup_date_range" placeholder="Signup date range">
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="form-group">
                                                    <label for="purchase_status">Purchase status:</label>
                                                    <select name="purchase_status" id="purchase_status" class="form-control select2 select2-hidden-accessible @error('purchase_status') is-invalid @enderror" style="width: 100%;" data-select2-id="1" tabindex="-1" aria-hidden="true">
                                                        <option></option>
                                                        <option data-select2-id="" value="all">ALL</option>
                                                        <option data-select2-id="{{ \App\Models\Coupon::SUCCESS}}" value="{{  \App\Models\Coupon::SUCCESS }}">{{  \App\Models\Coupon::SUCCESS_TEXT }}</option>
                                                        <option data-select2-id="{{ \App\Models\Coupon::FAILED}}" value="{{  \App\Models\Coupon::FAILED }}">{{  \App\Models\Coupon::FAILED_TEXT }}</option>
                                                        <option data-select2-id="{{ \App\Models\Coupon::RETURNED}}" value="{{  \App\Models\Coupon::RETURNED }}">{{  \App\Models\Coupon::RETURNED_TEXT }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label for="visits_and_browsing_history">Visits & browse history:</label>
                                                    <input type="text" name="visits_and_browsing_history" class="form-control" id="visits_and_browsing_history" @error('visits_and_browsing_history') is-invalid @enderror" value="{{ old('visits_and_browsing_history') }}" placeholder="No.of visits & browse history">
                                                    @error('visits_and_browsing_history')
                                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('visits_and_browsing_history') }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="form-group">
                                                    <label for="save_for_later">Save for later:</label>
                                                    <input type="text" name="save_for_later" class="form-control" id="save_for_later" @error('save_for_later') is-invalid @enderror" value="{{ old('save_for_later') }}" placeholder="Save for later">
                                                    @error('save_for_later')
                                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('save_for_later') }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="form-group">
                                                    <label for="cart_value">Cart value:</label>
                                                    <input type="text" name="cart_value_amt" class="form-control" id="cart_value_amt" @error('cart_value_amt') is-invalid @enderror" value="{{ old('cart_value_amt') }}" placeholder="Cart value">
                                                    @error('cart_value_amt')
                                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('cart_value_amt') }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                           
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label>Search</label>
                                                <div class="form-group">
                                                    <input type="text" class="form-control" name="search" id="search" >
                                                </div>

                                            </div>

                                            <div class="col-md-2">
                                                <button type="button" class="btn btn-primary" id="filter-btn">Filter</button>
                                                <button id="btn-clear" type="button" class="btn btn-primary">Clear</button>
                                            </div>

                                        </div>
                                        <div class="row">
                                            
                                        <div class="col-md-2">            
                                                <x-inputs.course id="course" class="{{ $errors->has('course_id') ? ' is-invalid' : '' }}">
                                                @if(!empty($private_coupon))
                                                @if(!empty(old('course_id', $private_coupon->course_id)))
                                            <option value="{{ old('course_id', $private_coupon->course_id) }}" selected>{{ old('course_id_text', $private_coupon->course->name) }}</option>
                                        @endif

                                        @endif                       
                                    </x-inputs.course>   
                                            </div>

                                            <div class="col-md-2">                                            
                                                    <x-inputs.level id="level" related="#course">
                                                @if(!empty($private_coupon))
                                                @if(!empty(old('level_id', $private_coupon->level_id)))
                                            <option value="{{ old('level_id', $private_coupon->level_id) }}" selected>{{ old('level_id_text', $private_coupon->level->name) }}</option>
                                        @endif
                                        @endif
                                    </x-inputs.level>
                                            </div>
                                            <div class="col-md-2">
                                            <select class="form-control" id="package_type" name="package_type">
                                        <option value="">Choose Type</option>
                                        @if(!empty($types))
                                        @foreach($types as $type)
                                        @if(!empty($type->packagetype))
                                        @if($private_coupon->package_type_id == $type->packagetype->id))
                                            <option value="{{ old('package_type_id', $private_coupon->package_type->id) }}" selected>{{ old('level_id_text', $private_coupon->package_type->name) }}</option>
                                        @else
                                        <option value="{{$type->packagetype->id}}">{{$type->packagetype->name}}</option>
                                        @endif
                                        @endif
                                        @endforeach
                                        @endif
                                    </select>
    </div>
                                            <div class="col-md-2">
                                                <select name="subject_id" id="subject"  class="form-control select-level" style="width: 100% !important;" >
                                                @if(!empty($private_coupon))
                                                @if(!empty($private_coupon->subject_id))
                                                    <option value="{{ $private_coupon->subject_id }}" selected>{{ $private_coupon->subject->name }}</option>
                                                @endif
                                                @endif
                                                </select>
                                            </div>

                                            <div class="col-md-2">
                                                <select name="professor_id" id="professor"  class="form-control select-level" style="width: 100% !important;" >
                                                <option disabled selected>  Choose Professor </option>
                                                @foreach ($professors as $professor)
                                                    <option value="{{ $professor->id }}" @if(!empty($private_coupon)) @if($professor->id == $private_coupon->professor_id) selected @endif @endif>{{ $professor->name }}</option>
                                                @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-1">
                                                <label>Student</label>
                                                <input type="checkbox" name="student" id="student" value="student">
                                            </div>
                                            <div class="col-md-1">
                                                <button id="btn-clear2" type="button" class="btn btn-primary">Clear</button>
                                            </div> 

                                        </div>
                                       
                                        <div class="row">
                                            <div class="col-12">
                                            {!! $html->table(['id' => 'students-table'], true) !!}
                                            </div>
                                        </div>
                                    </div>
{{--                                    </form>--}}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button id="create-btn" type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-md-6">
        </div>
    </div>
@stop

@section('js')
    {!! $html->scripts() !!}
    <script type="text/javascript">
         
        var rows_selected = [];
        var rows_unchecked = [];

        function renderCheckbox(data, type, full, meta){
            if(full.flag==true){
                return '<input id="'+data+'"  value="'+data+'"  checked  class="check-row" name="users[]" type="checkbox" >';
            }
            else{
                return '<input id="'+data+'"  value="'+data+'"  class="check-row" name="users[]" type="checkbox" >';
            }

        }

        function callback(row, data, dataIndex){
            var rowId = data[0];

            // If row ID is in the list of selected row IDs
            if($.inArray(rowId, rows_selected) !== -1){
                $(row).find('input[type="checkbox"]').prop('checked', true);
                $(row).addClass('selected');
            }
            // If row ID is in the list of removed row IDs
            if($.inArray(rowId, rows_unchecked) !== -1){
                $(row).find('input[type="checkbox"]').prop('checked', false);
                $(row).removeClass('selected');
            }
        }

        $(function () {

            $('#amount_type').change(function(){
                var amount_type = $('#amount_type').val();
                if(amount_type == '1'){
                    $('.maximum-discount-container').hide();
                }else{
                    $('.maximum-discount-container').show();
                }
            });
            
            var table = $('#students-table').DataTable();

            // Handle click on checkbox
            $('#students-table tbody').on('click', 'input[type="checkbox"]', function(e){
                var $row = $(this).closest('tr');

                // Get row data
                var data = table.row($row).data();

                // Get row ID
                var rowId = data.id;

                // Determine whether row ID is in the list of selected row IDs
                var index = $.inArray(rowId, rows_selected);
                var index_removed = $.inArray(rowId, rows_unchecked);


                // If checkbox is checked and row ID is not in list of selected row IDs
                if(this.checked && index === -1){
                    rows_selected.push(rowId);
                    // Otherwise, if checkbox is not checked and row ID is in list of selected row IDs
                } else if (!this.checked && index !== -1){
                    rows_selected.splice(index, 1);
                }


                if(!this.checked && index_removed === -1){
                    rows_unchecked.push(rowId);
                }else{
                    rows_unchecked.splice(index_removed, 1);
                }

                if(this.checked){
                    $row.addClass('selected');

                } else {
                    $row.removeClass('selected');
                }

                // Update state of "Select all" control
                updateDataTableSelectAllCtrl(table);

                // Prevent click event from propagating to parent
                e.stopPropagation();
            });

            // Handle click on table cells with checkboxes , thead th:first-child
            $('#students-table').on('click', 'tbody td', function(e){
                $(this).parent().find('input[type="checkbox"]').trigger('click');
            });

            // Handle click on "Select all" control
            $('#students-table').on('click', 'thead input[name="select_all"]',function(e){
                if(this.checked){
                    $('#datatable tbody input[type="checkbox"]:not(:checked)').trigger('click');
                } else {
                    $('#datatable tbody input[type="checkbox"]:checked').trigger('click');
                }

                // Prevent click event from propagating to parent
                e.stopPropagation();
            });

            // Handle table draw event
            table.on('draw', function(){
                // Update state of "Select all" control
                updateDataTableSelectAllCtrl(table);
            });

            $('#edit_coupon_form').validate({
                rules: {
                    name: {
                      required: true
                    },
                    amount: {
                        required: true
                    },
                    coupon_per_user: {
                        required: true
                    },
                    total_coupon_limit: {
                        required: true
                    },
                    max_discount_amount: {
                        required: true
                    }
                }
            });

            // Handle form submission event
            $('#edit_coupon_form').on('submit', function(e){
                var form = this;

                // Iterate over all selected checkboxes
                $.each(rows_selected, function(index, rowId){
                    // Create a hidden element
                    $(form).append(
                        $('<input>')
                            .attr('type', 'hidden')
                            .attr('name', 'selected_students[]')
                            .val(rowId),
                    );
                });
                $.each(rows_unchecked, function(index, rowId){
                    // Create a hidden element
                    $(form).append(
                        $('<input>')
                            .attr('type', 'hidden')
                            .attr('name', 'removed_students[]')
                            .val(rowId),
                    );
                });
            });


// Updates "Select all" control in a data table
//
            function updateDataTableSelectAllCtrl(table){
                var $table             = table.table().node();
                var $chkbox_all        = $('tbody input[type="checkbox"]', $table);
                var $chkbox_checked    = $('tbody input[type="checkbox"]:checked', $table);
                var chkbox_select_all  = $('thead input[name="select_all"]', $table).get(0);

                // If none of the checkboxes are checked
                if($chkbox_checked.length === 0){
                    chkbox_select_all.checked = false;
                    if('indeterminate' in chkbox_select_all){
                        chkbox_select_all.indeterminate = false;
                    }

                    // If all of the checkboxes are checked
                } else if ($chkbox_checked.length === $chkbox_all.length){
                    chkbox_select_all.checked = true;
                    if('indeterminate' in chkbox_select_all){
                        chkbox_select_all.indeterminate = false;
                    }

                    // If some of the checkboxes are checked
                } else {
                    chkbox_select_all.checked = true;
                    if('indeterminate' in chkbox_select_all){
                        chkbox_select_all.indeterminate = true;
                    }
                }
            }



            //    other jquery scripts

            $('#filter-btn').click(function(e) {
                e.preventDefault();
                // To filter the datatable
                var $table = $('#students-table');
                $table.on('preXhr.dt', function ( e, settings, data ) {
                    data.filter = {
                        signup_date_range: $('#signup_date_range').val(),
                        purchase_status: $('#purchase_status').find(":selected").val(),
                        search: $('#search').val(),
                    };
                });
                $table.DataTable().draw();
            });

            $('#btn-clear').click(function() {
                var $table = $('#students-table');
                $('#search').val('');
                $('#signup_date_range').val(''),
                $table.DataTable().draw();
            });

            $("#btn-clear2").click(function(){
                $("#course").select2('val', ' ');
                $("#level").select2('val', ' ');
                $("#subject").select2('val', ' ');
                $("#professor").select2('val', ' ');
                $("#package_type").select2('val', ' ');
                $("#student").prop("checked", false);
            });
         
            $("#validity").daterangepicker({
                startDate: $('#valid_from').val(),
                endDate: $('#valid_to').val(),
                locale: {
                    format: 'YYYY-MM-DD'
                }
            });
            //    other jquery scripts
            // $('#signup_date_range').daterangepicker({
            //     locale: {
            //         format: 'YYYY-MM-DD'
            //     }
            // });

            $('input[name="signup_date_range"]').daterangepicker({
                autoUpdateInput: false,
                locale: {
                    cancelLabel: 'Clear'
                }
            });

            $('input[name="signup_date_range"]').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
            });

            $('input[name="signup_date_range"]').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
            });


          
            $('.buttons-csv').hide();
            $('.buttons-pdf').hide();
            $("#amount_type").select2({
                placeholder: 'Please choose amount type'
            });
            $("#purchase_status").select2({
                placeholder: 'Select status'
            });


            $( "#coupon_type" ).click(function() {
                if($(this).val()==2){
                    $('#privateCoupon').hide();
                    $('#coupon_type').val(1);
                }
                else{
                    $('#privateCoupon').show();
                    $('#coupon_type').val(2);
                }
            });

            $('#amount_type').change(function () {
                if ($(this).val() === '{{ \App\Models\Coupon::FIXED_PRICE }}') {
                    $('.coupon-per-user-container').addClass('d-none');
                    $('.coupon-limit-container').addClass('d-none');
                    $('.maximum-discount-container').addClass('d-none');
                    $('.coupon-type-container').addClass('d-none');
                } else {
                    $('.coupon-per-user-container').removeClass('d-none');
                    $('.coupon-limit-container').removeClass('d-none');
                    $('.maximum-discount-container').removeClass('d-none');
                    $('.coupon-type-container').removeClass('d-none');
                }
            });
        });
    </script>

    <script>
    
        $('#course').select2({
                placeholder: 'Course'
            });

            $('#level').select2({
                placeholder: 'Level'
            });

            $('#subject').select2({
                placeholder: 'Subject'
            });

            $('#professor').select2({
                placeholder: 'Professor'
        });
        $('#package_type').select2({
                placeholder: 'Type'
        });

        $(function(){
                $("#btn-clear2").click(function(){
                $("#course").select2('val', ' ');
                $("#level").select2('val', ' ');
                $("#package_type").select2('val', ' ');
                $("#subject").select2('val', ' ');
                $("#professor").select2('val', ' ');
                $("#student").prop("checked", false);
                });
        });

      
        $(function () {

// Course wise Levels

$('#course').on('change', function () {
    $('#level').empty();
    $('#package_type').empty();
    $('#subject').empty();
    var CourseID = $(this).val();

    if (CourseID) {
        $.ajax({
            url: '{{ url('/getlevels/ajax') }}' + '/' + CourseID,
            type: "GET",
            dataType: "json",
            success: function (data) {
            //    $('#level').empty();
                $('#level').append('<option disabled selected>  Choose Level </option>');
                $.each(data, function (key, value) {
                    $('#level').append('<option value="' + value.id + '">' + value.name + '</option>');
                });

            }
        });
    } else {
    //    $('#level').empty();
    }
});

//Level wise sublects

$('#level').on('change', function () {
    $('#package_type').empty();
    $('#subject').empty();
                var LevelID = $(this).val();
                if (LevelID) {
                $.ajax({
                    url: '{{ url('/gettypes/ajax') }}' + '/' + LevelID,
                    type: "GET",
                    dataType: "json",
                    success: function (data) {
                    //    $('#package_type').empty();
                        $('#package_type').append('<option disabled selected>  Choose Type </option>');
                        $.each(data, function (key, value) {
                            if(jQuery.isEmptyObject(value.packagetype)!=true){
                                $('#package_type').append('<option value="' + value.packagetype.id + '">' + value.packagetype.name + '</option>');
                            }
                        });
                        getSubject(package_type,LevelID);
                    }
                });
              

                } else {
                //    $('#package_type').empty();
                }
            });
            $('#package_type').on('change', function () {
                $('#subject').empty();
                var package_type = $(this).val();
                var level_id=$("#level").val();
                if(package_type && level_id){
                    getSubject(package_type,level_id);

                }
            });
            function getSubject(package_type,level_id){

               
               let url = '{{ url('get-subjects-by-level') }}';

               $.ajax({
                   url: url,
                   type: "GET",
                   dataType: 'json',
                   data: {
                      
                       "level_ids" : level_id ,
                       "type_id"  : package_type   ,
                   }
               }).done(function (response) {
                
                   $('#subject').empty();
                   if(response.length>0){
                       $('#subject').append('<option disabled selected>  Choose Subject </option>');
                      
                       $.each(response, function( index, value ) {
                       
                           var item = value.id;
                           var subject_id='{{@$private_coupon->subject_id}}';
                           if(item==subject_id){
                            exist=true;
                           }else{
                            exist=false;
                           }
                          console.log(exist);
                          
                           $('#subject').append('<option value="' + value.id + '" ' + ( exist ? 'selected':'') + ' >' + value.name + '</option>');

                       });
                       // $("#no_subjects_available").addClass('d-none');
                   }
                   else{
                   }

               });
           }

//Subject wise Professsors

$('#subject').on('change',function(){

    var subjectID = $(this).val();
    SubjectProfessors(subjectID);
});

function SubjectProfessors(subjectID){

    if(subjectID){
        $.ajax({
            url: '{{ url('/getprofessors/ajax')}}' + '/' + subjectID,
            type: "GET",
            'dataType': "json",
            success: function (data){
                $('#professor').empty();
                $('#professor').append('<option disabled selected>  Choose Professor </option>');
                $.each(data, function (key, value) {
                    $('#professor').append('<option value="' + value.id + '">' + value.name + '</option>');
                });
            }

        });
    }

}

$("#student").change(function() {
    if(this.checked) {
        $(".all-students").css("display", "block");
         // To filter the datatable
        var $table = $('#students-table');
        $table.on('preXhr.dt', function ( e, settings, data ) {
            data.filter = {
                search: $('#search').val(),
                course: $('#course').val(),
                level: $('#level').val(),
                subject: $('#subject').val(),
                professor: $('#professor').val(),
            };
        });
        $table.DataTable().draw();
       
    }else{
        $(".all-students").css("display", "none");
    }
});





});


    </script>
@stop

