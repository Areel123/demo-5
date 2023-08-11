<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header text-center">
                        <h3>Login Form</h3>
                    </div>
                    <div class="card-body">
                        <?php
                        session_start();
                        require_once('dbconection.php');

                        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']) {
                            header('Location: index.php');
                            exit();
                        }

                        if ($_SERVER["REQUEST_METHOD"] == "POST") {
                            $db = new DBConnection();
                            $conn = $db->conn;

                            $username = $_POST['username'];
                            $password = $_POST['password'];

                            $query = $conn->prepare("SELECT password FROM user WHERE username = ?");
                            $query->bind_param('s', $username);
                            $query->execute();
                            $query->bind_result($storedPassword);
                            $query->fetch();

                            // Verify the entered password against the stored password (without hashing)
                            if ($password === $storedPassword) {
                                $_SESSION['logged_in'] = true;
                                header('Location: index.php');
                                exit();
                            } else {
                                $error_message = "Invalid username or password. Please try again.";
                            }

                            $query->close();
                            $db->close();
                        }
                        ?>

                        <?php if (isset($error_message)) { ?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo $error_message; ?>
                            </div>
                        <?php } ?>

                        <form method="POST">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Login</button>
                                <a href="forgot_password.php" class="btn btn-link">Forgot Password?</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
