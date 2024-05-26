<header>
    <a href="/LoginY_SingUp/src" class="brand">Your Name App</a>
    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
            <?php if (!isset($_SESSION["user_id"])): ?>
                <li><a href="login.php">Login</a></li>
                <li><a href="signup.php">Signup</a></li>
            <?php else: ?>
                <li><a href="logout.php">Logout</a></li>
                <?php if (isset($user) && isset($user["role"]) && $user["role"] === "admin"): ?>
                    <li><a href="admin.php">Admin Dashboard</a></li>
                <?php endif; ?>
            <?php endif; ?>
        </ul>
    </nav>
</header>
<style>
    header {
        border-bottom: 2px solid #eee;
        padding: 20px 0;
        margin-bottom: 10px;
        width: 100%;
        text-align: center;
        background-color: #f8f9fa;
    }
    .brand {
        font-size: 24px;
        font-weight: bold;
        color: #333;
        text-decoration: none;
        margin-right: 20px;
    }
    nav ul {
        list-style: none;
        padding: 0;
        display: inline-block;
    }
    nav ul li {
        display: inline;
        margin: 0 15px;
    }
    nav ul li a {
        text-decoration: none;
        color: #007bff;
        padding: 10px;
        transition: color 0.3s, background-color 0.3s;
    }
    nav ul li a:hover {
        color: #0056b3;
        background-color: #e2e6ea;
        border-radius: 5px;
    }
</style>
