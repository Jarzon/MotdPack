<h1 class="rules">Scoreboard</h1>

<p>
    <strong><?=$playerNumber?></strong> players have played over <strong><?=$playerTime?></strong> hours on our server.
</p>
<table class="scoreboard">
    <tr>
        <th>Pos.</th>
        <th>Name</th>
        <th>Time</th>
    </tr>
    <?php foreach($playerList as $player): ?>
    <tr class="<?=$player['class']?>">
        <td><?=$player['pos']?>.</td>
        <td><a href="http://steamcommunity.com/profiles/<?=$player['steamid']?>"><?=$player['name']?></a></td>
        <td class="center"><?=$player['hours']?>h</td>
    </tr>
    <?php endforeach; ?>
</table>
<p class="pagination">
    <?=$pagination?><br>
</p><br>