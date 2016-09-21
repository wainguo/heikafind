
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
    <link rel="stylesheet" type="text/css" href="../css/init.css">
    <link rel="stylesheet" type="text/css" href="../css/find.css">
</head>
<body>

@if(!empty($article->id))
    <div class="wrap pd-bot">
        <div class="details-box">
            <div class="find-detail">
                <img src="{{$article->cover}}"/>
                <div class="title-box">
                    <!-- <a href="heika://weixin?title=xxx&desc=xxx&link=xxx&imgUrl=xxx" class="icon-share"></a> -->
                    <h2>{{$article->title}}</h2>
                    <div class="share clearfix"><span>特派员：黑卡专员</span>
                        <div id="weixinShare" class="clearfix" style="display: none;">
                            <a href="{{$article->shareScheme}}" class="color-grey"><img class="ignore-gesture" src="../images/wx_icon.png"/><b>分享至微信</b></a>
                        </div>
                    </div>
                </div>
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
    <div class="pop-confirm">
        <p>在“黑卡”中打开链接吗？</p>
        <div class="btn">
            <a id="cancelOpenApp" href="javascript:;">取消</a>
            <a id="sureOpenApp" href="javascript:;">打开</a>
        </div>
    </div>

    <script type="text/javascript" src="../js/jquery-1.10.1.min.js"></script>
    <script type="text/javascript" src="../js/hammer.min.js"></script>
    <script type="text/javascript" src="../js/hammer-image.js"></script>
    <script type="text/javascript" src="../js/openApp.js"></script>
    <script type="text/javascript">
        $('.find-detail').find('img').not('.ignore-gesture').bind("click",function(){
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

//        function openApp(openUrl, callback) {
//            //检查app是否打开
//            function checkOpen(cb){
//                var _clickTime = +(new Date());
//                function check(elsTime) {
//                    if ( elsTime > 3000 || document.hidden || document.webkitHidden) {
//                        cb(1);
//                    } else {
//                        cb(0);
//                    }
//                }
//                //启动间隔20ms运行的定时器，并检测累计消耗时间是否超过3000ms，超过则结束
//                var _count = 0, intHandle;
//                intHandle = setInterval(function(){
//                    _count++;
//                    var elsTime = +(new Date()) - _clickTime;
//                    if (_count>=100 || elsTime > 3000 ) {
//                        clearInterval(intHandle);
//                        check(elsTime);
//                    }
//                }, 20);
//            }
//
//            //在iframe 中打开APP
//            var ifr = document.createElement('iframe');
//            ifr.src = openUrl;
//            ifr.style.display = 'none';
//            if (callback) {
//                checkOpen(function(opened){
//                    callback && callback(opened);
//                });
//            }
//
//            document.body.appendChild(ifr);
//            setTimeout(function() {
//                document.body.removeChild(ifr);
//            }, 2000);
//        }
//        function logOpen(opened) {
//            console.log("opened:"+opened);
//        }

        $('.once-use').click(function(){
            var scheme = $(this).data('src');
            if(platform.isWeixin){
                $('.brower-guide').show();
                $('.layer-filter').show();
                $('.layer-filter').click(function(e){
                    if(e.target.className != 'brower-guide'){
                        $('.layer-filter').hide();
                        $('.brower-guide').hide();
                    }
                });
            }else{
                window.location.href = scheme;
                setTimeout(function() { openApp(scheme)}, 200);
            }
        });

        $(function(){
            var scheme = $('.once-use').data('src');

            if(! platform.isWeixin){
                $('#weixinShare').show();
                openApp(scheme);
            }
        });
    </script>
@endif