document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('form');
    
    // Fetch the leaderboard when the page loads
    fetchLeaderboard();

    // Handle form submission
    form.addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent the form from submitting normally

        const formData = new FormData(form);

        // Send the form data to the backend
        fetch('../backend/leaderboardupdate.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            // Refresh the leaderboard after updating
            fetchLeaderboard();
           
            form.reset();
        })
        .catch(error => console.error('Error updating score:', error));
    });

    // Fetch the leaderboard from the backend   
    function fetchLeaderboard() {
        fetch('../backend/leaderboardupdate.php')
            .then(response => response.json())
            .then(data => {
                const leaderboardTable = document.getElementById('leaderboard');
                
                leaderboardTable.innerHTML = `
                    <tr>
                        <th>Rank</th>
                        <th>Name</th>
                        <th>Score</th>
                    </tr>
                `;

                // Add a row for each player
                data.forEach((player, index) => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${index + 1}</td>
                        <td>${player.name}</td>
                        <td>${player.score}</td>
                    `;
                    leaderboardTable.appendChild(row);
                });
            })
            .catch(error => console.error('Error fetching leaderboard:', error));
    }
});