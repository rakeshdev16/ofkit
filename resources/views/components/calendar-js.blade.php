<script>
    function filterCalendar(params = {}) {
        var url = "{{ $filterRoute }}";
        var paramLength = Object.keys(params).length;        
        if (paramLength > 0) {
            url = url+"?"+queryParam(params);
        }
        fetch(url).then((response) => response.json()).then((data) => {
            if ("{{ Route::currentRouteName() }}" == 'schedule.index') {
                $('#childrenFilter').html(data.childrens);
                $('#staffFilter').html(data.users);
            }            
            calendar(data.calenderEvents, data.calenderHeader);
            $(window).scrollTop(scrollingPosition);
            setTimeout(() => {
                const targetElement = $('.calendar_default_scroll > div > div:nth-of-type(2)')[0];
                $(window).keyup(function (e) {
                    var key = e.which;
                    if(key == 13 || key == 39) {
                        targetElement.scrollLeft += 200;
                    } else if(key == 37) {
                        targetElement.scrollLeft -= 200;
                    }
                });
            }, 1500);
        });
    }

    function calendar(events = '', list) {
        if (window.dp) {
            window.dp.dispose();
        }
        var type = "{{ $type }}";
        window.dp = new DayPilot.Calendar("scheduleCalendar", {
            rtl: true,
            startDate: DayPilot.Date.today(),
            viewType: "Resources",
            columnWidthSpec: "Fixed",
            headerLevels: "Auto",
            headerLevelHeights: [ 40, 60 ],
            heightSpec: "BusinessHoursNoScroll",
            height: 500,
            columnWidth: 100,
            businessBeginsHour: 7,
            businessEndsHour: 17,
            timeHeaderCellDuration: 15,
            cellDuration: 15,
            events: events,
            columns: list.map(column => {
                return {
                    name: `<span class="days-header">${column.name}</span>`,
                    id: column.id,
                    children: Array.isArray(column.children) ? column.children.map(child => ({
                            id: child.id,
                            name: `<div class="schedule-user-name text-center wrap-text">
                                ${child.first_name ?? '-'} ${child.family_name ?? '-'}<br>
                                ${child.profession ?? '-'}<br><hr style="margin: 0rem;">
                                ${child.association ?? '-'}
                            </div>`
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
                    eventData.startTime = args.start.value.split("T")[1].slice(0, 5);
                    eventData.endTime = args.end.value.split("T")[1].slice(0, 5);
                    eventData.therapistIds = [resource[1]];
                    eventData.mode = 'create';
                    $('#eventTypeModal').modal('toggle');
                }
            },
            onBeforeEventRender: function(args) {
                let title = '';
                let startTime = new Date(args.data.start);
                let endTime = new Date(args.data.end);
                let timeDiff = ((endTime.getTime() - startTime.getTime()) / 1000)/60;

                function eventName(fullNames) {
                    if (args.data.eventCount > 2) {
                        return '';
                    }
                    return fullNames.split(", ").map(fullName => {
                        const nameParts = fullName.trim().split(" ");
                        const firstName = nameParts[0];
                        const lastNameInitial = nameParts.length > 1 ? nameParts[1][0]+'.' : "";
                        return `${lastNameInitial} ${firstName}`;
                    }).join(", ");
                }
                let cellTitle = args.data.type.split('-').map((item, index) => item[0].toUpperCase()+''+item.slice(1) ).join(' ');
                switch (args.data.type) {
                    case 'staff-meeting':
                        title = `<div style="${timeDiff >= 45 ? "font-weight: bold;" : ""}">: Staff Meeting<br>${eventName(args.data.twoChildrenNames)}</div>`;
                        break;
                    case 'group':
                        title = `<div style="${timeDiff >= 45 ? "font-weight: bold;" : ""}">: ${args.data.groupName}<br>${eventName(args.data.twoChildrenNames)}</div>`;
                        break;
                    case 'individual':
                        title = `<div style="${timeDiff >= 45 ? "font-weight: bold;" : ""}">
                                    <p style="font-size: 16px; margin-bottom: 0px;">${eventName(args.data.twoChildrenNames)}</p>
                                </div>`;
                        break;
                    case 'parental-guidance':
                        title = `<div style="${timeDiff >= 45 ? "font-weight: bold;" : ""}">
                                    <p style="font-size: 16px; margin-bottom: 0px;">${eventName(args.data.twoChildrenNames)}</p>
                                </div>`;
                        break;
                    default:
                        title = `<div style="${timeDiff >= 45 ? "font-weight: bold;" : ""}">${cellTitle}</div>`;
                        break;
                }

                function escapeJson(json) {
                    return JSON.stringify(json).replace(/'/g, '&#39;');
                }
                args.data.html = `
                <div class="p-1 event-box d-flex flex-column justify-content-between" style="${args.data.color[0]}; ${args.data.color[1]}">
                    ${args.data.eventCount >= 3 ? `
                        <div class="position-absolute" style="text-align: left;">
                            <span style="display: block; font-size: 14px;">
                                <i class="fa fa-${args.data.icon}"></i>
                            </span>
                            <span style="display: block; font-size: 12px; margin-top: 4px;">
                                ${args.data.start.toString("HH:mm")}
                            </span>
                        </div>
                    ` : `
                        <div class="d-flex justify-content-between">
                            <span>${args.data.start.toString("HH:mm")}</span>
                            <span><i class="fa fa-${args.data.icon}"></i></span>
                        </div>
                    `}

                    <div class="d-flex align-items-center justify-content-center h-100" style="font-size: 12px; text-align: center;">${title}</div>
                    ${type === 'create' && args.data.eventCount !== 3 && timeDiff != 30 ? `
                        <div class="d-flex justify-content-start mt-auto" style="position: relative; bottom: 0;">
                            <i class="fa fa-edit" onclick='editEvent(${escapeJson(args.data)})'></i>&nbsp;
                            <i class="fa fa-trash" onclick='deleteEvent(["${args.data.uniqueId}"])'></i>&nbsp;
                        </div>
                    ` : ''}
                </div>`;

                args.data.bubbleHtml = `
                <div class="p-3 calendar-event-overlay tooltip-left" style="word-wrap: break-word; white-space: normal; direction: rtl; text-align: right;">
                    <ul>
                        <li class="d-flex gap-4 text-dark fs-6 mb-2 justify-content-between">
                            <div class="d-flex justify-content-start">
                                <div class="d-flex gap-2 justify-content-end">
                                    <i class="fa fa-info fa-lg"></i>&nbsp;&nbsp;&nbsp;&nbsp;<div>${cellTitle.trim()}</div>
                                </div>
                            </div>
                            ${type === 'create' ? `
                                <div class="d-flex gap-2 justify-content-end">
                                    <i class="fa fa-edit" onclick='editEvent(${escapeJson(args.data)})' style="cursor: pointer;"></i>
                                    <i class="fa fa-trash" onclick='deleteEvent(["${args.data.uniqueId}"])' style="cursor: pointer;"></i>
                                </div>
                            ` : ''}
                        </li>
                        ${['individual', 'group', 'staff-meeting', 'parental-guidance'].includes(args.data.type) ? `
                            <li class="d-flex gap-4 text-dark fs-6 mb-2 justify-content-start">
                                <i class="fa fa-${args.data.icon}"></i>${args.data.childrenNames.trim()}
                            </li>
                        ` : ''}
                        <li class="d-flex gap-4 text-dark fs-6 mb-2 justify-content-start">
                            <i class="fa fa-calendar"></i>${args.data.start.toString("HH:mm")} - ${args.data.end.toString("HH:mm")}
                        </li>
                        <li class="d-flex gap-4 text-dark fs-6 mb-2 justify-content-start">
                            <i class="fa fa-clock-o"></i>${args.data.frequencyRepeat || ''} ${args.data.frequencyRepeatAt || ''}
                        </li>
                        <li class="d-flex gap-4 text-dark fs-6 mb-2 justify-content-start">
                            <i class="fa fa-users"></i>${args.data.therapistNames.trim()}
                        </li>
                        <li class="d-flex gap-4 text-dark fs-6 mb-2 justify-content-start">
                            <i class="fa fa-align-justify"></i>
                            <p class="m-0">${args.data.description || ''}</p>
                        </li>

                    </ul>
                </div>`;
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

</script>