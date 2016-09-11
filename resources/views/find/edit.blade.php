@include('include.header')

<div id="editArticle" class="container">
    @include('include/messages')

        <div class="row">
        <div class="col-md-8">

            <form action="{{ url('/save') }}" method="post" class="form-horizontal">
                {{ csrf_field() }}
                <input type="hidden" name="article_id" v-model="article_id" value="{{ $article->id }}">
                <input type="hidden" v-model="csrfToken" value="{{csrf_token()}}">
                <input type="hidden" name="cover" v-model="article.cover" value="{{$article->cover}}">

                <div class="form-group">
                    <label for="title" class="col-sm-2 control-label">文章标题</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="title" name="title"
                               placeholder="文章标题" value="{{ $article->title or ''}}"
                               v-model="article.title">
                    </div>
                </div>
                <div class="form-group">
                    <label for="description" class="col-sm-2 control-label">摘要</label>
                    <div class="col-sm-10">
                        <textarea class="form-control" name="description" id="description" rows="2"
                                  v-model="article.description">{{ $article->description or ''}}</textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label for="description" class="col-sm-2 control-label">品类</label>
                    <div class="col-sm-4">
                        <select class="form-control" name="category" value="{{$article->category or ''}}"
                                v-model="article.category">
                            @foreach($categories as $key=>$value)
                                <option value="{{$key}}" {{($article->category == $key)?'selected':''}}>{{$value}}</option>
                            @endforeach
                        </select>
                    </div>
                    <label for="detailid" class="col-sm-2 control-label">ID(知识库)</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" id="detailId" name="detailId"
                               placeholder="ID" value="{{ $article->detailId or ''}}"
                               v-model="article.detailId">
                    </div>
                </div>
                <div class="form-group">
                    <label for="content" class="col-sm-2 control-label">文章内容</label>
                    <div class="col-sm-10">
                        <textarea class="form-control" name="content" id="editor1" rows="3" v-model="article.content">
                            {{ $article->content or ''}}
                        </textarea>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-default">提交</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-md-4">
            <form id="uploadCoverForm" class="ui form" method="POST">
                {!! csrf_field() !!}
                <div class="form-group">
                    <label for="cover">封面图片</label>
                    <input type="file" name="file" id="cover">
                    {{--<p class="help-block">选择上传封面图片</p>--}}
                    <p></p>
                    <img v-bind:src="article.cover" width="100%">
                </div>
            </form>
        </div>
    </div>
</div>

<script src="{{ asset('js/jquery-2.2.0.min.js') }}"></script>
<script src="{{ asset('js/jquery.form.js') }}"></script>
<script src="{{ asset('js/vue.min.js') }}"></script>
<script src="{{ asset('js/vue-resource.min.js') }}"></script>
<script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
<script src="{{ asset('js/edit-article.js') }}"></script>

@include('include.footer')