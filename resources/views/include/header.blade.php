<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>黑卡前端-发现频道</title>
    <link href="{{asset('vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
</head>

<body id="heikaBody">
<nav class="navbar navbar-default">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="/">发现频道内容管理</a>
        </div>
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li><a href="{{url('/edit')}}">录入文章</a></li>
                <li><a href="{{url('/list')}}">文章列表</a></li>
                <li><a href="{{url('/prebuild')}}">构建</a></li>
                <li><a href="/find/find.html" target="_blank">预览</a></li>
            </ul>
        </div>
    </div>
</nav>