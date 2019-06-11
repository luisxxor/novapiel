<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.7.4/css/bulma.min.css">
    <script defer src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>
    <title>Login</title>
    <style>
      body {
        background-image: url("<?PHP echo base_url('assets/images/loginwallpaper.jpg'); ?>");
        min-height: 100vh;
        max-width: 100vw;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        background-repeat: no-repeat;
        background-position: right bottom;
        background-color: #FFFFFF;
        background-size: contain;
      }

      .section {
        flex: 1 1 100%;
      }

      .only-danger {
        display: none;
      }

      .is-danger ~ .only-danger {
        display: block;
      }
    </style>
   
    <script>
      
      function onSubmit(event) {
        const $form = document.getElementById("login_form");
        let user = checkUser();
        let pass = checkPass();
        if(user && pass)
        {
          event.preventDefault();
          grecaptcha.execute();
        }
      }

      function sendForm(token) {
        const $form = document.getElementById("login_form");
        form.submit();
      }

      function hideMessage() {
        const $message = document.querySelector('article.message');

        if ($message) $message.remove();
      }

      function checkUser() {
        hideMessage();
        const $user = document.getElementById("username");
        let user_is_valid = $user.checkValidity();

        if(user_is_valid) {
          $user.classList.remove('is-danger');
        }
        else {
          $user.classList.add('is-danger');
        }

        return user_is_valid;
      }

      function checkPass() {
        hideMessage();
        const $pass = document.getElementById("password");
        let pass_is_valid = $pass.checkValidity()

        if(pass_is_valid) {
          $pass.classList.remove('is-danger');
        }
        else {
          $pass.classList.add('is-danger');
        }

        return pass_is_valid;
      }

      function hideMessage() {
        document.querySelector('.message').style.display = "none";
      }

      window.addEventListener('load',function() {
        document.getElementById('username').addEventListener('input',checkUser);
        document.getElementById('password').addEventListener('input',checkPass);
      });
    </script>

    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body>
  <section class="section">
    <div class="container">
      <div class="columns">
        <div class="column is-narrow-mobile is-6-tablet is-offset-3-tablet is-4-desktop is-offset-4-desktop">
          <div class="card">
            <div class="card-content">
              <div class="content">
                <form id="login_form" action="<?PHP echo base_url('admin/process_login') ?>" method="POST">
                  <div class="field">
                    <div class="control has-icons-left">
                      <input class="input" id="username" type="text" name="username" required placeholder="Nombre de usuario"/>
                        <span class="icon is-small is-left">
                          <i class="fas fa-user"></i>
                        </span>
                      <p class="help is-danger only-danger">El nombre de usuario no puede estar vacío.</p>
                    </div>
                  </div>
                  <div class="field">
                    <div class="control has-icons-left">
                      <input class="input" type="password" id="password" name="password" required placeholder="********"/>
                      <span class="icon is-small is-left">
                        <i class="fas fa-lock"></i>
                      </span>
                      <p class="help is-danger only-danger">La contraseña no puede estar vacía.</p>
                    </div>
                  </div>
                  <?PHP if(isset($_GET['msg'])): ?>
                    <?PHP if($_GET['msg'] == 1): ?>
                      <article class="message is-danger">
                        <div class="message-body">
                          Usuario y/o contraseña incorrectos.
                        </div>
                      </article>
                    <?PHP elseif($_GET['msg'] == 2): ?>
                      <article class="message is-danger">
                        <div class="message-body">
                          Error con el Re-captcha.
                        </div>
                      </article>
                    <?PHP endif; ?>
                  <?PHP endif; ?>

                  <div class="g-recaptcha"
                    data-sitekey="6LdXvJsUAAAAAAX91zwKh0J7QEaKbYF76eME_uto"
                    data-callback="onSubmit"
                    data-size="invisible">
                  </div>
                  <input type="hidden" id="captcha" name="captcha" value="">
                  <div class="field">
                    <div class="control">
                      <button class="button is-link">Iniciar sesión</button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</body>
</html>