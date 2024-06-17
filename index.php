<?php
require_once("connectPSQL.php"); // Database connection using PDO for PostgreSQL - Returns 'pdo' object
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Data Viewer</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Customer Data Viewer</h1>
        <div class="form-group">
            <label for="apiKey">Company:</label>
            <select id="apiKey" name="apiKey">
                <?php
                    // SQL query to get all Vendors and API Keys for Testing.
                    $vendorQuery = 'SELECT a.api_key, v.name FROM vendors v 
                                    INNER JOIN api_keys a ON a.vendor_id = v.id';
                    $vendorStmt = $pdo->query($vendorQuery);
                    $vendors = $vendorStmt->fetchAll();

                    // Loop through results and build Option boxes.
                    foreach ($vendors as $vendor) {
                        echo '<option value="' . htmlspecialchars($vendor['api_key']) . '">'
                            . htmlspecialchars($vendor['name'])
                            . '</option>';
                    }
                // Add an invalid key for testing
                ?>
                <option value="invalid_key">Invalid Key</option>
            </select>
            <br/>

            <label for="county">County:</label>
            <select id="county" name="county">
                <option value="0">Any</option>
                <?php
                    // SQL query to get all Counties.
                    $countyQuery = 'SELECT DISTINCT county FROM applicants';
                    $countyStmt = $pdo->query($countyQuery);
                    $counties = $countyStmt->fetchAll();

                    // Loop through results and build Option boxes.
                    foreach ($counties as $county) {
                        echo '<option value="' . htmlspecialchars($county['county']) . '">'
                            . htmlspecialchars($county['county'])
                            . '</option>';
                    }
                ?>
            </select>
            <br/>

            <label for="appliedFor">Applied For:</label>
            <select id="appliedFor" name="appliedFor">
                <option value="0">Any</option>
                <?php
                    // SQL query to get all Jobs.
                    $jobQuery = 'SELECT DISTINCT applied_for FROM applicants';
                    $jobStmt = $pdo->query($jobQuery);
                    $jobs = $jobStmt->fetchAll();

                    // Loop through results and build Option boxes.
                    foreach ($jobs as $job) {
                        echo '<option value="' . htmlspecialchars($job['applied_for']) . '">'
                            . htmlspecialchars($job['applied_for'])
                            . '</option>';
                    }
                ?>
            </select>
            <br/>

            <label for="dbsRequired">DBS Required:</label>
            <select id="dbsRequired" name="dbsRequired">
                <option value="0">Any</option>
                <option value="True">True</option>
                <option value="False">False</option>
            </select>
        </div>
        <button id="fetchButton">Fetch Applicants</button>
        <div class="results" id="results"></div>
    </div>
    <script>
        document.getElementById('fetchButton').addEventListener('click', function() {
            // Get Options Chosen from the form elements
            const apiKey = document.getElementById('apiKey').value;
            const county = document.getElementById('county').value;
            const appliedFor = document.getElementById('appliedFor').value;
            const dbsRequired = document.getElementById('dbsRequired').value;
            
            // Build URL for Fetch using chosen options
            const targetUrl = 'api.php';
            let params = new URLSearchParams();
            params.append('API-KEY', apiKey);
            params.append('county', county);
            params.append('appliedFor', appliedFor);
            params.append('dbsRequired', dbsRequired);

            // Define div to display results and show the Query Params sent to the API
            const results = document.getElementById('results');
            results.innerHTML = `<p><small>Params: ${params}</small></p>`;
            
            // Fetch Data and Display Results
            fetch(`${targetUrl}?${params}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network Error: ' + response.statusText);
                }
                return response.json();
            })
            .then(data => {
                
                if (data.error) {
                    results.innerHTML += `<p>${data.error}</p>`;
                } else {
                    results.innerHTML += `<p>Showing <b>${data.length}</b> Results</p>`;
                    data.forEach(customer => {
                        const customerDiv = document.createElement('div');
                        customerDiv.className = 'customer';
                        customerDiv.innerHTML = `
                            <p><strong>Name:</strong> ${customer.name}</p>
                            <p><strong>Email:</strong> ${customer.email}</p>
                            <p><strong>County:</strong> ${customer.county}</p>
                            <p><strong>Company ID:</strong> ${customer.vendor_id}</p>
                            <p><strong>Applied For:</strong> ${customer.applied_for}</p>
                            <p><strong>DBS Required?</strong> ${customer.require_dbs_check}</p>
                            <p><a download href=''>Download CV</a></p>
                        `;
                        results.appendChild(customerDiv);
                    });
                }
            })
            .catch(error => {
                results.innerHTML = `<p>${error.message}</p>`;
            });
        });
    </script>
</body>
</html>