// script.js – BookMyFlight Enhancements

// Smooth scroll to top after booking
function scrollToTop() {
  window.scrollTo({ top: 0, behavior: 'smooth' });
}

// Simple form validation
function validateBookingForm() {
  const name = document.querySelector('[name="name"]').value.trim();
  const phone = document.querySelector('[name="phone"]').value.trim();
  const amount = document.querySelector('[name="amount"]').value.trim();

  if (name === "" || phone === "" || amount === "") {
    alert("⚠️ Please fill all required fields before submitting.");
    return false;
  }

  if (!/^[0-9]{10}$/.test(phone)) {
    alert("⚠️ Enter a valid 10-digit phone number.");
    return false;
  }

  if (isNaN(amount) || amount <= 0) {
    alert("⚠️ Enter a valid payment amount.");
    return false;
  }

  return true;
}

// Confirmation message on booking submission
function confirmBooking() {
  return confirm("Do you want to confirm this booking?");
}
