// auth-guard.js
(function () {
  const protectedPages = [
    'buy-sell.php',
    'cart.php',
    'profile.php',
    'profile-settings.php',
    'checkout.php',
    'deliver.php'
  ];

  const currentPage = window.location.pathname.split('/').pop();

  if (protectedPages.includes(currentPage)) {
    const isLoggedIn = localStorage.getItem('isLoggedIn') === 'true' || sessionStorage.getItem('isLoggedIn') === 'true';

    if (!isLoggedIn) {
      alert('Access Restricted: Please log in or register to access this page.');
      window.location.href = 'login.php';
    }
  }
})();