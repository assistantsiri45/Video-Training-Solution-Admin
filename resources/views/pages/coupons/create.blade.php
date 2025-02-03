@extends('adminlte::page')

@section('title', 'Create Coupons')

@section('content_header')
    <h1 class="m-0 text-dark">Create Coupons</h1>
@stop

@section('css')
    <style>
        .sidebar-dark-primary .nav-sidebar > .nav-item > .nav-link.active, .sidebar-light-primary .nav-sidebar > .nav-item > .nav-link.active {
            background-color: #007bff !important;
            color: #fff !important;
        }
        /*#filter-btn{*/
            /*margin-top: 30px;*/
        /*}*/
        #students-table_filter{
            display: none;
        }
    </style>

@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <form role="form" id="create_coupon_form" method="POST" action="{{route('coupons.store')}}" >
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" name="name" required class="form-control" id="coupon_name" @error('name') is-invalid @enderror value="{{ old('name') }}" placeholder="Coupon Name">
                                    @error('name')
                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('name') }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="amount">Coupon Amount</label>
                                    <input type="number" min="0" step="any" name="amount" required class="form-control" id="amount" @error('amount') is-invalid @enderror value="{{ old('amount') }}" placeholder="Coupon amount">
                                    @error('amount')
                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('amount') }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="amount_type">Type</label>
                                    <select name="amount_type" id="amount_type" required class="form-control select2 select2-hidden-accessible @error('amount_type') is-invalid @enderror" style="width: 100%;" data-select2-id="1" tabindex="-1" aria-hidden="true">
                                        <option></option>
                                        <option data-select2-id="{{ \App\Models\Coupon::FLAT}}" @if((old('amount_type')== \App\Models\Coupon::FLAT)) selected @endif  value="{{  \App\Models\Coupon::FLAT }}">{{  \App\Models\Coupon::FLAT_TEXT }}</option>
                                        <option data-select2-id="{{ \App\Models\Coupon::PERCENTAGE}}"  @if((old('amount_type')== \App\Models\Coupon::PERCENTAGE)) selected @endif value="{{  \App\Models\Coupon::PERCENTAGE }}">{{  \App\Models\Coupon::PERCENTAGE_TEXT }}</option>
                                        <!-- <option data-select2-id="{{ \App\Models\Coupon::FIXED_PRICE }}" @if((old('amount_type') == \App\Models\Coupon::FIXED_PRICE)) selected @endif value="{{ \App\Models\Coupon::FIXED_PRICE }}">{{ \App\Models\Coupon::FIXED_PRICE_TEXT }}</option> -->
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4 coupon-per-user-container @if((old('amount_type') == \App\Models\Coupon::FIXED_PRICE)) d-none @endif">
                                <div class="form-group">
                                    <label for="coupon_per_user">Total no. of coupons per user</label>
                                    <input type="number" min="0"  name="coupon_per_user" required class="form-control" id="coupon_per_user" @error('coupon_per_user') is-invalid @enderror value="{{ old('coupon_per_user') }}" placeholder="Total no. of coupons per user">
                                    @error('coupon_per_user')
                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('coupon_per_user') }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-4 coupon-limit-container @if((old('amount_type') == \App\Models\Coupon::FIXED_PRICE)) d-none @endif">
                                <div class="form-group">
                                    <label for="total_coupon_limit">Total coupon limit</label>
                                    <input type="number" min="0"  name="total_coupon_limit" required class="form-control" id="total_coupon_limit" @error('total_coupon_limit') is-invalid @enderror value="{{ old('total_coupon_limit') }}" placeholder="Total coupon limit">
                                    @error('total_coupon_limit')
                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('total_coupon_limit') }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="validity">Coupon validity</label>
                                    <input id="validity" name="validity" type="text" class="form-control @error('validity') is-invalid @enderror value="{{ old('validity') }}">
                                    @error('validity')
                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('validity') }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="min_purchase_amount">Minimum purchase amount</label>
                                    <input id="min_purchase_amount" min="0" name="min_purchase_amount" type="number"  class="form-control" @error('min_purchase_amount') is-invalid @enderror value="{{ old('min_purchase_amount') }}" placeholder="Minimum purchase amount">
                                    @error('min_purchase_amount')
                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('min_purchase_amount') }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-4 maximum-discount-container @if((old('amount_type') == \App\Models\Coupon::FIXED_PRICE)) d-none @endif">
                                <div class="form-group">
                                    <label for="max_discount_amount">Maximum discount amount</label>
                                    <input id="max_discount_amount" min="0" name="max_discount_amount" type="number" required class="form-control" @error('max_discount_amount') is-invalid @enderror value="{{ old('max_discount_amount') }}" placeholder="Maximum discount amount">
                                    @error('max_discount_amount')
                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('max_discount_amount') }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-4 coupon-type-container @if((old('amount_type') == \App\Models\Coupon::FIXED_PRICE)) d-none @endif">
                                <div class="form-group">
                                    <label for="coupon_type">Coupon Type</label>
                                    <div class="checkbox">
                                        <label>
                                            <input name="coupon_type" id="private_cpn" type="checkbox" value="1"  data-toggle="collapse" data-target="#privateCoupon" aria-expanded="false" aria-controls="privateCoupon">
                                            Private
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 mt-3">
                                <div class="collapse" id="privateCoupon">
                                        <div class="card card-body">
                                        <div class="row">
                                            <!-- <div class="col-sm-4">
                                                <div class="form-group">
                                                    <input type="text" class="form-control pull-right" name="search" id="search" >
                                                    </div>

                                            </div> -->
                                            <div class="col-md-2">
                                                <select class="form-control " name="course_id" id="course"  style="width: 100% !important;">
                                                    <option value=""></option>
                                                    @foreach ($courses as $course)
                                                        <option value="{{ $course->id }}">{{ $course->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-2">
                                                <select name="level_id" id="level"  class="form-control select-level" style="width: 100% !important;" >
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <select name="package_type" id="package_type"  class="form-control select-level" style="width: 100% !important;" >
                                                <option value="">Choose Type</option>
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <select name="subject_id" id="subject"  class="form-control select-level" style="width: 100% !important;" >
                                                </select>
                                            </div>
                                           
                                            <div class="col-md-2">
                                                <select name="professor_id" id="professor"  class="form-control select-level" style="width: 100% !important;" >
                                                <option disabled selected>  Choose Professor </option>
                                                @foreach ($professors as $professor)
                                                    <option value="{{ $professor->id }}">{{ $professor->name }}</option>
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


                                            {{--<div class="col-sm-2">--}}
                                                {{--<div class="form-group">--}}
                                                    {{--<label for="signup_date_range">Signup date range:</label>--}}
                                                    {{--<input type="text" class="form-control pull-right" name="signup_date_range" id="signup_date_range">--}}
                                                {{--</div>--}}
                                            {{--</div>--}}
                                            {{--<div class="col-sm-2">--}}
                                                {{--<div class="form-group">--}}
                                                    {{--<label for="purchase_status">Purchase status:</label>--}}
                                                    {{--<select name="purchase_status" id="purchase_status" class="form-control select2 select2-hidden-accessible @error('purchase_status') is-invalid @enderror" style="width: 100%;" data-select2-id="1" tabindex="-1" aria-hidden="true">--}}
                                                        {{--<option data-select2-id="" value="all">ALL</option>--}}
                                                        {{--<option data-select2-id="{{ \App\Models\Order::PAYMENT_STATUS_SUCCESS}}" value="{{  \App\Models\Order::PAYMENT_STATUS_SUCCESS }}">{{  \App\Models\Order::PAYMENT_STATUS_SUCCESS }}</option>--}}
                                                        {{--<option data-select2-id="{{ \App\Models\Order::PAYMENT_STATUS_FAILED}}" value="{{  \App\Models\Order::PAYMENT_STATUS_FAILED }}">{{  \App\Models\Order::PAYMENT_STATUS_FAILED }}</option>--}}
                                                        {{--<option data-select2-id="{{ \App\Models\Order::PAYMENT_STATUS_RETURN}}" value="{{  \App\Models\Order::PAYMENT_STATUS_RETURN }}">{{  \App\Models\Order::PAYMENT_STATUS_RETURN }}</option>--}}
                                                        {{--<option data-select2-id="{{ \App\Models\Order::PAYMENT_STATUS_ABORTED}}" value="{{  \App\Models\Order::PAYMENT_STATUS_ABORTED }}">{{  \App\Models\Order::PAYMENT_STATUS_ABORTED }}</option>--}}
                                                        {{--<option data-select2-id="{{ \App\Models\Order::STATUS_PENDING}}" value="{{  \App\Models\Order::STATUS_PENDING }}">{{  \App\Models\Order::STATUS_PENDING }}</option>--}}
                                                    {{--</select>--}}
                                                {{--</div>--}}
                                            {{--</div>--}}
                                            {{--<div class="col-sm-3">--}}
                                                {{--<div class="form-group">--}}
                                                    {{--<label for="visits_and_browsing_history">Visits & browse history:</label>--}}
                                                    {{--<input type="text" name="visits_and_browsing_history" class="form-control" id="visits_and_browsing_history" @error('visits_and_browsing_history') is-invalid @enderror  value="{{ old('visits_and_browsing_history') }}" placeholder="No.of visits & browse history">--}}
                                                    {{--@error('visits_and_browsing_history')--}}
                                                    {{--<span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('visits_and_browsing_history') }}</span>--}}
                                                    {{--@enderror--}}
                                                {{--</div>--}}
                                            {{--</div>--}}
                                            {{--<div class="col-sm-2">--}}
                                                {{--<div class="form-group">--}}
                                                    {{--<label for="save_for_later">Save for later:</label>--}}
                                                    {{--<input type="text" name="save_for_later" class="form-control" id="save_for_later" @error('save_for_later') is-invalid @enderror value="{{ old('save_for_later') }}" placeholder="Save for later">--}}
                                                    {{--@error('save_for_later')--}}
                                                    {{--<span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('save_for_later') }}</span>--}}
                                                    {{--@enderror--}}
                                                {{--</div>--}}
                                            {{--</div>--}}
                                            {{--<div class="col-sm-2">--}}
                                                {{--<div class="form-group">--}}
                                                    {{--<label for="cart_value">Cart value:</label>--}}
                                                    {{--<input type="text" name="cart_value_amt" class="form-control" id="cart_value_amt" @error('cart_value_amt') is-invalid @enderror value="{{ old('cart_value_amt') }}" placeholder="Cart value">--}}
                                                    {{--@error('cart_value_amt')--}}
                                                    {{--<span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('cart_value_amt') }}</span>--}}
                                                    {{--@enderror--}}
                                                {{--</div>--}}
                                            {{--</div>--}}
                                            <!-- <div class="col-sm-1">
                                               <button type="button" class="btn btn-primary" id="filter-btn">Filter</button>
                                            </div>
                                            <div class="col-md-1">
                                                <button id="btn-clear" class="btn btn-primary">Clear</button>
                                            </div> -->
                                        </div><br><br>
                                        <div class="row">
                                             <div class="col-md-4">
                                                <div class="form-group">
                                                    <input type="text" class="form-control " name="search" id="search" >
                                                    </div>

                                            </div>
                                             <div class="col-md-2">
                                               <button type="button" class="btn btn-primary" id="filter-btn">Filter</button>
                                            
                                                <button id="btn-clear" type="button" class="btn btn-primary">Clear</button>
                                            </div>

                                        </div>
                                        <div class="col-12 all-students" style="display: none;">
                                            {!! $html->table(['id' => 'students-table'], true) !!}
                                        </div>

                                        <div class="col-12 filtered-students">

                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button id="create-btn"  type="submit" class="btn btn-primary">Create</button>
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

        function renderCheckbox(data, type, row, meta){
            return '<input id="'+data+'" class="check-row" name="users[]" type="checkbox">';
        }

        function callback(row, data, dataIndex){
            var rowId = data[0];

            // If row ID is in the list of selected row IDs
            if($.inArray(rowId, rows_selected) !== -1){
                $(row).find('input[type="checkbox"]').prop('checked', true);
                $(row).addClass('selected');
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
            
             $('#filter-btn').click(function(e) {
                e.preventDefault();
                 // To filter the datatable
                 var $table = $('#students-table');
                 $table.on('preXhr.dt', function ( e, settings, data ) {
                     data.filter = {
                         search: $('#search').val(),
                         //purchase_status: $('#purchase_status').find(":selected").val()
                     };
                 });
                $table.DataTable().draw();
            });



        var table = $('#students-table').DataTable();

            $('#btn-clear').click(function() {
                search: $('#search').val('');
                table.draw();
            });

            // Handle click on checkbox
            $('#students-table tbody').on('click', 'input[type="checkbox"]', function(e){
                var $row = $(this).closest('tr');

                // Get row data
                var data = table.row($row).data();

                // Get row ID
                var rowId = data.id;

                // Determine whether row ID is in the list of selected row IDs
                var index = $.inArray(rowId, rows_selected);

                // If checkbox is checked and row ID is not in list of selected row IDs
                if(this.checked && index === -1){
                    rows_selected.push(rowId);

                    // Otherwise, if checkbox is not checked and row ID is in list of selected row IDs
                } else if (!this.checked && index !== -1){
                    rows_selected.splice(index, 1);
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
                    $('#students-table tbody input[type="checkbox"]:not(:checked)').trigger('click');
                } else {
                    $('#students-table tbody input[type="checkbox"]:checked').trigger('click');
                }

                // Prevent click event from propagating to parent
                e.stopPropagation();
            });

            // Handle table draw event
            table.on('draw', function(){
                // Update state of "Select all" control
                updateDataTableSelectAllCtrl(table);
            });

            // Handle form submission event
            $('#create_coupon_form').on('submit', function(e){
                var form = this;
                var cid ;
                var pid ;

                cid = $('#course').val();
                pid = $('#professor').val();
                
                
                if($('#private_cpn').is(':checked')){
                    if(cid == '' && (pid == null || pid == '') && ($('#student').is(":not(:checked)"))){
                        alert('Please fill any private detail');
                        $('create-btn').prop('disabled',true);
                        e.preventDefault();
                    }else{
                        if($('#student').is(':checked')){
                            var stds = $('.check-row:checkbox:checked').length;
                            if(stds <= 0){

                            alert('Please select Students');
                            $('create-btn').prop('disabled',true);
                            e.preventDefault();
                            }
                        }
                    }
                }

                // Iterate over all selected checkboxes
                $.each(rows_selected, function(index, rowId){
                    // Create a hidden element
                    $(form).append(
                        $('<input>')
                            .attr('type', 'hidden')
                            .attr('name', 'id[]')
                            .val(rowId)
                    );
                });

                $('create-btn').prop('disabled',true);
            });


// Updates "Select all" control in a data table

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

            $("#amount_type").select2({
                placeholder: 'Select amount type'
            });

            $("#purchase_status").select2({
                placeholder: 'Select status'
            });

            //    other jquery scripts
            $('#signup_date_range').daterangepicker({
                locale: {
                    format: 'YYYY-MM-DD'
                }
            });
            $('#validity').daterangepicker({
                locale: {
                    format: 'YYYY-MM-DD'
                }
            });
            $('#type').select2({
                placeholder: 'Type'
            });
            $('.buttons-csv').hide();
            $('.buttons-pdf').hide();

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
        })
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
                $("#subject").select2('val', ' ');
                $("#professor").select2('val', ' ');
                $("#package_type").select2('val', ' ');
                $("#student").prop("checked", false);
                });
            });

            $(function () {

                // Course wise Levels

                $('#course').on('change', function () {
                    var CourseID = $(this).val();
                    $('#level').empty();
                    $('#package_type').empty();
                    $('#subject').empty();

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
                      //  $('#level').empty();
                    }
                });
            var package_type;
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
                 //   $('#package_type').empty();
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
                           
                           
                            $('#subject').append('<option value="' + value.id + '">' + value.name + '</option>');

                        });
                        
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

