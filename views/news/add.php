
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
            <form action="/create/sync">
            <select name="rubric" id="rubric" required>
                <?
                var_dump($rubrics);
                    foreach ($rubrics as $rubric){
                ?>
                        <option value="<?= $rubric['id']?>"><?= $rubric['rubric']?></option>
                <?
                }
                ?>
            </select>
            <input type="text" id="title" name="title" placeholder="Введите заголовок" style="margin-top: 30px;" required>
            <textarea name="text" id="text" cols="30" rows="10" placeholder="Введите текст статьи" required></textarea>
            <textarea name="anonce" id="anonce" cols="30" rows="10" placeholder="Введите текст анонса" required></textarea>
                <div class="send-block d-flex justify-content-between">
                    <button id="sync" >Отправить синхронно</button>
                    <button id="async" onclick="send(event)">Отправить асинхронно</button>
                </div>
            </form>
        </div>
        <div class="col-md-3">
            <div class="autorinfo sticky-top">

            </div>
        </div>

    </div>
</div>
<script>
    function send(event){
        event.preventDefault();
        if (rubric.value.trim() === '' || title.value.trim() === '' || text.value.trim() === '' || anonce.value.trim() === '') {
            alert('Заполните все поля формы');
        }else{
            $.ajax({

                url: "/create/async",
                type: "POST",
                data: {
                    rubric: rubric.value,
                    title: title.value,
                    text: text.value,
                    anonce: anonce.value

                },
                success: function (data) {
                    console.log(data);
                }
            });
        }


    }
</script>
</body>
</html>
