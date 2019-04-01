<link rel="stylesheet" href="<?PHP echo base_url('assets/bulma/bulma-tooltip.min.css'); ?>">
<link rel="stylesheet" href="<?PHP echo base_url('assets/animate/animate.css'); ?>">
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.18.0/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vee-validate@latest/dist/vee-validate.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@7.33.1/dist/sweetalert2.all.min.js" integrity="sha256-Qfxgn9jULeGAdbaeDjXeIhZB3Ra6NCK3dvjwAG8Y+xU=" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://unpkg.com/buefy/dist/buefy.min.css">
<script src="https://unpkg.com/buefy/dist/buefy.min.js"></script>

<style>
  [v-cloak] {
    display: none;
  }

  form {
    display: contents;
  }

  #pageTitle {
    margin-top: 1em;
  }

  #addClientButton {
    margin-bottom: 1em;
  }

  .clickable {
    cursor: pointer;
  }

  .slideLeft-enter, .slideRight-enter {
    position: absolute;
  }

  .slideLeft-enter-to, .slideRight-enter-to {
    position: static;
  }

  .slideLeft-enter-active {
    animation: .3s slideLeftIn ease-in-out;
  }

  .slideLeft-leave-active {
    animation: .3s slideLeftOut ease-in-out;
  }

  .slideRight-enter-active {
    animation: .3s slideRightIn ease-in-out;
  }

  .slideRight-leave-active {
    animation: .3s slideRightOut ease-in-out;
  }

  .button.is-primary {
    background-color: #00d1b2;
    border-color: transparent;
    color: #fff;
  }

  .button.is-primary.is-active, .button.is-primary:active {
    background-color: #00b89c;
    border-color: transparent;
    color: #fff;
  }

  .button.is-primary.is-focused:not(:active), .button.is-primary:focus:not(:active) {
    box-shadow: 0 0 0 0.125em rgba(0,209,178,.25);
  }

  .button.is-primary.is-hovered, .button.is-primary:hover {
    background-color: #00c4a7;
    border-color: transparent;
    color: #fff;
  }

  .icon.has-text-info.tooltip {
    cursor: pointer;
  }

  #infoTable {
    margin: 0 auto;
  }

  #updateButton {
    margin-bottom: 1em;
  }

  @keyframes slideLeftOut {
    0% {
      transform: translateX(0);
    }
    1% {
      position: absolute;
    }
    100% {
      transform: translateX(-640px);
      position: absolute;
    }
  }
  
  @keyframes slideLeftIn {
    from {
      transform: translateX(640px);
    }
    to {
      transform: translateX(0);
    }
  }

  @keyframes slideRightOut {
    0% {
      transform: translateX(0);
    }
    1% {
      position: absolute;
    }
    100% {
      transform: translateX(640px);
      position: absolute;
    }
  }
  
  @keyframes slideRightIn {
    from {
      transform: translateX(-640px);
    }
    to {
      transform: translateX(0);
    }
  }


</style>

