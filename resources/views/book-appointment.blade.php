<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Book Appointment</title>
  <!-- Bootstrap CSS (via CDN) -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <!-- jQuery UI CSS (via CDN) -->
  <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
  <style>
    /* Global Styles */
    body {
      background: linear-gradient(135deg, #2c3e50, #bdc3c7);
      font-family: 'Roboto', sans-serif;
      color: #333;
      min-height: 100vh;
      margin: 0;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    /* Container Styles */
    .container {
      max-width: 600px;
      background: #fff;
      padding: 40px 50px;
      border-radius: 10px;
      box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
      animation: fadeIn 1s ease-out;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(-20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    /* Heading */
    h2 {
      text-align: center;
      margin-bottom: 30px;
      color: #2c3e50;
      font-weight: 500;
    }

    /* Form Label and Input Styles */
    .form-group label {
      font-weight: 500;
      color: #34495e;
    }

    .form-control {
      border: 1px solid #bdc3c7;
      border-radius: 5px;
      transition: border-color 0.3s, box-shadow 0.3s;
    }

    .form-control:focus {
      border-color: #2c3e50;
      box-shadow: 0 0 8px rgba(44, 62, 80, 0.3);
    }

    /* Button Styles */
    .btn-primary {
      background-color: #2c3e50;
      border: none;
      transition: background-color 0.3s, transform 0.3s;
    }

    .btn-primary:hover {
      background-color: #34495e;
      transform: translateY(-2px);
    }

    .btn-primary:active {
      transform: translateY(0);
    }

    /* DatePicker Custom Styling */
    .ui-datepicker {
      background: #ecf0f1;
      border: 1px solid #bdc3c7;
      border-radius: 5px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .ui-state-default {
      background: #fff;
      border: 1px solid transparent;
      transition: background-color 0.3s;
    }

    .ui-state-default:hover {
      background: #dfe6e9;
      border-color: #bdc3c7;
    }

    /* Disabled (Holiday) Date Styling */
    .holiday a {
      background-color: #e74c3c !important;
      color: #fff !important;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Book Your Meeting</h2>
    <form id="appointment_form" method="POST" action="{{ route('bookMeeting') }}">
      @csrf
      <!-- Appointment Date Input -->
      <div class="form-group">
        <label for="appointment_date">Select Appointment Date:</label>
        <input type="text" id="appointment_date" name="appointment_date" class="form-control" autocomplete="off" placeholder="Select Date">
      </div>
      
      <!-- Available Slots Dropdown -->
      <div class="form-group">
        <label for="slot_id">Select an Available Slot:</label>
        <select id="slot_id" name="slot_id" class="form-control">
          <option value="">Select a date to load slots</option>
        </select>
      </div>
      
      <!-- Additional fields (e.g., lead details) can be added here -->
      
      <button type="submit" class="btn btn-primary btn-block">Book Meeting</button>
    </form>
  </div>
  
  <!-- jQuery, jQuery UI, and Bootstrap JS (via CDN) -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  
  <script>
    $(document).ready(function() {
      // Retrieve holiday dates passed from the controller (as a JSON array).
      var holidays = @json($holidays); // Example: ["2025-12-25", "2025-01-01", "2025-05-01"]
  
      // Initialize jQuery UI DatePicker.
      $("#appointment_date").datepicker({
        dateFormat: "yy-mm-dd",
        beforeShowDay: function(date) {
          var formattedDate = $.datepicker.formatDate('yy-mm-dd', date);
          // If the date is in the holidays array, disable it.
          if (holidays.indexOf(formattedDate) !== -1) {
            return [false, "holiday", "Holiday"];
          }
          return [true, ""];
        },
        onSelect: function(dateText) {
          // When a date is selected, fetch available slots via AJAX.
          $.ajax({
            url: '/api/available-slots',  // Ensure this route is registered in your routes/api.php
            type: 'GET',
            data: { appointment_date: dateText },
            success: function(response) {
              var slotSelect = $('#slot_id');
              slotSelect.empty(); // Clear current options
              if (response.success) {
                if (response.availableSlots.length === 0) {
                  slotSelect.append('<option value="">No available slots</option>');
                } else {
                  $.each(response.availableSlots, function(index, slot) {
                    slotSelect.append(
                      '<option value="' + slot.id + '">' +
                      slot.start_time + ' - ' + slot.end_time + ' (' +
                      slot.available_capacity + ' agent' + (slot.available_capacity > 1 ? 's' : '') + ' available)' +
                      '</option>'
                    );
                  });
                }
              } else {
                slotSelect.append('<option value="">Error loading slots</option>');
              }
            },
            error: function(xhr) {
              console.error('Error fetching slots:', xhr.responseText);
              $('#slot_id').html('<option value="">Error loading slots</option>');
            }
          });
        }
      });
    });
  </script>
</body>
</html>
