<?php
ini_set('display_error', 1);
error_reporting(E_ALL);
// --- STEP 1: CONFIGURE YOUR DATABASE CONNECTION ---
// Replace these with your actual database details.
// For local testing with XAMPP, the username is usually "root" and the password is "" (empty).
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "portfolio_db"; // You can name your database anything you like.

// --- STEP 2: ESTABLISH THE DATABASE CONNECTION ---
// Create a new connection to the MySQL database.
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if the connection failed. If so, stop the script and show an error.
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// --- STEP 3: GET DATA FROM THE FORM AND PREPARE IT FOR THE DATABASE ---
// Check if the form was submitted using the POST method.
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get the raw data from the form and use htmlspecialchars to prevent XSS attacks.
    $name    = htmlspecialchars($_POST['name']);
    $email   = htmlspecialchars($_POST['email']);
    $message = htmlspecialchars($_POST['message']);

    // --- STEP 4: CREATE AND EXECUTE THE SQL INSERT QUERY ---
    // Prepare an SQL statement to prevent SQL injection.
    $stmt = $conn->prepare("INSERT INTO contacts (name, email, message) VALUES (?, ?, ?)");
    
    // Bind the variables to the prepared statement as strings ("sss").
    $stmt->bind_param("sss", $name, $email, $message);

    // Execute the statement and check if it was successful.
    if ($stmt->execute()) {
        // If successful, display a thank you message.
        echo "<h1>Thank You, " . $name . "!</h1>";
        echo "<p>Your message has been received successfully. I will get back to you shortly.</p>";
        echo "<a href='index.html'>Return back to the home page</a>"; // Link back to your main page
    } else {
        // If there was an error, display a generic error message.
        echo "<h1>Error</h1>";
        echo "<p>Sorry, something went wrong. Please try again later.</p>";
    }

    // --- STEP 5: CLOSE THE CONNECTION ---
    $stmt->close();
    $conn->close();
} else {
    // If the page is accessed directly without submitting the form, redirect to the homepage.
    header("Location: index.html");
    exit();
}
?>