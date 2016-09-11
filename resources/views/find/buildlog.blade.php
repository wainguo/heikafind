@include('include.header')
<div class="container">
    <a href="/build" class="btn btn-success btn-lg text-center">立即构建</a>

    @if(count($buildlogs))
    <div class="panel panel-default" style="margin-top: 20px;">
        <div class="panel-heading">
            <h3 class="panel-title">构建日志</h3>
        </div>
        <div class="panel-body">
            @foreach($buildlogs as $log)
            <p>
                {!! $log !!}
            </p>
            @endforeach
        </div>
    </div>
    @endif
</div>
@include('include.footer')