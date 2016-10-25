
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
                <div class="title-box">
                    <a id="weixinShare" href="{{$article->shareScheme}}" class="icon-share"></a>
                    <h2>{{$article->title}}</h2>
                    <div class="share clearfix"><span>特派员：黑卡专员</span>
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

    </div>

    <div class="layer-filter"></div>
    <div id="mask" class="brower-guide">
        <img src="../images/guide.png" alt=""/>
    </div>
    <a id="sureOpenApp" href="{{$jumpScheme}}" style="display: none">打开</a>

    <script type="text/javascript" src="../js/jquery-1.10.1.min.js"></script>
    <script type="text/javascript" src="../js/openApp.js?t={{$rand or ''}}"></script>
    <script type="text/javascript">
        //百度统计
        var _hmt = _hmt || [];
        (function() {
            var hm = document.createElement("script");
            hm.src = "//hm.baidu.com/hm.js?f362cd569e9aeee07ad0232adabfedec";
            var s = document.getElementsByTagName("script")[0];
            s.parentNode.insertBefore(hm, s);
        })();

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