@extends('layouts.adminlte-default')

@section('title')
    Expense
@stop

@section('header')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                <h1 class="m-0 text-dark">My Expenses</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active"><a href="#">Dashboard</a></li>
                </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <canvas id="pieChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
        </div>
    </div>
@stop

@section('js')
<script src="{{ asset('js/Chart.min.js') }}"></script>
<script type="text/javascript">
    $(function(){

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });

        var pieChartCanvas = $('#pieChart').get(0).getContext('2d')
        var pieOptions     = {
            maintainAspectRatio : false,
            responsive : true,
        }

        loadDashboardData();

        function loadDashboardData()
        {
            $.ajax({
                url: "{{ route('home.api') }}",
                method: 'POST',
                beforeSend: function(){

                },
                success: function(data){
                    var labels = [];
                    var datas = [];
                    var datasets = [];
                    var backgroundColor = [];
                    $.each(data, function(key, value){
                        labels.push(value.category.name);
                        datas.push(value.total);
                        backgroundColor.push('#'+(Math.random()*0xFFFFFF<<0).toString(16));
                    });
                    datasets.push({ data: datas, backgroundColor: backgroundColor });
                    var result = {labels : labels, datasets: datasets};
                    var pieChart = new Chart(pieChartCanvas, {
                        type: 'pie',
                        data: result,
                        options: pieOptions
                    });
                },
                error: function(){

                },
                complete: function(){
                },
            });


        }

    });
</script>
@endsection
