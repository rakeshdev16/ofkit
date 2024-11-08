<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weekly Schedule</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .days {
            background: #095F59 !important;
            color: #ffff !important;
        }
        .schedule-table th, .schedule-table td {
            text-align: center;
            vertical-align: middle;
            cursor: pointer;
            position: relative;
        }
        .event {
            padding: 8px;
            border-radius: 5px;
            color: white;
            font-weight: bold;
            background: #1a9eb6
        }
        .selected {
            background-color: #1a9eb6 !important;
        }
        /* Tooltip Form Styling */
        .tooltip-form {
            position: absolute;
            top: -100%;
            left: 50%;
            transform: translateX(-50%);
            width: 200px;
            background-color: #fff;
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            z-index: 1000;
        }
        .tooltip-form:before {
            content: "";
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            border-width: 5px;
            border-style: solid;
            border-color: #ddd transparent transparent transparent;
        }

        .schedule-table {
            table-layout: fixed; /* Ensure equal cell widths */
        }

        .schedule-cell {
            position: relative;
            overflow: auto; /* Enables scrolling */
            max-height: 100px; /* Limit height for vertical scrolling */
        }

        .schedule-cell .event {
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .table-responsive {
            max-width: 100%;
            overflow-x: auto; /* Horizontal scrolling for the entire table */
        }

    </style>
</head>
<body>

    @php
        $days = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
        $times = ["08:00", "08:30", "09:00", "09:30", "10:00", "10:30", "11:00"]; // Add more times as needed

        // Sample data for events (associative array for simplicity)
        $events = [
            "Wednesday" => [
                "08:30" => ["title" => "Oral Reserve", "color" => "bg-light"],
                "09:00" => ["title" => "Eating Group", "color" => "bg-purple", "details" => "Location: Room 302, Bldg. A"],
            ],
            "Tuesday" => [
                "09:00" => ["title" => "Eating Group", "color" => "bg-green", "details" => "Location: Room 303, Bldg. B"],
            ],
            "Thursday" => [
                "09:00" => ["title" => "Eating Group", "color" => "bg-orange", "details" => "Location: Room 304, Bldg. C"],
            ]
        ];

        // Sample data for users working on each day
        $users = [
            "Monday" => ["John Doe", "Jane Smith"],
            "Tuesday" => ["Alice Johnson", "Bob Brown"],
            "Wednesday" => ["Charlie Davis"],
            "Thursday" => ["Emily White", "Frank Green"],
            "Friday" => ["George Harris"],
            // Add more as needed
        ];
    @endphp

<div class="container mt-5">
    <h2 class="text-center">Weekly Schedule</h2>

    <!-- Schedule Table -->
    <div class="table-responsive">
        <table class="table table-bordered schedule-table mt-4">
            <thead>
                <!-- Days of the Week Row -->
                <tr>
                    <th scope="col" class="days"></th>
                    @foreach ($days as $day)
                        @php
                            $colspan = isset($users[$day]) ? count($users[$day]) : 1;
                        @endphp
                        <th class="days" scope="col" colspan="{{ $colspan }}">{{ $day }}</th>
                    @endforeach
                </tr>

                <!-- Users Row -->
                <tr>
                    <th scope="col">Employees</th>
                    @foreach ($days as $day)
                        @if (isset($users[$day]))
                            @foreach ($users[$day] as $user)
                                <th>{{ $user }}</th>
                            @endforeach
                        @else
                            <th>-</th> <!-- Placeholder if no users are scheduled -->
                        @endif
                    @endforeach
                </tr>
            </thead>
            <tbody>
                <!-- Times and Events -->
                @foreach ($times as $time)
                    <tr>
                        <td>{{ $time }}</td>
                        @foreach ($days as $day)
                            @if (isset($users[$day]))
                                @foreach ($users[$day] as $user)
                                    <td data-day="{{ $day }}" data-time="{{ $time }}" class="schedule-cell position-relative">
                                        <!-- Display event data if available for this time and user day -->
                                        @if (isset($events[$day][$time]))
                                            @php
                                                $event = $events[$day][$time];
                                            @endphp
                                            <div class="event {{ $event['color'] }}">
                                                <span>{{ $event['title'] }}</span>
                                                @if (isset($event['details']))
                                                    <div class="tooltip-content">
                                                        <p><strong>{{ $event['title'] }}</strong></p>
                                                        <p>{{ $event['details'] }}</p>
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                    </td>
                                @endforeach
                            @else
                                <td class="position-relative"></td> <!-- Empty cell if no users -->
                            @endif
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Tooltip Form (Hidden initially) -->
<div class="tooltip-form d-none" id="tooltipForm">
    <form id="eventForm">
        <div class="mb-2">
            <label for="eventTitle" class="form-label">Title</label>
            <input type="text" class="form-control" id="eventTitle" required>
        </div>
        <div class="mb-2">
            <label for="eventDetails" class="form-label">Details</label>
            <input type="text" class="form-control" id="eventDetails">
        </div>
        <button type="submit" class="btn btn-primary btn-sm w-100">Add Event</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    let selectedCells = [];
    let startTime, endTime, day, tooltipCell;

    // Event listener for table cell selection
    document.querySelectorAll('.schedule-cell').forEach(cell => {
        cell.addEventListener('click', function () {   
            console.log(cell);         
            day = this.getAttribute('data-day');
            startTime = this.getAttribute('data-time');
            selectedCells.push(this);
            this.classList.add('selected');
            tooltipCell = this; // save the first cell to position the tooltip
            showTooltipForm();
        });
    });

    // Show tooltip form at the selected cell
    function showTooltipForm() {
        const tooltipForm = document.getElementById('tooltipForm');
        tooltipForm.classList.remove('d-none');

        // Get the position of the tooltip
        const tooltipLeft = tooltipCell.offsetLeft;
        const tooltipTop = tooltipCell.offsetTop - tooltipForm.offsetHeight;
        
        // Check if the tooltip fits on the right side, otherwise place it on the left side
        const windowWidth = window.innerWidth;
        const cellWidth = tooltipCell.offsetWidth;
        const tooltipWidth = tooltipForm.offsetWidth;
        const spaceOnRight = windowWidth - tooltipLeft - cellWidth;

        // Calculate where to place the tooltip
        if (spaceOnRight > tooltipWidth) {
            tooltipForm.style.top = `${tooltipTop}px`;
            tooltipForm.style.left = `${tooltipLeft + cellWidth / 2 - tooltipWidth / 2}px`; // Center above the cell
        } else {
            tooltipForm.style.top = `${tooltipTop}px`;
            tooltipForm.style.left = `${tooltipLeft - tooltipWidth / 2 + cellWidth / 2}px`; // Left side of the cell
        }
    }

    // Hide tooltip form and clear selection
    function hideTooltipForm() {
        const tooltipForm = document.getElementById('tooltipForm');
        tooltipForm.classList.add('d-none');
        selectedCells.forEach(cell => cell.classList.remove('selected'));
        selectedCells = [];
    }

    // Form submission for adding event
    document.getElementById('eventForm').addEventListener('submit', function (e) {
        e.preventDefault();
        
        const title = document.getElementById('eventTitle').value;
        const details = document.getElementById('eventDetails').value;

        // Display event on selected cells
        selectedCells.forEach(cell => {
            cell.innerHTML = `<div class="event bg-primary">${title}<br><small>${details}</small></div>`;
            cell.classList.remove('selected');
        });

        // Clear the form
        document.getElementById('eventTitle').value = '';
        document.getElementById('eventDetails').value = '';

        // Hide tooltip form
        hideTooltipForm();
    });

    // Close the tooltip form if clicked outside
    document.addEventListener('click', function (e) {
        const tooltipForm = document.getElementById('tooltipForm');
        if (!tooltipForm.contains(e.target) && !e.target.classList.contains('schedule-cell')) {
            hideTooltipForm();
        }
    });
</script>


</body>
</html>
