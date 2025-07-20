<?php include 'db_connect.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CO Attainment Performance - Course Assessment System</title>
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
                <a href="display.php" class="btn btn-secondary">📋 View Records</a>
                <a href="index.php" class="btn btn-secondary">← Dashboard</a>
            </div>
        </div>
    </header>

    <!-- Main Container -->
    <div class="main-container">
        <!-- Page Header -->
        <div style="text-align: center; margin-bottom: 2rem; color: white;">
            <h1 style="font-size: 2.5rem; font-weight: 700; margin-bottom: 0.5rem;">📊 CO Attainment Analysis</h1>
            <p style="font-size: 1.125rem; opacity: 0.9;">Direct Assessment Performance Evaluation</p>
        </div>

        <?php
        $total_students = 0;
        $attained_internal = 0;
        $attained_external = 0;

        // Threshold values (63% of maximum marks)
        $internal_threshold = 15.75; // 63% of 25
        $external_threshold = 31.5;  // 63% of 50

        $sql = "SELECT * FROM students ORDER BY reg_no";
        $result = $conn->query($sql);
        $total_students = $result->num_rows;

        if ($total_students > 0) {
        ?>

        <!-- Performance Summary Cards -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
            <?php
            // Calculate statistics
            $above_internal = 0;
            $above_external = 0;
            $above_both = 0;
            $total_internal = 0;
            $total_external = 0;
            
            $result->data_seek(0); // Reset result pointer
            while ($row = $result->fetch_assoc()) {
                $total_internal += $row['lab_internal'];
                $total_external += $row['end_exam'];
                
                if ($row['lab_internal'] >= $internal_threshold) $above_internal++;
                if ($row['end_exam'] >= $external_threshold) $above_external++;
                if ($row['lab_internal'] >= $internal_threshold && $row['end_exam'] >= $external_threshold) $above_both++;
            }
            
            $avg_internal = round($total_internal / $total_students, 2);
            $avg_external = round($total_external / $total_students, 2);
            $percent_internal = round(($above_internal / $total_students) * 100, 2);
            $percent_external = round(($above_external / $total_students) * 100, 2);
            $percent_both = round(($above_both / $total_students) * 100, 2);
            ?>
            
            <div style="background: var(--bg-primary); padding: 2rem; border-radius: var(--radius-xl); box-shadow: var(--shadow-lg); text-align: center;">
                <div style="font-size: 2.5rem; margin-bottom: 0.5rem;">👥</div>
                <div style="font-size: 2rem; font-weight: 700; color: var(--primary-color); margin-bottom: 0.5rem;"><?php echo $total_students; ?></div>
                <div style="color: var(--text-secondary); font-weight: 500;">Total Students</div>
            </div>
            
            <div style="background: var(--bg-primary); padding: 2rem; border-radius: var(--radius-xl); box-shadow: var(--shadow-lg); text-align: center;">
                <div style="font-size: 2.5rem; margin-bottom: 0.5rem;">🧪</div>
                <div style="font-size: 2rem; font-weight: 700; color: var(--accent-color); margin-bottom: 0.5rem;"><?php echo $percent_internal; ?>%</div>
                <div style="color: var(--text-secondary); font-weight: 500;">Internal Attainment</div>
                <div style="font-size: 0.875rem; color: var(--text-light); margin-top: 0.25rem;">Avg: <?php echo $avg_internal; ?>/25</div>
            </div>
            
            <div style="background: var(--bg-primary); padding: 2rem; border-radius: var(--radius-xl); box-shadow: var(--shadow-lg); text-align: center;">
                <div style="font-size: 2.5rem; margin-bottom: 0.5rem;">📝</div>
                <div style="font-size: 2rem; font-weight: 700; color: var(--warning-color); margin-bottom: 0.5rem;"><?php echo $percent_external; ?>%</div>
                <div style="color: var(--text-secondary); font-weight: 500;">External Attainment</div>
                <div style="font-size: 0.875rem; color: var(--text-light); margin-top: 0.25rem;">Avg: <?php echo $avg_external; ?>/50</div>
            </div>
            
            <div style="background: var(--bg-primary); padding: 2rem; border-radius: var(--radius-xl); box-shadow: var(--shadow-lg); text-align: center;">
                <div style="font-size: 2.5rem; margin-bottom: 0.5rem;">🎯</div>
                <div style="font-size: 2rem; font-weight: 700; color: var(--success-color); margin-bottom: 0.5rem;"><?php echo $percent_both; ?>%</div>
                <div style="color: var(--text-secondary); font-weight: 500;">Overall Attainment</div>
                <div style="font-size: 0.875rem; color: var(--text-light); margin-top: 0.25rem;">Both Components</div>
            </div>
        </div>

        <!-- Detailed Performance Table -->
        <div class="table-container">
            <div class="table-header">
                <h2 class="table-title">Detailed Performance Analysis</h2>
                <div style="display: flex; gap: 1rem; align-items: center;">
                    <input type="text" 
                           id="searchInput" 
                           placeholder="Search students..." 
                           style="padding: 0.5rem 1rem; border: 1px solid var(--border-color); border-radius: var(--radius-md); min-width: 200px;">
                    <select id="filterSelect" style="padding: 0.5rem 1rem; border: 1px solid var(--border-color); border-radius: var(--radius-md);">
                        <option value="">All Students</option>
                        <option value="internal">Internal Attained</option>
                        <option value="external">External Attained</option>
                        <option value="both">Both Attained</option>
                        <option value="none">Below Threshold</option>
                    </select>
                </div>
            </div>

            <div class="table-responsive">
                <table class="data-table" id="performanceTable">
                    <thead>
                        <tr>
                            <th>Reg No</th>
                            <th>Student Name</th>
                            <th>Lab Internal</th>
                            <th>Internal Status</th>
                            <th>End Exam</th>
                            <th>External Status</th>
                            <th>Total Score</th>
                            <th>Overall Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $result->data_seek(0); // Reset result pointer
                        while ($row = $result->fetch_assoc()) {
                            $internal_status = $row['lab_internal'] >= $internal_threshold ? "Attained" : "Not Attained";
                            $external_status = $row['end_exam'] >= $external_threshold ? "Attained" : "Not Attained";
                            $overall_status = ($row['lab_internal'] >= $internal_threshold && $row['end_exam'] >= $external_threshold) ? "Attained" : "Not Attained";
                            
                            $internal_class = $internal_status === "Attained" ? "badge-success" : "badge-warning";
                            $external_class = $external_status === "Attained" ? "badge-success" : "badge-warning";
                            $overall_class = $overall_status === "Attained" ? "badge-success" : "badge-danger";
                            
                            $total_score = $row['lab_internal'] + $row['end_exam'];
                            $percentage = round(($total_score / 75) * 100, 1);
                            
                            $filter_class = '';
                            if ($internal_status === "Attained" && $external_status === "Attained") $filter_class = 'both';
                            elseif ($internal_status === "Attained") $filter_class = 'internal';
                            elseif ($external_status === "Attained") $filter_class = 'external';
                            else $filter_class = 'none';

                            echo "<tr data-filter='$filter_class'>
                                    <td><strong>{$row['reg_no']}</strong></td>
                                    <td>{$row['name']}</td>
                                    <td>{$row['lab_internal']}/25</td>
                                    <td><span class='badge $internal_class'>$internal_status</span></td>
                                    <td>{$row['end_exam']}/50</td>
                                    <td><span class='badge $external_class'>$external_status</span></td>
                                    <td><strong>$total_score/75 ($percentage%)</strong></td>
                                    <td><span class='badge $overall_class'>$overall_status</span></td>
                                  </tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- CO Attainment Levels -->
        <div class="table-container">
            <h2 class="table-title">Course Outcome Attainment Levels</h2>
            
            <?php
            function get_level($percent) {
                if ($percent >= 70) return 3;
                elseif ($percent >= 50) return 2;
                else return 1;
            }
            
            $internal_level = get_level($percent_internal);
            $external_level = get_level($percent_external);
            $overall_level = get_level($percent_both);
            ?>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
                <div style="background: var(--bg-secondary); padding: 2rem; border-radius: var(--radius-lg); border-left: 4px solid var(--accent-color);">
                    <h3 style="margin-bottom: 1rem; color: var(--text-primary);">🧪 Internal Assessment</h3>
                    <div style="font-size: 2rem; font-weight: 700; color: var(--accent-color); margin-bottom: 0.5rem;">
                        Level <?php echo $internal_level; ?>
                    </div>
                    <div style="color: var(--text-secondary); margin-bottom: 1rem;">
                        <?php echo $percent_internal; ?>% students attained (<?php echo $above_internal; ?>/<?php echo $total_students; ?>)
                    </div>
                    <div style="background: var(--bg-primary); padding: 1rem; border-radius: var(--radius-md);">
                        <div style="font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem;">Threshold: 15.75/25 (63%)</div>
                        <div style="background: var(--border-color); height: 8px; border-radius: 4px; overflow: hidden;">
                            <div style="background: var(--accent-color); height: 100%; width: <?php echo $percent_internal; ?>%; transition: width 0.5s ease;"></div>
                        </div>
                    </div>
                </div>
                
                <div style="background: var(--bg-secondary); padding: 2rem; border-radius: var(--radius-lg); border-left: 4px solid var(--warning-color);">
                    <h3 style="margin-bottom: 1rem; color: var(--text-primary);">📝 External Assessment</h3>
                    <div style="font-size: 2rem; font-weight: 700; color: var(--warning-color); margin-bottom: 0.5rem;">
                        Level <?php echo $external_level; ?>
                    </div>
                    <div style="color: var(--text-secondary); margin-bottom: 1rem;">
                        <?php echo $percent_external; ?>% students attained (<?php echo $above_external; ?>/<?php echo $total_students; ?>)
                    </div>
                    <div style="background: var(--bg-primary); padding: 1rem; border-radius: var(--radius-md);">
                        <div style="font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem;">Threshold: 31.5/50 (63%)</div>
                        <div style="background: var(--border-color); height: 8px; border-radius: 4px; overflow: hidden;">
                            <div style="background: var(--warning-color); height: 100%; width: <?php echo $percent_external; ?>%; transition: width 0.5s ease;"></div>
                        </div>
                    </div>
                </div>
                
                <div style="background: var(--bg-secondary); padding: 2rem; border-radius: var(--radius-lg); border-left: 4px solid var(--success-color);">
                    <h3 style="margin-bottom: 1rem; color: var(--text-primary);">🎯 Overall Attainment</h3>
                    <div style="font-size: 2rem; font-weight: 700; color: var(--success-color); margin-bottom: 0.5rem;">
                        Level <?php echo $overall_level; ?>
                    </div>
                    <div style="color: var(--text-secondary); margin-bottom: 1rem;">
                        <?php echo $percent_both; ?>% students attained both (<?php echo $above_both; ?>/<?php echo $total_students; ?>)
                    </div>
                    <div style="background: var(--bg-primary); padding: 1rem; border-radius: var(--radius-md);">
                        <div style="font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem;">Both components ≥ 63%</div>
                        <div style="background: var(--border-color); height: 8px; border-radius: 4px; overflow: hidden;">
                            <div style="background: var(--success-color); height: 100%; width: <?php echo $percent_both; ?>%; transition: width 0.5s ease;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php
        } else {
            echo "<div class='table-container' style='text-align: center; padding: 3rem;'>
                    <div style='font-size: 3rem; margin-bottom: 1rem;'>📊</div>
                    <h3>No Performance Data Available</h3>
                    <p style='color: var(--text-secondary); margin-bottom: 2rem;'>Add student records to view performance analysis.</p>
                    <a href='form.php' class='btn btn-primary'>➕ Add Student Data</a>
                  </div>";
        }
        $conn->close();
        ?>

        <!-- Action Buttons -->
        <div style="display: flex; gap: 1rem; justify-content: center; margin-top: 2rem; flex-wrap: wrap;">
            <a href="final_attainment.php" class="btn btn-primary">🎯 View Final Attainment</a>
            <a href="assessment_summary.php" class="btn btn-secondary">📄 Generate Report</a>
            <button onclick="exportPerformanceData()" class="btn btn-secondary">📥 Export Analysis</button>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <p>Course Assessment System • Performance Analysis Dashboard</p>
    </footer>

    <script src="assets/js/modern-interactions.js"></script>
    <script>
        // Search and Filter Functionality
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const filterSelect = document.getElementById('filterSelect');
            const table = document.getElementById('performanceTable');
            const rows = table ? table.querySelectorAll('tbody tr') : [];

            function filterTable() {
                const searchTerm = searchInput.value.toLowerCase();
                const filterValue = filterSelect.value;

                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    const filterClass = row.getAttribute('data-filter');
                    
                    let showRow = text.includes(searchTerm);
                    
                    if (showRow && filterValue) {
                        showRow = filterClass === filterValue;
                    }
                    
                    row.style.display = showRow ? '' : 'none';
                });
                
                updateVisibleCount();
            }

            function updateVisibleCount() {
                const visibleRows = Array.from(rows).filter(row => row.style.display !== 'none');
                console.log(`Showing ${visibleRows.length} of ${rows.length} students`);
            }

            if (searchInput) searchInput.addEventListener('input', filterTable);
            if (filterSelect) filterSelect.addEventListener('change', filterTable);
        });

        // Export Performance Data
        function exportPerformanceData() {
            const table = document.getElementById('performanceTable');
            if (!table) return;

            let csv = [];
            const rows = table.querySelectorAll('tr');

            for (let i = 0; i < rows.length; i++) {
                const row = [], cols = rows[i].querySelectorAll('td, th');
                
                for (let j = 0; j < cols.length; j++) {
                    let cellText = cols[j].innerText.replace(/"/g, '""');
                    row.push('"' + cellText + '"');
                }
                
                csv.push(row.join(','));
            }

            const csvFile = new Blob([csv.join('\n')], { type: 'text/csv' });
            const downloadLink = document.createElement('a');
            downloadLink.download = 'co_attainment_analysis.csv';
            downloadLink.href = window.URL.createObjectURL(csvFile);
            downloadLink.style.display = 'none';
            document.body.appendChild(downloadLink);
            downloadLink.click();
            document.body.removeChild(downloadLink);
        }

        // Animate progress bars on load
        window.addEventListener('load', function() {
            const progressBars = document.querySelectorAll('[style*="width:"]');
            progressBars.forEach(bar => {
                const width = bar.style.width;
                bar.style.width = '0%';
                setTimeout(() => {
                    bar.style.width = width;
                }, 500);
            });
        });
    </script>
</body>
</html>

