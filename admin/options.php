<?php
function wsme_options_page() {
?>
<div class="wrap">
<h1>WSME (beta)</h1>

<form method="post" action="options.php">
    <?php settings_fields( 'wsme_options_group' ); ?>
    <?php do_settings_sections( 'wsme_options_group' ); ?>
    <table class="form-table">
      <tr valign="top">
      <th scope="row">Número de whatsapp:</th>
      <td><input type="tel" name="wsme_number" value="<?php echo esc_attr( get_option('wsme_number') ); ?>" placeholder="+573213211234" /></td>
      </tr>
    </table>

    <!-- staging -->
    <div style="
      display: flex;
      align-items: center;
    ">
      <button style="margin-top: 6px; margin-right: 8px" class="button button-secondary">
        Configuración inicial
      </button>
      <?php submit_button(); ?>
    </div>

</form>
</div>
<?php } ?>