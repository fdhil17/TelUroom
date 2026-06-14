import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import interactionPlugin from '@fullcalendar/interaction';

document.addEventListener('DOMContentLoaded', function () {
    const calendarEl = document.getElementById('calendar');
    if (!calendarEl) return;

    const ruanganSelect = document.getElementById('ruangan_filter');

    const calendar = new Calendar(calendarEl, {
        plugins: [dayGridPlugin, timeGridPlugin, interactionPlugin],
        initialView: 'timeGridWeek',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay',
        },
        slotMinTime: '07:00:00',
        slotMaxTime: '20:00:00',
        allDaySlot: false,
        locale: 'id',
        height: 'auto',
        events: function (info, successCallback, failureCallback) {
            const ruanganId = ruanganSelect ? ruanganSelect.value : '';
            const url = '/calendar/events' + (ruanganId ? '?ruangan_id=' + ruanganId : '');

            fetch(url)
                .then((response) => response.json())
                .then((data) => successCallback(data))
                .catch((error) => failureCallback(error));
        },
        eventClick: function (info) {
            const props = info.event.extendedProps;
            let detail = '';

            if (props.type === 'akademik') {
                detail = 'Kuliah\nRuangan: ' + props.ruangan + '\nDosen: ' + props.dosen;
            } else {
                detail = 'Reservasi\nRuangan: ' + props.ruangan + '\nMahasiswa: ' + props.mahasiswa + '\nKeperluan: ' + props.keperluan;
            }

            alert(detail);
        },
    });

    calendar.render();

    if (ruanganSelect) {
        ruanganSelect.addEventListener('change', function () {
            calendar.refetchEvents();
        });
    }
});
