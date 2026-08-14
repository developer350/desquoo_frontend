<div class="eventCalendar">
    <div class="hdWrap">
        <a class="nav-btn" id="prev">
            <svg width="14" height="23" viewBox="0 0 14 23" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12.332 22.133L1.13203 11.733L12.332 1.33301" stroke="white" stroke-linecap="square" />
            </svg>
        </a>
        <div class="header-text">
            <div id="month-name"></div>
            <div id="todayDayName" class="visually-hidden"></div>
        </div>
        <a class="nav-btn" id="next">
            <svg width="14" height="23" viewBox="0 0 14 23" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M1.13281 1.33281L12.3328 11.7328L1.13281 22.1328" stroke="white" stroke-linecap="square" />
            </svg>
        </a>
    </div>
    <div id="calendar-table" class="calendar-cells">
        <div id="table-header">
            <div class="weekRow">
                <div class="weekCol">Mo</div>
                <div class="weekCol">Tu</div>
                <div class="weekCol">We</div>
                <div class="weekCol">Th</div>
                <div class="weekCol">Fr</div>
                <div class="weekCol">Sa</div>
                <div class="weekCol">Su</div>
            </div>
        </div>
        <div id="table-body" class=""></div>
    </div>
</div>
<div class="header-text visually-hidden">
    <div id="eventSelectedDate">Date</div>
    <div id="eventSelectedDay">Day</div>
</div>

@push('js')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // CUSTOM_DATEPICKER
            var calendar = document.getElementById("calendar-table");
            var gridTable = document.getElementById("table-body");
            var currentDate = new Date();
            var selectedDate = currentDate;
            var selectedDayBlock = null;
            var globalEventObj = {};
            var sidebar = document.getElementById("sidebar");

            function isPastDay(dayDate) {
                const today = new Date();
                today.setHours(0, 0, 0, 0);
                dayDate.setHours(0, 0, 0, 0);
                return dayDate < today;
            }

            function markPastDays() {
                const dayElements = document.querySelectorAll('.dayCol:not(.empty-day)');
                dayElements.forEach(dayElement => {
                    const dayDate = new Date(dayElement.getAttribute('data-date'));
                    if (isPastDay(dayDate)) {
                        dayElement.classList.add('pastDay');
                    } else {
                        dayElement.classList.remove('pastDay');
                    }
                });
            }

            function createCalendar(date, side) {
                var currentDate = new Date(date);
                var startDate = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);

                var monthTitle = document.getElementById("month-name");
                var monthName = currentDate.toLocaleString("en-US", {
                    month: "long"
                });
                var yearNum = currentDate.toLocaleString("en-US", {
                    year: "numeric"
                });
                monthTitle.innerHTML = `${monthName} ${yearNum}`;

                if (side == "left") {
                    gridTable.className = "animated fadeOutRight";
                } else {
                    gridTable.className = "animated fadeOutLeft";
                }

                setTimeout(() => {
                    gridTable.innerHTML = "";
                    var newTr = document.createElement("div");
                    newTr.className = "dayRow";
                    var currentTr = gridTable.appendChild(newTr);

                    for (let i = 1; i < (startDate.getDay() || 7); i++) {
                        let emptyDivCol = document.createElement("div");
                        emptyDivCol.className = "dayCol empty-day";
                        currentTr.appendChild(emptyDivCol);
                    }

                    var lastDay = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0)
                        .getDate();

                    for (let i = 1; i <= lastDay; i++) {
                        if (currentTr.children.length >= 7) {
                            currentTr = gridTable.appendChild(addNewRow());
                        }
                        let currentDay = document.createElement("div");
                        currentDay.className = "dayCol";
                        let dayDate = new Date(currentDate.getFullYear(), currentDate.getMonth(), i);
                        currentDay.setAttribute('data-date', dayDate.toDateString());

                        if (selectedDayBlock == null && i == new Date().getDate() ||
                            selectedDate.toDateString() == dayDate.toDateString()) {
                            selectedDate = dayDate;
                            document.getElementById("eventSelectedDate").innerHTML = selectedDate
                                .toLocaleString("en-US", {
                                    month: "long",
                                    day: "numeric",
                                    year: "numeric"
                                });
                            document.getElementById("eventSelectedDay").innerHTML = selectedDate
                                .toLocaleString("en-US", {
                                    weekday: "long",
                                });
                            selectedDayBlock = currentDay;
                            setTimeout(() => {
                                currentDay.classList.add("today");
                            }, 10);
                        }

                        currentDay.innerHTML = i;

                        if (globalEventObj[dayDate.toDateString()]) {
                            let eventMark = document.createElement("div");
                            eventMark.className = "day-mark";
                            currentDay.appendChild(eventMark);
                        }

                        currentTr.appendChild(currentDay);
                    }

                    for (let i = currentTr.getElementsByTagName("div").length; i < 7; i++) {
                        let emptyDivCol = document.createElement("div");
                        emptyDivCol.className = "dayCol empty-day";
                        currentTr.appendChild(emptyDivCol);
                    }

                    if (side == "left") {
                        gridTable.className = "animated fadeInLeft";
                    } else {
                        gridTable.className = "animated fadeInRight";
                    }

                    markPastDays();

                    function addNewRow() {
                        let node = document.createElement("div");
                        node.className = "dayRow";
                        return node;
                    }
                }, !side ? 0 : 270);
            }

            createCalendar(currentDate);

            var todayDayName = document.getElementById("todayDayName");
            todayDayName.innerHTML = "Today is " + currentDate.toLocaleString("en-US", {
                weekday: "long",
                day: "numeric",
                month: "short"
            });

            var prevButton = document.getElementById("prev");
            var nextButton = document.getElementById("next");

            prevButton.onclick = function changeMonthPrev() {
                currentDate = new Date(currentDate.getFullYear(), currentDate.getMonth() - 1);
                createCalendar(currentDate, "left");
            }

            nextButton.onclick = function changeMonthNext() {
                currentDate = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1);
                createCalendar(currentDate, "right");
            }

            function addEvent(title, desc) {
                if (!globalEventObj[selectedDate.toDateString()]) {
                    globalEventObj[selectedDate.toDateString()] = {};
                }
                globalEventObj[selectedDate.toDateString()][title] = desc;
            }

            gridTable.onclick = function(e) {
                if (!e.target.classList.contains("dayCol") || e.target.classList.contains("empty-day")) {
                    return;
                }

                if (selectedDayBlock) {
                    if (selectedDayBlock.classList.contains("selected")) {
                        selectedDayBlock.classList.remove("selected");
                    }
                }
                selectedDayBlock = e.target;
                selectedDayBlock.classList.add("selected");

                selectedDate = new Date(currentDate.getFullYear(), currentDate.getMonth(), parseInt(e.target
                    .innerHTML));

                document.getElementById("eventSelectedDate").innerHTML = selectedDate.toLocaleString("en-US", {
                    month: "long",
                    day: "numeric",
                    year: "numeric"
                });
                document.getElementById("eventSelectedDay").innerHTML = selectedDate.toLocaleString("en-US", {
                    weekday: "long",
                });
            }

        });
    </script>
@endpush
