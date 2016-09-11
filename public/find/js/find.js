$(function(){

	// var lis = $(".find-box ul li");
	//发现页数据填充
	var listFill=function(){
		var addstring = '';
    	for(var i=0; i< 3; i++){
    		console.log('===');
			addstring+='<li><a href="javascript:;"><img src="images/find_b1.jpg"/></a><div class="res-details"><h2>满足您味蕾的八大京城西餐厅</h2><p>带着你的胃和我们一起走进我儿时的的味道吧，我爷爷小的时候，常在这里玩耍！</p></div></li>';
		}	    
	    $(".find-box ul").html(addstring);	
	};

	listFill();

	var findList = function () {
		$.ajax({
			url: 'data_1.json',
			type: 'get',
			dataType: 'json',
			success: function(res){
				var detailLists = res.data.dataInfo;
				if(noTrim(orderLists.gaodeLat) == '' || noTrim(orderLists.gaodeLng) == ''){
					$('.address').find('.angle-icon').hide();
				}else{
					$('.address').find('.angle-icon').show();
					$('.address').attr('href','/banner/app/v2.8/createMap.html?key='+orderKey)
				}
				//初始化数据
				$.ajax({
					url: 'api/productshare/detail/'+orderKey+'serviceType='+serviceType,
					type: 'get',
					dataType: 'json',
					success: function(res){
						if(res.status==0){
							var detailLists = res.data.dataInfo;
							//公用的数据：图片，标题，描述，温馨提示
							var detailPicHtml,detailTitleHtml,detailDecorationHTml,detailTipHtml;
							//私有数据
							// 蛋糕：标签，英文名字，甜度，价位，商品详情，电话；
							// 餐厅：类型人均，特点，图片列表；
							// 演出:价位，时间；
							// 酒吧：价格，时间
							if(serviceType == 'restaurant'){

							}
							if(serviceType == 'tearoom'){

							}
							if(serviceType == 'bar'){

							}
							if(serviceType == 'cake'){

							}

						}else{
							alert(res.message);
						}
					}
				});
			}
		});
	}
    
    //绑定上下滑动事件  
    /*$("#list").tap( function () {
    	//超过20条后显示加载图标
		if( lis.length >= 20 ){
			$(".load").show().delay(10000).hide();
		}

    });*/

})