@extends('adminlte::page')

@section('title', 'Usage')

@section('content_header')
    <div class="row">
        <div class="col">
            <h1 class="m-0 text-dark">Usage</h1>
        </div>
    </div>
@stop
@section('css')
<style>
     #datatable_filter{
            display: none;
        }
</style>
@stop
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
            <div class="card-header">
             <div class="row">
                         <div class="col-md-2">
                             <input id="name" type="text" class="form-control" placeholder="Student name">
                         </div>
                         <div class="col-md-2">
                             <select id="activity" class="form-control">
                                 <option></option>
                                 <option value="{{\App\Models\JMoney::SIGN_UP}}">Sign Up</option>
                                 <option value="{{\App\Models\JMoney::FIRST_PURCHASE}}">First Purchase</option>
                                 <option value="{{\App\Models\JMoney::PROMOTIONAL_ACTIVITY}}">Promotional Activity</option>
                                 <option value="{{\App\Models\JMoney::REFERRAL_ACTIVITY}}">Referral Activity </option>
                                 <option value="{{\App\Models\JMoney::REFUND}}">Refund</option>
                                 <option value="{{\App\Models\JMoney::CASHBACK}}">Cashback</option>
                                 <option value="{{\App\Models\JMoney::PURCHASE}}">Purchase</option>
                             </select>
                         </div>
                         <div class="col-md-2">
                             <select id="transaction_type" class="form-control">
                                 <option></option>
                                 <option value="1">Credit</option>
                                 <option value="2">Debit</option>
                             </select>
                         </div>
                         <div class="col-md-2">
                             <div class="input-group">
                                 <input id="transaction_date" type="text" class="form-control float-right" placeholder="Transaction Date">
                             </div>
                         </div>
                         <div class="col-md-2">
                             <div class="input-group">
                                 <input id="date" type="text" class="form-control float-right" placeholder="Expire at">
                             </div>
                         </div>
                         <div class="col-md-2">
                             <button id="button-filter" class="btn btn-primary">Filter</button>
                             <button id="btn-clear" class="btn btn-primary ml-2">Clear</button>
                         </div>
                     </div>
             </div>
                {!! $html->table(['id' => 'datatable'], true) !!}
            </div>
        </div>
    </div>
@stop

@section('js')
    {!! $html->scripts() !!}
    
      <script>
          $(function() {
              $('#activity').select2({
                  placeholder: 'Activity'
              });
  
              $('#transaction_type').select2({
                  placeholder: 'Transaction Type'
              });
  
              $('#date').datepicker({
                  format: 'dd-mm-yyyy',
                  autoclose: true
              });
              
              $('#transaction_date').daterangepicker({
                locale: {
                    format: 'DD/MM/YYYY',
                    separator: ' - '
                },
                    autoUpdateInput: false
                }, function (startDate, endDate) {
                    $('#transaction_date').val(startDate.format('DD/MM/YYYY') + ' - ' + endDate.format('DD/MM/YYYY'));
                });

              let table = $('#datatable').DataTable();
  
              table.on('preXhr.dt', function( e, settings, data) {
                  data.filter = {
                      date: $('#date').val(),
                      name: $('#name').val(),
                      activity: $('#activity').val(),
                      transaction_date : $('#transaction_date').val(), 
                      transaction_type: $('#transaction_type').val()
                  }
              });
  
              $('#button-filter').click(function() {
                  table.draw();
              });
  
              $('#btn-clear').click(function() {
                  $('#date').val('').change();
                  $('#amount').val('').change();
                  $('#transaction_type').val('').change();
                  $('#transaction_date').val('').change();
                  $('#name').val('');
                  table.draw();
              });
  
          });
  </script>
@stop
