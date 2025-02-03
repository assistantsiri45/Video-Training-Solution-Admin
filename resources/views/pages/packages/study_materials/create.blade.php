@extends('adminlte::page')

@section('title', 'Package Study Materials')

@section('content_header')
    <div class="row">
        <div class="col">
            <h1 class="m-0 text-dark">Package Study Materials</h1>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <form id="package-study-materials-form" action="{{url('package-study-materials')}}" method="POST">
                    @csrf
                    {!! $html->table(['id' => 'study_materials_table'], true) !!}

                    <div class="card-footer">
                        <button id="create-btn"  type="submit" class="btn btn-primary">Update</button>
                    </div>
                    <input hidden name="package_id" value="{{$id}}">
                </form>
            </div>

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
                return '<input id="'+data+'"  value="'+data+'"  checked  class="check-row" name="study_materials[]" type="checkbox" >';
            }
            else{
                return '<input id="'+data+'"  value="'+data+'"  class="check-row" name="study_materials[]" type="checkbox" >';
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

            var table = $('#study_materials_table').DataTable();

            // Handle click on checkbox
            $('#study_materials_table tbody').on('click', 'input[type="checkbox"]', function(e){
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
            $('#study_materials_table').on('click', 'tbody td', function(e){
                $(this).parent().find('input[type="checkbox"]').trigger('click');
            });

            // Handle click on "Select all" control
            $('#study_materials_table').on('click', 'thead input[name="select_all"]',function(e){
                if(this.checked){
                    $('#study_materials_table tbody input[type="checkbox"]:not(:checked)').trigger('click');
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

            // Handle form submission event
            $('#package-study-materials-form').on('submit', function(e){
                var form = this;

                // Iterate over all selected checkboxes
                $.each(rows_selected, function(index, rowId){
                    // Create a hidden element
                    $(form).append(
                        $('<input>')
                            .attr('type', 'hidden')
                            .attr('name', 'selected_study_materials[]')
                            .val(rowId),
                    );
                });
                $.each(rows_unchecked, function(index, rowId){
                    // Create a hidden element
                    $(form).append(
                        $('<input>')
                            .attr('type', 'hidden')
                            .attr('name', 'removed_study_materials[]')
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
        })
    </script>
@stop
