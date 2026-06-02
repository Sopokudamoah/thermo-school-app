import './bootstrap';

import jQuery from 'jquery';
import Alpine from 'alpinejs';
import {createIcons, icons} from 'lucide';
import 'datatables.net-dt';
import 'datatables.net-responsive-dt';

import {Calendar} from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import listPlugin from '@fullcalendar/list';
import interactionPlugin from '@fullcalendar/interaction';
import toastr from 'toastr';
import html2canvas from 'html2canvas';
import {jsPDF} from 'jspdf';
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
import './tour';

window.$ = window.jQuery = jQuery;

window.Alpine = Alpine;
Alpine.start();

window.lucide = { createIcons, icons };
window.toastr = toastr;
window.html2canvas = html2canvas;
window.jsPDF = jsPDF;
window.jspdf = {jsPDF};
window.ClassicEditor = ClassicEditor;
window.FullCalendar = {Calendar, dayGridPlugin, timeGridPlugin, listPlugin, interactionPlugin};

document.addEventListener('DOMContentLoaded', () => {
    createIcons({ icons });
});
