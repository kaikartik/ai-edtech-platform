'use strict';

// Show register form
document.querySelector('#crtacc').addEventListener('click', function () {
    document.querySelector('#loginbox').style.display = 'none';
    document.querySelector('#regbox').style.display = 'grid';
    document.querySelector('#reset').style.display = 'none';
});

// Show login form
document.querySelector('#log').addEventListener('click', function () {
    document.querySelector('#loginbox').style.display = 'grid';
    document.querySelector('#regbox').style.display = 'none';
    document.querySelector('#reset').style.display = 'none';
});

// Show reset form
document.querySelector('#frgpass').addEventListener('click', function () {
    document.querySelector('#reset').style.display = 'grid';
    document.querySelector('#loginbox').style.display = 'none';
    document.querySelector('#regbox').style.display = 'none';
});

// Back button from reset to login
document.querySelector('#back1').addEventListener('click', function () {
    document.querySelector('#loginbox').style.display = 'grid';
    document.querySelector('#regbox').style.display = 'none';
    document.querySelector('#reset').style.display = 'none';
});

// Back button from register to login
document.querySelector('#back').addEventListener('click', function () {
    document.querySelector('#loginbox').style.display = 'grid';
    document.querySelector('#regbox').style.display = 'none';
    document.querySelector('#reset').style.display = 'none';
});

// Show correct section based on URL hash on page load
document.addEventListener("DOMContentLoaded", function () {
    if (window.location.hash === "#register") {
        document.querySelector('#loginbox').style.display = 'none';
        document.querySelector('#regbox').style.display = 'grid';
        document.querySelector('#reset').style.display = 'none';
    }
    if (window.location.hash === "#login") {
        document.querySelector('#loginbox').style.display = 'grid';
        document.querySelector('#regbox').style.display = 'none';
        document.querySelector('#reset').style.display = 'none';
    }
    if (window.location.hash === "#reset") {
        document.querySelector('#reset').style.display = 'grid';
        document.querySelector('#loginbox').style.display = 'none';
        document.querySelector('#regbox').style.display = 'none';
    }
});
