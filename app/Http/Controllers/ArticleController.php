<?php
/**
 * Created by PhpStorm.
 * User: wainguo
 * Date: 16/7/3
 * Time: 下午8:31
 */

namespace App\Http\Controllers;


use App\Article;
use App\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;


class ArticleController extends Controller
{
    protected $allowedExtensions = ["png", "jpg", "jpeg", "gif"];
    protected $imageUploadPath = '';
    protected $imageUploadUrl = '';

    public function __construct()
    {
        $this->imageUploadPath = storage_path('app/public');
        $this->imageUploadUrl = url(Storage::url(''));
    }

//  预览
    public function previewArticleList()
    {
        $articles = Article::orderBy('created_at', 'desc')->paginate(2);
        return view('find.listpreview', [
            'articles' => $articles,
        ]);
    }

    public function previewArticle($id)
    {
        $article = Article::find($id);
        $scheme = "heika://refresh";
        switch ($article->category){
            case 'restaurant':
                $scheme = "heika://resDetail?id=".$article->detailId;
                break;
            case 'cake':
                $scheme = "heika://cakeDetail?id=".$article->detailId;
                break;
            case 'teaRoom':
                $scheme = "heika://highteaDetail?id=".$article->detailId;
                break;
            case 'bar':
                $scheme = "heika://barDetail?id=".$article->detailId;
                break;
            case 'ticket':
                $scheme = "heika://showDetail?id=".$article->detailId;
                break;
            default:
                $scheme = "heika://refresh";
                break;
        }
        return view('find.preview', [
            'article' => $article,
            'scheme' => $scheme,
        ]);
    }
    public function prebuild()
    {
        $buildlogs = array();
        return view('find.buildlog', [
            'buildlogs' => $buildlogs
        ]);
    }

    public function cleanDir($dir)
    {
        if(empty($dir)){
            return;
        }
        $dh = opendir($dir);
        while ($file = readdir($dh)) {
            if($file!="." && $file!="..") {
                $fullpath = $dir."/".$file;
                if(!is_dir($fullpath)) {
                    unlink($fullpath);
                } else {
                    $this->cleanDir($fullpath);
                }
            }
        }
        closedir($dh);
    }

