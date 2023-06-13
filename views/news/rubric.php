
<!DOCTYPE html>
<html  lang='ru'>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Didact+Gothic&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Spartan:wght@900&display=swap" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="/public/css/styles.css">
	<title>Новости</title>
</head>
<body>
<?php include ROOT.'/public/layouts/header.php'; ?>

<div class="container d-flex align-items-center" style="margin-bottom: 40px;">
    <i class="bi bi-search"></i>
    <input class="search" id="sinput" type="search" placeholder="Поиск постов" onchange="search()" >
</div>
<hr>
<div class="container">
    <div class="d-flex row">
        <div class="col-md-9" id="content">

            <?
                foreach($posts as $post){

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
            ?>
        </div>
        <div class="col-md-3">
            <div class="autorinfo sticky-top">

            </div>
        </div>
    </div>
    <script>
        function search(){
            if (sinput.value !== ''){
                var req = sinput.value;
            }else{
                var req = 'allposts';
            }
            $.ajax({

                url: "/search/posts",
                type: "POST",
                data: {
                    req: req

                },
                success: function (data) {
                    content.innerHTML = data;
                }
            });

        }
    </script>
</div>
</body>
</html>
