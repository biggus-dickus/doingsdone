'use strict';

var expandControls = document.querySelectorAll('.expand-control');

var hidePopups = function() {
  [].forEach.call(document.querySelectorAll('.expand-list'), function(item) {
    item.classList.add('hidden');
  });
};

document.body.addEventListener('click', hidePopups, true);

[].forEach.call(expandControls, function(item) {
  item.addEventListener('click', function() {
    item.nextElementSibling.classList.toggle('hidden');
  });
});

var $checkbox = document.getElementsByClassName('checkbox__input')[0];

if($checkbox) {
  $checkbox.addEventListener('change', function(event) {
      var is_checked = +event.target.checked;

      window.location = '/index.php?show_completed=' + is_checked;
  });
}

var filterRadios = document.querySelectorAll('.radio-button__input');

var projectId = (getParameterByName('project_id')) ? '&project_id=' + getParameterByName('project_id') : '';

[].forEach.call(filterRadios, function(radio) {
    radio.addEventListener('change', function() {
        if (radio.value === 'all') {
            window.location = '/index.php';
            return;
        }

        window.location = '/index.php?show_tasks=' + radio.value + projectId;
    });
});


/**
 * Parse the address bar contents and get the value of specified query string.
 * @param {string} name
 * @param {string} url
 * @returns {string}
 */
function getParameterByName(name, url) {
    if (!url) {
        url = window.location.href;
    }
    
    name = name.replace(/[\[\]]/g, '\\$&');
    
    var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
        results = regex.exec(url);
    
    if (!results) {
        return null;
    }

    if (!results[2]) {
        return '';
    }

    return decodeURIComponent(results[2].replace(/\+/g, ' '));
}
