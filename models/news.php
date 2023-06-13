<?php


/**
 * 
 */
class News
{
    public static function searchPost($req){
        $db = Db::Connection();
        $req = '%'.$req.'%';
        $searchPost = $db->prepare("SELECT * FROM news AS n INNER JOIN users AS u ON u.uid = n.author INNER JOIN rubrics AS r ON n.rubid = r.id WHERE n.text LIKE :req OR n.title LIKE :req OR r.rubric LIKE :req OR u.username LIKE :req");
        $searchPost->bindParam(':req',$req);
        $searchPost->execute();
        $posts = $searchPost->fetchAll(PDO::FETCH_BOTH);


        $getAllRubrics = $db->prepare("SELECT * FROM rubrics");
        $getAllRubrics->execute();
        $rubrics = $getAllRubrics->fetchAll(PDO::FETCH_BOTH);

        $getPostKeys = $db->prepare("SELECT * FROM newskey");
        $getPostKeys->execute();
        $postKeys = $getPostKeys->fetchAll(PDO::FETCH_BOTH);

        foreach($posts as $post){

            $idKeys = array_keys(array_column($postKeys, 'nid'), $post['newsid']);
            for ($i = 0; $i < count($idKeys); $i++){
                $rubNameKey = array_search($postKeys[$idKeys[$i]]['nrubid'], array_column($rubrics, 'id'));
                $post['rubrics'][$i]['rubtitle'] = $rubrics[$rubNameKey]['rubric'];
                //var_dump($rubrics[$rubNameKey]['rubric']);
                $post['rubrics'][$i]['id'] = $rubrics[$rubNameKey]['id'];
                $post['rubrics'][$i]['url'] = $rubrics[$rubNameKey]['ruburl'];
            }
            $data[] = $post;
        }
        if ($req == '%allposts%'){
            $data = News::getAllPosts();
        }

        foreach ($data as $post){
            ?>
            <div class="post-body">
                <div class="d-flex justify-content-between">
                    <div class="d-flex author-block">
                        <img src="<?= $post['img']; ?>" alt="<?= $post['username'];?>" style="max-height: 30px; max-width: 30px;">
                        <div class="author-name"> <?= $post['username'];?></div>
                    </div>
                    <div><?= $post['created'];?></div>
                </div>
                <div class="d-flex row">
                    <div class="col">
                        <?
                        foreach ($post['rubrics'] as $postrub){
                            ?>
                            <a href="/<?= $postrub['url'];?>">#<?= $postrub['rubtitle'];?></a>
                            <?
                        }
                        ?>
                    </div>
                </div>

                <h2><?= $post['title']?></h2>
                <p><?= $post['anonce']?></p>
                <a href="/news/<?= $post['url'];?>"><button class="secbut">Читать далее</button></a>
            </div>
            <hr>
            <?
        }

        exit;
    }
    public static function addPostASync($data){
        $db = Db::Connection();
        var_dump($data);
        $author = 1;
        $url = rand(100000,999999);

        $addPost = $db->prepare("INSERT INTO news (`title`, `text`,`author`, `url`, `rubid`) VALUES (:title, :text, :author, :url, :rubid)");
        $addPost->bindParam(':title',$data['title']);
        $addPost->bindParam(':text',$data['text']);
        $addPost->bindParam(':author',$author);
        $addPost->bindParam(':url',$url);
        $addPost->bindParam(':rubid',$data['rubric']);
        $addPost->execute();
        $postid = $db->lastInsertId();

        $addkey = $db->prepare("INSERT INTO newskey (`nid`, `nrubid`) VALUES (:nid, :nrubid)");
        $addkey->bindParam(':nid',$postid);
        $addkey->bindParam(':nrubid',$data['rubric']);
        $addkey->execute();

        if (isset($addPost)){
            $response = ['success' => true];
        }else{
            $response = ['success' => false];
        }

        return $response;
    }
    public static function addPostSync($data){
        $db = Db::Connection();
        var_dump($data);
        $author = 1;
        $url = rand(100000,999999);

        $addPost = $db->prepare("INSERT INTO news (`title`, `text`,`author`, `url`, `rubid`) VALUES (:title, :text, :author, :url, :rubid)");
        $addPost->bindParam(':title',$data['title']);
        $addPost->bindParam(':text',$data['text']);
        $addPost->bindParam(':author',$author);
        $addPost->bindParam(':url',$url);
        $addPost->bindParam(':rubid',$data['rubric']);
        $addPost->execute();
        $postid = $db->lastInsertId();

        $addkey = $db->prepare("INSERT INTO newskey (`nid`, `nrubid`) VALUES (:nid, :nrubid)");
        $addkey->bindParam(':nid',$postid);
        $addkey->bindParam(':nrubid',$data['rubric']);
        $addkey->execute();
        return $url;

    }

