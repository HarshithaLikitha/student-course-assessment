<?php include 'db_connect.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CO-PO Mapping - Course Assessment System</title>
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
                <h1>🔗 CO-PO Mapping</h1>
                <p>Define the mapping between Course Outcomes and Program Outcomes</p>
            </div>

            <form method="POST" class="mapping-form">
                <div class="table-container">
                    <table class="mapping-table">
                        <thead>
                            <tr>
                                <th>Course Outcome</th>
                                <?php
                                    $po_headers = ["PO1", "PO2", "PO3", "PO4", "PO5", "PO6", "PO7", "PO8", "PO9", "PO10", "PSO1", "PSO2", "PSO3"];
                                    foreach ($po_headers as $header) {
                                        echo "<th>$header</th>";
                                    }
                                ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            for ($co = 1; $co <= 5; $co++) {
                                echo "<tr><td class='co-label'><strong>CO$co</strong></td>";
                                foreach ($po_headers as $po) {
                                    echo "<td>
                                            <select name='mapping[CO$co][$po]' class='mapping-select'>
                                                <option value='0'>0 - No Correlation</option>
                                                <option value='1'>1 - Low</option>
                                                <option value='2'>2 - Medium</option>
                                                <option value='3'>3 - High</option>
                                            </select>
                                          </td>";
                                }
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

                <div class="form-actions">
                    <button type="submit" name="submit" class="btn btn-primary">
                        Save Mapping
                    </button>
                </div>
            </form>

            <?php
            if (isset($_POST['submit'])) {
                $mapping = $_POST['mapping'];

                foreach ($mapping as $co => $po_data) {
                    foreach ($po_data as $po => $level) {
                        $stmt = $conn->prepare("INSERT INTO co_po_mapping (co_number, po_number, level) VALUES (?, ?, ?)");
                        $stmt->bind_param("ssi", $co, $po, $level);
                        $stmt->execute();
                    }
                }
                echo "<div class='alert alert-success'>
                        ✅ CO-PO mapping saved successfully!
                      </div>";
            }
            ?>

            <div class="mapping-guide">
                <h3>📋 Mapping Guidelines</h3>
                <div class="guide-grid">
                    <div class="guide-item">
                        <strong>0 - No Correlation:</strong> No relationship between CO and PO
                    </div>
                    <div class="guide-item">
                        <strong>1 - Low:</strong> Slight relationship between CO and PO
                    </div>
                    <div class="guide-item">
                        <strong>2 - Medium:</strong> Moderate relationship between CO and PO
                    </div>
                    <div class="guide-item">
                        <strong>3 - High:</strong> Strong relationship between CO and PO
                    </div>
                </div>
            </div>

            <div class="quick-actions">
                <h3>Quick Actions</h3>
                <div class="action-buttons">
                    <a href="po_attainment.php" class="btn btn-secondary">📐 View PO Attainment</a>
                    <a href="final_attainment.php" class="btn btn-secondary">🎯 Final CO Attainment</a>
                </div>
            </div>
        </div>
    </main>
</body>
</html>

