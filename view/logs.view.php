
<body>
    <div class="logs-container">
        <div class="logs-header">
            <h1><span>Audit</span> Logs</h1>
            <div class="logs-header__button">
                <button class="back-button" onclick="window.location.href='/FinalProject/controllers/download-logs-pdf.php'">Download Reports</button>
                <button class="back-button" onclick="window.location.href='/FinalProject/'">← Back to Dashboard</button>
            </div>    
        </div>

        <div class="filters">
            <form method="GET" style="display: contents;">
                <div class="filter-group">
                    <label>Action</label>
                    <select name="action">
                        <option value="">All Actions</option>
                        <?php foreach ($actions as $action): ?>
                            <option value="<?= htmlspecialchars($action) ?>" <?= $actionFilter === $action ? 'selected' : '' ?>>
                                <?= htmlspecialchars($action) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="filter-group">
                    <label>Entity Type</label>
                    <select name="entity_type">
                        <option value="">All Entities</option>
                        <?php foreach ($entityTypes as $type): ?>
                            <option value="<?= htmlspecialchars($type) ?>" <?= $entityTypeFilter === $type ? 'selected' : '' ?>>
                                <?= htmlspecialchars($type) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="filter-buttons">
                    <button type="submit" class="filter-button filter-apply">Filter</button>
                    <a href="/FinalProject/viewlogs" class="filter-button filter-reset">Reset</a>
                </div>
            </form>
        </div>

        <div class="logs-content">
            <div class="stats">
                <div class="stat-card">
                    <h3>Total Logs</h3>
                    <div class="value"><?= number_format($totalLogs) ?></div>
                </div>
                <div class="stat-card">
                    <h3>Page <?= $page ?></h3>
                    <div class="value">of <?= $totalPages ?></div>
                </div>
            </div>

            <?php if (empty($logs)): ?>
                <div class="empty-state">
                    <p>📭 No logs found</p>
                    <small>Try adjusting your filters</small>
                </div>
            <?php else: ?>
                <table class="logs-table">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Action</th>
                            <th>Entity</th>
                            <th>ID</th>
                            <th>Timestamp</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($logs as $index => $log): ?>
                            <tr>
                                <td><?= htmlspecialchars($log['name'] ?? 'System') ?> <small style="color: #ccc;">(ID: <?= $log['user_id'] ?>)</small></td>
                                <td>
                                    <span class="action-badge action-<?= strtolower($log['action']) ?>">
                                        <?= htmlspecialchars($log['action']) ?>
                                    </span>
                                </td>
                                <td><span class="entity-badge"><?= htmlspecialchars($log['entity_type']) ?></span></td>
                                <td><?= $log['entity_id'] ? '#' . $log['entity_id'] : '—' ?></td>
                                <td>
                                    <div class="timestamp"><?= date('Y-m-d H:i:s', strtotime($log['created_at'])) ?></div>
                                </td>
                            </tr>
                            <?php if ($log['details']): ?>
                                <tr class="details-row" id="details-<?= $index ?>">
                                    <td colspan="6">
                                        <div class="details-content">
                                            <pre><?= htmlspecialchars(json_encode(json_decode($log['details'], true), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)) ?></pre>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <?php if ($totalPages > 1): ?>
                    <div class="pagination">
                        <?php if ($page > 1): ?>
                            <a href="?page=1<?= $actionFilter ? '&action=' . urlencode($actionFilter) : '' ?><?= $entityTypeFilter ? '&entity_type=' . urlencode($entityTypeFilter) : '' ?>">First</a>
                            <a href="?page=<?= $page - 1 ?><?= $actionFilter ? '&action=' . urlencode($actionFilter) : '' ?><?= $entityTypeFilter ? '&entity_type=' . urlencode($entityTypeFilter) : '' ?>">← Previous</a>
                        <?php else: ?>
                            <span class="disabled">First</span>
                            <span class="disabled">← Previous</span>
                        <?php endif; ?>

                        <?php for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++): ?>
                            <?php if ($i === $page): ?>
                                <span class="current"><?= $i ?></span>
                            <?php else: ?>
                                <a href="?page=<?= $i ?><?= $actionFilter ? '&action=' . urlencode($actionFilter) : '' ?><?= $entityTypeFilter ? '&entity_type=' . urlencode($entityTypeFilter) : '' ?>"><?= $i ?></a>
                            <?php endif; ?>
                        <?php endfor; ?>

                        <?php if ($page < $totalPages): ?>
                            <a href="?page=<?= $page + 1 ?><?= $actionFilter ? '&action=' . urlencode($actionFilter) : '' ?><?= $entityTypeFilter ? '&entity_type=' . urlencode($entityTypeFilter) : '' ?>">Next →</a>
                            <a href="?page=<?= $totalPages ?><?= $actionFilter ? '&action=' . urlencode($actionFilter) : '' ?><?= $entityTypeFilter ? '&entity_type=' . urlencode($entityTypeFilter) : '' ?>">Last</a>
                        <?php else: ?>
                            <span class="disabled">Next →</span>
                            <span class="disabled">Last</span>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

<script>
function downloadLogsPDF() {
    // Get the current HTML content
    const htmlContent = document.documentElement.outerHTML;
    
    // Send to PHP controller and open in new tab
    fetch('/FinalProject/controllers/download-logs-pdf.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: 'html=' + encodeURIComponent(htmlContent)
    })
    .then(res => res.blob())
    .then(blob => {
        // Open PDF in new tab
        const url = window.URL.createObjectURL(blob);
        window.open(url, '_blank');
    })
    .catch(err => console.error("Error:", err));
}
</script>
