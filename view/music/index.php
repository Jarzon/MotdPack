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
    <?php
    if($songList):
        foreach($songList as $song): ?>
            <tr>
                <td>♫ <a href="play/<?=urlencode($song['name'])?>"><?=$song['name']?></a></td>
                <?php if($admin):?>
                    <td><?=$song->playCount?></td>
                    <td><a href="edit/<?=$song['id']?>">Edit</a></td>
                <?php endif?>
            </tr>
        <?php
        endforeach;
    else: ?>
        <tr>
            <td colspan="1">There is no song</td>
        </tr>
    <?php endif; ?>
</table>
<p class="pagination">
    <?=$pagination?><br>
</p><br>