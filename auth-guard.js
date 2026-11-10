// auth-guard.js
(function () {
  const protectedPages = [
    'buy-sell.html',
    'cart.html',
    'profile.html',
    'profile-settings.html',
    'checkout.html',
    'deliver.html'
  ];

  const currentPage = window.location.pathname.split('/').pop();

  if (protectedPages.includes(currentPage)) {
    const isLoggedIn = localStorage.getItem('isLoggedIn') === 'true' || sessionStorage.getItem('isLoggedIn') === 'true';

    if (!isLoggedIn) {
      alert('Access Restricted: Please log in or register to access this page.');
      window.location.href = 'login.html';
    }
  }
})();