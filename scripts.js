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

// resetBoard by sayed
});
