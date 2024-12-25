document.addEventListener('DOMContentLoaded', () => {
    const leaderboardTable = document.getElementById('leaderboard');
    const tbody = leaderboardTable.querySelector('tbody') || leaderboardTable;

    function fetchLeaderboard() {
        fetch('leaderboard.php')
            .then(response => {
                if (!response.ok) throw new Error('Network response failed');
                return response.json();
            })
            .then(response => {
                if (!response.success) throw new Error(response.error);
                updateLeaderboard(response.data);
            })
            .catch(error => {
                console.error('Error:', error);
                showError(error.message);
            });
    }

    function updateLeaderboard(players) {
        tbody.innerHTML = '';
        
        players.forEach((player, index) => {
            const row = document.createElement('tr');
            const rank = index + 1;
            
            row.innerHTML = `
                <td class="rank">${rank}</td>
                <td class="name">${player.name}</td>
                <td class="score">${player.score}</td>
            `;
            
            // Add special classes for top 3
            if (rank <= 3) row.classList.add(`rank-${rank}`);
            
            tbody.appendChild(row);
        });
    }

    function showError(message) {
        tbody.innerHTML = `
            <tr>
                <td colspan="3" class="error">
                    Failed to load leaderboard: ${message}
                </td>
            </tr>
        `;
    }

    // Initial fetch
    fetchLeaderboard();
    
    // Fetch leaderboard every 5 seconds
    setInterval(fetchLeaderboard, 5000);

});