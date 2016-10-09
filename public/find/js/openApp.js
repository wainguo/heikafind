/**
 * Created by liuzhonghui on 2016/9/19.
 */
//获取url中的参数值
function getUrlParam(name){
    //构造一个含有目标参数的正则表达式对象
    var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
    //匹配目标参数
    var r = window.location.search.substr(1).match(reg);
    //返回参数值
    if (r!=null) return unescape(r[2]);
    return null;
}
var ua = navigator.userAgent;
var platform = {
    // android终端或者uc浏览器
    android: ua.indexOf('Android') > -1 || ua.indexOf('Linux') > -1,
    androidVersion : 0,
    // 是否为iPhone或者QQHD浏览器
    iPhone: ua.indexOf('iPhone') > -1 ,
    // 是否iPad
    iPad: ua.indexOf('iPad') > -1,
    //ios version
    iosVersion : 0,
    //windows phone
    wPhone: ua.indexOf('Windows Phone') > -1,
    //是否在 微信 客户端内
    isWeixin : ua.indexOf('MicroMessenger') > -1,
    //是否在 黑卡 app内
    isHeika : ua.indexOf('Heika') > -1
};

var out = /iPhone OS (\d+)/.exec(ua);
if(  out ){
    platform.iosVersion = parseInt(out[1], 10);
}else if( out = /Android (\d+)/.exec(ua) ){
    platform.androidVersion = parseInt( out[1], 10 );
}

var yingyongbaoURL = 'http://a.app.qq.com/o/simple.jsp?pkgname=com.renrendai.heika';
var appStoreURL = 'https://itunes.apple.com/us/app/hei-ka/id1014380550?mt=8';
var androidDownloadURL = 'http://m.heika.com/download/android/newest.apk';
var APP_SCHEMA = "heika://";

var isappInstalled = getUrlParam('isappinstalled');

function getDocHeight() {
    var D = document;
    return Math.max(
        Math.max(D.body.scrollHeight, D.documentElement.scrollHeight),
        Math.max(D.body.offsetHeight, D.documentElement.offsetHeight),
        Math.max(D.body.clientHeight, D.documentElement.clientHeight)
    );
}

function openApp(appDetailUrl) {
    $('#sureOpenApp').attr('href',appDetailUrl);
    var installUrl;
    if(platform.iPhone || platform.iPad){
        installUrl = appStoreURL;
    }
    if(platform.android){
        installUrl = yingyongbaoURL;
    }
    if(isappInstalled == 0){
        window.location.href = installUrl;
    }
    if(isappInstalled == 1){
        $('#sureOpenApp')[0].click();
    }
}

