<link rel="stylesheet" href="<?PHP echo base_url('assets/bulma/bulma-tooltip.min.css'); ?>">
<link rel="stylesheet" href="<?PHP echo base_url('assets/animate/animate.css'); ?>">
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.18.0/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vee-validate@latest/dist/vee-validate.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@7.33.1/dist/sweetalert2.all.min.js" integrity="sha256-Qfxgn9jULeGAdbaeDjXeIhZB3Ra6NCK3dvjwAG8Y+xU=" crossorigin="anonymous"></script>
<div id="app">
  <section>
    <div class="container">
      <h2 id="title" class="title is-2 has-text-centered">Listado de Usuarios</h2>
      <button @click="formDialog = true" id="addUserButton" class="button is-rounded">Añadir usuario</button>

      <div v-cloak class="card">
        <div v-cloak class="card-content">
          <div v-cloak class="content">    
            <table v-cloak class="table">
              <thead v-cloak v-show="!usersIsEmpty">
                <tr v-cloak>
                  <th>
                    ID
                  </th>
                  <th>
                    Nombre de usuario
                  </th>
                  <th>
                    Acciones
                  </th>
                </tr>
              </thead>
              <tbody v-cloak>
                <tr v-cloak v-show="!usersIsEmpty" v-for="(user,index) in users">
                  <td v-cloak>{{ user.id }}</td>
                  <td v-cloak>{{ user.username }}</td>
                  <td>
                    <div>
                      <span
                        class="icon clickable tooltip"
                        data-tooltip="Editar"
                        @click="editItem(index)"
                      >
                        <i class="fas fa-edit"></i>
                      </span>
                      <span
                        class="icon clickable tooltip"
                        data-tooltip="Eliminar"
                        @click="deleteItem(index)"
                      >
                        <i class="fas fa-trash"></i>
                      </span>
                    </div>
                  </td>
                </tr>
                <tr v-show="usersIsEmpty">
                  <td class="has-text-centered">
                    Lo sentimos, no hay usuarios registrados.
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!--
        Modal
      -->

      <div id="modalForm" class="modal" :class="formDialog ? 'is-active' : ''">
        <transition name="fade">
          <div @click="formDialog = false" class="modal-background" v-show="formDialog"></div>
        </transition>
        <transition name="bounce">
          <div class="modal-card" v-show="formDialog">
            <header class="modal-card-head">
              <p v-cloak class="modal-card-title">{{ formTitle }}</p>
              <button @click="formDialog = false" class="delete" aria-label="close"></button>
            </header>
            <section class="modal-card-body">
              <form class="form" @submit="save">
                <div class="field">
                  <p class="control has-icons-left">
                    <input
                      class="input"
                      :class="errors.items.find(val => val.field == 'username') != undefined ? 'is-danger' : ''"
                      v-model="form.username"
                      id="username"
                      type="text"
                      name="username"
                      placeholder="Nombre de usuario"
                      v-validate="'required|isUsernameAvailable'"  
                    />
                    <span class="icon is-small is-left">
                      <i class="fas fa-user"></i>
                    </span>
                  </p>
                  <p class="help is-danger">{{ errors.first('username') }}</p>
                </div>
                <div class="field">
                  <p class="control has-icons-left has-icons-right">
                    <input
                      :type="showPassword ? 'text' : 'password'"
                      class="input"
                      :class="errors.items.find(val => val.field == 'password') != undefined ? 'is-danger' : ''"
                      v-model="form.password"
                      id="password"
                      name="password"
                      placeholder="Contraseña"
                      :v-validate="editmode ? '' : 'required'"  
                    >
                    <span class="icon is-small is-left">
                      <i class="fas fa-lock"></i>
                    </span>
                    <span
                      class="icon is-small is-right"
                      v-show="showPassword"
                      @click="showPassword = !showPassword"
                      style="pointer-events: initial; cursor: pointer;"
                    >
                      <i class="class fas fa-eye-slash"></i>
                    </span>
                    <span
                      class="icon is-small is-right"
                      v-show="!showPassword"
                      @click="showPassword = !showPassword"
                      style="pointer-events: initial; cursor: pointer;"
                    >
                      <i class="class fas fa-eye"></i>
                    </span>
                  </p>
                  <p class="help is-danger">{{ errors.first('password') }}</p>
                </div>
                <article class="message is-small is-warning" v-if="this.editmode">
                  <div class="message-body">
                    Si dejas el campo contraseña vacío, no se actualizará la contraseña y se dejará la anterior.<br>
                    Si introduces algo en el campo contraseña reemplazarás la contraseña anterior.
                  </div>
                </article>
              </form>
            </section>
            <footer class="modal-card-foot">
              <button @click="save" :disabled="invalidForm" class="button is-primary">Aceptar</button>
              <button @click="formDialog = false" class="button">Cancelar</button>
            </footer>
          </div>
        </transition>
      </div>
    </div>
  </section>
