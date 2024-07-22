function saveText(text) {
    localStorage.setItem('hall_parking', text);
    window.location.href = '/Dashboard/?Parking';
}