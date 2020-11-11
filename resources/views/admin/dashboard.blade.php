@extends('layouts.admin')

@section('content')

<div class="content-area">

  @include('includes.form-success')



  @if($activation_notify != "")

  <div class="alert alert-danger validation">

    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span

      aria-hidden="true">×</span></button>

      <h3 class="text-center">{!! $activation_notify !!}</h3>

    </div>

    @endif

    

    @if(Session::has('cache'))



    <div class="alert alert-success validation">

      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span

        aria-hidden="true">×</span></button>

        <h3 class="text-center">{{ Session::get("cache") }}</h3>

      </div>





      @endif







      <!-- <div class="row row-cards-one">

        <div class="col-md-12 col-lg-6 col-xl-4">

          <div class="mycard bg1">

            <div class="left">

              <h5 class="title">{{ __('Orders Pending!') }} </h5>

              <span class="number">{{count($pending)}}</span>

              <a href="{{route('admin-order-pending',$storename)}}" class="link">{{ __('View All') }}</a>

            </div>

            <div class="right d-flex align-self-center">

              <div class="icon">

                <i class="icofont-dollar"></i>

              </div>

            </div>

          </div>

        </div>

        <div class="col-md-12 col-lg-6 col-xl-4">

          <div class="mycard bg2">

            <div class="left">

              <h5 class="title">{{ __('Orders Procsessing!') }}</h5>

              <span class="number">{{count($processing)}}</span>

              <a href="{{route('admin-order-processing',$storename)}}" class="link">{{ __('View All') }}</a>

            </div>

            <div class="right d-flex align-self-center">

              <div class="icon">

                <i class="icofont-truck-alt"></i>

              </div>

            </div>

          </div>

        </div>

        <div class="col-md-12 col-lg-6 col-xl-4">

          <div class="mycard bg3">

            <div class="left">

              <h5 class="title">{{ __('Orders Completed!') }}</h5>

              <span class="number">{{count($completed)}}</span>

              <a href="{{route('admin-order-completed',$storename)}}" class="link">{{ __('View All') }}</a>

            </div>

            <div class="right d-flex align-self-center">

              <div class="icon">

                <i class="icofont-check-circled"></i>

              </div>

            </div>

          </div>

        </div>

        <div class="col-md-12 col-lg-6 col-xl-4">

          <div class="mycard bg4">

            <div class="left">

              <h5 class="title">{{ __('Total Products!') }}</h5>

              <span class="number">{{count($products)}}</span>

              <a href="{{route('admin-prod-index',$storename)}}" class="link">{{ __('View All') }}</a>

            </div>

            <div class="right d-flex align-self-center">

              <div class="icon">

                <i class="icofont-cart-alt"></i>

              </div>

            </div>

          </div>

        </div>

        <div class="col-md-12 col-lg-6 col-xl-4">

          <div class="mycard bg5">

            <div class="left">

              <h5 class="title">{{ __('Total Customers!') }}</h5>

              <span class="number">{{count($users)}}</span>

              <a href="{{route('admin-user-index',$storename)}}" class="link">{{ __('View All') }}</a>

            </div>

            <div class="right d-flex align-self-center">

              <div class="icon">

                <i class="icofont-users-alt-5"></i>

              </div>

            </div>

          </div>

        </div>

        <div class="col-md-12 col-lg-6 col-xl-4">

          <div class="mycard bg6">

            <div class="left">

              <h5 class="title">{{ __('Total Posts!') }}</h5>

              <span class="number">{{count($blogs)}}</span>

              <a href="{{ route('admin-blog-index',$storename) }}" class="link">{{ __('View All') }}</a>

            </div>

            <div class="right d-flex align-self-center">

              <div class="icon">

                <i class="icofont-newspaper"></i>

              </div>

            </div>

          </div>

        </div>



      </div> -->

