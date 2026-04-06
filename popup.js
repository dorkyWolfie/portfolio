// Function for opening popups
function openPopup(popupId) {
    var popup = document.getElementById(popupId);
    if (popup) {
      popup.style.display = 'block';
      document.body.classList.add('popup-open');
    }
}

// Function for closing popups
 function closePopup(popupId) {
  var popup = document.getElementById(popupId);
  if (popup) {
    popup.style.display = 'none';
    document.body.classList.remove('popup-open');
  }
}