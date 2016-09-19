
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
    <div class="wrap pd-bot">
        <div class="details-box">
            <div class="find-detail">
                <img src="{{$article->cover}}"/>
                <h2>
                    <a href="{{$article->shareScheme}}" class="icon-share"></a>
                    <span>{{$article->title}}</span>
                </h2>
                <p><span>{{$article->description}}</span></p>
                <div>
                    {!! $article->content !!}
                </div>
            {{--<ul>--}}
                {{--<li>--}}
                    {{--<img src="{{$article->cover}}"/>--}}
                    {{--<h2><span>{{$article->title}}</span></h2>--}}
                    {{--<p><span>{{$article->description}}</span></p>--}}
                {{--</li>--}}
                {{--<li>--}}
                    {{--{!! $article->content !!}--}}
                {{--</li>--}}
            {{--</ul>--}}
            </div>
        </div>
        <div class="watermark"></div>
        <a href="{{$scheme}}" class="once-use">立即享用</a>
    </div>

    <div class="big-pic">
        <img id="bigImage" src="" alt=""/>
    </div>
    <script type="text/javascript" src="../js/jquery-1.10.1.min.js"></script>
    <script type="text/javascript" src="../js/hammer.min.js"></script>
    <script type="text/javascript" src="../js/hammer-time.min.js"></script>
    <script type="text/javascript" src="../js/hammer-image.js"></script>
    <script type="text/javascript">
        $('.find-detail').find('img').bind("click",function(){
            var imgH = $(this).height();
            $('.big-pic').css('height',$(window).height());
            $('.wrap').css('height',$(window).height());
            $('.wrap').css('overflow','hidden');
            $('.wrap').css('padding-bottom',0);
            $('.wrap').addClass('filter-blur');
            $('.big-pic img').attr('src',$(this).attr('src'));
            $('.big-pic img').css('margin-top',-imgH/2);
            $('.big-pic').show();

            hammerImage(document.getElementById("bigImage"));
        });
        $('.big-pic').bind("click",function(e){
            console.log(e.target.tagName);
            if(e.target.tagName != 'IMG'){
                $(this).hide();
                $('.wrap').removeClass('filter-blur');
                $('.wrap').css('height','auto');
                $('.wrap').css('overflow','auto');
                $('.wrap').css('padding-bottom','80px');
            }
        });
        function isWeiXin(){
            var ua = navigator.userAgent.toLowerCase();
            if(ua.match(/MicroMessenger/i)=="micromessenger") {
                return true;
            } else {
                return false;
            }
        }

        $(function(){
            if(isWeiXin()){
                $('a.icon-share').hide();
            }
        });
    </script>
@endif