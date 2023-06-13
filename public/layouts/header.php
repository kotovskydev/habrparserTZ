<header>
    <div class="container d-flex justify-content-between align-items-center">
        <span>TZNews</span>
        <a href="/add/new-post"><button>Написать статью</button></a>
    </div>

</header>
<nav>
    <div class="container">
        <ul class="top-nav d-flex flex-wrap">
            <a href="/"><li>Все посты</li></a>
            <?
                foreach ($mrubs as $mrub){
            ?>
                    <a href="/<?= $mrub['ruburl'];?>"><li><?= $mrub['rubric'];?></li></a>
            <?
                }
            ?>
        </ul>
        <hr>
    </div>
</nav>
