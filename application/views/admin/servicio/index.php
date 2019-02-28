<link rel="stylesheet" href="<?PHP echo base_url('assets/bulma/bulma-tooltip.min.css'); ?>">
<link rel="stylesheet" href="<?PHP echo base_url('assets/animate/animate.css'); ?>">
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.18.0/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vee-validate@latest/dist/vee-validate.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@7.33.1/dist/sweetalert2.all.min.js" integrity="sha256-Qfxgn9jULeGAdbaeDjXeIhZB3Ra6NCK3dvjwAG8Y+xU=" crossorigin="anonymous"></script>
<div id="app">
  <section>
    <div class="container">
      <h2 id="pageTitle" class="title is-2 has-text-centered">Listado de Servicios</h2>
      <button @click="formDialog = true" id="addServiceButton" class="button is-rounded">Añadir servicio</button>

      <div v-cloak class="card">
        <div v-cloak class="card-content">
          <div v-cloak class="content">    
            <table v-cloak class="table">
              <thead v-cloak v-show="!servicesIsEmpty">
                <tr v-cloak>
                  <th>
                    ID
                  </th>
                  <th>
                    Titulo
                  </th>
                  <th>
                    Descripcion
                  </th>
                  <th>
                    Precio
                  </th>
                  <th>
                    Acciones
                  </th>
                </tr>
              </thead>
              <tbody v-cloak>
                <tr v-cloak v-show="!servicesIsEmpty" v-for="(service,index) in services">
                  <td v-cloak>{{ service.id }}</td>
                  <td v-cloak>{{ service.title }}</td>
                  <td v-cloak>{{ service.description }}</td>
                  <td v-cloak>{{ service.price }}</td>
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
                <tr v-show="servicesIsEmpty">
                  <td class="has-text-centered">
                    Lo sentimos, no hay servicios registrados.
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
                  <label for="title" class="label">Título</label>
                  <p class="control">
                    <input
                    class="input"
                    :class="errors.items.find(val => val.field == 'title') != undefined ? 'is-danger' : ''"
                    v-model="form.title"
                    id="title"
                    type="text"
                    name="title"
                    placeholder="Titulo del servicio"
                    v-validate="'required'"  
                    />
                  </p>
                  <p class="help is-danger">{{ errors.first('title') }}</p>
                </div>
                <div class="field">
                  <label for="price" class="label">Precio</label>
                  <p class="control has-icons-left">
                    <input
                    type="number"
                    min="0"
                    class="input"
                    :class="errors.items.find(val => val.field == 'price') != undefined ? 'is-danger' : ''"
                    v-model="form.price"
                    id="price"
                    name="price"
                    placeholder="Precio"
                    v-validate="'required|min_value:0'"  
                    >
                    <span class="icon is-small is-left">
                      <i class="fas fa-dollar-sign"></i>
                    </span>
                  </p>
                  <p class="help is-danger">{{ errors.first('price') }}</p>
                </div>
                <div class="field">
                  <div class="control">
                    <label for="description" class="label">Descripción</label>
                    <textarea
                      class="textarea has-fixed-size"
                      :class="errors.items.find(val => val.field == 'description') != undefined ? 'is-danger' : ''"
                      v-model="form.description"
                      id="description"
                      name="description"
                      placeholder="Descripcion (opcional)"
                    >
                    </textarea>
                  </div>
                </div>
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
    title: {
      required: 'El nombre del servicio es requerido'
    },
    price: {
      min_value: 'No se permiten números negativos',
      decimal: 'Solo se permiten números enteros'
    }
  }
  };
  
  Vue.use(VeeValidate);
  new Vue({
    el: '#app',
    data: {
      services: [],
      form: {
        id: null,
        title: '',
        price: 0,
        description: ''
      },
      formDialog: false,
      editmode: false
    },
    computed: {
      servicesIsEmpty() {
        return this.services.length == 0;
      },
      invalidForm() {
        return this.errors.all().length > 0;
      },
      id() {
        return this.form.id
      },
      formTitle() {
        return this.editmode ? 'Editar servicio' : 'Crear servicio'
      }
    },
    methods: {
      editItem(index)
      {
        this.form = Object.assign({},this.services[index]);
        this.formDialog = true;
        this.editmode = true;
      },
      deleteItem(index)
      {
        Swal({
          title: '¿Estás seguro?',
          text: "¡El servicio será eliminado para siempre!",
          type: 'warning',
          showCancelButton: true,
          confirmButtonText: '¡Si! ¡eliminar!',
          cancelButtonText: '¡No! ¡cancelar!',
          reverseButtons: true
        }).then((result) => {
          if (result.value) {
            let data = new FormData();
            data.append('id',this.services[index].id);
            axios.post('servicio/delete',data)
            .then(response => {
              if(response)
              {
                Swal(
                  '¡Eliminado!',
                  'El servicio ha sido eliminado.',
                  'success'
                ).then(response => {
                  this.loadServices();
                })
              }
              else
              {
                Swal(
                  'Error',
                  'Ha ocurrido un error.',
                  'warning'
                ).then(response => {
                  this.loadServices();
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
        title: '',
        price: 0,
        description: ''
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
          data.append('service_form',JSON.stringify(this.form));
          if(!this.editmode)
          {            
            axios.post('servicio/create',data)
            .then(response => {
              if(response.data.status == 201)
              {
                Swal.fire({
                  type: 'success',
                  title: 'Exito!',
                  text: 'Ha creado el servicio correctamente'
                })
                this.formDialog  = false;
                this.loadServices();
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
            axios.post('servicio/update',data)
            .then(response => {
              if(response.data.status == 200)
              {
                Swal.fire({
                  type: 'success',
                  title: 'Exito!',
                  text: 'Servicio actualizado correctamente'
                });
                this.formDialog = false;
                this.loadServices();
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
      loadServices() {
        axios.get('servicio/getServices')
        .then(({data: {services}}) => {
          this.services = services
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
      this.loadServices();
    }
  })
</script>

<style>
  #pageTitle {
    margin-top: 1em;
  }

  #addServiceButton {
    margin-bottom: 1em;
  }

  .clickable {
    cursor: pointer;
  }
</style>