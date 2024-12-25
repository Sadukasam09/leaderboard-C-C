document.addEventListener('DOMContentLoaded', () => {
    const table = document.getElementById('players-details');
    const tbody = table.querySelector('tbody');

    function fetchPlayers() {
        fetch('../backend/viewplayers.php')
            .then(response => {
                if (!response.ok) throw new Error('Network response failed');
                return response.json();
            })
            .then(data => {
                if (!Array.isArray(data)) {
                    throw new Error('Invalid data format');
                }
                
                tbody.innerHTML = '';
                data.forEach(player => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${player.id || ''}</td>
                        <td>${player.name || 'Unknown'}</td>
                        <td>${player.school || 'N/A'}</td>
                        <td>${player.email || 'N/A'}</td>
                        <td>${player.phone || 'N/A'}</td>
                    `;
                    tbody.appendChild(row);
                });
            })
            .catch(error => {
                console.error('Error:', error);
                tbody.innerHTML = `
                    <tr>
                        <td colspan="5" class="error-message">
                            Error loading players: ${error.message}
                        </td>
                    </tr>
                `;
            });
    }

    // Initial fetch
    fetchPlayers();
    
    // Auto refresh every 30 seconds
    setInterval(fetchPlayers, 30000);
    
    // Add refresh button (optional)
    const refreshButton = document.createElement('button');
    refreshButton.textContent = 'Refresh';
    refreshButton.onclick = fetchPlayers;
    table.parentNode.insertBefore(refreshButton, table);
});