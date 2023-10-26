import { Notyf } from 'notyf';
import 'notyf/notyf.min.css';

window.notyf = new Notyf({
    duration: 2000,
    dismissible: true,
    ripple: false,
    position: {
        x: 'right',
        y: 'top',
    },
    types: [
        {
            type: 'success',
            background: '#36d399',
            icon: {
                className: 'material-icons',
                tagName: 'i',
                text: 'success'
            }
        },
        {
            type: 'info',
            background: '#3abff8',
            icon: {
                className: 'material-icons',
                tagName: 'i',
                text: 'info'
            }
        },
        {
            type: 'warning',
            background: '#fbbd23',
            icon: {
                className: 'material-icons',
                tagName: 'i',
                text: 'warning'
            }
        },
        {
            type: 'error',
            background: '#f87272',
            icon: {
                className: 'material-icons',
                tagName: 'i',
                text: 'error'
            }
        },
    ]
});
