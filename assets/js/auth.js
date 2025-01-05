// Authentication functions
function checkAuth() {
    const token = localStorage.getItem('auth_token');
    return token != null;
}

function logout() {
    const logoutLink = document.querySelector('[data-action="logout"]');
    if (logoutLink) {
        logoutLink.click();
    }
}

// Initialize Toastr options
toastr.options = {
    "closeButton": true,
    "progressBar": true,
    "positionClass": "toast-top-right",
    "timeOut": "3000"
}; 