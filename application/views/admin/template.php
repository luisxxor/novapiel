<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Novapiel</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.7.4/css/bulma.min.css">
  <script defer src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/vue@2.6.6/dist/vue.js"></script>
  <style>
    [v-cloak] {
      display: none;
    }

    html {
      overflow-y: auto;
    }
  </style>
</head>
<body>
  <nav class="navbar is-light" role="navigation" aria-label="main navigation">
    <div class="navbar-brand">
      <a class="navbar-item" href="<?PHP echo base_url('/'); ?>">
        <img src="<?PHP echo base_url('assets/images/sin-ttulo-1-276x235.png'); ?>" alt="Novapiel">
      </a>

      <a
        role="button"
        class="navbar-burger burger"
        aria-label="menu"
        aria-expanded="false"
        data-target="navbarBasicExample"
        id="hamburguerMenu"
       >
        <span aria-hidden="true"></span>
        <span aria-hidden="true"></span>
        <span aria-hidden="true"></span>
      </a>
    </div>

    <div id="navbarBasicExample" class="navbar-menu">
      <div class="navbar-start">
        <a href="<?PHP echo base_url('usuario'); ?>" class="navbar-item">
          Usuarios
        </a>

        <a href="<?PHP echo base_url('servicio'); ?>" class="navbar-item">
          Servicios
        </a>

        <a href="<?PHP echo base_url('cliente'); ?>" class="navbar-item">
          Clientes
        </a>

        <a class="navbar-item">
          Contacto
        </a>

        <a class="navbar-item">
          Ventas
        </a>

      </div>

      <div class="navbar-end">
        <div class="navbar-item">
          <div class="buttons">
            <a href="<?PHP echo base_url('admin/logout'); ?>" class="button is-danger">
              <strong>Cerrar sesión</strong>
            </a>
          </div>
        </div>
      </div>
    </div>
  </nav>


  <?PHP $this->load->view($content); ?>
  <script>
    window.addEventListener('DOMContentLoaded', () => {
      const $hamburguerMenu = document.getElementById('hamburguerMenu');

      $hamburguerMenu.addEventListener('click', () => {
        const target = $hamburguerMenu.dataset.target;
        const $target = document.getElementById(target);

        $hamburguerMenu.classList.toggle('is-active');
        $target.classList.toggle('is-active');

      })
    })
  </script>
  <style>
   
    .bounce-enter {
      transform: scale(0);
    }

    .bounce-leave {
      transform: scale(1);
    }

    .bounce-enter-active {
      animation: .3s bounce-in;
    }

    .bounce-leave-active {
      animation: .3s bounce-in reverse;
    }

    .modal.is-active {
      transition: .3s all;
      visibility: visible;
    }

    .modal {
      transition: .3s all;
      display: flex!important;
      visibility: hidden;
    }

    .fade-enter {
      opacity: 0;
    }

    .fade-enter-active, .fade-leave-active {
      transition: .3s ease opacity;
    }

    .fade-enter-to, .fade-leave{
      opacity: 1;
    }

    .fade-leave-to {
      opacity: 0;
    }

    @keyframes bounce-in {
      0% {
        transform: scale(0);
      }
      100% {
        transform: scale(1);
      }
    }
  </style>
</body>
</html>