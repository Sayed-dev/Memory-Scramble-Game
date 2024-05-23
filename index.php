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
            <?php
            foreach ($cards as $index => $card) {
                echo '<div class="card" data-card="' . $card . '" data-index="' . $index . '">
                        <div class="card-inner">
                            <div class="card-front">?</div>
                            <div class="card-back">' . $card . '</div>
                        </div>
                    </div>';
            }
            ?>
        </div>
        <button onclick="location.href='index.php?reset=true'">Reset Game</button>
    <?php endif; ?>
<script>
        let tries = <?= $tries ?>;
        let timeLimit = <?= $timeLimit ?>;
        document.addEventListener('DOMContentLoaded', () => {
            const cards = document.querySelectorAll('.card');
            const triesDisplay = document.getElementById('tries');
            const timeRemainingDisplay = document.getElementById('time_remaining');
            let hasFlippedCard = false;
            let lockBoard = false;
            let firstCard, secondCard;
            let matches = 0;
            const totalPairs = cards.length / 2;

            function flipCard() {
                if (lockBoard) return;
                if (this === firstCard) return;

                this.classList.add('flip');

                if (!hasFlippedCard) {
                    hasFlippedCard = true;
                    firstCard = this;
                    return;
                }

                secondCard = this;
                checkForMatch();
            }

            function checkForMatch() {
                let isMatch = firstCard.dataset.card === secondCard.dataset.card;
                isMatch ? disableCards() : unflipCards();
                updateTries();
                if (isMatch) {
                    matches++;
                    if (matches === totalPairs) {
                        clearInterval(timerInterval);
                        alert('Congratulations, ' + document.getElementById('player_name_display').innerText + '! You completed the game!');
                    }
                }
            }

           // TODO add rest js for timer and disableCards, unflipCards,updateTries and resetBoard 
            
        });
    </script>
</body>
</html>