    public static function getAllRubrics(){
        $db = Db::Connection();
        $getAllRubrics = $db->prepare("SELECT * FROM rubrics");
        $getAllRubrics->execute();
        $rubrics = $getAllRubrics->fetchAll(PDO::FETCH_BOTH);

        return $rubrics;
    }
    public static function getAllPosts(){
        $db = Db::Connection();
        $getAllPosts = $db->prepare("SELECT * FROM news AS n INNER JOIN users AS u ON u.uid = n.author  ORDER BY n.created DESC");
        $getAllPosts->execute();
        $posts = $getAllPosts->fetchAll(PDO::FETCH_BOTH);

        $getAllRubrics = $db->prepare("SELECT * FROM rubrics");
        $getAllRubrics->execute();
        $rubrics = $getAllRubrics->fetchAll(PDO::FETCH_BOTH);

        $getPostKeys = $db->prepare("SELECT * FROM newskey");
        $getPostKeys->execute();
        $postKeys = $getPostKeys->fetchAll(PDO::FETCH_BOTH);

        $data = [];
        foreach($posts as $post){

            $idKeys = array_keys(array_column($postKeys, 'nid'), $post['newsid']);
            for ($i = 0; $i < count($idKeys); $i++){
                $rubNameKey = array_search($postKeys[$idKeys[$i]]['nrubid'], array_column($rubrics, 'id'));
                $post['rubrics'][$i]['rubtitle'] = $rubrics[$rubNameKey]['rubric'];
                //var_dump($rubrics[$rubNameKey]['rubric']);
                $post['rubrics'][$i]['id'] = $rubrics[$rubNameKey]['id'];
                $post['rubrics'][$i]['url'] = $rubrics[$rubNameKey]['ruburl'];
            }
            $data[] = $post;
        }

        return $data;

    }
    public static function getRubPosts($rubricId){
        $db = Db::Connection();

        $getAllRubrics = $db->prepare("SELECT * FROM rubrics");
        $getAllRubrics->execute();
        $rubrics = $getAllRubrics->fetchAll(PDO::FETCH_BOTH);

        $getPostKeys = $db->prepare("SELECT * FROM newskey");
        $getPostKeys->execute();
        $postKeys = $getPostKeys->fetchAll(PDO::FETCH_BOTH);

        $getRubPosts = $db->prepare("WITH RECURSIVE child_categories AS (
          SELECT id FROM rubrics WHERE id = :rubricId
          UNION ALL
          SELECT r.id FROM rubrics AS r
          INNER JOIN rubrickeys AS rk ON rk.rchild = r.id
          INNER JOIN child_categories AS cc ON cc.id = rk.rparent
        )
        SELECT * 
        FROM news AS n
        INNER JOIN rubrics AS r ON n.rubid = r.id
        INNER JOIN users AS u ON u.uid = n.author
        WHERE r.id IN (SELECT id FROM child_categories) ORDER BY n.created DESC");
        $getRubPosts->bindParam(':rubricId',$rubricId);
        $getRubPosts->execute();
        $posts = $getRubPosts->fetchAll(PDO::FETCH_BOTH);

        $data = [];
        foreach($posts as $post){
            ;
          $idKeys = array_keys(array_column($postKeys, 'nid'), $post['newsid']);
          for ($i = 0; $i < count($idKeys); $i++){
              $rubNameKey = array_search($postKeys[$idKeys[$i]]['nrubid'], array_column($rubrics, 'id'));
              $post['rubrics'][$i]['rubtitle'] = $rubrics[$rubNameKey]['rubric'];
              //var_dump($rubrics[$rubNameKey]['rubric']);
              $post['rubrics'][$i]['id'] = $rubrics[$rubNameKey]['id'];
              $post['rubrics'][$i]['url'] = $rubrics[$rubNameKey]['ruburl'];
          }

            $data[] = $post;
        }

        return $data;
    }

    public static function getRubric($rubricUrl){
        $db = Db::Connection();
        $getRubric = $db->prepare("SELECT * FROM rubrics AS r WHERE r.ruburl = :rubric");
        $getRubric->bindParam(':rubric',$rubricUrl);
        $getRubric->execute();
        $rubric = $getRubric->fetch(PDO::FETCH_BOTH);

        return $rubric;
    }
    public static function getMainRubs(){
        $db = Db::Connection();
        $getMainRubs = $db->prepare("SELECT * FROM rubrics AS r WHERE r.main = 1");
        $getMainRubs->execute();
        $mrubs = $getMainRubs->fetchAll(PDO::FETCH_BOTH);

        return $mrubs;
    }
    public static function getPostRubs($postid){
        $db = Db::Connection();
        $getPostRubs = $db->prepare("SELECT * FROM newskey AS nk INNER JOIN rubrics AS r ON r.id = nk.nrubid WHERE (nk.nid = :postid)");
        $getPostRubs->bindParam(':postid',$postid);
        $getPostRubs->execute();
        $rubs = $getPostRubs->fetchAll(PDO::FETCH_BOTH);

        return $rubs;
    }
	public static function getPost($postid){
        $db = Db::Connection();
        $getPost = $db->prepare("
        SELECT * FROM news AS n 
        INNER JOIN newskey AS nk ON nk.nid = n.newsid 
        INNER JOIN rubrics AS r ON r.id = n.rubid  
        INNER JOIN users AS u ON u.uid = n.author WHERE (n.url = :postid)");
        $getPost->bindParam(':postid',$postid);
        $getPost->execute();
        $post = $getPost->fetch(PDO::FETCH_BOTH);

        return $post;
    }


}