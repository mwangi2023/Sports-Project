// Toggle pages
function showPage(pageId) {
  document.querySelectorAll(".page").forEach(page => page.classList.remove("active"));
  document.getElementById(pageId).classList.add("active");
}

// Backend API base URL
const API_URL = "http://localhost:5000/api"; // ✅ Adjust this if your PHP backend path is different

// ✅ Register function
async function register(event) {
  event.preventDefault();

  const name = document.getElementById("regName").value;
  const email = document.getElementById("regEmail").value;
  const password = document.getElementById("regPassword").value;

  try {
    const res = await fetch(`${API_URL}/auth/register`, { // ✅ backticks used
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ name, email, password })
    });

    const data = await res.json();

    if (res.ok) {
      alert("✅ Registered successfully!");
      showPage("login");
    } else {
      alert("❌ Registration failed: " + (data.message || "Unknown error"));
    }

  } catch (error) {
    alert("⚠️ Network or server error: " + error.message);
  }
}

// ✅ Login function
async function login(event) {
  event.preventDefault();

  const email = document.getElementById("loginEmail").value;
  const password = document.getElementById("loginPassword").value;

  try {
    const res = await fetch(`${API_URL}/auth/login`, { // ✅ fixed
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ email, password })
    });

    const data = await res.json();

    if (res.ok) {
      alert("✅ Login successful!");
      localStorage.setItem("token", data.token);
      showPage("home");
    } else {
      alert("❌ Login failed: " + (data.message || "Invalid credentials"));
    }

  } catch (error) {
    alert("⚠️ Network or server error: " + error.message);
  }
}