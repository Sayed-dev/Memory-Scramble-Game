document.addEventListener('DOMContentLoaded', () => {
    const cards = document.querySelectorAll('.card');
    const triesDisplay = document.getElementById('tries');
    let tries = parseInt(triesDisplay.innerText);
    let hasFlippedCard = false;
    let lockBoard = false;
    let firstCard, secondCard;
    let matches = 0;
    const totalPairs = cards.length / 2;

// flipCard by Bisho
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

// checkForMatch by Sayed
function checkForMatch() {
    let isMatch = firstCard.dataset.card === secondCard.dataset.card;
    isMatch ? disableCards() : unflipCards();
    updateTries();
    if (isMatch) {
        matches++;
        if (matches === totalPairs) {
            alert('Congratulations! You completed the game!');
        }
    }
}
// disableCardsby Bisho
function disableCards() {
        firstCard.removeEventListener('click', flipCard);
        secondCard.removeEventListener('click', flipCard);

        resetBoard();
    }
// unflipCards by sayed
function unflipCards() {
    lockBoard = true;

    setTimeout(() => {
        firstCard.classList.remove('flip');
        secondCard.classList.remove('flip');

        resetBoard();
    }, 1500);
}
//updateTries by sayed
function updateTries() {
        tries++;
        triesDisplay.innerText = tries;
        <?php $_SESSION['tries'] = 'tries'; ?> // Update session variable with tries count
    }
// resetBoard by sayed
     function resetBoard() {
        [hasFlippedCard, lockBoard] = [false, false];
        [firstCard, secondCard] = [null, null];
    }

    cards.forEach(card => card.addEventListener('click', flipCard));
});
