<?php

require 'vendor/autoload.php';

use Google\Auth\Credentials\ServiceAccountCredentials;
use Google\Client;
use Google\Service\Sheets;

// Service account credentials
$serviceAccount = json_decode(file_get_contents('service-account.json'), true);

// Spreadsheet ID
$spreadsheetId = '1SwjYFD51tNJlKjN2spHlMzsRojH9cVXWwbCtfaPYy18';

// Initialize the Google Sheets API using service account credentials
$client = new Client();
$client->setApplicationName('test');
$client->setScopes([Sheets::SPREADSHEETS]);
$client->setAuthConfig($serviceAccount);

$service = new Sheets($client);

// Fetch data from the spreadsheet using Google Sheets API
$spreadsheet = $service->spreadsheets->get($spreadsheetId);
$sheet = $spreadsheet->getSheets()[0];
$range = $sheet->getProperties()->getTitle();

$response = $service->spreadsheets_values->get($spreadsheetId, $range);
$values = $response->getValues();

// Identify the column index for "Spreadsheet Access"
$columnIndexToHide = -1;
foreach ($values[0] as $index => $header) {
    if (strtolower($header) === 'spreadsheet access') {
        $columnIndexToHide = $index;
        break;
    }
}

// Display the content in an attractive HTML table with auto-adjusting column widths
echo '<style>
    tr:first-child {background-color: black; color: #fff; font-weight: 700; font-size: 16px;}
    table {
        border-collapse: collapse;
        width: auto;
        margin-top: 20px;
        display: flex;
        justify-content: center;
    }
    th, td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    th {background-color: #f2f2f2;}
    .run-scraper-btn {
        padding: 8px;
        background-color: #4caf50;
        color: #fff;
        border: none;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
    }
</style>';
echo '<table>';
foreach ($values as $rowIndex => $row) {
    echo '<tr>';
    foreach ($row as $colIndex => $value) {
        if ($colIndex !== $columnIndexToHide) {
            echo '<td>' . $value . '</td>';
        }
    }
    if ($rowIndex === 0) {
        echo '<td>Action</td>'; // Add "Action" in the first row of the button column
    } elseif ($rowIndex > 0) {
        echo '<td><button class="run-scraper-btn" onclick="runScraper()">Run Scraper</button></td>';
    }
    echo '</tr>';
}
echo '</table>';
?>

<script>
    function runScraper() {
        // Add your logic here to run the scraper when the button is clicked
        alert('Scraper is running!');
    }
</script>
