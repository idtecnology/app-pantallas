const csrfToken = document
    .querySelector('meta[name="csrf-token"]')
    .getAttribute("content");

const checkSess = document
    .querySelector('meta[name="auth-check"]')
    .getAttribute("content");

//mostrar imgs
const url = "http://localhost:8000/storage/uploads/tmp/";

const extensionesImagen = [
    "bmp",
    "gif",
    "jpg",
    "jpeg",
    "png",
    "svg",
    "webp",
    "BMP",
    "GIF",
    "JPG",
    "JPEG",
    "PNG",
    "SVG",
    "WEBP",
];
const extensionesVideo = [
    "avi",
    "m4v",
    "mov",
    "mp4",
    "mpeg",
    "ogv",
    "qt",
    "webm",
    "wmv",
    "AVI",
    "M4V",
    "MOV",
    "MP4",
    "MPEG",
    "OGV",
    "QT",
    "WEBM",
    "VWM",
];

function formatearFecha(fechaOriginal) {
    var fecha = new Date(fechaOriginal + "T00:00:00-04:00");
    var options = {
        day: "2-digit",
        month: "2-digit",
        year: "numeric",
        timeZone: "America/Caracas",
    };
    var formatoFecha = new Intl.DateTimeFormat("es-ES", options);
    return formatoFecha.format(fecha);
}

function cambiarFormatoHora(horaOriginal) {
    var fecha = new Date("2000-01-01T" + horaOriginal);
    var horas = fecha.getHours();
    var minutos = fecha.getMinutes();
    var horasFormateadas = horas < 10 ? "0" + horas : horas;
    var minutosFormateados = minutos < 10 ? "0" + minutos : minutos;
    return horasFormateadas + ":" + minutosFormateados;
}

function notifySpinner(obj) {
    Swal.fire({
        title: obj.title,
        html: obj.html,
        allowOutsideClick: obj.allowOutsideClick,
        showLoaderOnConfirm: obj.showLoaderOnConfirm,
        didOpen: () => {
            Swal.showLoading();
        },
    });
}

function notifyGeneral(obj) {
    Swal.fire({
        title: obj.title,
        text: obj.text,
        icon: obj.icon,
    });
}

async function getAvailabilityDates(time, screen_id) {
    var inputFechaModal = document.querySelector("#inputFechaModal");
    const response = await fetch("/api/availability-dates", {
        method: "POST",
        body: JSON.stringify({
            duration: time,
            screen_id: screen_id,
        }),
        headers: {
            "content-type": "application/json",
        },
    });
    const data = await response.json();
    const count = data.length - 1;
    inputFechaModal.min = data[0].fecha;
    inputFechaModal.max = data[count].fecha;
}

async function buscarTramos(fecha, lugar, time, screen_id) {
    try {
        var tramo_modal = document.querySelector("#tramo_modal");
        var tramo_fuera = document.querySelector("#tramo_fuera");
        var divs = "";

        document.getElementById("date_hidden").value = fecha;

        const response = await fetch("/api/tramo", {
            method: "POST",
            body: JSON.stringify({
                fecha: fecha,
                duration: time,
                screen_id: screen_id,
            }),
            headers: {
                "content-type": "application/json",
            },
        });

        const data = await response.json();

        if (lugar == 1) {
            if (data == "") {
                divs += `Vacio, no hay tramos disponibles`;
            } else {
                for (var tramos in data) {
                    divs += `<div class="col-3 px-0">
                                <a data-bs-dismiss="modal" onclick="seleccionTramo(this, '1', \'${
                                    data[tramos].fecha
                                }\')" class="btn btn-primary mb-2">${cambiarFormatoHora(
                        data[tramos].tramos
                    )}</a>
                            </div>`;
                }
            }
            tramo_modal.innerHTML = divs;
        } else {
            if (data == "") {
                divs += `Vacio, no hay tramos disponibles`;
            } else {
                for (var i = 0; i < 10; i++) {
                    divs += `
                                <a onclick="seleccionTramo(this, '1', \'${
                                    data[i].fecha
                                }\')" class="btn btn-primary btn-sm mb-2 mx-1 col-2">${cambiarFormatoHora(
                        data[i].tramos
                    )}</a>
                            `;
                }
            }
            tramo_fuera.innerHTML = divs;
        }

        if (data != "") {
            var fechaFormateada = formatearFecha(data[0].fecha);
            document.querySelector("#span_tramo").innerHTML = `${
                data[0].fecha == fecha ? "Hoy" : fechaFormateada
            }, ${cambiarFormatoHora(data[0].tramos)} hs`;
            document.querySelector(
                "#fehca_visualizacion"
            ).innerHTML = `Se visualizara el ${fechaFormateada} - ${cambiarFormatoHora(
                data[0].tramos
            )} hs`;
            document.getElementById("tramo_select").value = data[0].tramos;
        }
    } catch (error) {
        console.error("Error en buscarTramos:", error);
    }
}

function seleccionTramo(tramo, lugar, fecha) {
    var textoDelEnlace = tramo.innerText || tramo.textContent;

    var fechaFormateada = formatearFecha(fecha);
    if (lugar == 1) {
        document.querySelector(
            "#span_tramo"
        ).innerHTML = `${fechaFormateada}, ${textoDelEnlace} hs`;
        document.querySelector(
            "#fehca_visualizacion"
        ).innerHTML = `Se visualizara el ${fechaFormateada} - ${textoDelEnlace} hs`;
        document.getElementById("tramo_select").value = textoDelEnlace;
        document.querySelector("#pagar").classList.remove("disabled");
    } else {
        document.querySelector(
            "#span_tramo"
        ).innerHTML = `${fechaFormateada}, ${textoDelEnlace} hs`;
        document.getElementById("tramo_select").value = textoDelEnlace;
        document.querySelector(
            "#fehca_visualizacion"
        ).innerHTML = `Se visualizara el ${fechaFormateada} - ${textoDelEnlace} hs`;
        document.querySelector("#pagar").classList.remove("disabled");
    }
}

function openFiles() {
    document.querySelector("#archivos").click();
}
