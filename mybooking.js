function pay() {
    if (confirm("Are you sure you want to pay for this booking?")) {
        window.location.href = "process_payment.php";
                } else {
                    alert("Payment cancelled!");
                }
            };

function cancelBooking() {
    if (confirm("Are you sure you want to cancel this booking?")) {
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    location.reload();
                } else {
                    alert("Error cancelling booking.");
                }
            }
        };
        xhr.open("POST", "cancel_booking.php", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.send();
    }
}