<div class="row row-cards-one">

    <div class="col-md-7">

      <div class="row">

        <div class="col-md-6">

          <div class="card c-info-box-area" style="padding: 3px 30px 38px !important;">

            <div class="c-info-box-content" style="text-align: left !important;">

              <h6 class="title">{{ __('Total Sales') }}</h6>

              

            </div>

              <p id="total_sales">{{ App\Models\Order::where('storename',$storename)->where('status','=','completed')->get()->count() }}</p>

            <hr/>

            <p class="text">{{ __('Last 30 Days') }}

              <a href="{{ route('admin-order-index', $storename) }}" style="float: right;">View Report</a>

            </p>

          </div>

        </div>

        <div class="col-md-6">

          <div class="card c-info-box-area" style="padding: 3px 30px 38px !important;">

            <div class="c-info-box-content" style="text-align: left !important;">

              <h6 class="title">{{ __('Sale Amount') }}</h6>

              

            </div>

              <h6>{{ '$'.round(App\Models\Order::where('storename',$storename)->select(DB::raw('(pay_amount*currency_value) AS total_sales'))->where('status','=','completed')->get()->sum('total_sales'))  }}</h6>

            <hr/>

            <p class="text">{{ __('Last 30 Days') }}

              <!--<a href="{{url('admin/users')}}" style="float: right;">View Report</a>-->

            </p>

          </div>

        </div>

      </div>

      <br/>

      <div class="card">

          <h5 class="card-header">{{ __('Recent Order(s)') }}</h5>

          <div class="card-body">



            <div class="my-table-responsiv">

              <table class="table table-hover dt-responsive" cellspacing="0" width="100%">

                <thead>

                  <tr>



                    <th>{{ __('Order Number') }}</th>

                    <th>{{ __('Order Date') }}</th>

                  </tr>

                  @foreach($rorders as $data)

                  <tr>

                    <td>{{ $data->order_number }}</td>

                    <td>{{ date('Y-m-d',strtotime($data->created_at)) }}</td>

                    <td>

                      <div class="action-list"><a href="{{ route('admin-order-show',[$storename,$data->id]) }}"><i

                        class="fas fa-eye"></i> {{ __('Details') }}</a>

                      </div>

                    </td>

                  </tr>

                  @endforeach

                </thead>

              </table>

            </div>



          </div>

        </div>

    </div>

    <div class="col-md-5">

    

      <div class="card">

       
        <table>
          
          <tr>

            <td>
       
              <select id="duration" name="duration" class="form-control" >
                
                <option value="{{ __('0') }}">{{ __('Today') }}</option>
                
                <option value="{{ __('1') }}">{{ __('Yesterday') }}</option>
                
                <option value="{{ __('7') }}" selected>{{ __('This Week') }}</option>
                
                <option value="{{ __('30') }}">{{ __('This Month') }}</option>
              
              </select>
       
            </td>

            <td>
       
              <input type="text" name="datetimes" id="datetimes" class="form-control" />
       
            </td>
       
          </tr>

          <tr>
              
            <td><h5> {{ __('Total Orders') }} </h5></td>
            <td id="t_orders"> <b> {{ App\Models\Order::where('storename',$storename)->where('status','=','completed')->get()->count() }}  </b> </td>
          
          </tr>

          <tr>
              
            <td><h5> {{ __('Total Sales') }} </h5></td>
              
            <td id="t_sales_1"> <b> {{ '$'.round(App\Models\Order::where('storename',$storename)->select(DB::raw('(pay_amount*currency_value) AS total_sales'))->where('status','=','completed')->get()->sum('total_sales'))  }} </b> </td>
          
          </tr>
        </table>
        <div>

        </div>
        <div  id="header">

    
          

        </div>

          

          <div class="card-body" id="line-chart">



            <canvas id="lineChart"></canvas>



          </div>


         
        </div>

    </div>

