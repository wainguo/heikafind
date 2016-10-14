
<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>黑卡发现</title>
    <meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
    <meta http-equiv="pragma" content="no-cache">
    <meta http-equiv="cache-control" content="no-cache">
    <meta name="format-detection" content="telephone=no">
    <meta http-equiv="expires" content="0">
    <link rel="stylesheet" type="text/css" href="../css/init.css?t={{$rand or ''}}">
    <link rel="stylesheet" type="text/css" href="../css/find.css?t={{$rand or ''}}">
</head>
<body>

@if(!empty($article->id))
    <div class="wrap pd-bot">
        <div class="details-box">
            <div class="find-detail">
                {{--<img src="{{$article->cover}}"/>--}}
                <div class="title-box">
                    <a id="weixinShare" href="{{$article->shareScheme}}" class="icon-share"></a>
                    <h2>{{$article->title}}</h2>
                    <div class="share clearfix"><span>特派员：黑卡专员</span>
                        {{--<div id="weixinShare" class="clearfix" style="display: none;">--}}
                            {{--<a href="{{$article->shareScheme}}" class="color-grey"><img class="ignore-gesture" src="../images/wx_icon.png"/><b>分享至微信</b></a>--}}
                        {{--</div>--}}
                    </div>
                </div>
                <hr>
                <div class="has-distance">
                    <p><span>{{$article->description}}</span></p>
                    {!! $article->content !!}
                    <a href="javascript:;" data-src="{{$scheme}}" data-jumpsrc="{{$jumpScheme}}" class="once-use">立即享用</a>
                </div>
            </div>
        </div>
        {{--<div class="watermark"></div>--}}

    </div>

    <div class="big-pic">
        <img id="bigImage" src="" alt=""/>
    </div>

    <div class="layer-filter"></div>
    <div id="mask" class="brower-guide">
        <img src="../images/guide.png" alt=""/>
    </div>
    <a id="sureOpenApp" href="{{$jumpScheme}}" style="display: none">打开</a>

    <script type="text/javascript" src="../js/jquery-1.10.1.min.js"></script>
    <script type="text/javascript" src="../js/hammer.min.js"></script>
    <script type="text/javascript" src="../js/hammer-image.js?t={{$rand or ''}}"></script>
    <script type="text/javascript" src="../js/openApp.js?t={{$rand or ''}}"></script>
    <script type="text/javascript">
        //防止浮层可见时,点击浮层会同时触发浮层下的图片的点击事件
        $('.big-pic img')[0].addEventListener("touchend", function(e){
            e.preventDefault();
        });
        $('.find-detail').find('img').not('.ignore-gesture').bind("click",function(){
            var imgH = $(this).height();
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
            e.preventDefault();
            e.stopPropagation();
        });

        $('.once-use').click(function(){
            var scheme = $(this).data('src');
            var jumpScheme = $(this).data('jumpsrc');
            if(platform.isWeixin){
                $('.brower-guide').show();
                $('.layer-filter').css("height",getDocHeight());
                $('.layer-filter').show();
                $('.layer-filter').click(function(e){
                    if(e.target.className != 'brower-guide'){
                        $('.layer-filter').hide();
                        $('.brower-guide').hide();
                    }
                });
            }else{
                if(platform.isHeika) {
                    //尝试通过内部链接跳转(在app内部)
                    window.location.href = scheme;
                }
                else{
                    //如果不是在黑卡打卡,通过外部跳转到app(外部浏览器)
                    openApp(jumpScheme);
                }
            }
        });

        $(function(){
            if(platform.isHeika){
                $('#weixinShare').show();
            }
        });
    </script>
@endif