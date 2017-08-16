<h1 class="rules"><?=$song->name?></h1>

<audio id="music" src="/songs/<?=$song->file?>" autoplay>
    Your browser does not support the <code>audio</code> element.
</audio>
<script>
    var music = document.querySelector('#music');
    music.volume = <?=$volume / 10?>;
</script>