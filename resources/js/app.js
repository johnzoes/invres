import 'select2/dist/css/select2.min.css';

import './bootstrap';
import Alpine from 'alpinejs';
import $ from 'jquery';
import 'select2';

window.Alpine = Alpine;
Alpine.start();

// Inicializar Select2
$(document).ready(function() {
    $('#items').select2();
});
