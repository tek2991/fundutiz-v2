import './bootstrap';

import Alpine from 'alpinejs';
import focus from '@alpinejs/focus';
import 'flowbite';
import flatpickr from "flatpickr";
window.Alpine = Alpine;
window.flatpickr = flatpickr;

Alpine.plugin(focus);

import './../../vendor/power-components/livewire-powergrid/dist/powergrid'
import './../../vendor/power-components/livewire-powergrid/dist/powergrid.css'

import './../../node_modules/flatpickr/dist/flatpickr.min.css'

Alpine.start();
