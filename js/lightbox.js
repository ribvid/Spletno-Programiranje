/**
 * Funkcija prikaže sliko v večji velikosti
 * @param {String} modalId
 * @param {String} imageId
 * @param {String} fullSizeImageId
 * @param {String} captionId
 * @param {String} closeId
 */

function showImage(modalId, imageId, fullSizeImageId, captionId, closeId) {
    var modal = document.getElementById(modalId);   // Modalno okno
    var img = document.getElementById(imageId);     // Izvorna slika
    var modalImg = document.getElementById(fullSizeImageId);    // Povečana slika
    var captionText = document.getElementById(captionId);   // Opis slike
    
    modal.style.display = "block";
    modalImg.src = img.src;
    captionText.innerHTML = img.alt;

    var close = document.getElementById(closeId);   // Gumb za zapiranje slike
    close.onclick = function() { 
        modal.style.display = "none";
    }
}