/*SUCESSO*/
function fast_alert_salvar() {

    Swal.fire({
        position: "top-end",
        icon: "success",
        title: "Registro salvo com sucesso!",
        showConfirmButton: false,
        timer: 2000
    })
}


function  fast_alert_excluir() {

    Swal.fire({
        position: "top-end",
        icon: "success",
        title: "Registro excluido com sucesso!",
        showConfirmButton: false,
        timer: 2000
    })
}

/*ERRO*/
function fast_alert_erro_salvar() {

    Swal.fire({
        position: "top-end",
        icon: "warning",
        title: "Não foi possivel salvar o registro",
        showConfirmButton: false,
        timer: 2000
    })
}


function fast_alert_erro_excluir() {

    Swal.fire({
        position: "top-end",
        icon: "warning",
        title: "Não foi possivel excluir o registro",
        showConfirmButton: false,
        timer: 2000
    })
}
function fast_alert_erro_listar() {

    Swal.fire({
        position: "top-end",
        icon: "warning",
        title: "Não foi possivel listar o registro",
        showConfirmButton: false,
        timer: 2000
    })
}

