@include('include.header')
<div id="articleList">
    <table class="table table-bordered">
        <tr>
            <th width="20%">文章标题</th>
            <th width="5%">品类</th>
            <th width="10%">知识库ID</th>
            <th width="35%">摘要</th>
            <th width="10%">操作</th>
            <th width="10%">回链同步(48)</th>
            <th width="10%">回链同步(线上)</th>
        </tr>
        @foreach($articles as $article)
            <tr>
                <td>
                    <a href="{{url('/preview/'.$article->id)}}" target="_blank">
                        {{$article->title}}
                    </a>
                </td>
                <td>
                    <span>{{$article->category}}</span>
                </td>
                <td>
                    <span>{{$article->detailId}}</span>
                </td>
                <td>
                    <span>{{$article->description}}</span>
                </td>
                <td>
                    {{--<a href="{{url('/preview/'.$article->id)}}" class="btn btn-sm btn-default" target="_blank">预览</a>--}}
                    <a href="{{url('/edit/'.$article->id)}}" class="btn btn-sm btn-success" target="_blank">编辑</a>
                    <a href="{{url('/delete/'.$article->id)}}" onclick="return confirm('删除后无法恢复,确定要删除吗')" class="btn btn-sm btn-danger">删除</a>
                </td>
                <td>
                    @if(env('FIND_FOR_ENV', 'test')=='product')
                    <button onclick='addLinksTest("{{$article->id}}", "{{$article->category}}", "{{$article->detailId}}" )'>添加</button>
                    {{--<button onclick='addLinksTest({{$article}})'>添加</button>--}}
                    <button onclick='deleteLinksTest({{$article}})'>删除</button>
                    {{--<button onclick='deleteLinksTest({{$article}})'>删除</button>--}}
                    @endif
                </td>
                <td>
                    @if(env('FIND_FOR_ENV', 'test')=='product')
                    <button onclick='addLinks({{$article}})'>添加</button>
                    <button onclick='deleteLinks({{$article}})'>删除</button>
                    @endif
                </td>
            </tr>
        @endforeach
    </table>
{{--    @include('include.pagination')--}}
    {{ $articles->links() }}
</div>
<script src="{{ asset('js/jquery-2.2.0.min.js') }}"></script>
<script>
//    function addLinksTest(article) {
    function addLinksTest(id, serviceType, detailId) {
        console.log(arguments);

        var data = {
            status: "NORMAL",
            articleUrl: 'http://172.16.2.113/banner/app/v2.8/p/'+id+'.html',
            serviceType: serviceType,
            itemId: detailId
        };
        $.ajax({
            method: "POST",
            url: "/internalTest/article/syncArticle",
            contentType: 'application/json; charset=utf-8', // 很重要
            traditional: true,
            data: JSON.stringify(data)
        })
        .done(function( response ) {
            console.log(response);
            if(response.status == 0){
                alert('同步成功');
            }
            else{
                alert('同步失败');
            }
        })
        .fail(function (response) {
            alert('同步失败');
        });
    }
    function deleteLinksTest(id, serviceType, detailId) {

        var data = {
            status: "DELETED",
            articleUrl: 'http://172.16.2.113/banner/app/v2.8/p/'+id+'.html',
            serviceType: serviceType,
            itemId: detailId
        };
        $.ajax({
            method: "POST",
            url: "/internalTest/article/syncArticle",
            contentType: 'application/json; charset=utf-8', // 很重要
            traditional: true,
            data: JSON.stringify(data)
        })
        .done(function( response ) {
            console.log(response);
            if(response.status == 0){
                alert('同步成功');
            }
            else{
                alert('同步失败');
            }
        })
        .fail(function (response) {
            alert('同步失败');
        });
    }
    function addLinks(id, serviceType, detailId) {

        var data = {
            status: "NORMAL",
            articleUrl: 'http://172.16.2.113/banner/app/v2.8/p/'+id+'.html',
            serviceType: serviceType,
            itemId: detailId
        };
        $.ajax({
            method: "POST",
            url: "/internal/article/syncArticle",
            contentType: 'application/json; charset=utf-8',
            traditional: true,
            data: JSON.stringify(data)
        })
        .done(function( response ) {
            console.log(response);
            if(response.status == 0){
                alert('同步成功');
            }
            else{
                alert('同步失败');
            }
        })
        .fail(function (response) {
            alert('同步失败');
        });
    }
    function deleteLinks(id, serviceType, detailId) {

        var data = {
            status: "DELETED",
            articleUrl: 'http://172.16.2.113/banner/app/v2.8/p/'+id+'.html',
            serviceType: serviceType,
            itemId: detailId
        };
        $.ajax({
            method: "POST",
            url: "/internal/article/syncArticle",
            contentType: 'application/json; charset=utf-8',
            traditional: true,
            data: JSON.stringify(data)
        })
        .done(function( response ) {
            console.log(response);
            if(response.status == 0){
                alert('同步成功');
            }
            else{
                alert('同步失败');
            }
        })
        .fail(function (response) {
            alert('同步失败');
        })
    }
</script>
@include('include.footer')