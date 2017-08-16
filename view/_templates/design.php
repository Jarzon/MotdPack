<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="author" content="Master J">

    <title><?=$title?></title>

    <link href="https://www.masterj.net/motd/style/base.css" rel="stylesheet">
    <link href="/css/main.css" rel="stylesheet">
</head>

<body>
    <div id="content">
        <?php if(isset($connectedRatio)):?>
        <div class="headerText connectedRatio">
            <?=$connectedRatio?>
        </div>
        <?php endif;?>

        <div class="headerText playtimeCounter">
            <?php if(isset($playtime)) :?>
                <a href="/scoreboard/"><?=$playtime?></a>
            <?php endif ?>
        </div>

        <div id="logo"><a href="/"><img src="/img/logo.png"></a></div>

        <div class="box">
            <?= $this->section('default') ?>
        </div>
        <p class="headerText madeBy">by Master J </p>
    </div>
</body>
</html>