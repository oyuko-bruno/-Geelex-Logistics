<?php include 'auth.php'; ?>
<?php include 'header.php'; ?>

<style>
    .admin-dashboard {
        width: 100%;
        max-width: 500px;
        margin: 40px auto;
        padding: 20px;
        background-color: #ffffff;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        border-radius: 12px;
        text-align: center;
        font-family: 'Segoe UI', sans-serif;
    }

    .admin-dashboard h2 {
        margin-bottom: 20px;
        color: #222;
        font-size: 24px;
    }

    .admin-dashboard ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .admin-dashboard li {
        margin: 15px 0;
    }

    .admin-dashboard a {
        display: block;
        padding: 14px 25px;
        border-radius: 10px;
        background: #007bff;
        color: white;
        text-decoration: none;
        font-size: 16px;
        font-weight: 500;
        transition: background 0.3s ease;
    }

    .admin-dashboard a:hover {
        background: #0056b3;
    }

    /* Responsive Styling */
    @media (max-width: 480px) {
        .admin-dashboard {
            margin: 20px 10px;
            padding: 15px;
        }

        .admin-dashboard h2 {
            font-size: 20px;
        }

        .admin-dashboard a {
            font-size: 15px;
            padding: 12px 20px;
        }
    }

    @media (max-width: 360px) {
        .admin-dashboard {
            padding: 10px;
        }

        .admin-dashboard a {
            font-size: 18px;
            padding: 10px 15px;
        }
    }
</style>

<div class="admin-dashboard">
    <h2>👋 Welcome, Admin</h2>
    <ul>
        <li><a href="bookings.php">📦 View All Bookings</a></li>
        <li><a href="post_tracking.php">📍 Track a Booking</a></li>
        <li><a href="logout.php">🚪 Logout</a></li>
    </ul>
</div>

<?php include 'footer.php'; ?>