</div>

    <div class="row row-cards-one">


      <div class="col-md-12">
        



      </div>

    </div>
   



    <div class="row row-cards-one">



      <div class="col-md-12 col-lg-12 col-xl-12">

        



      </div>



    </div>

    

      <div class="row row-cards-one">

        <div class="col-md-6 col-xl-3">

          <div class="card c-info-box-area">

            <div class="c-info-box box1">

              <p>{{ App\Models\User::where('storename',$storename)->where( 'created_at', '>', Carbon\Carbon::now()->subDays(30))->get()->count()  }}</p>

            </div>

            <div class="c-info-box-content">

              <h6 class="title">{{ __('New Customers') }}</h6>

              <p class="text">{{ __('Last 30 Days') }}</p>

            </div>

          </div>

        </div>

        <div class="col-md-6 col-xl-3">

          <div class="card c-info-box-area">

            <div class="c-info-box box2">

              <p>{{ App\Models\User::where('storename',$storename)->count() }}</p>

            </div>

            <div class="c-info-box-content">

              <h6 class="title">{{ __('Total Customers') }}</h6>

              <p class="text">{{ __('All Time') }}</p>

            </div>

          </div>

        </div>

        <div class="col-md-6 col-xl-3">

          <div class="card c-info-box-area">

            <div class="c-info-box box3">

              <p>{{ App\Models\Order::where('storename',$storename)->where('storename',$storename)->where('status','=','completed')->where( 'created_at', '>', Carbon\Carbon::now()->subDays(30))->get()->count()  }}</p>

            </div>

            <div class="c-info-box-content">

              <h6 class="title">{{ __('Total Sales') }}</h6>

              <p class="text">{{ __('Last 30 days') }}</p>

            </div>

          </div>

        </div>

        <div class="col-md-6 col-xl-3">

          <div class="card c-info-box-area">

            <div class="c-info-box box4">

             <p>{{ App\Models\Order::where('storename',$storename)->where('storename',$storename)->where('status','=','completed')->get()->count() }}</p>

           </div>

           <div class="c-info-box-content">

            <h6 class="title">{{ __('Total Sales') }}</h6>

            <p class="text">{{ __('All Time') }}</p>

          </div>

        </div>

      </div>

    </div>







    <div class="row row-cards-one">



      <div class="col-md-12 col-lg-12 col-xl-12">

        <div class="card">

          <h5 class="card-header">{{ __('Popular Product(s)') }}</h5>

          <div class="card-body">



            <div class="table-responsiv  dashboard-home-table">

              <table id="poproducts" class="table table-hover dt-responsive" cellspacing="0" width="100%">

                <thead>

                  <tr>

                    <th>{{ __('Featured Image') }}</th>

                    <th>{{ __('Name') }}</th>

                    <th>{{ __('Category') }}</th>

                    <th>{{ __('Type') }}</th>

                    <th>{{ __('Price') }}</th>

                    <th></th>



                  </tr>

                </thead>

                <tbody>

                  @foreach($poproducts as $data)

                  <tr>

                    <td><img src="{{filter_var($data->photo, FILTER_VALIDATE_URL) ?$data->photo:asset('assets/images/products/'.$data->photo)}}"></td>

                    <td>{{  mb_strlen(strip_tags($data->name),'utf-8') > 50 ? mb_substr(strip_tags($data->name),0,50,'utf-8').'...' : strip_tags($data->name) }}</td>

                    <td>{{ $data->category->name }}

                      @if(isset($data->subcategory))

                      <br>

                      {{ $data->subcategory->name }}

                      @endif

                      @if(isset($data->childcategory))

                      <br>

                      {{ $data->childcategory->name }}

                      @endif

                    </td>

                    <td>{{ $data->type }}</td>



                    <td> {{ $data->showPrice($storename) }} </td>



                    <td>

                      <div class="action-list"><a href="{{ route('admin-prod-edit',[$storename,$data->id]) }}"><i

                        class="fas fa-eye"></i> {{ __('Details') }}</a>

                      </div>

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



    <div class="row row-cards-one">



      <div class="col-md-12 col-lg-12 col-xl-12">

        <div class="card">

          <h5 class="card-header">{{ __('Recent Product(s)') }}</h5>

          <div class="card-body">



            <div class="table-responsiv dashboard-home-table">

              <table id="pproducts" class="table table-hover dt-responsive" cellspacing="0" width="100%">

                <thead>

                  <tr>

                    <th>{{ __('Featured Image') }}</th>

                    <th>{{ __('Name') }}</th>

                    <th>{{ __('Category') }}</th>

                    <th>{{ __('Type') }}</th>

                    <th>{{ __('Price') }}</th>

                    <th></th>



                  </tr>

                </thead>

                <tbody>

                  @foreach($pproducts as $data)

                  <tr>

                    <td><img src="{{filter_var($data->photo, FILTER_VALIDATE_URL) ?$data->photo:asset('assets/images/products/'.$data->photo)}}"></td>

                    <td>{{  mb_strlen(strip_tags($data->name),'utf-8') > 50 ? mb_substr(strip_tags($data->name),0,50,'utf-8').'...' : strip_tags($data->name) }}</td>

                    <td>{{ $data->category->name }}

                      @if(isset($data->subcategory))

                      <br>

                      {{ $data->subcategory->name }}

                      @endif

                      @if(isset($data->childcategory))

                      <br>

                      {{ $data->childcategory->name }}

                      @endif

                    </td>

                    <td>{{ $data->type }}</td>

                    <td> {{ $data->showPrice($storename) }} </td>

                    <td>

                      <div class="action-list"><a href="{{ route('admin-prod-edit',[$storename,$data->id]) }}"><i

                        class="fas fa-eye"></i> {{ __('Details') }}</a>

                      </div>

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



    









   <!--  <div class="row row-cards-one">



      <div class="col-md-6 col-lg-6 col-xl-6">

        <div class="card">

          <h5 class="card-header">{{ __('Top Referrals') }}</h5>

          <div class="card-body">

            <div class="admin-fix-height-card">

             <div id="chartContainer-topReference"></div>

           </div>



         </div>

       </div>



     </div>



     <div class="col-md-6 col-lg-6 col-xl-6">

      <div class="card">

        <h5 class="card-header">{{ __('Most Used OS') }}</h5>

        <div class="card-body">

          <div class="admin-fix-height-card">

            <div id="chartContainer-os"></div>

          </div>

        </div>

      </div>

    </div>



  </div> -->