<div id="app">
  <section>
    <div class="container">
      <h2 id="pageTitle" class="title is-2 has-text-centered">Configuración</h2>
      
      <form @submit.prevent="save">

        <button id="updateButton" class="button is-primary">Actualizar</button>

        <div v-cloak class="columns is-laptop">
          <div class="column">
            <div class="card">
              <div class="card-content">
                <h4 class="title is-4 has-text-centered">Credenciales</h4>
                <div class="field">
                  <label for="email" class="label">Usuario</label>
                  <p class="control">
                    <input
                    class="input"
                    :class="errors.has('email') ? 'is-danger' : ''"
                    v-model="form.email"
                    id="email"
                    type="text"
                    name="email"
                    placeholder="Usuario del SMTP"
                    v-validate="'required'"  
                    />
                  </p>
                  <p v-cloak class="help is-danger">{{ errors.first('email') }}</p>
                </div>
                <div class="field">
                  <label v-cloak for="password" class="label">Contraseña</label>
                  <p class="control">
                    <input
                    class="input"
                    :class="errors.has('password') ? 'is-danger' : ''"
                    v-model="form.password"
                    id="password"
                    type="password"
                    name="password"
                    placeholder="Contraseña del SMTP"
                    v-validate="'required'"  
                    />
                  </p>
                  <p v-cloak class="help is-danger">{{ errors.first('password') }}</p>
                </div>
              </div>
            </div>
          </div>
          <div class="column">
            <div class="card">
              <div class="card-content">
                <h4 class="title is-4 has-text-centered">Host SMTP</h4>
                <div class="field">
                  <label for="smtp" class="label">Host SMTP</label>
                  <p class="control">
                    <input
                    class="input"
                    :class="errors.has('smtp') ? 'is-danger' : ''"
                    v-model="form.smtp"
                    id="smtp"
                    type="text"
                    name="smtp"
                    placeholder="URL del host SMTP"
                    v-validate="'required'"  
                    />
                  </p>
                  <p v-cloak class="help is-danger">{{ errors.first('smtp') }}</p>
                </div>
                <div class="field">
                  <label for="port" class="label">Puerto</label>
                  <p class="control">
                    <input
                    class="input"
                    :class="errors.has('port') ? 'is-danger' : ''"
                    v-model="form.port"
                    id="port"
                    type="text"
                    name="port"
                    placeholder="Puerto del host SMTP"
                    v-validate="'required'"  
                    />
                  </p>
                  <p v-cloak class="help is-danger">{{ errors.first('port') }}</p>
                </div>
              </div>
            </div>
          </div>
          <div class="column">
            <div class="card">
              <div class="card-content">
                <h4 class="title is-4 has-text-centered">
                  Email
                  <span @click.prevent="infoDialog = true" class="icon has-text-info tooltip" data-tooltip="Haz click para ver la lista de variables.">
                    <i class="fas fa-info-circle"></i>
                  </span>
                </h4>
                 <div class="field">
                  <b-field
                    label="Destinatario(s)"
                    :type="{'is-danger': errors.has('receipts')}"
                    :message="errors.first('receipts')"
                  >
                    <b-taginput
                      :before-adding="beforeAdding"
                      v-model="form.receipts"
                      :placeholder="form.receipts.length > 0 ? '' : 'Agrega un email y presiona enter'"
                      name="receipts"
                      v-validate="'required'"
                    ></b-taginput>
                  </b-field>

                  <label for="title" class="label">Titulo</label>
                  <p class="control">
                    <input
                    class="input"
                    :class="errors.has('title') ? 'is-danger' : ''"
                    v-model="form.title"
                    id="title"
                    type="text"
                    name="title"
                    placeholder="Titulo del mensaje"
                    v-validate="'required'"  
                    />
                  </p>
                  <p v-cloak class="help is-danger">{{ errors.first('title') }}</p>
                </div>
                <div class="field">
                  <label class="label" for="message">Mensaje</label>
                  <p class="control">
                    <textarea
                      name="message"
                      type="text"
                      class="textarea"
                      :class="errors.has('message') ? 'is-danger' : ''"
                      v-model="form.message"
                      id="message"
                      placeholder="Cuerpo del mensaje"
                      v-validate="'required'"
                      rows="3"
                    >
                    </textarea>
                    <p v-cloak class="help is-danger">{{ errors.first('message') }}</p>
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div id="modalForm" class="modal" :class="{'is-active': infoDialog}">
          <transition name="fade">
            <div @click.prevent="infoDialog = false" class="modal-background" v-show="infoDialog"></div>
          </transition>
          <transition name="bounce">
            <div class="modal-card" v-show="infoDialog">
              <header class="modal-card-head">
                <p v-cloak class="modal-card-title">Lista de variables</p>
                <button @click.prevent="infoDialog = false" class="delete" aria-label="close"></button>
              </header>
              <section class="modal-card-body" style="overflow-x: hidden;">
                <article class="message">
                  <div class="message-body">
                    Debido a que esta es una plantilla de mensajes, cada vez que alguien envia un formulario de contacto, envía información y 4 datos variables, el nombre, el email, el teléfono y el mensaje del posible cliente, las variables representan la posición en donde se encontrarán esos datos y pueden estar tanto en el título como en el cuerpo del mensaje.
                  </div>
                </article>
                <table class="table is-bordered is-striped" id="infoTable">
                  <thead>
                    <th>
                      Variable
                    </th>
                    <th>
                      Valor
                    </th>
                  </thead>
                  <tbody>
                    <tr>
                      <td>$nombre</td>
                      <td>Nombre de la persona que envió el mensaje.</td>
                    </tr>
                    <tr>
                      <td>$email</td>
                      <td>Email de la persona que envió el mensaje.</td>
                    </tr>
                    <tr>
                      <td>$telefono</td>
                      <td>Telefono de la persona que envió el mensaje.</td>
                    </tr>
                    <tr>
                      <td>$mensaje</td>
                      <td>El mensaje principal.</td>
                    </tr>
                  </tbody>
                </table>
              </section>
            </div>
          </transition>
        </div>
      </form>
    </div>
  </section>
</div>

<script>
  axios.defaults.baseURL = '<?PHP echo base_url(); ?>';
  const dict = {
  custom: {
    email: {
      required: 'El email del SMTP es requerido'
    },
    password: {
      required: 'La contraseña del SMTP es requerida'
    },
    smtp: {
      required: 'La url del SMTP es requerida'
    },
    port: {
      required: 'El puerto del SMTP es requerido'
    },
    title: {
      required: 'El titulo del email es requerido'
    },
    message: {
      required: 'El cuerpo del email es requerido'
    },
    receipts: {
      required: 'Ingrese al menos una dirección de email'
    }
  }
  };
  
  Vue.use(VeeValidate);
  new Vue({
    el: '#app',
    data: {
      form: {
        email: '',
        password: '',
        smtp: '',
        port: '',
        title: '',
        message: '',
        receipts: []
      },
      infoDialog: false
    },
    computed: {
      invalidForm() {
        return this.errors.all().length > 0;
      },
    },
    methods: {
      beforeAdding(tag) {
        return /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(tag);
      },
      save() {
        console.log('hi')
        this.$validator.validate().then(result => {
          if(!result) return;
          let data = new FormData();
          data.append('config_form',JSON.stringify(this.form));
          
          axios.post('configuracion/update',data)
          .then(response => {
            if(response.data.status == 200)
            {
              Swal.fire({
                type: 'success',
                title: 'Exito!',
                text: 'Configuración actualizada correctamente'
              });
              this.formDialog = false;
              this.loadConfig();
            }
            else
            {
              Swal.fire({
                type: 'error',
                title: 'Lo sentimos',
                text: 'Ha ocurrido un error'
              })
            }
          })
        });
      },
      loadConfig() {
        axios.get('configuracion/get')
        .then(({data: {configuration}}) => {
          this.form = configuration
        })
      },
    },
    created() {
      this.$validator.localize('en',dict);
      this.loadConfig();
    }
  })
</script>
