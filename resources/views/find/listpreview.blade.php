<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>发现</title>
    <meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
    <meta http-equiv="pragma" content="no-cache">
    <meta http-equiv="cache-control" content="no-cache">
    <meta name="format-detection" content="telephone=no">
    <meta http-equiv="expires" content="0">
    <link rel="stylesheet" type="text/css" href="/css/find.css">

</head>
<body>
<div class="wrap">
    <div class="find-box">
        <ul>
            @foreach($articles as $article)
            <li>
                <a href="{{'p/'.$article->id.'.html'}}">
                <img src="{{$article->cover}}"/>
                <div class="res-details">
                    <h2>{{$article->title}}</h2>
                    <p>{{$article->description}}</p>
                </div>
                </a>
            </li>
            @endforeach
        </ul>
    </div>
</div>

{{--<script type="text/javascript" src="js/jquery-1.10.1.min.js"></script>--}}
{{--<script src="js/find.js"></script>--}}
</body>
</html>