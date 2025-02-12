<script>
    let availableTime = null;
    fetch("{{ route('get-therapist-time') }}?kindergarten_id="+getQueryParam('kindergarten_id')).then(response => response.json()).then(data => {
        availableTime = data;
    });
    function filterCalendar(params = {}) {
        var url = "{{ $filterRoute }}";
        let scrollingPosition = 0;
        var paramLength = Object.keys(params).length;        
        if (paramLength > 0) {
            url = url+"?"+queryParam(params);
        }
        fetch(url).then((response) => response.json()).then((data) => {
            $('#childrenFilter').html(data.childrens);
            $('#staffFilter').html(data.users);
            calendar(data.calenderEvents, data.calenderHeader);
            $(window).scrollTop(scrollingPosition);
            setTimeout(() => {
                setCalendar();
                // $('#export').on('click', function() {
                //     $(this).attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');
                //     let div = $('#scheduleCalendar')[0];
                //     let targetElement = $('.calendar_default_scroll > div > div:nth-of-type(2)')[0];
                //     html2canvas(div, {
                //         useCORS: true,
                //         scrollX: -window.scrollX,
                //         scrollY: -window.scrollY,
                //         allowTaint: true,
                //         logging: true,
                //         width: targetElement.scrollWidth + 30,
                //         height: targetElement.scrollHeight + 110,
                //         windowWidth: targetElement.scrollWidth + 60,
                //         windowHeight: targetElement.scrollHeight,
                //     }).then(function(canvas) {
                //         $('#output').empty();
                //         let containerWidth = $('#output').width();
                //         let aspectRatio = canvas.width / canvas.height;
                //         let newWidth = containerWidth;
                //         let newHeight = newWidth / aspectRatio;
                //         let resizedCanvas = document.createElement('canvas');
                //         let ctx = resizedCanvas.getContext('2d');
                //         resizedCanvas.width = newWidth;
                //         resizedCanvas.height = newHeight;
                //         ctx.drawImage(canvas, 0, 0, canvas.width, canvas.height, 0, 0, newWidth, newHeight);
                //         $('#output')[0].appendChild(resizedCanvas);
                //         $('#export').attr('disabled', false).html('Export');
                //         $('#exportBtns').show();
                //         $('html, body').animate({
                //             scrollTop: $("#output").offset().top
                //         }, 500);
                //     });
                // });

            }, 1500);
        });
    }

    function calendar(events = '', list) {
        let windowWidth = window.screen.width;
        let columsCount = list.map((item) => (Array.isArray(item.children) ? item.children.length : 1)).reduce((sum, count) => sum + count, 0);
        let columnWidth = 100;
        if (columsCount == 0 || columsCount <= 7) {
            columnWidth = (windowWidth-100)/7;
        } else if (columsCount*100 < windowWidth) {
            columnWidth = (windowWidth-100)/columsCount;
        }
        let headerLevel = "{{ in_array(Route::currentRouteName() , ['children-schedule.index', 'therapy-schedule.index']) }}" ? "1" : "4";
        if (window.dp) {
            window.dp.dispose();
        }
        var type = "{{ $type }}";
        window.dp = new DayPilot.Calendar("scheduleCalendar", {
            rtl: true,
            startDate: DayPilot.Date.today(),
            viewType: "Resources",
            columnWidthSpec: "Fixed",
            headerLevels: headerLevel,
            headerLevelHeights: [ 40, 40 ],
            heightSpec: "BusinessHoursNoScroll",
            height: 500,
            columnWidth: columnWidth,
            businessBeginsHour: 7,
            businessEndsHour: 17,
            timeHeaderCellDuration: 15,
            cellDuration: 15,
            eventMoveHandling: "Disabled",
            eventResizeHandling: "Disabled",
            events: events,
            columns: list.map(column => {
                return {
                    id: column.id,
                    name: `<span class="days-header">${column.name}</span>`,
                    children: Array.isArray(column.children) ? column.children.map(child => ({
                        id: child.id,
                        name: `<span class="team-header">${child.first_name ?? '-'} ${child.family_name ?? '-'}</span>`,
                        children: {
                            id: child.id,
                            name: child.profession ?? '-',
                            children: {
                                id: child.id,
                                name: child.association ?? '-',
                            }
                        }
                    })) : undefined,
                };
            }),
            onBeforeTimeHeaderRender: function (args) {
                var hour = DayPilot.Date.today().addTime(args.header.time);
                args.header.html = hour.toString("HH:mm");
            },
            onTimeRangeSelected: async args => {
                if (type == 'view') {
                    dp.clearSelection();
                } else {
                    if (args.resource == '' || args.resource == undefined || args.resource == null) {
                        toastr.error("The chosen resource dosen't have any user");
                        return true;
                    }
                    const resource = args.resource.match(/^(\d+)([a-zA-Z]+)$/);
                    Object.keys(eventData).forEach(key => delete eventData[key]);
                    eventData.day = resource[2].charAt(0).toUpperCase() + resource[2].slice(1);
                    eventData.resource = args.resource;
                    eventData.startTime = args.start.value.split("T")[1].slice(0, 8);
                    eventData.endTime = args.end.value.split("T")[1].slice(0, 8);
                    eventData.therapistIds = [resource[1]];
                    $('#eventTypeModal').modal('toggle');
                }
            },
            onEventClicked: (args) => {
                const event = args.e.data;
                const handleAction = (type, data) => {
                    DayPilot.Modal.close();
                    if (type == 'edit') editEvent(data);
                    if (type == 'delete') deleteEvent([data]);
                };
                window.handleAction = handleAction;
                DayPilot.Modal.alert(event.eventDetailSlotHtml);
            },
            onBeforeEventRender: function(args) {
                args.data.html = `${args.data.eventSlotHtml}`;
            },
            onBeforeCellRender: function(args) {
                // console.log("availableTime", availableTime);
                if (availableTime.length > 0) {
                    let event = availableTime.find(e => e.resource === args.cell.resource);
                    if (event.resource === args.cell.resource) {
                        var startHour = parseInt(event.startHour);
                        var endHour = parseInt(event.endHour);
                        var hour = args.cell.start.getHours();
                        if (startHour <= hour && hour < endHour) {
                            args.cell.business = false;
                            args.cell.cssClass = "available-cell";
                        } else {
                            args.cell.business = true;
                        }
                    }
                }
            },
            headerHeightAutoFit: true,
            showCurrentTime: false
        });
        dp.init();
    }

    function queryParam(params = {}) {
        var currentUrl = new URL(window.location.href);
        var searchParams = currentUrl.searchParams;
        for (const [key, value] of Object.entries(params)) {
            if (value === null || value === undefined || value === '') {
                searchParams.delete(key);
            } else {
                searchParams.set(key, value);
            }
        }
        var newUrl = currentUrl.origin + currentUrl.pathname + '?' + searchParams.toString();
        history.replaceState(null, '', newUrl);
        return searchParams.toString();
    }

    function getQueryParam(query) {
        var currentUrl = new URLSearchParams(window.location.search);
        return currentUrl.get(query);
    }

    function setCalendar() {
        $('.calendar_default_scroll > div > div:nth-of-type(2)').css('height', '500px');
        $('.calendar_default_scroll > div > div:nth-of-type(1)').css('height', '500px');
        $('.calendar_default_scroll').css('height', '500px');
        const targetElement = $('.calendar_default_scroll > div > div:nth-of-type(2)')[0];
        $(window).keyup(function (e) {
            var key = e.which;
            if (key == 13 || key == 39) targetElement.scrollLeft += 200;
            if (key == 37) targetElement.scrollLeft -= 200;
        });
    }
</script>