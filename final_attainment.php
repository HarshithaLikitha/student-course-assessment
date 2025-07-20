<?php include 'db_connect.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Final CO Attainment - Course Assessment System</title>
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
                <h1>🎯 Final CO Attainment</h1>
                <p>Combined direct and indirect assessment results</p>
            </div>

            <?php
            // STEP 1: Hardcoded Direct CO Levels (Replace with DB fetch later)
            $direct_attainment = [
                "CO1" => 3.0,
                "CO2" => 2.9,
                "CO3" => 3.0,
                "CO4" => 2.8,
                "CO5" => 3.0
            ];

            // STEP 2: Calculate Indirect CO Averages
            $sql = "SELECT AVG(co1) AS co1, AVG(co2) AS co2, AVG(co3) AS co3, AVG(co4) AS co4, AVG(co5) AS co5 FROM indirect_feedback";
            $result = $conn->query($sql);
            $indirect = $result->fetch_assoc();

            // STEP 3: Calculate Final Attainment
            echo "<div class='table-container'>
                    <table class='simple-table'>
                        <thead>
                            <tr>
                                <th>Course Outcome</th>
                                <th>Direct Assessment</th>
                                <th>Indirect Assessment</th>
                                <th>Final Attainment</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>";

            for ($i = 1; $i <= 5; $i++) {
                $co = "CO$i";
                $direct = $direct_attainment[$co];
                $indirect_val = round($indirect["co$i"], 2);
                $final = round(($direct + $indirect_val) / 2, 2);
                
                // Determine status
                $status = '';
                $status_class = '';
                if ($final >= 2.5) {
                    $status = 'Achieved';
                    $status_class = 'badge-success';
                } else if ($final >= 2.0) {
                    $status = 'Partially Achieved';
                    $status_class = 'badge-warning';
                } else {
                    $status = 'Not Achieved';
                    $status_class = 'badge-error';
                }

                echo "<tr>
                        <td><strong>$co</strong></td>
                        <td>$direct</td>
                        <td>$indirect_val</td>
                        <td><strong>$final</strong></td>
                        <td><span class='badge $status_class'>$status</span></td>
                      </tr>";
            }

            echo "</tbody></table></div>";

            $conn->close();
            ?>

            <div class="summary-stats">
                <h3>Assessment Summary</h3>
                <div class="stats-grid">
                    <div class="stat-item">
                        <span class="stat-label">Assessment Method</span>
                        <span class="stat-value">Combined (Direct + Indirect)</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Threshold</span>
                        <span class="stat-value">2.5 (Minimum for Achievement)</span>
                    </div>
                </div>
            </div>

            <div class="quick-actions">
                <h3>Quick Actions</h3>
                <div class="action-buttons">
                    <a href="performance.php" class="btn btn-secondary">📊 View Performance</a>
                    <a href="report.php" class="btn btn-secondary">📄 Generate Report</a>
                </div>
            </div>
        </div>
    </main>
</body>
</html>

