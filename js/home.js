document.getElementById('hamburger').addEventListener('click', function () {
    const nav = document.querySelector('header nav');
    nav.classList.toggle('overlay-active');
  });
  
  document.getElementById('closeNav').addEventListener('click', function () {
    document.querySelector('header nav').classList.remove('overlay-active');
  });
  
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
      document.querySelector('header nav').classList.remove('overlay-active');
    }
  });
  
  document.querySelector('header nav').addEventListener('click', function (e) {
    if (e.target.tagName === 'NAV') {
      this.classList.remove('overlay-active');
    }
  });
  