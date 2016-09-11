
<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>发现详情页</title>
    <meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
    <meta http-equiv="pragma" content="no-cache">
    <meta http-equiv="cache-control" content="no-cache">
    <meta name="format-detection" content="telephone=no">
    <meta http-equiv="expires" content="0">
    <link rel="stylesheet" type="text/css" href="../css/find.css">
</head>
<body>

@if(!empty($article->id))
    <div class="wrap">
        <div class="details-box">
            <ul>
                <li>
                    <img src="{{$article->cover}}"/>
                    <h2><span>{{$article->title}}</span></h2>
                    <p><span>{{$article->description}}</span></p>
                </li>
                <li>
                    {!! $article->content !!}
                </li>
            </ul>
        </div>
        <a href="{{$scheme}}" class="once-use">立即享用</a>
    </div>
@endif