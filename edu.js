document.querySelector('#log').addEventListener('click', function () {
    window.location.href = 'http://localhost/Edu/index.php';
})
const noob = document.querySelector('#noob');
const dbba = document.querySelector('#dbba');

noob.addEventListener('mouseenter', function () {
    dbba.style.display = 'block';
});

noob.addEventListener('mouseleave', function () {
    dbba.style.display = 'none';
});
document.querySelector('#expnow').addEventListener('click', function () {
    window.location.href = 'learn.html';
})
document.querySelector('#imgbox').addEventListener('click', function () {
    window.location.href = 'new.html';
})