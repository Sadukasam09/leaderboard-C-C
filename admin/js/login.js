document.addEventListener('DOMContentLoaded', () => {
    const loginForm = document.querySelector('form');
    
    loginForm.addEventListener('submit', (e) => {
        e.preventDefault();
        
        const username = document.querySelector('input[name="username"]').value;
        const password = document.querySelector('input[name="password"]').value;
        
        if (!username || !password) {
            alert('Please fill in all fields');
            return;
        }    
        loginForm.submit();
    });
});