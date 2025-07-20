<?php include 'db_connect.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PO/PSO Attainment - Course Assessment System</title>
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
                <h1>📐 PO/PSO Attainment</h1>
                <p>Program Outcome and Program Specific Outcome attainment calculation</p>
            </div>

            <?php
            // Step 1: Final CO Attainment (hardcoded for now)
            $final_co_attainment = [
                "CO1" => 2.89,
                "CO2" => 2.78,
                "CO3" => 2.92,
                "CO4" => 2.70,
                "CO5" => 2.85
            ];

            // Step 2: Get CO-PO mappings
            $query = "SELECT * FROM co_po_mapping";
            $result = $conn->query($query);

            // Step 3: Collect contributions per PO/PSO
            $po_scores = []; // po_number => sum of weighted scores
            $po_counts = []; // po_number => count of COs contributing

            while ($row = $result->fetch_assoc()) {
                $co = $row['co_number'];
                $po = $row['po_number'];
                $level = $row['level']; // 1, 2, 3

                if (!isset($final_co_attainment[$co])) continue;

                $co_value = $final_co_attainment[$co];
                $weighted_score = ($co_value * $level) / 3;

                if (!isset($po_scores[$po])) {
                    $po_scores[$po] = 0;
                    $po_counts[$po] = 0;
                }

                $po_scores[$po] += $weighted_score;
                $po_counts[$po]++;
            }

            // Step 4: Display in table
            echo "<div class='table-container'>
                    <table class='simple-table'>
                        <thead>
                            <tr>
                                <th>Program Outcome</th>
                                <th>Attainment Level</th>
                                <th>Status</th>
                                <th>Contributing COs</th>
                            </tr>
                        </thead>
                        <tbody>";

            foreach ($po_scores as $po => $total) {
                $avg = round($total / $po_counts[$po], 2);
                $count = $po_counts[$po];
                
                // Determine status
                $status = '';
                $status_class = '';
                if ($avg >= 2.5) {
                    $status = 'Achieved';
                    $status_class = 'badge-success';
                } else if ($avg >= 2.0) {
                    $status = 'Partially Achieved';
                    $status_class = 'badge-warning';
                } else {
                    $status = 'Not Achieved';
                    $status_class = 'badge-error';
                }

                echo "<tr>
                        <td><strong>$po</strong></td>
                        <td><strong>$avg</strong></td>
                        <td><span class='badge $status_class'>$status</span></td>
                        <td>$count COs</td>
                      </tr>";
            }

            echo "</tbody></table></div>";

            $conn->close();
            ?>

            <div class="calculation-info">
                <h3>📊 Calculation Method</h3>
                <div class="info-grid">
                    <div class="info-item">
                        <strong>Formula:</strong> PO Attainment = Σ(CO Value × Mapping Level) / (3 × Number of Contributing COs)
                    </div>
                    <div class="info-item">
                        <strong>Threshold:</strong> 2.5 for Achievement, 2.0 for Partial Achievement
                    </div>
                </div>
            </div>

            <div class="quick-actions">
                <h3>Quick Actions</h3>
                <div class="action-buttons">
                    <a href="co_po_mapping.php" class="btn btn-secondary">🔗 Edit CO-PO Mapping</a>
                    <a href="assessment_summary.php" class="btn btn-secondary">📄 Assessment Summary</a>
                </div>
            </div>
        </div>
    </main>
</body>
</html>

