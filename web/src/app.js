import './bootstrap';
import './htmx';
import './notify';
import './sweetalert2';

import Alpine from 'alpinejs';

import Chart from 'chart.js/auto';

import {notify} from "./utils.js";

import './css/tailwind.css';
import '/node_modules/@shoelace-style/shoelace/dist/themes/light.css';

import './css/app.scss';

window.Alpine = Alpine;

Alpine.start();

window.notify = notify;
window.Chart = Chart;
