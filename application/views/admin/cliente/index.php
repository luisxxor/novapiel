<link rel="stylesheet" href="<?PHP echo base_url('assets/bulma/bulma-tooltip.min.css'); ?>">
<link rel="stylesheet" href="<?PHP echo base_url('assets/animate/animate.css'); ?>">
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.18.0/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vee-validate@latest/dist/vee-validate.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@7.33.1/dist/sweetalert2.all.min.js" integrity="sha256-Qfxgn9jULeGAdbaeDjXeIhZB3Ra6NCK3dvjwAG8Y+xU=" crossorigin="anonymous"></script>
<div id="app">
  <section>
    <div class="container">
      <h2 id="pageTitle" class="title is-2 has-text-centered">Listado de Clientes</h2>
      <button @click="formDialog = true" id="addClientButton" class="button is-rounded">Añadir Cliente</button>

      <div v-cloak class="card">
        <div v-cloak class="card-content">
          <div v-cloak class="content">    
            <table v-cloak class="table">
              <thead v-cloak v-show="!clientsIsEmpty">
                <tr v-cloak>
                  <th>
                    ID
                  </th>
                  <th>
                    Nombre
                  </th>
                  <th>
                    Edad
                  </th>
                  <th>
                    Telefono
                  </th>
                  <th>
                    Email
                  </th>
                  <th>
                    Acciones
                  </th>
                </tr>
              </thead>
              <tbody v-cloak>
                <tr v-cloak v-show="!clientsIsEmpty" v-for="(client,index) in clients">
                  <td v-cloak>{{ client.id }}</td>
                  <td v-cloak>{{ client.nombre }}</td>
                  <td v-cloak>{{ client.edad }}</td>
                  <td v-cloak>{{ client.telefono }}</td>
                  <td v-cloak>{{ client.email }}</td>
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
                <tr v-show="clientsIsEmpty">
                  <td class="has-text-centered">
                    Lo sentimos, no hay clientes registrados.
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
        <div id="modalForm" class="modal" :class="{'is-active': formDialog}">
          <transition name="fade">
            <div @click="formDialog = false" class="modal-background" v-show="formDialog"></div>
          </transition>
          <transition name="bounce">
            <div class="modal-card" v-show="formDialog">
              <header class="modal-card-head">
                <p v-cloak class="modal-card-title">{{ formTitle }}</p>
                <button @click="formDialog = false" class="delete" aria-label="close"></button>
              </header>
              <section class="modal-card-body" style="overflow-x: hidden;">
                <form class="form" @submit="save">
                  <div class="tabs">
                    <ul>
                      <li @click="changeTab(0)" :class="tab == 0 ? 'is-active' : ''"><a>Datos Personales</a></li>
                      <li @click="changeTab(1)" :class="tab == 1 ? 'is-active' : ''"><a>Enfermedades Crónicas</a></li>
                      <li @click="changeTab(2)" :class="tab == 2 ? 'is-active' : ''"><a>Medicamentos</a></li>
                      <li @click="changeTab(3)" :class="tab == 3 ? 'is-active' : ''"><a>Otros</a></li>
                    </ul>
                  </div>
                  <transition-group :name="slideDirection" style="position: relative;">
                    <div key="tab0" v-show="tab==0">                    
                      <div class="field">
                        <label for="nombre" class="label">Nombre</label>
                        <p class="control">
                          <input
                          class="input"
                          :class="errors.items.find(val => val.field == 'nombre') != undefined ? 'is-danger' : ''"
                          v-model="form.nombre"
                          id="nombre"
                          type="text"
                          name="nombre"
                          placeholder="Nombre del cliente"
                          v-validate="'required'"  
                          />
                        </p>
                        <p class="help is-danger">{{ errors.first('nombre') }}</p>
                      </div>
                      <div class="field">
                        <label for="edad" class="label">Edad</label>
                        <p class="control">
                          <input
                          type="number"
                          min="0"
                          class="input"
                          :class="errors.items.find(val => val.field == 'edad') != undefined ? 'is-danger' : ''"
                          v-model="form.edad"
                          id="edad"
                          name="edad"
                          placeholder="Edad del cliente"
                          v-validate="'required|min_value:0'"  
                          >
                        </p>
                        <p class="help is-danger">{{ errors.first('edad') }}</p>
                      </div>
                      <div class="field">
                        <label for="telefono" class="label">Telefono</label>
                        <p class="control">
                          <input
                          class="input"
                          :class="errors.items.find(val => val.field == 'telefono') != undefined ? 'is-danger' : ''"
                          v-model="form.telefono"
                          id="telefono"
                          type="text"
                          name="telefono"
                          placeholder="Telefono del cliente"
                          />
                        </p>
                        <p class="help is-danger">{{ errors.first('telefono') }}</p>
                      </div>
                      <div class="field">
                        <label for="email" class="label">Email</label>
                        <p class="control">
                          <input
                          class="input"
                          :class="errors.items.find(val => val.field == 'email') != undefined ? 'is-danger' : ''"
                          v-model="form.email"
                          id="email"
                          type="text"
                          name="email"
                          placeholder="Email del cliente"
                          v-validate="'email'"  
                          />
                        </p>
                        <p class="help is-danger">{{ errors.first('email') }}</p>
                      </div>
                      <div class="field">
                        <label for="ocupacion" class="label">Ocupación</label>
                        <p class="control">
                          <input
                          class="input"
                          v-model="form.ocupacion"
                          id="ocupacion"
                          type="text"
                          name="ocupacion"
                          placeholder="Ocupación del cliente" 
                          />
                        </p>
                        <p class="help is-danger">{{ errors.first('ocupacion') }}</p>
                      </div>
                    </div>
                    <div key="tab1" v-show="tab==1">
                      <section class="section">
                        <div class="buttons" style="justify-content: center;">
                          <span class="button is-outlined" @click="form.hipertension = !form.hipertension" :class="form.hipertension ? 'is-selected is-danger' : ''">
                            Hipertensión &nbsp;
                            <span v-show="form.hipertension" class="icon">
                              <i class="fas fa-check"></i>
                            </span>
                          </span>

                          <span class="button is-outlined" @click="form.diabetes = !form.diabetes" :class="form.diabetes ? 'is-selected is-danger' : ''">
                            Diabetes &nbsp;
                            <span v-show="form.diabetes" class="icon">
                              <i class="fas fa-check"></i>
                            </span>
                          </span>
                          
                          <span class="button is-outlined" @click="form.dislipidemia = !form.dislipidemia" :class="form.dislipidemia ? 'is-selected is-danger' : ''">
                            Dislipidemia &nbsp;
                            <span v-show="form.dislipidemia" class="icon">
                              <i class="fas fa-check"></i>
                            </span>
                          </span>
                          
                          <span class="button is-outlined" @click="form.hipotiroidismo = !form.hipotiroidismo" :class="form.hipotiroidismo ? 'is-selected is-danger' : ''">
                            Hipotiroidismo &nbsp;
                            <span v-show="form.hipotiroidismo" class="icon">
                              <i class="fas fa-check"></i>
                            </span>
                          </span>
                          
                          <span class="button is-outlined" @click="form.cancer = !form.cancer" :class="form.cancer ? 'is-selected is-danger' : ''">
                            Cancer &nbsp;
                            <span v-show="form.cancer" class="icon">
                              <i class="fas fa-check"></i>
                            </span>
                          </span>
                          
                          <span class="button is-outlined" @click="form.miastenia_gravis = !form.miastenia_gravis" :class="form.miastenia_gravis ? 'is-selected is-danger' : ''">
                            Miastenia gravis &nbsp;
                            <span v-show="form.miastenia_gravis" class="icon">
                              <i class="fas fa-check"></i>
                            </span>
                          </span>
                          
                          <span class="button is-outlined" @click="form.enfermedad_neurologica = !form.enfermedad_neurologica" :class="form.enfermedad_neurologica ? 'is-selected is-danger' : ''">
                            Enfermedad neurológica &nbsp;
                            <span v-show="form.enfermedad_neurologica" class="icon">
                              <i class="fas fa-check"></i>
                            </span>
                          </span>
                          
                          <span class="button is-outlined" @click="form.problemas_coagulacion = !form.problemas_coagulacion" :class="form.problemas_coagulacion ? 'is-selected is-danger' : ''">
                            Problemas de coagulación &nbsp;
                            <span v-show="form.problemas_coagulacion" class="icon">
                              <i class="fas fa-check"></i>
                            </span>
                          </span>
                          
                          <span class="button is-outlined" @click="form.enfermedad_autoinmune = !form.enfermedad_autoinmune" :class="form.enfermedad_autoinmune ? 'is-selected is-danger' : ''">
                            Enfermedad autoinmune &nbsp;
                            <span v-show="form.enfermedad_autoinmune" class="icon">
                              <i class="fas fa-check"></i>
                            </span>
                          </span> 
                          
                          <span class="button is-outlined" @click="form.infarto_acc_vascular = !form.infarto_acc_vascular" :class="this.form.infarto_acc_vascular ? 'is-selected is-danger' : ''">
                            Infarto cardíaco / Acc vascular &nbsp;
                            <span v-show="form.infarto_acc_vascular" class="icon">
                              <i class="fas fa-check"></i>
                            </span>
                          </span>
                        </div>

                      </section>
                    </div>
                    <div key="tab2" v-show="tab==2">
                      <section class="section">
                        <div class="buttons" style="justify-content: center">
                          <span class="button is-outlined" @click="form.anticoagulantes = !form.anticoagulantes" :class="form.anticoagulantes ? 'is-selected is-danger' : ''">
                            Anticoagulantes &nbsp;
                            <span v-show="form.anticoagulantes" class="icon">
                              <i class="fas fa-check"></i>
                            </span>
                          </span>

                          <span class="button is-outlined" @click="form.aspirina = !form.aspirina" :class="form.aspirina ? 'is-selected is-danger' : ''">
                            Aspirina &nbsp;
                            <span v-show="form.aspirina" class="icon">
                              <i class="fas fa-check"></i>
                            </span>
                          </span>
                          
                          <span class="button is-outlined" @click="form.antiinflamatorios = !form.antiinflamatorios" :class="form.antiinflamatorios ? 'is-selected is-danger' : ''">
                            Antiinflamatorios &nbsp;
                            <span v-show="form.antiinflamatorios" class="icon">
                              <i class="fas fa-check"></i>
                            </span>
                          </span>
                          
                          <span class="button is-outlined" @click="form.corticoides = !form.corticoides" :class="form.corticoides ? 'is-selected is-danger' : ''">
                            Corticoides &nbsp;
                            <span v-show="form.corticoides" class="icon">
                              <i class="fas fa-check"></i>
                            </span>
                          </span>
                          
                          <span class="button is-outlined" @click="form.relajantes_musculares = !form.relajantes_musculares" :class="form.relajantes_musculares ? 'is-selected is-danger' : ''">
                            Relajantes musculares &nbsp;
                            <span v-show="form.relajantes_musculares" class="icon">
                              <i class="fas fa-check"></i>
                            </span>
                          </span>
                          
                          <span class="button is-outlined" @click="form.antibioticos = !form.antibioticos" :class="form.antibioticos ? 'is-selected is-danger' : ''">
                            Antibioticos &nbsp;
                            <span v-show="form.antibioticos" class="icon">
                              <i class="fas fa-check"></i>
                            </span>
                          </span>
                          
                          <span class="button is-outlined" @click="form.hormonas = !form.hormonas" :class="form.hormonas ? 'is-selected is-danger' : ''">
                            Hormonas &nbsp;
                            <span v-show="form.hormonas" class="icon">
                              <i class="fas fa-check"></i>
                            </span>
                          </span>
                          
                          <span class="button is-outlined" @click="form.otro_medicamento = !form.otro_medicamento" :class="form.otro_medicamento ? 'is-selected is-danger' : ''">
                            Otro medicamento &nbsp;
                            <span v-show="form.otro_medicamento" class="icon">
                              <i class="fas fa-check"></i>
                            </span>
                          </span>
                        </div>
                      </section>
                    
                    </div>
                    <div key="tab3" v-show="tab==3">
                      <section class="section">
                        <div class="buttons" style="justify-content: center">
                          <span class="button is-outlined" @click="form.embarazo = !form.embarazo" :class="form.embarazo ? 'is-selected is-danger' : ''">
                            Embarazo &nbsp;
                            <span v-show="form.embarazo" class="icon">
                              <i class="fas fa-check"></i>
                            </span>
                          </span>

                          <span class="button is-outlined" @click="form.lactancia = !form.lactancia" :class="form.lactancia ? 'is-selected is-danger' : ''">
                            Lactancia &nbsp;
                            <span v-show="form.lactancia" class="icon">
                              <i class="fas fa-check"></i>
                            </span>
                          </span>
                          
                          <span class="button is-outlined" @click="form.alergia_huevo = !form.alergia_huevo" :class="form.alergia_huevo ? 'is-selected is-danger' : ''">
                            Alergia al huevo &nbsp;
                            <span v-show="form.alergia_huevo" class="icon">
                              <i class="fas fa-check"></i>
                            </span>
                          </span>
                          
                          <span class="button is-outlined" @click="form.alergia_lactosa = !form.alergia_lactosa" :class="form.alergia_lactosa ? 'is-selected is-danger' : ''">
                            Alergia a la lactosa &nbsp;
                            <span v-show="form.alergia_lactosa" class="icon">
                              <i class="fas fa-check"></i>
                            </span>
                          </span>
                          
                          <span class="button is-outlined" @click="form.alergia_otro_medicamento = !form.alergia_otro_medicamento" :class="form.alergia_otro_medicamento ? 'is-selected is-danger' : ''">
                            Alergia a otro medicamento &nbsp;
                            <span v-show="form.alergia_otro_medicamento" class="icon">
                              <i class="fas fa-check"></i>
                            </span>
                          </span>
                        </div>
                      </section>
                    </div>
                  </transition-group>

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
      nombre: {
        required: 'El nombre del cliente es requerido'
      },
      edad: {
        min_value: 'La edad seleccionada no es válida',
        numeric: 'El número introducido no es válido',
        decimal: 'El número introducido no es válido'
      }
    }
  };
  
  Vue.use(VeeValidate);
  new Vue({
    el: '#app',
    data: {
      slideDirection: 'slideLeft',
      clients: [],
      form: {
        id: null,
        nombre: '',
        telefono: '',
        email: '',
        ocupacion: '',
        hipertension: false,
        diabetes: false,
        dislipidemia: false,
        hipotiroidismo: false,
        cancer: false,
        miastenia_gravis: false,
        enfermedad_neurologica: false,
        problemas_coagulacion: false,
        enfermedad_autoinmune: false,
        infarto_acc_vascular: false,
        anticoagulantes: false,
        aspirina: false,
        antiinflamatorios: false,
        corticoides: false,
        relajantes_musculares: false,
        antibioticos: false,
        hormonas: false,
        otro_medicamento: false,
        embarazo: false,
        lactancia: false,
        alergia_huevo: false,
        alergia_lactosa: false,
        alergia_otro_medicamento: false
      },
      tab: 0,
      formDialog: false,
      editmode: false
    },
    computed: {
      clientsIsEmpty() {
        return this.clients.length == 0;
      },
      invalidForm() {
        return this.errors.all().length > 0;
      },
      id() {
        return this.form.id
      },
      formTitle() {
        return this.editmode ? 'Editar cliente' : 'Crear cliente'
      }
    },
    methods: {
      editItem(index) {
        this.errors.clear();
        this.form = Object.assign({},this.clients[index]);

        for(let [key, value] of Object.entries(this.form))
        {
          if(['id','nombre','edad','telefono','email','ocupacion'].indexOf(key) == -1)
          {
            this.form[key] = !!+value;
          }
        }
        this.formDialog = true;
        this.editmode = true;
      },
      deleteItem(index){
        Swal({
          title: '¿Estás seguro?',
          text: "¡El cliente será eliminado para siempre!",
          type: 'warning',
          showCancelButton: true,
          confirmButtonText: '¡Si! ¡eliminar!',
          cancelButtonText: '¡No! ¡cancelar!',
          reverseButtons: true
        }).then((result) => {
          if (result.value) {
            let data = new FormData();
            data.append('id',this.clients[index].id);
            axios.post('cliente/delete',data)
            .then(response => {
              if(response)
              {
                Swal(
                  '¡Eliminado!',
                  'El cliente ha sido eliminado.',
                  'success'
                ).then(response => {
                  this.loadClients();
                })
              }
              else
              {
                Swal(
                  'Error',
                  'Ha ocurrido un error.',
                  'warning'
                ).then(response => {
                  this.loadClients();
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
        this.editmode = false;
        setTimeout(() => {
          this.form = {
            id: null,
            nombre: '',
            telefono: '',
            email: '',
            ocupacion: '',
            hipertension: false,
            diabetes: false,
            dislipidemia: false,
            hipotiroidismo: false,
            cancer: false,
            miastenia_gravis: false,
            enfermedad_neurologica: false,
            problemas_coagulacion: false,
            enfermedad_autoinmune: false,
            infarto_acc_vascular: false,
            anticoagulantes: false,
            aspirina: false,
            antiinflamatorios: false,
            corticoides: false,
            relajantes_musculares: false,
            antibioticos: false,
            hormonas: false,
            otro_medicamento: false,
            embarazo: false,
            lactancia: false,
            alergia_huevo: false,
            alergia_lactosa: false,
            alergia_otro_medicamento: false
          };
          this.tab = 0;
          this.errors.clear();
        }, 300);
      },
      save() {
        this.$validator.validate().then(result => {
          if(result)
          {
            let data = new FormData();
            data.append('client_form',JSON.stringify(this.form));
            if(!this.editmode)
            {            
              axios.post('cliente/create',data)
              .then(response => {
                if(response.data.status == 201)
                {
                  Swal.fire({
                    type: 'success',
                    title: 'Exito!',
                    text: 'Ha creado el cliente correctamente'
                  })
                  this.formDialog  = false;
                  this.loadClients();
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
              axios.post('cliente/update',data)
              .then(response => {
                if(response.data.status == 200)
                {
                  Swal.fire({
                    type: 'success',
                    title: 'Exito!',
                    text: 'Cliente actualizado correctamente'
                  });
                  this.formDialog = false;
                  this.loadClients();
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
          }
        });
      },
      loadClients() {
        axios.get('cliente/getClients')
        .then(({data: {clients}}) => {
          this.clients = clients
        })
      },
      changeTab(number)
      {
        let oldTab = this.tab;

        if(oldTab != number)
        {
          if(oldTab > number) //Slide derecha
          {
            this.slideDirection = "slideRight"
          }
          else //Slide izquierda
          {
            this.slideDirection = "slideLeft"
          }

          this.tab = number;
        }
      }
    },
    watch: {
      formDialog(val) {
        return val || this.close();
      }
    },
    created() {
      this.$validator.localize('en',dict);
      let t = this;
      this.loadClients();
    }
  })
</script>

<style>
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