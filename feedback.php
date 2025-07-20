<?php include 'db_connect.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Feedback - Course Assessment System</title>
    <link rel="stylesheet" href="assets/css/modern-style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <header class="header">
        <div class="header-content">
            <a href="index.php" class="logo">
                📚 Course Assessment
            </a>
            <a href="index.php" class="back-btn">← Back to Dashboard</a>
        </div>
    </header>

    <main class="main-content">
        <div class="simple-container">
            <div class="page-header">
                <h1>🗣️ Student Feedback</h1>
                <p>Provide your feedback on course outcome attainment</p>
            </div>

            <form method="POST" class="simple-form">
                <?php
                for ($i = 1; $i <= 5; $i++) {
                    echo "<div class='form-group'>
                            <label>How well do you think you attained CO$i?</label>
                            <select name='co$i' required class='form-select'>
                                <option value=''>--Select Level--</option>
                                <option value='1'>1 - Low (Below 50%)</option>
                                <option value='2'>2 - Medium (50-69%)</option>
                                <option value='3'>3 - High (70% and above)</option>
                            </select>
                          </div>";
                }
                ?>

                <button type="submit" name="submit" class="btn btn-primary">
                    Submit Feedback
                </button>
            </form>

            <?php
            if (isset($_POST['submit'])) {
                $co1 = $_POST['co1'];
                $co2 = $_POST['co2'];
                $co3 = $_POST['co3'];
                $co4 = $_POST['co4'];
                $co5 = $_POST['co5'];

                $sql = "INSERT INTO indirect_feedback (co1, co2, co3, co4, co5)
                        VALUES ('$co1', '$co2', '$co3', '$co4', '$co5')";

                if ($conn->query($sql) === TRUE) {
                    echo "<div class='alert alert-success'>
                            ✅ Feedback submitted successfully!
                          </div>";
                } else {
                    echo "<div class='alert alert-error'>
                            ❌ Error submitting feedback. Please try again.
                          </div>";
                }
            }
            ?>

            <div class="quick-actions">
                <h3>Quick Actions</h3>
                <div class="action-buttons">
                    <a href="display.php" class="btn btn-secondary">📋 View Records</a>
                    <a href="performance.php" class="btn btn-secondary">📊 Check Performance</a>
                </div>
            </div>
        </div>
    </main>
</body>
</html>

