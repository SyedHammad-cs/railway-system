<?php
$servername = "localhost";
$username = "root";
$password = "";
$port = 4306;
$dbname = "railway";

$conn = new mysqli($servername, $username, $password, $dbname, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $departure = $_POST['departure'];
    $arrival = $_POST['arrival'];
    $date = $_POST['date'];

    $sql = "SELECT train_number, train_name, origin, destination, date_of_journey, departure_time, 3A, 2A, 1A FROM searchtrain WHERE origin=? AND destination=? AND date_of_journey=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $departure, $arrival, $date);
    $stmt->execute();
    $result = $stmt->get_result();

    $trains = [];
    while ($row = $result->fetch_assoc()) {
        $trains[] = $row;
    }

    if (count($trains) > 0) {
        echo json_encode(['success' => true, 'trains' => $trains]);
    } else {
        echo json_encode(['success' => false]);
    }

    $stmt->close();
    $conn->close();
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Railway Reservation System</title>
    <style>

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid black;
            color: blue;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        .popup {
            display: none;
            position: fixed;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            padding: 10px;
            background-color: rgba(255, 255, 255, 0.95);
            box-shadow: 0 0 10px rgba(0,0,0,0.5);
            z-index: 1000; /* Ensures the popup is above other elements */

        }
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999; /* Ensures the overlay is behind the popup but above other elements */
            display: none;
        }
       .b{
        color: white;
       }
       
    </style>
    <link rel="stylesheet" href="st.css">
</head>
<body>
    <header>
    <p class="RRS">RRS</p>
        <nav>
            <ul>
                <li><a href="index.html#home">Home</a></li>
                <li><a href="index.html#about_us">About Us</a></li>
                <li><a href="index.html#holiday">Destinations</a></li>
                <li><a href="">Contact Us</a></li>
            </ul>
        </nav>
        <div class="account">
        <button class="btn"><a href="http://localhost/railway%20system/login.php">Login | Signup</a></button>
        </div>
    </header>

    <main>
        <div class="details">
            <h1>Destination Detail:</h1>
            <form class="booking-form" id="searchForm">
                <input type="text" id="departure" name="departure" placeholder="From">
                <input type="text" id="arrival" name="arrival" placeholder="To">
                <input type="date" id="date" name="date">
                <div class="buttons">
                    <button type="submit"><p class="b">Search</p></button>
                    <button  type="button"><p class="b">View Status</p></button>
                </div>
            </form>
        </div>

        <div id="popup" class="popup">
            <p>Train Details:</p>
            <table id="trainDetailsTable">
                <thead>
                    <tr>
                        <th>Train Number</th>
                        <th>Train Name</th>
                        <th>Origin</th>
                        <th>Destination</th>
                        <th>Journey Date</th>
                        <th>Departure Time</th>
                        <th>3A</th>
                        <th>2A</th>
                        <th>1A</th>
                        <th>Book Ticket</th>
                    </tr>
                </thead>
                <tbody id="trainDetails"></tbody>
            </table>
            <button onclick="closePopup()">Close</button>
        </div>
    </main>

    <footer style="margin-top: -4.5vw;">
        &copy; 2024 Indian Railways. All rights reserved.
    </footer>

    <script>
        document.getElementById('searchForm').addEventListener('submit', function(event) {
            event.preventDefault();
            const departure = document.getElementById('departure').value;
            const arrival = document.getElementById('arrival').value;
            const date = document.getElementById('date').value;

            const x = new XMLHttpRequest();
            x.open('POST', window.location.href, true);
            x.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            x.onload = function() {
                if (x.status == 200) {
                    const data = JSON.parse(x.responseText);

                    if (data.success) {
                        const tableBody = document.getElementById('trainDetails');
                        tableBody.innerHTML = '';
                        data.trains.forEach(train => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${train.train_number}</td>
                                <td>${train.train_name}</td>
                                <td>${train.origin}</td>
                                <td>${train.destination}</td>
                                <td>${train.date_of_journey}</td>
                                <td>${train.departure_time}</td>
                                <td>${train['3A']}</td>
                                <td>${train['2A']}</td>
                                <td>${train['1A']}</td>
                                <td><button onclick="bookTicket('${train.train_number}', '${train.date_of_journey}')">Book</button></td>
                            `;
                            tableBody.appendChild(row);
                        });
                        document.getElementById('popup').style.display = 'block';
                    } else {
                        alert('No trains found');
                    }
                } else {
                    console.error('Request failed. Returned status of ' + x.status);
                }
            };

            x.onerror = function() {
                console.error('Request failed.');
            };
            x.send(`departure=${departure}&arrival=${arrival}&date=${date}`);
        });

        function closePopup() {
            document.getElementById('popup').style.display = 'none';
        }

        function bookTicket(trainNumber, journeyDate) {
            alert(`Booking ticket for train number ${trainNumber} on ${journeyDate}`);
        }
    </script>
</body>
</html>




