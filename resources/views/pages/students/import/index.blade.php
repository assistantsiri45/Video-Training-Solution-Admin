@extends('adminlte::page')

@section('title', 'Import Students')

@section('content_header')
    <div class="row">
        <div class="col">
            <h1 class="m-0 text-dark">Import Students</h1>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card card-primary">
                <form role="form" id="create" method="POST" action="{{ route('students.import.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>File</label>
                                <div class="custom-file">
                                    <input class="custom-file-input" id="file" name="file" type="file">
                                    <label class="custom-file-label" id="file-label" for="file">Choose file</label>
                                </div>
                                <a href="{{ url('downloads/sample.csv') }}">Sample File</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary float-right">Import</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="modal-response" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body table-responsive p-0">
                                    <table class="table table-hover">
                                        <tbody>
                                        <tr>
                                            <td class="border-right w-25"><label>Status</label></td>
                                            <td class="w-100 response-status"></td>
                                        </tr>
                                        <tr>
                                            <td class="border-right w-25"><label>Imported Users</label></td>
                                            <td class="w-100 response-imported-count"></td>
                                        </tr>
                                        <tr>
                                            <td class="border-right w-25"><label>Existing Users</label></td>
                                            <td class="w-100 response-existing-count"></td>
                                        </tr>
                                        <tr>
                                            <td class="border-right w-25"><label>Message</label></td>
                                            <td class="w-100 response-message"></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <button type="button" class="btn btn-default text-center" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script>
        $(function () {
            $('#file').on('change', function() {
                let fileName = $(this).val().split('\\').pop();
                $(this).siblings('#file-label').addClass('selected').html(fileName);
            });

            @if (session()->has('response'))
            let response = JSON.parse('{!! json_encode(session()->get('response')) !!}');

            if (response.status) {
                $('.response-status').text('Success');
                $('.response-message').text('Data successfully imported');
            } else {
                $('.response-status').text('Failed');
                $('.response-message').text('There is an error while importing data');
            }

            $('.response-imported-count').text(response.success_count);
            $('.response-existing-count').text(response.failed_count);
            $('#modal-response').modal('toggle');

            @endif
        });
    </script>
@stop
