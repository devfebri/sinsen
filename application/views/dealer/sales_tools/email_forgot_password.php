<?php $this->load->view('email/header'); ?>

<body class="">
  <table role="presentation" border="0" cellpadding="0" cellspacing="0" class="body">
    <tr>
      <td>&nbsp;</td>
      <td class="container">
        <div class="content">

          <table role="presentation" class="main">
            <tr>
              <td height="2" style="width:33.3%;background: rgb(255,0,0);
  background: linear-gradient(90deg, rgba(255,0,0,1) 0%, rgba(255,188,188,1) 50%, rgba(255,0,0,1) 100%);line-height:2px;font-size:2px;">&nbsp;</td>
            </tr>
            <tr>
              <td class="wrapper">
                <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                  <tr>
                    <td align="center">
                      <p style="border-bottom: 1px solid #D0D0D0;"><img src="<?= $logo ?>" alt="SINARSENTOSA" height="60px">
                    </td>
                  </tr>
                </table>
                <table>
                  <tr>
                    <td style='font-size:18pt;border-bottom:1px solid #D0D0D0'>
                      Reset Password
                    </td>
                  </tr>
                  <tr>
                    <td style='padding-left:20px'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Untuk melakukan reset password, silahkan klik tombol di bawah ini dan ikuti langkah-langkah selanjutnya. Jika tidak, silahkan abaikan email ini. Terimakasih
                      <hr>
                    </td>
                  </tr>
                  <tr>
                    <td align='center'>
                      <a href="<?= $url ?>" class="btn btn-primary" style='width:30%' target='_blank'>Reset Password</a>
                    </td>
                  </tr>
                </table>
          </table>
          <?php $this->load->view('email/footer'); ?>