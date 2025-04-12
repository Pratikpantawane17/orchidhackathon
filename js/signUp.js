document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('signupForm');
    const passwordInput = document.getElementById('password');
    const togglePassword = document.getElementById('togglePassword');
    const passwordStrength = document.getElementById('passwordStrength');
    const getLocationBtn = document.getElementById('getLocation');
    const latitudeInput = document.getElementById('latitude');
    const longitudeInput = document.getElementById('longitude');
    const errorMessages = {
      name: 'Please enter your full name',
      email: 'Please enter a valid email address',
      password: 'Password must be at least 8 characters with letters and numbers',
      phone: 'Please enter a valid phone number',
      user_type: 'Please select a user type',
      terms: 'You must agree to the terms and conditions'
    };
  
    // Toggle password visibility
    togglePassword.addEventListener('click', function() {
      const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
      passwordInput.setAttribute('type', type);
      
      // Update the eye icon
      const svg = this.querySelector('svg');
      if (type === 'text') {
        svg.innerHTML = `
          <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
          <line x1="1" y1="1" x2="23" y2="23"></line>
        `;
      } else {
        svg.innerHTML = `
          <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
          <circle cx="12" cy="12" r="3"></circle>
        `;
      }
    });
  
    // Check password strength
    passwordInput.addEventListener('input', function() {
      const password = this.value;
      let strength = '';
      
      if (password.length === 0) {
        passwordStrength.className = 'password-strength';
        return;
      }
      
      // Simple password strength check
      if (password.length < 8) {
        strength = 'weak';
      } else if (password.length >= 8 && /[a-zA-Z]/.test(password) && /[0-9]/.test(password)) {
        if (password.length >= 12 && /[!@#$%^&*(),.?":{}|<>]/.test(password)) {
          strength = 'strong';
        } else {
          strength = 'medium';
        }
      } else {
        strength = 'weak';
      }
      
      passwordStrength.className = `password-strength ${strength}`;
    });
  
    // Get current location
    getLocationBtn.addEventListener('click', function() {
      if (navigator.geolocation) {
        getLocationBtn.textContent = 'Getting location...';
        getLocationBtn.disabled = true;
        
        navigator.geolocation.getCurrentPosition(
          function(position) {
            latitudeInput.value = position.coords.latitude.toFixed(6);
            longitudeInput.value = position.coords.longitude.toFixed(6);
            getLocationBtn.innerHTML = `
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76"></polygon>
              </svg>
              Location detected
            `;
            getLocationBtn.disabled = false;
          },
          function(error) {
            console.error('Error getting location:', error);
            getLocationBtn.innerHTML = `
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76"></polygon>
              </svg>
              Get Current Location
            `;
            getLocationBtn.disabled = false;
            showError('location', 'Could not get your location. Please try again or enter manually.');
          }
        );
      } else {
        showError('location', 'Geolocation is not supported by your browser');
      }
    });
  
    // Form validation
    function validateForm() {
      let isValid = true;
      
      // Clear all previous errors
      document.querySelectorAll('.error-message').forEach(el => {
        el.textContent = '';
        el.classList.remove('visible');
      });
      
      // Validate name
      const name = document.getElementById('name').value.trim();
      if (!name) {
        showError('name', errorMessages.name);
        isValid = false;
      }
      
      // Validate email
      const email = document.getElementById('email').value.trim();
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!email || !emailRegex.test(email)) {
        showError('email', errorMessages.email);
        isValid = false;
      }
      
      // Validate password
      const password = document.getElementById('password').value;
      if (password.length < 8 || !/[a-zA-Z]/.test(password) || !/[0-9]/.test(password)) {
        showError('password', errorMessages.password);
        isValid = false;
      }
      
      // Validate phone
      const phone = document.getElementById('phone').value.trim();
      const phoneRegex = /^\+?[0-9\s\-$$$$]{8,20}$/;
      if (!phone || !phoneRegex.test(phone)) {
        showError('phone', errorMessages.phone);
        isValid = false;
      }
      
      // Validate user type
      const userType = document.getElementById('user_type').value;
      if (!userType) {
        showError('user_type', errorMessages.user_type);
        isValid = false;
      }
      
      // Validate terms checkbox
      const termsChecked = document.getElementById('terms').checked;
      if (!termsChecked) {
        showError('terms', errorMessages.terms);
        isValid = false;
      }
      
      return isValid;
    }
  
    function showError(field, message) {
      const errorElement = document.querySelector(`[data-error="${field}"]`);
      if (errorElement) {
        errorElement.textContent = message;
        errorElement.classList.add('visible');
        
        // Add shake animation to the input
        const inputElement = document.getElementById(field);
        if (inputElement) {
          inputElement.classList.add('shake');
          setTimeout(() => {
            inputElement.classList.remove('shake');
          }, 500);
        }
      }
    }
  
    // Form submission
    form.addEventListener('submit', function(e) {
      e.preventDefault();
      
      if (validateForm()) {
        const userData = {
          name: document.getElementById('name').value.trim(),
          email: document.getElementById('email').value.trim(),
          password: document.getElementById('password').value,
          phone: document.getElementById('phone').value.trim(),
          user_type: document.getElementById('user_type').value,
          latitude: document.getElementById('latitude').value.trim(),
          longitude: document.getElementById('longitude').value.trim()
        };
        
        console.log('User data to send:', userData);
        
        // Show success message (in a real app, you'd send this to your backend)
        const submitButton = document.querySelector('.submit-button');
        const originalText = submitButton.textContent;
        
        submitButton.textContent = 'Creating account...';
        submitButton.disabled = true;
        
        // Simulate API call
        setTimeout(() => {
          // Replace this with your actual API call
          alert('Account created successfully!');
          form.reset();
          passwordStrength.className = 'password-strength';
          submitButton.textContent = originalText;
          submitButton.disabled = false;
        }, 1500);
        
        // In a real application, you would use fetch to send the data to your backend:
        /*
        fetch('/api/register', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify(userData),
        })
        .then(res => {
          if (!res.ok) {
            throw new Error('Registration failed');
          }
          return res.json();
        })
        .then(data => {
          alert("Registration successful!");
          window.location.href = "/login.html";
        })
        .catch(err => {
          showError('general', "Error occurred during registration.");
          submitButton.textContent = originalText;
          submitButton.disabled = false;
        });
        */
      }
    });
  });