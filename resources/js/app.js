import './bootstrap';
import './webcomponents';

import Chart from 'chart.js/auto';

import svgPanZoom from "svg-pan-zoom";
import ky from 'ky';
import Split from 'split.js'

// import '../css/fomantic-ui/semantic.less';
import 'primeflex/primeflex.css';
import '../css/app.less';
import '../css/webcomponents.scss';

window.Chart = Chart;
window.svgPanZoom = svgPanZoom;
window.ky = ky;
window.Split = Split;

