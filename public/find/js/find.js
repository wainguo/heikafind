$(function(){
	pageIsLoading = false;
	totalPages = 1;
	currentPage = 0;
	count = 0;
	var timer;

	var findList = function (page) {
		if(!page) page = 1;

		if(page > totalPages) {
			console.log('没有更多数据了');
			return;
		}

		// 请求的数据文件,一页一个文件, 返回的数据中包括分页信息: 总页数
		var dataFileUrl = 'p/data_' + page + '.json';

		$.ajax({
			url: dataFileUrl,
			type: 'get',
			dataType: 'json',
			success: function(response){
				totalPages = response.totalPages;
				currentPage = response.currentPage;
				count = response.count;
				var addstring = '';
				if(Array.isArray(response.articles)){
					response.articles.forEach(function (article) {
						addstring+='<li><a href="p/'+article.id+'.html"><img src="p/' + article.cover + '"/></a><div class="res-details"><h2>'+article.title+'</h2><p>'+article.description + '</p></div></li>';
					})
				}
				$(".load").hide();
				clearTimeout(timer);
				$( ".find-box ul" ).append( addstring );
				pageIsLoading = false;

			},
			error: function (response) {
				pageIsLoading = false;
			}
		});
	};

	var loadNextPage = function () {
		console.log('currentPage:' + currentPage);
		console.log('totalPages:' + totalPages);
		console.log('count:' + count);
		var nextPage = currentPage + 1;
		findList(nextPage);
	};

	findList(1);

	//绑定上下滑动事件
	/*$("#list").tap( function () {
	 //超过20条后显示加载图标
	 if( lis.length >= 20 ){
	 $(".load").show().delay(10000).hide();
	 }

	 });*/

	/**$(window).bind('touchend',function() {
		var scrollTop = $(this).scrollTop();               //滚动条距离顶部的高度
		var scrollHeight = $(document).height();           //当前页面的总高度
		var windowHeight = $(this).height();//当前可视的页面高度
		if(scrollTop + windowHeight >= scrollHeight){      //距离顶部+当前高度 >=文档总高度 即代表滑动到底部
			//$(".load").show().delay(10000).hide();
			//判断是不是最后一页
			if(currentPage<totalPages){
				console.log(pageIsLoading);
				if(!pageIsLoading){
					pageIsLoading = true;
					timer = setTimeout(function(){
						loadNextPage();
					},1000);
				}

			}
		}
	});**/

	$(window).scroll(function(){
		var scrollTop = $(this).scrollTop();            //滚动条距离顶部的高度
		var scrollHeight = $(document).height();        //当前页面的总高度
		var windowHeight = $(this).height();			//当前可视的页面高度
		if(scrollTop + windowHeight >= scrollHeight){   //距离顶部+当前高度 >=文档总高度 即代表滑动到底部
			//$(".load").show().delay(10000).hide();
			//判断是不是最后一页
			if(currentPage<totalPages){
				console.log(pageIsLoading);
				if(!pageIsLoading){
					pageIsLoading = true;
					$(".load").show();
					timer = setTimeout(function(){
						loadNextPage();
					},1000);
				}
			}
		}
	});
});