    public function build(Request $request)
    {

        // $env in test, product
//        $env = $request->input('env');
        $env = env('FIND_FOR_ENV', 'product');

        if(empty($env)){
            $env = 'test';
        }

        $baseUrl="http://172.16.2.113/banner/app/v2.8/";
        if($env == "product"){
            $baseUrl="http://api.m.heika.com/banner/app/v2.8/";
        }

        $buildlogs = array();

        //clear old data
        $findpDir = public_path('find/p');
        $this->cleanDir($findpDir);

        array_push($buildlogs, 'clean old build data in path: '.$findpDir);
        array_push($buildlogs, 'build: ');

        //build new data

        //每页多少条?
        $countPerPage = 3;

        $findpath = public_path('find');
        $articles = Article::orderBy('created_at', 'desc')->get();

        $i = 0;
        $page = 0;
        $pagedArticles = array();
        foreach ($articles as $article){
            $scheme = "heika://refresh";
            $jumpScheme = 'heika://main';
            switch ($article->category){
                case 'restaurant':
                    $scheme = "heika://resDetail?id=".$article->detailId;
                    $jumpScheme = 'heika://main?jumpType=resDetail&id='.$article->detailId;
                    break;
                case 'cake':
                    $scheme = "heika://cakeDetail?id=".$article->detailId;
                    $jumpScheme = 'heika://main?jumpType=cakeDetail&id='.$article->detailId;
                    break;
                case 'teaRoom':
                    $scheme = "heika://highteaDetail?id=".$article->detailId;
                    $jumpScheme = 'heika://main?jumpType=highteaDetail&id='.$article->detailId;
                    break;
                case 'bar':
                    $scheme = "heika://barDetail?id=".$article->detailId;
                    $jumpScheme = 'heika://main?jumpType=barDetail&id='.$article->detailId;
                    break;
                case 'ticket':
                    $scheme = "heika://showDetail?id=".$article->detailId;
                    $jumpScheme = 'heika://main?jumpType=showDetail&id='.$article->detailId;
                    break;
                default:
                    $scheme = "heika://refresh";
                    $jumpScheme = 'heika://main';
                    break;
            }

            //process image
            $this->processImage(basename($article->cover));
            $article->cover = 'images/'.basename($article->cover);

            array_push($buildlogs, '压缩封面图片: '.basename($article->cover));

            $result = $this->processImageFromContent($article->content, 'images');
            $article->content = $result['content'];
            array_push($buildlogs, '压缩内容图片: '.json_encode($result['image_urls']));

            //文章地址绝对URL,图片地址绝对URL
            $articleUrl = $baseUrl.'p/'.$article->id.".html";
            $coverUrl = $baseUrl."p/images/".basename($article->cover);

            $query = array(
                'title' => $article->title,
                'shareDescription' => $article->description,
                'url' => $articleUrl,
                'imgUrl' => $coverUrl
            );
//            $params = http_build_query($query);
            $params = http_build_query($query, null, '&', PHP_QUERY_RFC3986);
            $shareScheme = "heika://share?". $params;

//            $shareScheme = "heika://share?title=".$article->title.
//                "&shareDescription=".$article->description."&url=".$articleUrl."&imgUrl=".$coverUrl;

//            $article->shareScheme =urlencode($shareScheme);
//            $article->shareScheme = htmlentities($shareScheme, ENT_QUOTES, "UTF-8");
            $article->shareScheme =$shareScheme;

            $view = view('find.preview', [
                'article' => $article,
                'scheme' => $scheme,
                'jumpScheme' => $jumpScheme,
            ]);
            $file = $findpath."/p/".$article->id.".html";
            file_put_contents($file, $view);
            array_push($buildlogs, "create detail file: ".$file."<span class='text-success'> success.</span>");

            array_push($pagedArticles, $article);
            $i += 1;
            if($i % $countPerPage == 0 || $i >= count($articles)){
                $page += 1;
                $data = array(
                    'totalPages' => ceil(count($articles)/$countPerPage),
                    'currentPage' => $page,
                    'perPage' => $countPerPage,
                    'count' => count($articles),
                    'articles' => $pagedArticles
                );
                $dataFile = $findpath."/p/data_".$page.".json";
                file_put_contents($dataFile, json_encode($data));

                //clear
                $pagedArticles = array();
                array_push($buildlogs, "create list data file: ".$dataFile."<span class='text-success'> success.</span>");
            }
        }

        return view('find.buildlog', [
            'buildlogs' => $buildlogs
        ]);
    }

    public function getList()
    {
        $articles = Article::orderBy('created_at', 'desc')->paginate(10);
        return view('find.list', [
            'articles' => $articles
        ]);
    }

    //为方便,直接走的get方法
    public function getDelete($id = null)
    {
        if(empty($id)) {
            return redirect()->back();
        }

        $article = Article::find($id);
        if(!empty($article)){
            $article->delete();
            return redirect('/list')->with('flash_success', '删除成功');
        }
        return redirect('/list');
    }
    public function getEdit($id = null)
    {
        if(!empty($id)) {
            $article = Article::find($id);
        }
        if(empty($article)) {
            $article = new Article();
        }

        $categories = array(
            "restaurant"=>"餐厅订座",
            "bar"=>"酒吧",
            "teaRoom"=>"茶点",
            "cake"=>"蛋糕",
            "ticket"=>"票务"
        );
//      异常处理,如果没有怎么?
        return view('find.edit', [
            'article' => $article,
            'categories' => $categories,
        ]);
    }
    //  编辑文章的保存
    public function postSave(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'cover' => 'required',
            'description' => 'required',
            'content' => 'required',
            'category' => 'required',
            'detailId' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $articleId = $request->article_id;
        if(!empty($articleId)){
            $article = Article::find($articleId);
        } else {
            $article = new Article();
        }

        if(empty($article)) {
            return view('errors.errorpage', ['error_message' => '没有找到对应的文章']);
        }

        $article->title = $request->input('title');
        $article->cover = $request->input('cover');

        $article->description = $request->input('description');
        $article->content = $request->input('content');
        $article->category = $request->input('category');
        $article->detailId = $request->input('detailId');
        $result = $this->getAndSaveImageFromContent($article->content, $this->imageUploadPath, $this->imageUploadUrl);
        $article->content = $result['content'];
        if(!$article->cover && count($result['image_urls'])>0){
            $article->cover = $result['image_urls'][0];
        }
        if($article->save()) {
            $request->session()->flash('flash_success', '文章保存成功!');
            return redirect('/edit/'.$article->id);
        }
        else {
            return redirect()->back()->withInput();
        }
    }

