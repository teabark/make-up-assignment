<div class="header">
        <h1>Administrator Dashboard</h1>
        <div>
            <!-- Display logged user name -->
            <span>Welcome, <?php echo ucfirst($_SESSION['user']); ?>!</span>
            <!-- Logout button -->
            <a href="../logout.php">Logout</a>
        </div>
    </div>