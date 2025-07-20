<?php include 'db_connect.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Records - Course Assessment System</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/modern-style.css">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="header-content">
            <a href="index.php" class="logo">
                <div class="logo-icon">📚</div>
                <span>Course Assessment</span>
            </a>
            <div style="display: flex; gap: 1rem; align-items: center;">
                <a href="form.php" class="btn btn-primary">➕ Add Student</a>
                <a href="index.php" class="btn btn-secondary">← Dashboard</a>
            </div>
        </div>
    </header>

    <!-- Main Container -->
    <div class="main-container">
        <div class="table-container">
            <div class="table-header">
                <h2 class="table-title">📋 Student Records</h2>
                <div style="display: flex; gap: 1rem; align-items: center;">
                    <input type="text" 
                           id="searchInput" 
                           placeholder="Search students..." 
                           style="padding: 0.5rem 1rem; border: 1px solid var(--border-color); border-radius: var(--radius-md); min-width: 200px;">
                    <select id="filterSelect" style="padding: 0.5rem 1rem; border: 1px solid var(--border-color); border-radius: var(--radius-md);">
                        <option value="">All Students</option>
                        <option value="above">Above Threshold (≥63%)</option>
                        <option value="below">Below Threshold (<63%)</option>
                    </select>
                </div>
            </div>

            <?php
            $sql = "SELECT * FROM students ORDER BY reg_no";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                echo "<div style='margin-bottom: 1rem; color: var(--text-secondary);'>
                        Total Records: <strong>{$result->num_rows}</strong> students
                      </div>";
                
                echo "<div class='table-responsive'>
                        <table class='data-table' id='studentsTable'>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Register Number</th>
                                    <th>Student Name</th>
                                    <th>Lab Internal (25)</th>
                                    <th>End Exam (50)</th>
                                    <th>Total (75)</th>
                                    <th>Percentage</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>";
                
                while($row = $result->fetch_assoc()) {
                    $total = $row['lab_internal'] + $row['end_exam'];
                    $percentage = round(($total / 75) * 100, 2);
                    $status = $percentage >= 63 ? 'Pass' : 'Below Threshold';
                    $statusClass = $percentage >= 63 ? 'badge-success' : 'badge-warning';
                    
                    echo "<tr data-percentage='$percentage'>
                            <td>{$row['id']}</td>
                            <td><strong>{$row['reg_no']}</strong></td>
                            <td>{$row['name']}</td>
                            <td>{$row['lab_internal']}</td>
                            <td>{$row['end_exam']}</td>
                            <td><strong>$total</strong></td>
                            <td>$percentage%</td>
                            <td><span class='badge $statusClass'>$status</span></td>
                            <td>
                                <button onclick='editStudent({$row['id']})' class='btn-small btn-secondary' style='padding: 0.25rem 0.5rem; font-size: 0.75rem; margin-right: 0.25rem;'>✏️ Edit</button>
                                <button onclick='deleteStudent({$row['id']}, \"{$row['reg_no']}\")' class='btn-small btn-danger' style='padding: 0.25rem 0.5rem; font-size: 0.75rem; background: var(--danger-color); color: white; border: none; border-radius: var(--radius-sm);'>🗑️ Delete</button>
                            </td>
                          </tr>";
                }
                echo "</tbody></table></div>";
                
                // Statistics Summary
                $stats_sql = "SELECT 
                    COUNT(*) as total_students,
                    AVG(lab_internal + end_exam) as avg_total,
                    MAX(lab_internal + end_exam) as max_total,
                    MIN(lab_internal + end_exam) as min_total,
                    SUM(CASE WHEN (lab_internal + end_exam) >= 47.25 THEN 1 ELSE 0 END) as above_threshold
                FROM students";
                $stats_result = $conn->query($stats_sql);
                $stats = $stats_result->fetch_assoc();
                
                $pass_percentage = $stats['total_students'] > 0 ? round(($stats['above_threshold'] / $stats['total_students']) * 100, 2) : 0;
                
                echo "<div style='margin-top: 2rem; display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;'>
                        <div style='background: var(--bg-secondary); padding: 1.5rem; border-radius: var(--radius-lg); text-align: center;'>
                            <div style='font-size: 1.5rem; font-weight: 700; color: var(--primary-color);'>{$stats['total_students']}</div>
                            <div style='color: var(--text-secondary);'>Total Students</div>
                        </div>
                        <div style='background: var(--bg-secondary); padding: 1.5rem; border-radius: var(--radius-lg); text-align: center;'>
                            <div style='font-size: 1.5rem; font-weight: 700; color: var(--accent-color);'>" . round($stats['avg_total'], 1) . "</div>
                            <div style='color: var(--text-secondary);'>Average Score</div>
                        </div>
                        <div style='background: var(--bg-secondary); padding: 1.5rem; border-radius: var(--radius-lg); text-align: center;'>
                            <div style='font-size: 1.5rem; font-weight: 700; color: var(--success-color);'>{$stats['max_total']}</div>
                            <div style='color: var(--text-secondary);'>Highest Score</div>
                        </div>
                        <div style='background: var(--bg-secondary); padding: 1.5rem; border-radius: var(--radius-lg); text-align: center;'>
                            <div style='font-size: 1.5rem; font-weight: 700; color: var(--warning-color);'>$pass_percentage%</div>
                            <div style='color: var(--text-secondary);'>Above Threshold</div>
                        </div>
                      </div>";
                
            } else {
                echo "<div style='text-align: center; padding: 3rem; color: var(--text-secondary);'>
                        <div style='font-size: 3rem; margin-bottom: 1rem;'>📝</div>
                        <h3>No Student Records Found</h3>
                        <p>Start by adding some student data to see records here.</p>
                        <a href='form.php' class='btn btn-primary' style='margin-top: 1rem;'>➕ Add First Student</a>
                      </div>";
            }
            $conn->close();
            ?>
        </div>

        <!-- Quick Actions -->
        <div style="display: flex; gap: 1rem; justify-content: center; margin-top: 2rem; flex-wrap: wrap;">
            <a href="performance.php" class="btn btn-primary">📊 View Performance Analysis</a>
            <a href="assessment_summary.php" class="btn btn-secondary">📄 Generate Report</a>
            <button onclick="exportToCSV()" class="btn btn-secondary">📥 Export Data</button>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <p>Course Assessment System • Student Records Management</p>
    </footer>

    <script src="assets/js/modern-interactions.js"></script>
    <script>
        // Search and Filter Functionality
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const filterSelect = document.getElementById('filterSelect');
            const table = document.getElementById('studentsTable');
            const rows = table ? table.querySelectorAll('tbody tr') : [];

            function filterTable() {
                const searchTerm = searchInput.value.toLowerCase();
                const filterValue = filterSelect.value;

                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    const percentage = parseFloat(row.getAttribute('data-percentage'));
                    
                    let showRow = text.includes(searchTerm);
                    
                    if (showRow && filterValue) {
                        if (filterValue === 'above' && percentage < 63) showRow = false;
                        if (filterValue === 'below' && percentage >= 63) showRow = false;
                    }
                    
                    row.style.display = showRow ? '' : 'none';
                });
                
                updateVisibleCount();
            }

            function updateVisibleCount() {
                const visibleRows = Array.from(rows).filter(row => row.style.display !== 'none');
                const countElement = document.querySelector('.table-header + div');
                if (countElement) {
                    countElement.innerHTML = `Showing: <strong>${visibleRows.length}</strong> of <strong>${rows.length}</strong> students`;
                }
            }

            if (searchInput) searchInput.addEventListener('input', filterTable);
            if (filterSelect) filterSelect.addEventListener('change', filterTable);
        });

        // Student Management Functions
        function editStudent(id) {
            // Simple edit functionality - in a real app, this would open a modal
            const newName = prompt('Enter new name (leave empty to cancel):');
            if (newName && newName.trim()) {
                // Here you would typically send an AJAX request to update the database
                alert('Edit functionality would be implemented with AJAX in a full application');
            }
        }

        function deleteStudent(id, regNo) {
            if (confirm(`Are you sure you want to delete student ${regNo}?`)) {
                // Here you would typically send an AJAX request to delete from database
                alert('Delete functionality would be implemented with AJAX in a full application');
            }
        }

        // Export to CSV
        function exportToCSV() {
            const table = document.getElementById('studentsTable');
            if (!table) return;

            let csv = [];
            const rows = table.querySelectorAll('tr');

            for (let i = 0; i < rows.length; i++) {
                const row = [], cols = rows[i].querySelectorAll('td, th');
                
                for (let j = 0; j < cols.length - 1; j++) { // Exclude actions column
                    let cellText = cols[j].innerText.replace(/"/g, '""');
                    row.push('"' + cellText + '"');
                }
                
                csv.push(row.join(','));
            }

            const csvFile = new Blob([csv.join('\n')], { type: 'text/csv' });
            const downloadLink = document.createElement('a');
            downloadLink.download = 'student_records.csv';
            downloadLink.href = window.URL.createObjectURL(csvFile);
            downloadLink.style.display = 'none';
            document.body.appendChild(downloadLink);
            downloadLink.click();
            document.body.removeChild(downloadLink);
        }

        // Add some additional styling for small buttons
        const style = document.createElement('style');
        style.textContent = `
            .btn-small {
                padding: 0.25rem 0.5rem;
                font-size: 0.75rem;
                border-radius: var(--radius-sm);
                border: none;
                cursor: pointer;
                transition: all 0.2s ease;
            }
            
            .btn-danger:hover {
                background: #dc2626 !important;
                transform: translateY(-1px);
            }
            
            .table-responsive {
                overflow-x: auto;
                margin: 1rem 0;
            }
            
            @media (max-width: 768px) {
                .data-table {
                    font-size: 0.75rem;
                }
                
                .data-table th,
                .data-table td {
                    padding: 0.5rem 0.25rem;
                }
                
                .btn-small {
                    padding: 0.125rem 0.25rem;
                    font-size: 0.625rem;
                }
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>

