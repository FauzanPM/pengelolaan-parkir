function checkLocalStorage() {
    const hall_parking = localStorage.getItem('hall_parking');
    if (hall_parking) {
        document.querySelector('.nameHall').textContent = hall_parking;
    } else {
        window.location.href = '/Dashboard';
    }
}

checkLocalStorage();

function redirectToDashboard() {
    window.location.href = "/dashboard";
}