"use strict";

// Getting form elemnets
const formLogIn = document.querySelector('.log-in');
const formRestore = document.querySelector('.restore');
const formLink = document.querySelectorAll('.form__link');

// Functions and blocks form related
const openForm = function () {
    formLogIn.classList.toggle('hidden');
    formRestore.classList.toggle('hidden');
}

for (let i = 0; i < formLink.length; i++) {
    formLink[i].addEventListener('click', openForm);
}


// Getting user menu elements
const btnMenu = document.querySelector('.content__user');
const userMenu = document.querySelector('.user-menu');
const userOverlay = document.querySelector('.user__overlay');

// Getting the btns for the themes
const btnDark = document.querySelector("#btnDark");
const btnLight = document.querySelector("#btnLight");

// Functions and blocks user menu related
const openUserMenu = function () {
    userMenu.classList.remove('hidden');
    userOverlay.classList.remove('hidden');
}

// Events user menu related
if (btnMenu) {
    btnMenu.addEventListener('mousedown', openUserMenu);
}

// Alerts taht we use throughout the app
function alerts(text) {
    $("#alert").removeClass("hidden");
    $("#alert__message").text(text);
}

function alertRed() {
    $('#alert').css('backgroundColor', 'var(--color-wrong)');
    $('#alert').css('color', 'var(--color-light)');
}

function alertGreen() {
    $('#alert').css('backgroundColor', 'var(--color-save)');
    $('#alert').css('color', 'var(--color-dark)');
}

function hideAlert() {
    $("#alert").addClass("hidden");
}

$("#btnCloseAlert").mousedown(function () {
    $("#alert").addClass("hidden");
});

// Estas son alertas en el peerfil de usuario
function alertsUserMenu(text) {
    $("#alert2").removeClass("hidden");
    $("#alert__message2").text(text);
}

function alertRed2() {
    $('#alert2').css('backgroundColor', 'var(--color-wrong)');
    $('#alert2').css('color', 'var(--color-light)');
}

function alertGreen2() {
    $('#alert2').css('backgroundColor', 'var(--color-save)');
    $('#alert2').css('color', 'var(--color-dark)');
}

function hideAlert2() {
    $("#alert2").addClass("hidden");
}

$("#btnCloseAlert2").mousedown(function () {
    $("#alert2").addClass("hidden");
});

function containsSpecialChars(str) {
    const specialChars = /[`!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?~]/;
    return specialChars.test(str);
}

function containsSpecialCharsGuion(str) {
    const specialChars = /[`!@#$%^&*()_+\=\[\]{};':"\\|,.<>\/?~]/;
    return specialChars.test(str);
}

function containsSpecialCharsNombre(str) {
    const specialChars = /[`!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?~]/;
    return specialChars.test(str);
}

function containsSpecialCharsPassword(str) {
    const specialChars = /[`%^&*()_+\-=\[\]{};':"\\|,.<>\/?~]/;
    return specialChars.test(str);
}

function containsSpecialCharsUbicacion(str) {
    const specialChars = /[`!@#$%^&*_+\-=\[\]{}'\\|<>\/?~]/;
    return specialChars.test(str);
}

function containsSpecialCharsDescripcion(str) {
    const specialChars = /[`@#$%^&*_+\-=\[\]{}'\\|<>\?~]/;
    return specialChars.test(str);
}





// Configurando el reloj en vivo
let currentTime = new Date();
let currentDay = currentTime.getDay();
let currentHour = currentTime.getHours();
let currentMinute = currentTime.getMinutes();
let currentSeconds = currentTime.getSeconds();

$('#time-app-user').text(`${concatZero((currentHour % 12) || 12)} : ${concatZero(currentMinute)} : ${concatZero(currentSeconds)}`);
$('#pm-am-time-user').html(currentHour >= 12 ? 'PM' : 'AM');

function concatZero(timeFrame) {
    return timeFrame < 10 ? '0'.concat(timeFrame) : timeFrame
}

setInterval(
    function time() {
        currentTime = new Date();
        currentHour = currentTime.getHours();
        currentMinute = currentTime.getMinutes();
        currentSeconds = currentTime.getSeconds();
        $('#time-app-user').html(`${concatZero((currentHour % 12) || 12)} : ${concatZero(currentMinute)} : ${concatZero(currentSeconds)}`);
        $('#pm-am-time-user').html(currentHour >= 12 ? 'PM' : 'AM');
    }, 1000);


// function modifyUrl(title, url) {
//     if (typeof (history.pushState) != "undefined") {
//         var obj = {
//             Title: title,
//             Url: url
//         };
//         history.pushState(obj, obj.Title, obj.Url);
//     }

//     Obj = new Object();
//     Obj.pagina = "";
//     $.ajax({
//         url: "php/ajax/ajax_app.php?" + $.param(Obj), success: function (respuesta) {
//             $("#resp").html(respuesta);
//         }
//     });
// }