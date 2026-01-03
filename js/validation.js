document.addEventListener("DOMContentLoaded", function () {
  const form = document.querySelector("form");

  form.addEventListener("submit", function (e) {
    const phone = form.querySelector("input[name='phone']").value.trim();
    const email = form.querySelector("input[name='email']").value.trim();
    const doctor = form.querySelector("select[name='doctor']").value;
    const amount = form.querySelector("input[name='amount']").value;
    const dateField = form.querySelector("input[name='appointment_date']");

    // Phone validation
    if (!/^\d{11}$/.test(phone)) {
      alert("Please enter a valid 11-digit phone number.");
      e.preventDefault(); return;
    }

    // Email validation
    if (email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
      alert("Please enter a valid email address.");
      e.preventDefault(); return;
    }

    // Doctor selection
    if (!doctor) {
      alert("Please select a doctor.");
      e.preventDefault(); return;
    }

    // Amount check
    if (!amount || amount <= 0) {
      alert("Invalid payment amount.");
      e.preventDefault(); return;
    }

    // Appointment date
    const today = new Date(); today.setHours(0,0,0,0);
    const appointmentDate = new Date(dateField.value);
    if (appointmentDate < today) {
      alert("Appointment date cannot be in the past.");
      e.preventDefault(); return;
    }
  });
});
