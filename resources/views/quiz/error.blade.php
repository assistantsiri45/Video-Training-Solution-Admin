
<div class="container-fluid status-block">
 <div class="card-body">
     @if ($errors->any())
         <div class="alert alert-danger">
             <ul>
                 @foreach ($errors->all() as $error)
                     <li>{{ $error }}</li>
                 @endforeach
             </ul>
         </div>
     @endif
     @if(session()->has('message'))
         <div class="alert alert-success">
             <ul>
                 <li>{{ session('message') }}</li>
             </ul>
         </div>
     @endif
 </div>
 </div>