<h1 class="rules">Music</h1>

<?php if($admin):?><b><a href="uploader">Upload</a></b><?php endif?>

<table class="music">
    <tr>
        <th>Song</th>
        <?php if($admin):?>
            <th>Plays</th>
            <th>Edit</th>
        <?php endif?>
    </tr>
    <?php if(isset($songList)) : while($song = $songList->fetch_object()) : ?>
        <tr>
            <td>♫ <a href="play/<?=urlencode($song->name)?>"><?=$song->name?></a></td>
            <?php if($admin):?>
                <td><?=$song->playCount?></td>
                <td><a href="edit/<?=$song->song_id?>">Edit</a></td>
            <?php endif?>
        </tr>
    <?php endwhile; endif; ?>
</table>
<p class="pagination">
    <?=$pagination?><br>
</p><br>