// Display user info
const loggedInUser = JSON.parse(localStorage.getItem('loggedInUser'));
if (loggedInUser) {
  document.getElementById('welcomeUser').textContent = `Welcome, ${loggedInUser.name}`;
  document.getElementById('profileName').textContent = loggedInUser.name;
  document.getElementById('profileEmail').textContent = loggedInUser.email;
}

// Workout data
let workouts = [];

// Elements
const workoutForm = document.getElementById('workoutForm');
const workoutTable = document.getElementById('workoutTable');
const workoutMessage = document.getElementById('workoutMessage');
const totalWorkoutsEl = document.getElementById('totalWorkouts');
const profileWorkoutsEl = document.getElementById('profileWorkouts');
const hoursWeekEl = document.getElementById('hoursWeek');
const caloriesEl = document.getElementById('calories');

// Initialize Chart.js
const ctx = document.getElementById('workoutChart').getContext('2d');
let workoutChart = new Chart(ctx, {
  type: 'bar',
  data: {
    labels: [], // Dates
    datasets: [{
      label: 'Workout Duration (hrs)',
      data: [],
      backgroundColor: 'rgba(46, 204, 113, 0.6)',
      borderColor: 'rgba(39, 174, 96, 1)',
      borderWidth: 1,
      borderRadius: 6
    }]
  },
  options: {
    responsive: true,
    plugins: {
      legend: { display: true }
    },
    scales: {
      y: { beginAtZero: true }
    }
  }
});

// Add workout form submit
workoutForm.addEventListener('submit', function (e) {
  e.preventDefault();

  const sport = document.getElementById('sportType').value;
  const duration = parseFloat(document.getElementById('duration').value);
  const date = document.getElementById('workoutDate').value;
  const notes = document.getElementById('notes').value;

  const newWorkout = { sport, duration, date, notes };
  workouts.push(newWorkout);

  // Update table
  const row = `<tr>
    <td>${sport}</td>
    <td>${duration} hrs</td>
    <td>${notes}</td>
    <td>${date}</td>
  </tr>`;
  workoutTable.innerHTML += row;

  // Update stats
  totalWorkoutsEl.textContent = workouts.length;
  profileWorkoutsEl.textContent = workouts.length;

  const totalHours = workouts.reduce((sum, w) => sum + w.duration, 0);
  hoursWeekEl.textContent = totalHours;

  // Simple calories burned estimate (400 kcal per hour)
  caloriesEl.textContent = totalHours * 400;

  // Update chart
  workoutChart.data.labels.push(date);
  workoutChart.data.datasets[0].data.push(duration);
  workoutChart.update();

  // Show message
  workoutMessage.textContent = "✅ Workout added successfully!";

  // Reset form
  workoutForm.reset();
  fetch("http://localhost/sports-tracker/BACKEND/get_user.php")
  .then(res => res.json())
  .then(data => {
    if (!data.error) {
      document.getElementById("userName").textContent = data.name;
      document.getElementById("userEmail").textContent = data.email;
    } else {
      alert("Please login first!");
      window.location.href = "login.html";
    }
  });

});