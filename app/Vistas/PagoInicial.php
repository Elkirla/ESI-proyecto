<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/PagoInicial.css">
    <link rel="icon" href="imagenes/logo.png" type="image/png">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <title>Ingreso a la cooperativa</title>
</head>
<body>
    <div class="IngresarPago section">
        <h1>Pago Inicial</h1>
        <p class="descripcion-pago">
            El pago inicial será contabilizado como tu pago mensual del primer mes. 
            Una vez aprobado, podrás ingresar a tu cuenta.
        </p>

        <div class="info-pago">
            <p> <span id="MontoMensual"></span></p> 
        </div>

        <form action="" class="form-pago" id="form-pago" enctype="multipart/form-data">
            <label for="archivo" class="label-file">
                Seleccionar Comprobante
            </label>
            <input type="file" name="archivo" id="archivo" accept=".jpg,.jpeg,.png,.pdf" required>
            <button type="submit" id="btn-pagar" class="btn-pago">Enviar pago</button>
            <label id="mensaje-error"></label>
        </form>

        <small class="nota">Formatos permitidos: .jpg, .jpeg, .png, .pdf</small>
    </div>

    <script src="js/pagoInicial.js"></script>
</body>
</html>
