<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Memory Scramble Game</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Memory Scramble Game</h1>
    <form name ="game-form" method="post" action="">
        <label for="player_name">Player Name:</label>
        <input type="text" id="player_name" name="player_name" required>
        <label for="rows">Number of Rows:</label>
        <input type="number" id="rows" name="rows" min="2" max="10" required>
        <label for="columns">Number of Columns:</label>
        <input type="number" id="columns" name="columns" min="2" max="10" required>
        <button type="submit">Start Game</button>
    </form>
    <?php if (isset($error)): ?>
        <p><?php echo $error; ?></p>
    <?php elseif ($cards): ?>
        <p>Player: <span id="player_name_display"><?= htmlspecialchars($playerName) ?></span></p>
        <p>Number of Tries: <span id="tries"><?= $tries ?></span></p>
        <p>Time Remaining: <span id="time_remaining"><?= $timeLimit ?></span> seconds</p>
        <div class="game-board" style="grid-template-columns: repeat(<?= $columns ?>, 100px);">

        </div>
        <button onclick="location.href='index.php?reset=true'">Reset Game</button>
    <?php endif; ?>
</body>
</html>
