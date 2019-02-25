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
        background: #83a4d4;
        background: -webkit-linear-gradient(to left, #b6fbff, #83a4d4);
        background: linear-gradient(to left, #b6fbff, #83a4d4);
        min-height: 100vh;
        max-width: 100vw;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
      }

      .section {
        flex: 1 1 100%;
      }
    </style>
</head>
<body>
  <section class="section">
    <div class="container">
      <div class="columns">
        <div class="column is-narrow-mobile is-6-tablet is-offset-3-tablet is-4-desktop is-offset-4-desktop">
          <div class="card">
            <div class="card-content">
              <div class="content">
                <form action="<?PHP echo base_url('admin/process_login') ?>" method="POST">
                  <div class="field">
                    <p class="control has-icons-left">
                      <input class="input" id="username" type="text" name="username" placeholder="Nombre de usuario"/>
                        <span class="icon is-small is-left">
                          <i class="fas fa-user"></i>
                        </span>
                    </p>
                  </div>
                  <div class="field">
                    <p class="control has-icons-left">
                      <input class="input" type="password" id="password" name="password" placeholder="********"/>
                      <span class="icon is-small is-left">
                        <i class="fas fa-lock"></i>
                      </span>
                    </p>
                  </div>

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