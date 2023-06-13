<?php

include_once ROOT.'/models/news.php';
include_once ROOT.'/models/parser.php';


class ParserController
{
    public function actionParseUrls(){
        $parser = [];
        $parser = Parser::getPostsUrl();
        ?><pre><?
        var_dump($parser);
        ?></pre><?
        return true;
    }
    public function actionParsing(){

        $parser = [];
        $parser= Parser::getHtml();

        ?><pre><?
        var_dump($parser);
        ?></pre><?


        return true;
    }

}