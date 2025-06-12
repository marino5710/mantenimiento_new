<div style="text-align: center; font-family: Arial, sans-serif; margin-top: 20px;">
    <h2 style="color: #003366;">Destellos de Glamour</h2>
    <p style="font-style: italic;">Sistema de Control Interno</p>
    <hr style="border: 1px solid #003366; width: 80%;">

    <div style="text-align: left; margin: 40px 10% 20px 10%; font-size: 14px; line-height: 1.5;">
        <p><strong>Guatemala, <?php echo date('d \d\e F \d\e Y'); ?></strong></p>

        <p>Estimado(a) colaborador(a),</p>

        <p>Nos complace informarle que se le ha proporcionado acceso al sistema de control interno de <strong>Destellos de Glamour</strong>. A continuación, encontrará la información necesaria para acceder a su cuenta.</p>

        <p><strong>Detalles de acceso:</strong></p>
        <ul style="list-style-type: none; padding-left: 0;">
            <li><strong>Nombre de usuario:</strong> <?php echo $usuarios[0]['usuario_nombre'] . ' ' . $usuarios[0]['usuario_apellido']; ?></li>
            <li><strong>Usuario:</strong> <?php echo $usuarios[0]['usuario_correo']; ?></li>
            <li><strong>Contraseña:</strong> <span style="color: #d9534f;"><?php echo $passwordGenerada; ?></span></li>
        </ul>

        <p><strong>Importante:</strong> La información proporcionada es confidencial. Usted es responsable del uso y seguridad de su contraseña y no debe compartirla con nadie. Solo el administrador puede cambiar la contraseña en caso de olvido o problemas de acceso. Mantenga sus credenciales seguras en todo momento.</p>


        <p>Atentamente,</p>
        <p><strong>Equipo de Destellos de Glamour</strong></p>
    </div>

</div>
