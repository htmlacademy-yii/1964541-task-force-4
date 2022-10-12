let popupReview = document.querySelector('.pop-up--completion');
let starsContainer = popupReview.querySelector('.stars-rating');
let allStars = starsContainer.querySelectorAll('span');
let activeStars = starsContainer.querySelectorAll('.fill-star');
let gradeInput = popupReview.querySelector('#reviewform-grade');

starsContainer.addEventListener('click', (evt) => {
    let myTarget = evt.target;
    let i = allStars.length;
    while(i--) {
        if(allStars[i] == myTarget) {
            var currentIndex = i;
            break;
        }
    }
    cStars(currentIndex);
    gradeInput.value = currentIndex + 1;
});

let cStars = function(nowPos) {
    for (let i = 0; allStars.length > i; i++) {
        allStars[i].classList.remove('fill-star');
    }
    for (let i = 0; nowPos + 1 > i; i++) {
        allStars[i].classList.toggle('fill-star');
    }
}