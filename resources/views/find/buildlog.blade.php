@include('include.header')
<div class="container">
    <form class="form" action="/build">
        <div class="radio">
            <label>
                <input type="radio" name="env" id="envTest" value="test"> for 113测试环境
            </label>
        </div>
        <div class="radio">
            <label>
                <input type="radio" name="env" id="envProduct" value="product"> for 线上环境
            </label>
        </div>

        <button type="submit" class="btn btn-success btn-lg text-center">立即构建</button>
    </form>


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