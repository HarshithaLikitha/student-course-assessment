<?php include 'db_connect.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Assessment Report - Course Assessment System</title>
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
                <h1>📄 Course Assessment Report</h1>
                <p>Comprehensive assessment report for academic documentation</p>
            </div>

            <div class="report-info">
                <div class="info-grid">
                    <div class="info-item">
                        <strong>Course:</strong> OOAD Lab (C312)
                    </div>
                    <div class="info-item">
                        <strong>Academic Year:</strong> 2024-2025
                    </div>
                    <div class="info-item">
                        <strong>Semester:</strong> III-I
                    </div>
                    <div class="info-item">
                        <strong>Branch:</strong> CSE
                    </div>
                </div>
            </div>

            <!-- Section 1: Final CO Attainment -->
            <div class="report-section">
                <h3>1. Final Course Outcome Attainment</h3>
                <div class="table-container">
                    <table class="simple-table">
                        <thead>
                            <tr>
                                <th>Course Outcome</th>
                                <th>Direct Assessment</th>
                                <th>Indirect Assessment</th>
                                <th>Final Attainment</th>
                                <th>Achievement Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Sample direct CO values
                            $direct = ["CO1"=>3.0, "CO2"=>2.9, "CO3"=>3.0, "CO4"=>2.8, "CO5"=>3.0];

                            // Indirect averages
                            $sql = "SELECT AVG(co1) as co1, AVG(co2) as co2, AVG(co3) as co3, AVG(co4) as co4, AVG(co5) as co5 FROM indirect_feedback";
                            $result = $conn->query($sql);
                            $ind = $result->fetch_assoc();

                            $final = [];

                            for ($i=1; $i<=5; $i++) {
                                $co = "CO$i";
                                $indirect = round($ind["co$i"], 2);
                                $final_score = round(($direct[$co] + $indirect) / 2, 2);
                                $final[$co] = $final_score;

                                $status = $final_score >= 2.5 ? 'Achieved' : ($final_score >= 2.0 ? 'Partially Achieved' : 'Not Achieved');
                                $status_class = $final_score >= 2.5 ? 'badge-success' : ($final_score >= 2.0 ? 'badge-warning' : 'badge-error');

                                echo "<tr>
                                        <td><strong>$co</strong></td>
                                        <td>{$direct[$co]}</td>
                                        <td>$indirect</td>
                                        <td><strong>$final_score</strong></td>
                                        <td><span class='badge $status_class'>$status</span></td>
                                      </tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Section 2: PO Attainment -->
            <div class="report-section">
                <h3>2. Program Outcome Attainment</h3>
                <div class="table-container">
                    <table class="simple-table">
                        <thead>
                            <tr>
                                <th>Program Outcome</th>
                                <th>Attainment Level</th>
                                <th>Achievement Status</th>
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

                            foreach ($po_scores as $po => $total) {
                                $avg = round($total / $po_counts[$po], 2);
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

            <div class="report-summary">
                <h3>📊 Assessment Summary</h3>
                <div class="summary-grid">
                    <div class="summary-item">
                        <span class="summary-label">Assessment Method</span>
                        <span class="summary-value">Direct + Indirect</span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">Threshold for Achievement</span>
                        <span class="summary-value">2.5 / 3.0</span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">Report Generated</span>
                        <span class="summary-value"><?php echo date('Y-m-d H:i:s'); ?></span>
                    </div>
                </div>
            </div>

            <div class="report-actions">
                <button onclick="window.print()" class="btn btn-primary">
                    🖨️ Print / Save as PDF
                </button>
                <a href="assessment_summary.php" class="btn btn-secondary">📄 View Summary</a>
            </div>
        </div>
    </main>
</body>
</html>

