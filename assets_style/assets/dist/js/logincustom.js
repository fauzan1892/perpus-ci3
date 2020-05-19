
//angka 500 dibawah ini artinya pesan akan muncul dalam 0,5 detik setelah document ready
//angka 3000 dibawah ini artinya pesan akan hilang dalam 3 detik setelah muncul

$(document).ready(function(){setTimeout(function(){$(".alert-success").fadeIn('slow');}, 5000);});
setTimeout(function(){$(".alert-success").fadeOut('slow');}, 8000);

$(document).ready(function(){setTimeout(function(){$(".alert-warning").fadeIn('slow');}, 5000);});
setTimeout(function(){$(".alert-warning").fadeOut('slow');}, 8000);

$(document).ready(function(){setTimeout(function(){$(".alert").fadeIn('slow');}, 5000);});
setTimeout(function(){$(".alert1").fadeOut('slow');}, 8000);
