<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Quality Pending Appointments</title>
  <!-- Bootstrap CSS (via CDN) -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <!-- Custom Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #f5f7fa, #c3cfe2);
      font-family: 'Roboto', sans-serif;
      margin: 0;
      padding: 0;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .container {
      background: #fff;
      padding: 40px 50px;
      border-radius: 10px;
      box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
      width: 90%;
      max-width: 900px;
      animation: fadeIn 0.8s ease-out;
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(-20px); }
      to { opacity: 1; transform: translateY(0); }
    }
    h2 {
      color: #2c3e50;
      text-align: center;
      font-weight: 700;
      margin-bottom: 30px;
    }
    .table-responsive {
      margin-top: 20px;
    }
    table {
      font-size: 0.95rem;
    }
    .thead-dark th {
      background-color: #34495e;
      border-color: #2c3e50;
    }
    .btn-success {
      transition: transform 0.3s ease;
    }
    .btn-success:hover {
      transform: scale(1.05);
    }
    .alert {
      font-size: 1.1rem;
    }
    /* Responsive adjustments */
    @media (max-width: 768px) {
      .container {
        padding: 30px 20px;
      }
      h2 {
        font-size: 1.5rem;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Quality Pending Appointments</h2>

    @if($pending_Appointments->isEmpty())
      <div class="alert alert-info text-center">
        There are no pending appointments.
      </div>
    @else
      <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <thead class="thead-dark">
              <tr>
                <th>ID</th>
                <th>Appointment Date</th>
                <th>Lead ID</th>
                <th>Agent</th>
                <th>Status</th>
                <th>Temporary?</th> 
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($pending_Appointments as $appointment)
                <tr>
                  <td>{{ $appointment->id }}</td>
                  <td>{{ $appointment->appointment_date }}</td>
                  <td>{{$appointment->slot->start_time}} - {{$appointment->slot->end_time}} </td>
                   
                  <td>{{ $appointment->agent->name }}</td>
                  <td>{{ $appointment->status }}</td>
                  <td>{{ $appointment->is_temporary_assigned ? 'Yes' : 'No' }}</td> 
                  <td>
                    <!-- Approve button form -->
                    <form action="{{ route('qualityApprove', $appointment->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure you want to approve this appointment?');">
                      @csrf
                      <button type="submit" class="btn btn-success btn-sm">Approve</button>
                    </form>
    
                    <!-- Reject button form -->
                    <form action="{{ route('qualityReject', $appointment->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure you want to reject and delete this appointment?');">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-danger btn-sm">Reject</button>
                    </form>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
      </div>
    @endif
  </div>
  
  <!-- jQuery and Bootstrap JS (via CDN) -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
