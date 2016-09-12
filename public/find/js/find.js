$(function(){

	// var lis = $(".find-box ul li");
	//发现页数据填充
	// var listFill=function(){
	// 	var addstring = '';
    	// for(var i=0; i< 3; i++){
    	// 	console.log('===');
	// 		addstring+='<li><a href="javascript:;"><img src="images/find_b1.jpg"/></a><div class="res-details"><h2>满足您味蕾的八大京城西餐厅</h2><p>带着你的胃和我们一起走进我儿时的的味道吧，我爷爷小的时候，常在这里玩耍！</p></div></li>';
	// 	}
	//     $(".find-box ul").html(addstring);
	// };

	// listFill();

    pageIsLoading = false;

    totalPages = 1;
    currentPage = 0;
    count = 0;

	var findList = function (page) {
	    if(!page) page = 1;

        if(pageIsLoading) {
            console.log('pageIsLoading...');
            return;
        }

        if(page > totalPages) {
            console.log('没有更多数据了');
            return;
        }

        pageIsLoading = true;

        // 请求的数据文件,一页一个文件, 返回的数据中包括分页信息: 总页数
        var dataFileUrl = 'p/data_' + page + '.json';

		$.ajax({
			url: dataFileUrl,
			type: 'get',
			dataType: 'json',
			success: function(response){
			    console.log(response);
                totalPages = response.totalPages;
                currentPage = response.currentPage;
                count = response.count;
                var addstring = '';
                if(Array.isArray(response.articles)){
                    response.articles.forEach(function (article) {
                        addstring+='<li><a href="p/'+article.id+'.html"><img src="p/' + article.cover + '"/></a><div class="res-details"><h2>'+article.title+'</h2><p>'+article.description + '</p></div></li>';
                    })
                }

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

    $(window).scroll(function(){
        var scrollTop = $(this).scrollTop();               //滚动条距离顶部的高度
        var scrollHeight = $(document).height();           //当前页面的总高度
        var windowHeight = $(this).height();               //当前可视的页面高度

        if(scrollTop + windowHeight >= scrollHeight){      //距离顶部+当前高度 >=文档总高度 即代表滑动到底部
            $(".load").show().delay(10000).hide();

            loadNextPage();

            // if(currentpage ==2){                           //如果加载ajax达到2次 停止加载
            //     $(".down_move").hide();                    //提示滚动 图片隐藏
            //     $(".submit_btn").css("display","block");   //提示可以提交该表单按钮出现。
            //     return false;                              //如果条件满足 停止运行该判断
            // }
        }
    });

})