</div>

<script>
  axios.defaults.baseURL = '<?PHP echo base_url(); ?>';
  const dict = {
    custom: {
      username: {
        required: 'El nombre de usuario es requerido'
      },
      password: {
        required: 'La contraseña es requerida'
      }
    }
  };
  
  Vue.use(VeeValidate);
  new Vue({
    el: '#app',
    data: {
      users: [],
      form: {
        id: null,
        username: '',
        password: ''
      },
      formDialog: false,
      showPassword: false,
      editmode: false
    },
    computed: {
      usersIsEmpty() {
        return this.users.length == 0;
      },
      invalidForm() {
        return this.errors.all().length > 0;
      },
      id() {
        return this.form.id
      },
      formTitle() {
        return this.editmode ? 'Editar usuario' : 'Crear usuario'
      }
    },
    methods: {
      editItem(index)
      {
        this.form = Object.assign({password: ''},this.users[index]);
        this.formDialog = true;
        this.editmode = true;
        console.log(`Editar usuario ${this.users[index].username}`);
      },
      deleteItem(index)
      {
        Swal({
          title: '¿Estás seguro?',
          text: "¡El usuario será eliminado para siempre!",
          type: 'warning',
          showCancelButton: true,
          confirmButtonText: '¡Si! ¡eliminar!',
          cancelButtonText: '¡No! ¡cancelar!',
          reverseButtons: true
        }).then((result) => {
          if (result.value) {
            let data = new FormData();
            data.append('id',this.users[index].id);
            axios.post('usuario/delete',data)
            .then(response => {
              if(response)
              {
                Swal(
                  '¡Eliminado!',
                  'El usuario ha sido eliminado.',
                  'success'
                ).then(response => {
                  this.loadUsers();
                })
              }
              else
              {
                Swal(
                  'Error',
                  'Ha ocurrido un error.',
                  'warning'
                ).then(response => {
                  this.loadUsers();
                })
              }
            })
          } else if (
            result.dismiss === Swal.DismissReason.cancel
          ) {
            Swal(
              'Cancelado',
              'El usuario no fue eliminado.',
              'success'
            )
          }
        })
      },
      close() {
        this.form = {
          id: null,
          username: '',
          password: ''
        }
        setTimeout(() => {
          this.editmode = false;
          this.errors.clear();
        }, 300);
      },
      save(e) {
        e.preventDefault()

        this.$validator.validate().then(result => {
        
          let data = new FormData();
          data.append('user_form',JSON.stringify(this.form));
          if(!this.editmode)
          {            
            axios.post('usuario/create',data).
            then(response => {
              if(response.data.status == 201)
              {
                Swal.fire({
                  type: 'success',
                  title: 'Exito!',
                  text: 'Ha creado al usuario correctamente'
                })
                this.formDialog  = false;
                this.loadUsers();
                this.close();
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
          }
          else
          {
            axios.post('usuario/update',data).
            then(response => {
              if(response.data.status == 200)
              {
                Swal.fire({
                  type: 'success',
                  title: 'Exito!',
                  text: 'Usuario actualizado correctamente'
                });
                this.formDialog = false;
                this.loadUsers();
                this.close();
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
          }
        });
      },
      loadUsers() {
        axios.get('usuario/getUsers')
        .then(({data: {users}}) => {
          this.users = users
        })
      }
    },
    watch: {
      formDialog(val){
        return val || this.close();
      }
    },
    created() {
      this.$validator.localize('en',dict);
      let t = this;
      this.$validator.extend('isUsernameAvailable', {
        getMessage: field => 'El nombre de usuario seleccionado no está disponible',
        validate: async function(value) {
          let data = new FormData();
          data.append('usernameTest',JSON.stringify({id: t.form.id,username: value}));
          const result = await axios.post(`usuario/usernameIsAvailable`,data)
          return result.data.response;
        }
      })

      this.loadUsers();
    }
  })
</script>

<style>
  #title {
    margin-top: 1em;
  }

  #addUserButton {
    margin-bottom: 1em;
  }

  .clickable {
    cursor: pointer;
  }
</style>