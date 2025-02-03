@if(env('SHOW_MAINTENANCE_BANNER',false))
@include('errors.maintenance')
@else
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <title>JKSHAH ONLINE</title>
</head>
<body style="height:100vh;background: linear-gradient(-45deg, #F6BA2F, #F09128, #F58457)">
<div class="h-100 d-flex justify-content-center align-items-center">
    <div class="row justify-content-center">
        <div class="col-6">
            <div class="text-center mb-3">
                <img src="{{ url('assets/images/logo.png') }}" width="137" height="62" alt="JK Shah Classes Online" title="JK Shah Classes Online">
            </div>
            <h4 class="text-center" style="color: #3B3485">The site is under maintenance. We will be back soon. We regret the inconvenience.</h4>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>
</body>
</html>
@endif