    public function postImage(Request $request) {
        if ($request->hasFile('file') && $request->file('file')->isValid()){
            $file = $request->file('file');

            //允许的图片后缀
            $allowedExtensions = ["png", "jpg", "jpeg", "gif"];
            if ($file->getClientOriginalExtension() && !in_array($file->getClientOriginalExtension(), $allowedExtensions)) {
                $errorMessage = '图片后缀只支持png,jpg,jpeg,gif,请检查！';
                return $this->jtmdsError($errorMessage);
            }
            else {
                $fileName = uniqid().'.'.$file->guessExtension();
                $fullImagePath = $this->imageUploadPath.DIRECTORY_SEPARATOR.$fileName;
                $path = $file->move($this->imageUploadPath, $fileName);
                $url = Storage::url($fileName);
                return $this->jtmdsSuccess(["imageUrl" => $url]);
            }
        }
        else {
            $errorMessage = '上传文件非法';
            return $this->jtmdsError($errorMessage);
        }
    }

    /**
     * deal with ckeditor image upload.
     *
     * @return \Illuminate\Http\Response
     */
    public function postUploadImage(Request $request)
    {
        $callback = $request->input('CKEditorFuncNum');

        if ($request->hasFile('upload') && $request->file('upload')->isValid()){
            $file = $request->file('upload');
//            $allowed_extensions = ["png", "jpg", "jpeg", "gif"]; //允许的图片后缀
            if ($file->getClientOriginalExtension() && !in_array($file->getClientOriginalExtension(), $this->allowedExtensions)) {
                $errorMessage = '图片后缀只支持png,jpg,gif,请检查！';
                $url = '';
            }
            else {
                $fileName = uniqid().'.'.$file->guessExtension();
//                $storagePath = storage_path('app/public');

                $fullImagePath = $this->imageUploadPath.DIRECTORY_SEPARATOR.$fileName;

//                Image::make($file)->resize(720, null, function ($constraint) {
//                    $constraint->aspectRatio();
//                })->save($fullImagePath, 80);
//            ->resize(320,240)->save($imageThumbnail)

                $path = $file->move($this->imageUploadPath, $fileName);

//            response()->json(["error" => "0", "msg" => "上传图片成功"]);
//            Storage::put(
//                'u'.$user->id.'/'.$imageName,
//                file_get_contents($request->file('avatar')->getRealPath())
//            );

                $url = url(Storage::url($fileName));
                $errorMessage = '';
            }
        }
        else {
            $url = '';
            $errorMessage = '上传文件非法';
        }

        $output = "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($callback, '$url', '$errorMessage');</script>";
        return response($output);
    }

    //图片处理方案写在这里(图片压缩,放缩等处理)
    //接收文件名, 图片原始存放在/public/storage下, 处理后的图片目标放在/public/find/p/images下
    public function processImage($fileName)
    {
        if(empty($fileName)){
            return;
        }

        $file = $this->imageUploadPath.DIRECTORY_SEPARATOR.$fileName;
        $toFile = public_path('find')."/p/images/".$fileName;

        Image::make($file)->resize(640, null, function ($constraint) {
            $constraint->aspectRatio();
        })->save($toFile, 80);
    }

