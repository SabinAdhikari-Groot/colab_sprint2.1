// Function to fetch room numbers asynchronously
function fetchRoomNumbers() {
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "rooms.php", true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                // If request is successful, populate room numbers
                var roomSelect = document.getElementById("room");
                roomSelect.innerHTML = xhr.responseText;
            } else {
                console.error("Error fetching room numbers: " + xhr.status);
            }
        }
    };
    xhr.send();
}

// Call the function to fetch room numbers when the page loads
window.onload = function() {
    fetchRoomNumbers();
};
