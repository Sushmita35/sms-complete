<?php
session_start();
include("database.php");

if(isset($_POST['login']))
{
    $username = $_POST['username'];
    $password = $_POST['password'];

    // PREPARED STATEMENT - fixes SQL injection
    $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE username = ?");
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if(mysqli_num_rows($result) == 1)
    {
        $row = mysqli_fetch_assoc($result);

        // SECURE PASSWORD CHECK using password_verify()
        if(password_verify($password, $row['password']))
        {
            $_SESSION['admin'] = $username;
            header("Location: dashboard.php");
            exit();
        }
        else
        {
            $error = "Invalid password";
        }
    }
    else
    {
        $error = "User not found";
    }

    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>
<html>

<head>
<title>Admin Login</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="flex items-center justify-center h-screen bg-cover bg-center"
style="background-image: url('images/pek.jpg');">

<div class="bg-white/90 backdrop-blur p-8 rounded-lg shadow-lg w-96">

<h2 class="text-2xl font-bold text-center mb-6">
Admin Login
</h2>

<?php
if(isset($error))
{
echo "<p class='text-red-500 text-center mb-4'>$error</p>";
}
?>

<form method="POST" class="space-y-4">

<input
type="text"
name="username"
placeholder="Username"
class="w-full border p-2 rounded"
required>

<input
type="password"
name="password"
placeholder="Password"
class="w-full border p-2 rounded"
required>

<button
type="submit"
name="login"
class="w-full bg-blue-500 text-white p-2 rounded hover:bg-blue-600">
Login
</button>

</form>

</div>

</body>

</html>