<h1>Server Rules</h1>

<ul class="rules">
    <li>Tolerate each other, don't be a dick.</li>
    <li>No spam</li>
    <li>Religious, homophobic, and racial slurs will not be tolerated</li>
    <li>Cheating will result in a permanent ban.</li>
    <li>No advertisting other servers/groups.</li>
</ul><br>

<p class="links">
    <a href="./scoreboard/">Scoreboard</a>
    - <a href="./music/">Music</a>
</p><br>

<h2>Admins</h2>
    <div class="admins">
    <?php if($admins) : foreach($admins as $player) : ?>
        <span class="tooltip <?=$player['status']?>">
            <img src="<?=$player['avatar']?>">
            <span>
                <a href="<?=$player['link']?>"><img src="<?=$player['avatar']?>"></a>
                <a href="<?=$player['link']?>"><strong><?=$player['name']?></strong></a>
            <br>
                <?=ucfirst($player['status'])?>
            </span>
        </span>
        <?php endforeach; endif; ?>
    </div>
</div>