    // build 时,将文章中的图片拷贝出来到p/images下面
    public function processImageFromContent($content, $baseUrl)
    {
//        $destPath, $baseUrl
        $imageList = $this->getImageByReg($content);
        $localBaseUrl = url('');
        foreach ($imageList as $key => $val) {
//            print_r($val['src']);
//            http://localhost:7878/storage/57d413308d9a8.jpeg

            $fileName = basename($val['src']);

//            if ( strpos($val['src'], $localBaseUrl) !== false ) {
//                $arr = explode('/', $val['src']);
//                $name = array_pop($arr);
//                $imageList[$key]['src'] = $name;
//                continue;
//            }
//            $arr = explode('.', $val['src']);
//            $ext = array_pop($arr);
//            if (!$ext || !in_array($ext, $this->allowedExtensions)) {
//                $ext = 'jpg';
//            }
//            $name = uniqid().'.'.$ext;
//            $imageList[$key]['src'] = $name;

            //处理图片, 并将图片放到生成目录的位置
            $this->processImage($fileName);

            $imageList[$key]['src'] = $fileName;
        }

        $newImgInfo = $this->replaceImageUrl($imageList, $baseUrl);
        $newImgTags = $newImgInfo['newImgTags'];
        $newImgUrls = $newImgInfo['newImgUrls'];

        $patterns = array('/<img\s.*?>/');
        $callback = function( $matches ) use ( &$newImgTags ) {
            $matches[0] = array_shift($newImgTags);
            return $matches[0];
        };
        $res = array();
        $res['content'] = preg_replace_callback($patterns, $callback, $content);
        $res['image_urls'] = $newImgUrls;
        return $res;
    }

    public function getAndSaveImageFromContent($content, $destPath, $baseUrl)
    {
        $imageList = $this->getImageByReg($content);
        $localBaseUrl = url('');
        foreach ($imageList as $key => $val) {
            if ( strpos($val['src'], $localBaseUrl) !== false ) {
                $arr = explode('/', $val['src']);
                $name = array_pop($arr);
                $imageList[$key]['src'] = $name;
                continue;
            }
            $arr = explode('.', $val['src']);
            $ext = array_pop($arr);
            if (!$ext || !in_array($ext, $this->allowedExtensions)) {
                $ext = 'jpg';
            }
            $name = uniqid().'.'.$ext;
            $imageList[$key]['src'] = $name;

            $file = file_get_contents($val['src']);
            file_put_contents($destPath .'/'. $name, $file);
        }

        $newImgInfo = $this->replaceImageUrl($imageList, $baseUrl);
        $newImgTags = $newImgInfo['newImgTags'];
        $newImgUrls = $newImgInfo['newImgUrls'];

        $patterns = array('/<img\s.*?>/');
        $callback = function( $matches ) use ( &$newImgTags ) {
            $matches[0] = array_shift($newImgTags);
            return $matches[0];
        };

        $res = array();
        $res['content'] = preg_replace_callback($patterns, $callback, $content);
        $res['image_urls'] = $newImgUrls;

        return $res;
    }

    private function getImageByReg($str)
    {
        $imageList = array();
        $c1 = preg_match_all('/<img\s.*?>/', $str, $m1);
        for($i = 0; $i < $c1; $i++) {
            $c2 = preg_match_all('/(\w+)\s*=\s*(?:(?:(["\'])(.*?)(?=\2))|([^\/\s]*))/', $m1[0][$i], $m2);
            for($j = 0; $j < $c2; $j++) {
                $imageList[$i][$m2[1][$j]] = !empty($m2[4][$j]) ? $m2[4][$j] : $m2[3][$j];
            }
        }

        return $imageList;
    }
    // baseUrl with postfix '/'
    private function replaceImageUrl($imageList, $baseUrl)
    {
        $newImgTags = array();
        $newImgUrls = array();

        foreach ($imageList as $key => $val) {
            $imgTag = '<img ';
            foreach ($val as $attr => $v) {
                if ($attr === 'src') {
                    $imgTag .= $attr . '="' . $baseUrl .'/'. $v . '" ';
                    $newImgUrls[] = $baseUrl .'/' . $v;
                } else {
                    $imgTag .= $attr . '="' . $v . '" ';
                }
            }
            $imgTag .= ' >';

            $newImgTags[$key] = $imgTag;
        }

        return array('newImgTags' => $newImgTags, 'newImgUrls' => $newImgUrls);
    }

}