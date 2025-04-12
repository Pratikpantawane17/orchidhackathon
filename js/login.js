
  document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('loginForm');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const togglePassword = document.getElementById('togglePassword');
    const loginButton = document.getElementById('loginButton');
    const errorMsg = document.getElementById('errorMsg');

    // Password toggle
    togglePassword.addEventListener('click', function () {
      const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
      passwordInput.setAttribute('type', type);

      const svg = this.querySelector('svg');
      svg.innerHTML = type === 'text'
        ? `<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
           <line x1="1" y1="1" x2="23" y2="23"></line>`
        : `<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
           <circle cx="12" cy="12" r="3"></circle>`;
    });

    emailInput.addEventListener('blur', validateEmail);
    passwordInput.addEventListener('blur', validatePassword);

    function validateEmail() {
      const email = emailInput.value.trim();
      const emailError = document.querySelector('[data-error="email"]');
      const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

      if (!email) return showError(emailInput, emailError, 'Email is required');
      if (!regex.test(email)) return showError(emailInput, emailError, 'Invalid email format');
      return clearError(emailInput, emailError);
    }

    function validatePassword() {
      const password = passwordInput.value;
      const passwordError = document.querySelector('[data-error="password"]');

      if (!password) return showError(passwordInput, passwordError, 'Password is required');
      return clearError(passwordInput, passwordError);
    }

    function showError(input, errorElement, message) {
      input.classList.add('error-input');
      errorElement.textContent = message;
      errorElement.classList.add('visible');
      return false;
    }

    function clearError(input, errorElement) {
      input.classList.remove('error-input');
      errorElement.textContent = '';
      errorElement.classList.remove('visible');
      return true;
    }

    // Decode JWT
    function parseJwt(token) {
      try {
        const base64Url = token.split('.')[1];
        const base64 = atob(base64Url.replace(/-/g, '+').replace(/_/g, '/'));
        return JSON.parse(decodeURIComponent(escape(base64)));
      } catch (e) {
        console.error("JWT decode failed:", e);
        return null;
      }
    }

    form.addEventListener('submit', async function (e) {
      e.preventDefault();

      errorMsg.textContent = '';

      const isEmailValid = validateEmail();
      const isPasswordValid = validatePassword();
      if (!isEmailValid || !isPasswordValid) return;

      const email = emailInput.value.trim();
      const password = passwordInput.value;
      const rememberMe = document.getElementById('remember').checked;

      loginButton.classList.add('loading');
      loginButton.disabled = true;

      try {
        const response = await fetch("http://localhost:9191/api/users/auth/login", {
          method: "POST",
          headers: {
            "Content-Type": "application/json"
          },
          body: JSON.stringify({ email, password })
        });
        
        let data;
        try {
          // Only try to parse if content-type is JSON
          const contentType = response.headers.get("content-type");
          if (contentType && contentType.includes("application/json")) {
            data = await response.json();
          } else {
            throw new Error("Expected JSON response but got: " + contentType);
          }
        } catch (err) {
          console.error("JSON parse error:", err);
          document.getElementById("errorMsg").textContent = "Server returned invalid response.";
          return;
        }
        
        if (response.ok && data.token) {
          localStorage.setItem("token", data.token);
          const userData = parseJwt(data.token);
          if (userData) {
            alert(`Welcome, ${userData.name || userData.email}!`);
          }
          // Redirect or further actions...
        } else {
          document.getElementById("errorMsg").textContent = data?.message || "Login failed";
        }
        
         
      } catch (err) {
        errorMsg.textContent = 'Network error: ' + err.message;
      } finally {
        loginButton.classList.remove('loading');
        loginButton.disabled = false;
      }
    });

    // Optional: social buttons (if needed)
    const socialButtons = document.querySelectorAll('.social-button');
    socialButtons.forEach(btn => {
      btn.addEventListener('click', () => {
        const provider = btn.classList.contains('google') ? 'Google' : 'Apple';
        alert(`${provider} login would go here.`);
      });
    });
  });

  // Optional utility
  function getUserFromToken() {
    const token = localStorage.getItem("token") || sessionStorage.getItem("token");
    if (token) {
      const user = parseJwt(token);
      return user && user.exp * 1000 > Date.now() ? user : null;
    }
    return null;
  }

