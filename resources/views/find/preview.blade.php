
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
            </div>
        </div>
        <div class="watermark"></div>
        {{--<a href="{{$scheme}}" class="once-use">立即享用</a>--}}
        <a href="javascript:;" data-src="{{$scheme}}" class="once-use">立即享用</a>
    </div>

    <div class="big-pic">
        <img id="bigImage" src="" alt=""/>
    </div>
    <div class="layer-filter"></div>
    <div id="mask" class="brower-guide">
        <img src="../images/guide.png" alt=""/>
    </div>
    <script type="text/javascript" src="../js/jquery-1.10.1.min.js"></script>
    <script type="text/javascript" src="../js/hammer.min.js"></script>
    <script type="text/javascript" src="../js/hammer-image.js"></script>
    <script type="text/javascript">
        var u = navigator.userAgent, app = navigator.appVersion;
        var isiOS = !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/);
        var yingyongbao = 'http://a.app.qq.com/o/simple.jsp?pkgname=com.renrendai.heika';
        var appStore = 'https://itunes.apple.com/us/app/hei-ka/id1014380550?mt=8';

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

        //打开app
        function openApp(appDetailUrl){
            var loadDateTime = new Date();
            window.setTimeout(function() {
                var timeOutDateTime = new Date();
                if (timeOutDateTime - loadDateTime > 5000) {
                    if(isiOS){
                        window.location = appStore;
                    }else{
                        window.location = yingyongbao;
                    }
                }
            },25);
            window.location = appDetailUrl;
        }

        $('.once-use').click(function(){
            var scheme = $(this).data('src');
            if(isWeiXin()){
                $('.brower-guide').show();
                $('.layer-filter').show();
                $('.layer-filter').click(function(e){
                    if(e.target.className != 'brower-guide'){
                        $('.layer-filter').hide();
                        $('.brower-guide').hide();
                    }
                });
            }else{
                openApp(scheme);
            }
        });

        $(function(){
            if(isWeiXin()){
                $('a.icon-share').hide();
            }
        });
    </script>
@endif