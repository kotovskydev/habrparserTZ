<?php

include_once ROOT.'/models/news.php';
include_once ROOT.'/models/parser.php';


class NewsController
{
    public function actionSearch(){

        $resp = News::searchPost($_POST['req']);

        var_dump($resp);
        return true;
    }
    public function actionAddASync(){
        News::addPostASync($_POST);
        return true;
    }
    public function actionAddSync(){
        $postid = News::addPostSync($_GET);

        $url = "Location: /news/{$postid}";
        header($url);
        return true;
    }
    public function actionAddPage(){
        $mrubs = [];
        $mrubs = News::getMainRubs();

        $rubrics = [];
        $rubrics = News::getAllRubrics();

        require_once(ROOT.'/views/news/add.php');
        return true;
    }

    public function actionMainPage(){

        $mrubs = [];
        $mrubs = News::getMainRubs();

        $posts = [];
        $posts = News::getAllPosts();
        require_once(ROOT.'/views/news/rubric.php');
        return true;
    }

	public function actionRubricPage($rubricUrl){

        $mrubs = [];
        $mrubs = News::getMainRubs();

        $rubric = [];
        $rubric = News::getRubric($rubricUrl);

        $posts = [];
        $posts = News::getRubPosts($rubric['id']);
        require_once(ROOT.'/views/news/rubric.php');
        return true;
    }
	public function actionIndex($postId) {



        $mrubs = [];
        $mrubs = News::getMainRubs();

        $post = [];
        $post = News::getPost($postId);

        $rubs = [];
        $rubs = News::getPostRubs($post['newsid']);

		require_once(ROOT.'/views/news/index.php');

		return true;
	}

    

}