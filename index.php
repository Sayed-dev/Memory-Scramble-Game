<?php
session_start();

// Reset game if requested
if (isset($_GET['reset'])) {
    session_destroy();
    header('Location: index.php');
    exit;
}

// Initialize game
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $rows = (int)$_POST['rows'];
    $columns = (int)$_POST['columns'];
    $totalCards = $rows * $columns;
    $playerName = $_POST['player_name'];

    if ($totalCards % 2 != 0) {
        $error = "The number of cells must be even to create pairs. Please adjust your input.";
    } else {
        $pairs = $totalCards / 2;
        $cards = array_merge(range(1, $pairs), range(1, $pairs));
        shuffle($cards);
        $_SESSION['cards'] = $cards;
        $_SESSION['rows'] = $rows;
        $_SESSION['columns'] = $columns;
        $_SESSION['tries'] = 0;  // Initialize tries counter
        $_SESSION['player_name'] = $playerName;  // Store player name

        // Set time limit based on the number of blocks
        if ($totalCards >= 4 && $totalCards < 9) {
            $_SESSION['time_limit'] = 60;  // 60 seconds for 4-8 blocks
        } elseif ($totalCards > 8 && $totalCards <= 20) {
            $_SESSION['time_limit'] = 120;  // 120 seconds for 9-20 blocks
        } else {
            $_SESSION['time_limit'] = 180;  // Default to 180 seconds for other cases
        }
    }
}

// Retrieve game state
$cards = $_SESSION['cards'] ?? [];
$rows = $_SESSION['rows'] ?? 0;
$columns = $_SESSION['columns'] ?? 0;
$tries = $_SESSION['tries'] ?? 0;
$playerName = $_SESSION['player_name'] ?? '';
$timeLimit = $_SESSION['time_limit'] ?? 0;
?>
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
             function disableCards() {
                firstCard.removeEventListener('click', flipCard);
                secondCard.removeEventListener('click', flipCard);

                resetBoard();
            }

            function unflipCards() {
                lockBoard = true;

                setTimeout(() => {
                    firstCard.classList.remove('flip');
                    secondCard.classList.remove('flip');

                    resetBoard();
                }, 1500);
            }

            function updateTries() {
                tries++;
                triesDisplay.innerText = tries;
            }

            function resetBoard() {
                [hasFlippedCard, lockBoard] = [false, false];
                [firstCard, secondCard] = [null, null];
            }

            function startTimer() {
                let timeLeft = timeLimit;
                timerInterval = setInterval(() => {
                    timeLeft--;
                    timeRemainingDisplay.innerText = timeLeft;
                    if (timeLeft <= 0) {
                        clearInterval(timerInterval);
                        alert('Time is up! Game over.');
                        location.href = 'index.php?reset=true';
                    }
                }, 1000);
            }

            startTimer();
            cards.forEach(card => card.addEventListener('click', flipCard));
            
        });
    </script>
</body>
</html>
