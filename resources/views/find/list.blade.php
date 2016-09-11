@include('include.header')
<div id="articleList">
    <table class="table table-bordered">
        <tr>
            <th width="25%">文章标题</th>
            <th width="5%">品类</th>
            <th width="7%">知识库ID</th>
            <th width="50%">摘要</th>
            <th width="13%">操作</th>
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
                    <a href="{{url('/preview/'.$article->id)}}" class="btn btn-sm btn-default" target="_blank">预览</a>
                    <a href="{{url('/edit/'.$article->id)}}" class="btn btn-sm btn-success" target="_blank">编辑</a>
                    <a href="{{url('/delete/'.$article->id)}}" class="btn btn-sm btn-danger">删除</a>
                </td>
            </tr>
        @endforeach
    </table>
{{--    @include('include.pagination')--}}
    {{ $articles->links() }}
</div>
@include('include.footer')