<input type="hidden" id="days">

<input type="hidden" id="sales">






</div>



@endsection



@section('scripts')

<script language="JavaScript">

  // displayLineChart();



  function displayLineChart(days, sales) {

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

    // alert(data);
    // console.log(data);
  }







</script>



<script type="text/javascript">

  $('#poproducts').dataTable( {

    "ordering": false,

    'lengthChange': false,

    'searching'   : false,

    'ordering'    : false,

    'info'        : false,

    'autoWidth'   : false,

    'responsive'  : true,

    'paging'  : false

  } );

</script>





<script type="text/javascript">

  $('#pproducts').dataTable( {

    "ordering": false,

    'lengthChange': false,

    'searching'   : false,

    'ordering'    : false,

    'info'        : false,

    'autoWidth'   : false,

    'responsive'  : true,

    'paging'  : false

  } );

</script>



<script type="text/javascript">

  var chart1 = new CanvasJS.Chart("chartContainer-topReference",

  {

    exportEnabled: true,

    animationEnabled: true,



    legend: {

      cursor: "pointer",

      horizontalAlign: "right",

      verticalAlign: "center",

      fontSize: 16,

      padding: {

        top: 20,

        bottom: 2,

        right: 20,

      },

    },

    data: [

    {

      type: "pie",

      showInLegend: true,

      legendText: "",

      toolTipContent: "{name}: <strong>{#percent%} (#percent%)</strong>",

      indexLabel: "#percent%",

      indexLabelFontColor: "white",

      indexLabelPlacement: "inside",

      dataPoints: [

      @foreach($referrals as $browser)

      {y:{{$browser->total_count}}, name: "{{$browser->referral}}"},

      @endforeach

      ]

    }

    ]

  });

  chart1.render();



  var chart = new CanvasJS.Chart("chartContainer-os",

  {

    exportEnabled: true,

    animationEnabled: true,

    legend: {

      cursor: "pointer",

      horizontalAlign: "right",

      verticalAlign: "center",

      fontSize: 16,

      padding: {

        top: 20,

        bottom: 2,

        right: 20,

      },

    },

    data: [

    {

      type: "pie",

      showInLegend: true,

      legendText: "",

      toolTipContent: "{name}: <strong>{#percent%} (#percent%)</strong>",

      indexLabel: "#percent%",

      indexLabelFontColor: "white",

      indexLabelPlacement: "inside",

      dataPoints: [

      @foreach($browsers as $browser)

      {y:{{$browser->total_count}}, name: "{{$browser->referral}}"},

      @endforeach

      ]

    }

    ]

  });

  chart.render();    

 


</script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"></script>


<script type="text/javascript" src="https://www.chartjs.org/samples/latest/utils.js"></script>




<script>

 $(function() {
  $('input[name="datetimes"]').daterangepicker({
    timePicker: true,
    startDate: moment().startOf('hour').subtract(168, 'hour'),
    endDate: moment().startOf('hour'),
    locale: {
      format: 'DD/MM/YYYY'
    }
  });
 });


