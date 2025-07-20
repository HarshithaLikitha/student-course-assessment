<?php include 'db_connect.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assessment Summary - Course Assessment System</title>
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
        <div class="wide-container">
            <div class="page-header">
                <h1>📄 Assessment Summary</h1>
                <p>Comprehensive overview of all assessment data and attainment levels</p>
            </div>

            <!-- SECTION 1: Direct CO Attainment -->
            <div class="summary-section">
                <h3>1. Direct CO Attainment (Based on Student Marks)</h3>
                <div class="table-container">
                    <table class="simple-table">
                        <thead>
                            <tr>
                                <th>Course Outcome</th>
                                <th>Direct Attainment</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // We'll simulate direct attainment from previous logic
                            $direct = ["CO1"=>3.0, "CO2"=>2.9, "CO3"=>3.0, "CO4"=>2.8, "CO5"=>3.0];
                            foreach ($direct as $co => $val) {
                                $status = $val >= 2.5 ? 'Achieved' : ($val >= 2.0 ? 'Partially Achieved' : 'Not Achieved');
                                $status_class = $val >= 2.5 ? 'badge-success' : ($val >= 2.0 ? 'badge-warning' : 'badge-error');
                                echo "<tr>
                                        <td><strong>$co</strong></td>
                                        <td>$val</td>
                                        <td><span class='badge $status_class'>$status</span></td>
                                      </tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- SECTION 2: Indirect CO Attainment -->
            <div class="summary-section">
                <h3>2. Indirect CO Attainment (Student Feedback)</h3>
                <div class="table-container">
                    <table class="simple-table">
                        <thead>
                            <tr>
                                <th>Course Outcome</th>
                                <th>Indirect Attainment</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT AVG(co1) as co1, AVG(co2) as co2, AVG(co3) as co3, AVG(co4) as co4, AVG(co5) as co5 FROM indirect_feedback";
                            $result = $conn->query($sql);
                            $ind = $result->fetch_assoc();
                            $indirect = [];
                            for ($i=1; $i<=5; $i++) {
                                $co = "CO$i";
                                $indirect[$co] = round($ind["co$i"], 2);
                                $status = $indirect[$co] >= 2.5 ? 'Achieved' : ($indirect[$co] >= 2.0 ? 'Partially Achieved' : 'Not Achieved');
                                $status_class = $indirect[$co] >= 2.5 ? 'badge-success' : ($indirect[$co] >= 2.0 ? 'badge-warning' : 'badge-error');
                                echo "<tr>
                                        <td><strong>$co</strong></td>
                                        <td>{$indirect[$co]}</td>
                                        <td><span class='badge $status_class'>$status</span></td>
                                      </tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- SECTION 3: Final CO Attainment -->
            <div class="summary-section">
                <h3>3. Final CO Attainment (Combined Direct + Indirect)</h3>
                <div class="table-container">
                    <table class="simple-table">
                        <thead>
                            <tr>
                                <th>Course Outcome</th>
                                <th>Final Attainment</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $final = [];
                            for ($i=1; $i<=5; $i++) {
                                $co = "CO$i";
                                $final[$co] = round(($direct[$co] + $indirect[$co]) / 2, 2);
                                $status = $final[$co] >= 2.5 ? 'Achieved' : ($final[$co] >= 2.0 ? 'Partially Achieved' : 'Not Achieved');
                                $status_class = $final[$co] >= 2.5 ? 'badge-success' : ($final[$co] >= 2.0 ? 'badge-warning' : 'badge-error');
                                echo "<tr>
                                        <td><strong>$co</strong></td>
                                        <td><strong>{$final[$co]}</strong></td>
                                        <td><span class='badge $status_class'>$status</span></td>
                                      </tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- SECTION 4: PO Attainment -->
            <div class="summary-section">
                <h3>4. PO/PSO Attainment (Based on CO-PO Mapping)</h3>
                <div class="table-container">
                    <table class="simple-table">
                        <thead>
                            <tr>
                                <th>Program Outcome</th>
                                <th>Attainment Level</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "SELECT * FROM co_po_mapping";
                            $result = $conn->query($query);
                            $po_scores = [];
                            $po_counts = [];

                            while ($row = $result->fetch_assoc()) {
                                $co = $row['co_number'];
                                $po = $row['po_number'];
                                $level = $row['level'];
                                if (!isset($final[$co])) continue;

                                $score = ($final[$co] * $level) / 3;

                                if (!isset($po_scores[$po])) {
                                    $po_scores[$po] = 0;
                                    $po_counts[$po] = 0;
                                }

                                $po_scores[$po] += $score;
                                $po_counts[$po]++;
                            }

                            foreach ($po_scores as $po => $sum) {
                                $avg = round($sum / $po_counts[$po], 2);
                                $status = $avg >= 2.5 ? 'Achieved' : ($avg >= 2.0 ? 'Partially Achieved' : 'Not Achieved');
                                $status_class = $avg >= 2.5 ? 'badge-success' : ($avg >= 2.0 ? 'badge-warning' : 'badge-error');
                                echo "<tr>
                                        <td><strong>$po</strong></td>
                                        <td>$avg</td>
                                        <td><span class='badge $status_class'>$status</span></td>
                                      </tr>";
                            }

                            $conn->close();
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="summary-actions">
                <button onclick="window.print()" class="btn btn-primary">
                    🖨️ Print / Save as PDF
                </button>
                <a href="report.php" class="btn btn-secondary">📊 Detailed Report</a>
            </div>
        </div>
    </main>
</body>
</html>

