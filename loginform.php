<form class="form-inline" method="POST">
    <div class="form-group">
        <label for="teamname">Team Name</label>
        <select class="form-control pickteamname" id="teamname" name="teamID">
        <?php foreach($teams_list as $team){
            echo '<option value="' . $team["team_id"] . '">' . $team["team_name"] . "</option>";
        } ?>
        </select>
    </div>

    <div class="form-group">
        <label for="teamsecret">Team Secret</label>
        <input type="text" class="form-control" id="teamsecret" name="teamsecret" placeholder="Ssssh" required="required" onkeyup="this.value = this.value.replace(/[^A-z 0-9]/, '')">
    </div>

    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" class="btn btn-default">Submit</button>
        </div>
    </div>
</form>

<script>
$(document).ready(function() {
    $('.pickteamname').select2();
});
</script>