$('#datetimes').on("change", function(){
$.ajax({
  type: "get",
  url: mainurl+"/admin/data",
  data: { 'date' : $('#datetimes').val(), 'storename' : "{{ $storename }}" },
  cache: false,
  success: function(response)
  {
    $('#t_sales_1').html("<b>" + "$" + Math.round(response.totalSales) + "</b>");

    $('#t_orders').html("<b>" + response.totalOrders + "<b>");
    
      if(response.totalOrders == 0)
    {
      document.getElementById('lineChart').remove();
    $('#line-chart').html('<h4 style="text-align:center; padding-top:30px" id="lineChart"> No Sales </h4>');
      
    }else{

    
    
    document.getElementById('lineChart').remove();

    $('#line-chart').html('<canvas id="lineChart"></canvas>');

    
    
 
  var config = {
			type: 'bar',
			data: {
				labels: [],
				datasets: [{
					label: 'Orders',
					backgroundColor: window.chartColors.blue,
					borderColor: window.chartColors.blue,
					data: [
					
					],
					// fill: true,
				}]
			},
			options: {
				responsive: true,
				title: {
					display: true,
					text: 'Sales'
				},
				tooltips: {
					mode: 'index',
					intersect: false,
				},
				hover: {
					mode: 'nearest',
					intersect: true
				},
				scales: {
					xAxes: [{
						display: true,
						scaleLabel: {
							display: true,
							labelString: 'Days'
						},ticks : { 
              autoSkip:true, 
              maxTicksLimit:10 
            }
					}],
					yAxes: [{
						display: true,
						scaleLabel: {
							display: true,
							labelString: 'Orders'
						},ticks: {
                min: 0,
                stepSize: 1
            }
					}]
				}
			}
		};

	var ctx = document.getElementById('lineChart').getContext('2d');
      window.myLine = new Chart(ctx, config);
      
    var days = response.days;
    var  daysArray= days.split(',');
    var sales = response.sales;

    var salesArray = sales.split(',');

    daysArray.forEach(function (item) { 
      window.myLine.data.labels.push(item);
  });

  var i;
  
  for (i = 0; i < salesArray.length; i++) 
  {
    window.myLine.data.datasets[0].data[i+1] = salesArray[i];
  }

  window.myLine.update();
}
}
  
});
});


$('#duration').on("change", function(){

$.ajax({

  type: "get",
  
  url: mainurl+"/admin/data",
  
  data: { 'time' : $('#duration').val(), 'storename' : "{{ $storename }}" },
  
  cache: false,
  
  success: function(response){
  
 
    $('#t_sales_1').html("<b>" + "$" + Math.round(response.totalSales) + "</b>");

    $('#t_orders').html("<b>" + response.totalOrders + "<b>");
    
      if(response.totalOrders == 0)
    {
      document.getElementById('lineChart').remove();
    $('#line-chart').html('<h4 style="text-align:center; padding-top:30px" id="lineChart"> No Sales </h4>');
      
    }else{

    document.getElementById('lineChart').remove();

    $('#line-chart').html('<canvas id="lineChart"></canvas>');

  
  var config = {
			type: 'bar',
			data: {
				labels: [],
				datasets: [{
					label: 'Orders',
					backgroundColor: window.chartColors.blue,
					borderColor: window.chartColors.blue,
					data: [
					
					],
					// fill: true,
				}]
			},
			options: {
				responsive: true,
				title: {
					display: true,
					text: 'Sales'
				},
				tooltips: {
					mode: 'index',
					intersect: false,
				},
				hover: {
					mode: 'nearest',
					intersect: true
				},
				scales: {
					xAxes: [{
						display: true,
						scaleLabel: {
							display: true,
							labelString: 'Month'
						},ticks : { 
              autoSkip:true, 
              maxTicksLimit:10 
            }
					}],
					yAxes: [{
						display: true,
						scaleLabel: {
							display: true,
							labelString: 'Orders'
						},ticks: {
                min: 0,
                stepSize: 1
            }
					}]
				}
			}
		};

	var ctx = document.getElementById('lineChart').getContext('2d');
      window.myLine = new Chart(ctx, config);
      
    var days = response.days;
    var  daysArray= days.split(',');
    var sales = response.sales;

    var salesArray = sales.split(',');

    daysArray.forEach(function (item) { 
      window.myLine.data.labels.push(item);
  });

  var i;
  
  for (i = 0; i < salesArray.length; i++) 
  {
    window.myLine.data.datasets[0].data[i+1] = salesArray[i];
  }

  window.myLine.update();
}

}

});

});



</script>



@endsection

