<h1 class="rules">Edit the song</h1>

<div>
    <a href="/music/">Go back</a> -
    <a href="/music/delete/<?=$song->song_id?>" onclick="return confirm('Are you sure?')">Delete this song</a> -
    <a href="hide/<?=$song->song_id?>" onclick="return confirm('Are you sure?')">Hide this song(WIP)</a><br>
    Play count: <b><?=$song->playCount?></b>
</div>

<form enctype="multipart/form-data" method="post" action="<?=$song->song_id?>">
    <label for="name">Song name:</label>
    <input id="name" name="name" type="text" value="<?=$song->name?>" style="width: 50%;"><br>

    <div class="center"><input name="submit" type="submit" value="Edit"></div>
</form>