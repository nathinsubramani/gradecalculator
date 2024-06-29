<?php
// Check if session is already started before calling session_start()
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['fname']) && isset($_POST['dept']) && isset($_POST['year'])) {
        // Store the names and other details in session variables
        $_SESSION['fname'] = htmlspecialchars($_POST['fname']);
        $_SESSION['dept'] = htmlspecialchars($_POST['dept']);
        $_SESSION['year'] = htmlspecialchars($_POST['year']);
    } elseif (isset($_POST['marks'])) {
        // Store the marks in session variables
        $_SESSION['marks'] = $_POST['marks'];  // This is an array of marks
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Student Grade Calculator</title>
    <link rel="stylesheet" type="text/css" href="index.css">
</head>
<body>
    <h1>Student Grade Calculator</h1>
    <p>This site is to calculate the student grade</p>

    <?php if (!isset($_SESSION['fname']) || !isset($_SESSION['dept']) || !isset($_SESSION['year'])): ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label for="fname">First name:</label>
            <input type="text" id="fname" name="fname" required>
            <label for="dept">Department:</label>
            <input type="text" id="dept" name="dept" required>
            <label for="year">Year of study:</label>
            <input type="number" id="year" name="year" required>
            <input type="submit" value="Submit Data">
        </form>
    <?php elseif (!isset($_SESSION['marks'])): ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <p>First Name: <?php echo $_SESSION['fname']; ?></p>
            <p>Department: <?php echo $_SESSION['dept']; ?></p>
            <p>Year of Study: <?php echo $_SESSION['year']; ?></p>
            <?php
            // Define an array of subjects
            $subjects = ['Subject 1', 'Subject 2', 'Subject 3', 'Subject 4', 'Subject 5'];
            for ($i = 0; $i < 5; $i++): ?>
                <label for="mark<?php echo $i + 1; ?>"><?php echo $subjects[$i]; ?>:</label>
                <input type="number" id="mark<?php echo $i + 1; ?>" name="marks[]" required>
            <?php endfor; ?>
            <input type="submit" value="Submit Marks">
        </form>
    <?php else: ?>
        <p>First Name: <?php echo $_SESSION['fname']; ?></p>
        <p>Department: <?php echo $_SESSION['dept']; ?></p>
        <p>Year of Study: <?php echo $_SESSION['year']; ?></p>
        <p>Marks:</p>
        <ul>
            <?php
            // Define an array of subjects
            $subjects = ['Subject 1', 'Subject 2', 'Subject 3', 'Subject 4', 'Subject 5'];
            $sum = 0;
            foreach ($_SESSION['marks'] as $index => $mark) {
                echo "<p>" . $subjects[$index] . ": Mark: " . htmlspecialchars($mark) . "</p>";
                $sum += $mark;  // Calculate the sum of marks
            }
            ?>
        </ul>
        <p>Sum of Marks: <?php echo $sum; ?></p>

        <?php
        // Calculate the average
        $count = count($_SESSION['marks']);  // Get the number of marks
        $average = $count > 0 ? $sum / $count : 0;  // Calculate the average
        ?>
        <p>Average Mark: <?php echo number_format($average, 2); ?></p>

        <?php
        // Determine the grade based on the average mark
        if ($average >= 90) {
            $grade = 'A';
        } elseif ($average >= 80) {
            $grade = 'B';
        } elseif ($average >= 70) {
            $grade = 'C';
        } elseif ($average >= 60) {
            $grade = 'D';
        } else {
            $grade = 'F';
        }
        ?>
        <p>Grade: <?php echo $grade; ?></p>

        <?php
        // Clear session data
        session_unset();
        session_destroy();
        ?>
        <a href="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="button-link">Submit Another Student</a>
    <?php endif; ?>
</body>
</html>
