/**
 * Created by wainguo on 16/7/23.
 */

var vm = new Vue({
    el: '#heikaBody',

    data: {
        csrfToken: '',
        article_id: '',
        article: {
            title: '',
            description: '',
            category: '',
            detailId: '',
            content: '',
            cover:''
        }
    },
    ready: function() {
        var self = this;

        CKEDITOR.replace( 'editor1', {
            language: 'zh-cn',
            filebrowserImageUploadUrl: "/upload?_token="+self.csrfToken,
            toolbarGroups: [
                { name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
                { name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
                { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
                { name: 'links', groups: [ 'links' ] },
                { name: 'insert', groups: [ 'insert' ] },
                { name: 'forms', groups: [ 'forms' ] },
                { name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
                { name: 'others', groups: [ 'others' ] },
                { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
                { name: 'styles', groups: [ 'styles' ] },
                { name: 'tools', groups: [ 'tools' ] },
                { name: 'colors', groups: [ 'colors' ] },
                { name: 'about', groups: [ 'about' ] }
            ],
            removeButtons: 'Subscript,Superscript,About,Source,Cut,Undo,Copy,Redo,Paste,PasteText,PasteFromWord,Scayt,Anchor,SpecialChar,RemoveFormat',
            disableObjectResizing: false,
            height: 240,
            extraPlugins: 'autogrow,image2',
            image2_alignClasses : ['image-center'],
            image2_captionedClass : 'image-captioned',
            autoGrow_minHeight: 240
        });

        var uploadCoverOptions = {
            url: "/cover",
            type: "post",
            dataType:'json',
            beforeSend: function() {
            },
            uploadProgress: function(event, position, total, percentComplete) {
                var percentVal = percentComplete + '%';
                console.log(percentVal);
            },
            error:function(response){
                alert("上传失败了");
            },
            success: function(response) {
                if(response.errorCode == 0) {
                    self.article.cover = response.content.imageUrl;
                }
            }
        };

        $("#cover").on('change', function(){
            console.log($("#cover").val());
            if ($("#cover").val() == "") {
                return;
            }
            $('#uploadCoverForm').ajaxSubmit(uploadCoverOptions);
        });
    },

    methods: {

        getCategories: function () {
            var self = this;
            this.$http.get('/api/get/categories').then(
                function(response) {
                    var jtmdsResponse = response.data;
                    if(jtmdsResponse.errorCode == 0) {
                        var categories = jtmdsResponse.content;
                        if(Array.isArray(categories)){
                            this.$set('categories', categories);
                        }
                    }
                    else {
                        console.log(jtmdsResponse.errorMessage);
                    }
                },
                function(response) {
                }
            )
        },

        getArticleProperties: function () {
            if(!this.article_id) {
                return;
            }
            var self = this;
            this.$http.get('/api/get/properties?article_id='+self.article_id).then(
                function(response) {
                    var jtmdsResponse = response.data;
                    if(jtmdsResponse.errorCode == 0) {
                        var properties = jtmdsResponse.content;
                        console.log(properties);
                        if(Array.isArray(properties.tags)){
                            this.$set('article_tags', properties.tags);
                        }
                        if(Array.isArray(properties.categories)){
                            this.$set('article_categories', properties.categories);

                            var checked_categories = [];
                            properties.categories.forEach(function (category) {
                                checked_categories.push(category.id);
                            });
                            this.$set('article_category_ids', checked_categories);
                        }
                    }
                    else {
                        console.log(jtmdsResponse.errorMessage);
                    }
                },
                function(response) {
                }
            )
        }
    }
});