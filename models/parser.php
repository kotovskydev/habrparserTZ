<?php


/**
 * 
 */
class Parser
{
    //https://habr.com/ru/flows/develop/
    public static function getPostsUrl(){
        $db = Db::Connection();
        $url = 'health';
        $html =  Parser::parseHtml('https://habr.com/ru/hub/'.$url);
        $rubric = 'popsci';

        $checkRub = $db->prepare("SELECT * FROM rubrics WHERE ruburl = :url");
        $checkRub->bindParam(':url',$rubric);
        $checkRub->execute();
        $rubric = $checkRub->fetch(PDO::FETCH_BOTH);

        $dom = new DOMDocument('4.0', 'UTF-8');
        $dom->loadHtml(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'), LIBXML_NOERROR);
        $finder = new DOMXpath($dom);
        $data = [];




        $data = Parser::getPostInfo($finder);

        foreach ($data as $post){
            try{
                Parser::getHtml($post['url'], $post['anonce'], $rubric['id']);
            }catch (Exception $e){

            }
        }

        return $data;

    }
    public static function getPostInfo($finder){
        $url = $finder->query("//a[contains(@class, 'tm-article-snippet__readmore')]/@href");
        $anonces = $finder->query("//div[contains(@class, 'article-formatted-body article-formatted-body article-formatted-body_version-2')]");

        $data = [];
        for($i = 0; $i < $anonces->length; $i++){
            $data[$i]['anonce'] = $anonces->item($i)->textContent;
            $data[$i]['url'] = $url->item($i)->textContent;
        }

        return $data;

    }


    public static function parseHtml($url){
        $db = Db::Connection();


        $curl = curl_init($url);
        $userAgent = 'Mozilla/5.0 (X11; U; Linux x86; en-US; rv:1.9.0.5) Gecko'
            .'/2008122010 Firefox/3.0.5';
        curl_setopt($curl, CURLOPT_USERAGENT, $userAgent);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $html = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);


        return $html;


    }
	//парсер
	public static function getHtml($parseUrl, $anonce, $rubricId){
        $db = Db::Connection();
        $html =  Parser::parseHtml('habr.ru'.$parseUrl);

        //подключение DOMXPath
        $dom = new DOMDocument('4.0', 'UTF-8');
        $dom->loadHtml(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'), LIBXML_NOERROR);
        $finder = new DOMXpath($dom);
        $rubrics = [];
        $data = [];
        $data['rubric'] = Parser::getRubric($finder);
        $data['rubric']['parent'] = $rubricId;
        $rubrics[] = $data['rubric']['parent'];
        $data['title'] = Parser::getTitle($finder);
        $data['text'] = Parser::getText($finder, $dom);
        $url = rand(100000,999999);
        $author = rand(1,3);
        $rubric = 3;

        //проверка рубрики
        $checkRub = $db->prepare("SELECT * FROM rubrics WHERE ruburl = :url");
        $checkRub->bindParam(':url',$data['rubric']['url']);
        $checkRub->execute();
        $user = $checkRub->fetch(PDO::FETCH_BOTH);


        //если такой рубрики нет, создаем новую и связь
        if (empty($user)){
            $addRub = $db->prepare("INSERT INTO rubrics (`rubric`,`ruburl`) VALUES (:rubric, :ruburl)");
            $addRub->bindParam(':rubric',$data['rubric']['title']);
            $addRub->bindParam(':ruburl',$data['rubric']['url']);
            $addRub->execute();
            $rubric = $db->lastInsertId();

            //Задаем связь дочерним рубрикам
            $rubKey = $db->prepare("INSERT INTO rubrickeys (`rchild`,`rparent`) VALUES (:rchild, :rparent)");
            $rubKey->bindParam(':rchild',$rubric);
            $rubKey->bindParam(':rparent',$data['rubric']['parent']);
            $rubKey->execute();
            $rubrics[] = $rubric;

        }else{
            $rubric = $user['id'];
            $rubrics[] = $rubric;
        }






        //создание поста
        $addNews = $db->prepare("INSERT INTO news (`title`, `text`, `author`, `url`, `rubid`, `parserUrl`, `anonce`) VALUES (:title, :text, :author, :url, :rubid, :parseUrl, :anonce)");
        $addNews->bindParam(':title',$data['title']);
        $addNews->bindParam(':text',$data['text']);
        $addNews->bindParam(':rubid',$rubric);
        $addNews->bindParam(':url',$url);
        $addNews->bindParam(':author',$author);
        $addNews->bindParam(':parseUrl',$parseUrl);
        $addNews->bindParam(':anonce',$anonce);
        $addNews->execute();
        $newsid = $db->lastInsertId();

        $rubData = [];
        for ($i = 0; $i < count($rubrics); $i++){
            $rubData[$i]['newsid'] = $newsid;
            $rubData[$i]['rubid'] = $rubrics[$i];
        }




        //создаем связь поста с рубрикой
        foreach($rubData as $rubs){
            $addNewsKey = $db->prepare("INSERT INTO newskey (`nid`,`nrubid`) VALUES (:nid, :nrubid)");
            $addNewsKey->bindParam(':nid',$rubs['newsid']);
            $addNewsKey->bindParam(':nrubid',$rubs['rubid']);
            $addNewsKey->execute();
        }

        return $data;
	}

    public static function getTitle($finder){
        $title = $finder->query("//h1")->item(0)->textContent;
        return $title;
    }

    public static function getText($finder, $dom){
        $text = $finder->query("//div[contains(@class, 'article-formatted-body')]");

        $result =
            str_replace('</div>', '',
                str_replace(
                    '<div class="article-formatted-body article-formatted-body article-formatted-body_version-1">',
                    '',$dom->saveHTML($text->item(0))));
        $result =
            str_replace('</div>', '',
            str_replace(
            '<div class="article-formatted-body article-formatted-body article-formatted-body_version-2">',
            '',$result));
        $result =
            str_replace(
                '<div xmlns="http://www.w3.org/1999/xhtml">',
                '',$result);
        return $result;
    }
    public static function getRubric($finder){
        $linksNode = $finder->query("//a[contains(@class, 'tm-article-snippet__hubs-item-link')]/@href");
        $titleNode = $finder->query("//a[contains(@class, 'tm-article-snippet__hubs-item-link')]");
        $data = [];
        $i = 0;
        foreach ($linksNode as $link){

            if (!strpos($link->textContent, 'companies')){
                $data['url'] = str_replace('/ru/hub/','', $link->textContent);
                $data['url'] = str_replace('/','',$data['url']);
                $data['title'] = trim(str_replace('*','', $titleNode->item($i)->textContent));
                break;
            }
            $i++;



        }

        return $data;
    }
}