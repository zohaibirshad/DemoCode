@extends('layouts.admin')

@section('content')
<div class="content-area">
  @include('includes.form-success')


    @if(Session::has('cache'))

    <div class="alert alert-success validation">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
        aria-hidden="true">×</span></button>
        <h3 class="text-center">{{ Session::get("cache") }}</h3>
      </div>


      @endif

<?php 

  $order_days = 7;
  if(isset($_POST['days'])){ $order_days = $_POST['days']; } 
?>

<div class="row row-cards-one">
    <div class="col-md-12">
      <div class="row">
      <div class="card col-md-12">
          <h5 class="card-header">{{ __('Order Analytics') }}</h5>
          <div class="card-body">
            <br/>
            <br/> 
            <form action="{{route('admin-get-analytics',$storename)}}" method="post">
              {{csrf_field()}}
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <select class="form-control" name="days" required>
                      <option value="7" {{($order_days == 7) ? 'selected' : ''}}>Last 7 days</option>
                      <option value="30" {{($order_days == 30) ? 'selected' : ''}}>Last 30 days</option>
                      <option value="60" {{($order_days == 60) ? 'selected' : ''}}>Last 60 days</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-6">
                  <button type="submit" class="btn btn-success">Submit</button>
                </div>
                
              </div>
            </form>
            <br/>
            <h3>
              Last {{$order_days}} Days Orders
            </h3>
            <br/>
            <canvas id="lineChart"></canvas>
          </div>
        </div>



    </div>


</div>




</div>

@endsection

@section('scripts')


<script type="text/javascript">
displayLineChart();

  function displayLineChart() {
    var data = {
      labels: [
      {!!$days!!}
      ],
      datasets: [{
        label: "Prime and Fibonacci",
        fillColor: "#3dbcff",
        strokeColor: "#0099ff",
        pointColor: "rgba(220,220,220,1)",
        pointStrokeColor: "#fff",
        pointHighlightFill: "#fff",
        pointHighlightStroke: "rgba(220,220,220,1)",
        data: [
        {!!$sales!!}
        ]
      }]
    };
    var ctx = document.getElementById("lineChart").getContext("2d");
    var options = {
      responsive: true
    };
    var lineChart = new Chart(ctx).Line(data, options);
  }
</script>

@endsection