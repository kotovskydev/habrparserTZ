
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


<div class="container">
    <div class="d-flex row">
        <div class="col-md-9" style="">
            <div class="d-flex hashtags">
                <?
                    foreach ($rubs as $rub){
                ?>
                         <a href="/<?= $rub['ruburl']; ?>">#<?= $rub['rubric']; ?></a>
                <?
                    }
                ?>

            </div>
            <h1><?= $post['title'];?></h1>
            <div class="post">
                <?= $post['text'];?>
            </div>


        </div>
        <div class="col-md-3">
            <div class="autorinfo sticky-top">
                <div class="avatar-img" style="background: no-repeat url(<?= $post['img'];?>); width: 100%; height: 250px; background-position: center;
    background-size: contain;">
                </div>
                <p class="sec-title"><?= $post['username'];?></p>
                <p><?= $post['created']?></p>
            </div>
        </div>
    </div>
</div>
</body>
</html>
