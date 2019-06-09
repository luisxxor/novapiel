<link rel="stylesheet" href="<?PHP echo base_url('assets/bulma/bulma-tooltip.min.css'); ?>">
<link rel="stylesheet" href="<?PHP echo base_url('assets/animate/animate.css'); ?>">
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.18.0/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vee-validate@latest/dist/vee-validate.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@7.33.1/dist/sweetalert2.all.min.js" integrity="sha256-Qfxgn9jULeGAdbaeDjXeIhZB3Ra6NCK3dvjwAG8Y+xU=" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://unpkg.com/buefy/dist/buefy.min.css">
<script src="https://unpkg.com/buefy/dist/buefy.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>

<link href="https://cdn.jsdelivr.net/npm/flatpickr@4/dist/flatpickr.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/flatpickr@4/dist/flatpickr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vue-flatpickr-component@8"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr@4.5.7/dist/l10n/es.js"></script>

<style>

  .table thead tr th,
  .table tbody tr td {
    text-align: center!important;
  }

  #pageTitle {
    margin-top: 1em;
  }

  #addOrderButton {
    margin-bottom: 1em;
  }

  .clickable {
    cursor: pointer;
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

  .button.is-primary[disabled], fieldset[disabled] .button.is-primary {
    background-color: #00d1b2;
    border-color: transparent;
    box-shadow: none;
  }


  .select:not(.is-multiple):not(.is-loading):after {
    border-color: #00d1b2;
    right: 1.125em;
    z-index: 4;
  }

  .has-text-primary {
    color: #00d1b2!important;
  }

  .switch:hover input[type=checkbox]:checked+.check {
    background: #00d1b2a8;
  }

  .switch input[type=checkbox]:checked+.check {
    background: #00d1b2;
  }

  .flatpickr-input {
    background-color: #fff;
    border-color: #dbdbdb;
    color: #363636;
    box-shadow: inset 0 1px 2px hsla(0,0%,4%,.1);
    max-width: 100%;
    width: 100%;
    -webkit-appearance: none;
    align-items: center;
    border: 1px solid #DBDBDB;
    border-radius: 4px;
    /* box-shadow: none; */
    display: inline-flex;
    font-size: 1rem;
    height: 2.25em;
    justify-content: flex-start;
    line-height: 1.5;
    padding: calc(.375em - 1px) calc(.625em - 1px);
    position: relative;
    vertical-align: top;
  }

  .b-table .table th .th-wrap {
    display: block!important;
  }

  @media screen and (min-width: 769px) {
     #modalSessions .modal-card {
      margin: 0;
      width: calc(95vw - 40px);
    }
  }

  @media screen and (max-width: 1088px) {
    #service_price_header,
    #total_price_header,
    .service_price_field,
    .total_price_field {
      display: none;
    }
  }

</style>

<div id="app">
  <section>
    <div class="container">
      <h2 id="pageTitle" class="title is-2 has-text-centered">Agendamientos</h2>
      <button @click="sessionsDialog = true" id="addOrderButton" class="button is-rounded">Añadir Agendamiento</button>
      <div v-cloak class="card">
        <div v-cloak class="card-content">
          <div v-cloak class="content">
            <b-table :data="appointments" :columns="columns_appointments">

              <template slot-scope="props">
                <b-table-column field="id" label="ID" sortable>
                  {{ props.row.id }}
                </b-table-column>

                <b-table-column field="fecha" label="Fecha" sortable>
                  {{ props.row.fecha | parseDate }}
                </b-table-column>

                <b-table-column field="nombre" label="Cliente" sortable>
                  {{ props.row.nombre }}
                </b-table-column>

                <b-table-column field="titulo" label="Servicio" sortable>
                  {{ props.row.titulo }}
                </b-table-column>

                <b-table-column field="precio" label="Precio" sortable>
                  {{ props.row.precio }}
                </b-table-column>

                <b-table-column field="descuento" label="Descuento" sortable>
                  {{ props.row.descuento }}
                </b-table-column>

                <b-table-column label="Acciones">
                  <button class="button is-small" @click.prevent="openAppointment(props.row.id)">
                    <b-icon pack="fa" icon="edit" ></b-icon>
                  </button>
                  <button class="button is-small" @click.prevent="deleteAppointment(props.row.id)">
                    <b-icon pack="fa" icon="trash" ></b-icon>
                  </button>
                </b-table-column>
              </template>

              <template slot="empty">
                <section class="section">
                    <div class="content has-text-grey has-text-centered">
                        <p>Nada que mostrar aquí.</p>
                    </div>
                </section>
              </template>
            
            </b-table>
          </div>
        </div>
      </div>

      <!--
        Modal form
      -->

      <div id="modalSessions" class="modal" :class="{'is-active': sessionsDialog}">
        <transition name="fade">
          <div class="modal-background" v-show="sessionsDialog"></div>
        </transition>
        <transition name="bounce">
          <div class="modal-card" v-show="sessionsDialog">
            <header class="modal-card-head">
              <p v-cloak class="modal-card-title">{{ sessionsDialogTitle }}</p>
              <button @click="sessionsDialog = false" class="delete" aria-label="close"></button>
            </header>
            <section class="modal-card-body" style="overflow-x: hidden;">
              <b-field label="Fecha">
                <b-input type="text"
                  :value="appointment.fecha | parseDate"
                  disabled
                >
                </b-input>
              </b-field>
              <b-field label="Cliente">
                <b-autocomplete
                  ref="autocomplete_client"
                  :data.sync="clientsFiltered"
                  v-validate="'required'"
                  field="nombre"
                  v-model="appointment_search"
                  placeholder="Selecciona un cliente"
                  name="client_appointment"
                  :open-on-focus="true"
                  @select="option => appointment.cliente_id = option ? option.id : null"
                >
                  <template slot="header">
                    <small v-if="appointment_search.length > 0"> Resultados de <i>{{ appointment_search }}</i> </small>
                    <small v-else> Mostrando todos los clientes </small>
                    <hr>
                  </template>
                  <template slot="empty">No hay clientes que contengan: "{{ appointment_search }}" </template>
                </b-autocomplete>
              </b-field>
              <p class="help is-danger" v-if="errors.first(`client_appointment`)">Debe seleccionar un cliente</p>
              <b-field label="Servicio">
                <b-select
                  v-validate="'required'"
                  v-model="appointment.servicio_id"
                  placeholder="Selecciona un servicio"
                  name="service_appointment"
                  expanded
                  @input="changeServicePrice"
                >
                  <option
                    v-for="service in services"
                    :value="service.id"
                    :key="`service_${service.id}`">
                    {{ service.title }}
                  </option>
                </b-select>
              </b-field>
              <p class="help is-danger" v-if="errors.first(`service_appointment`)">Debe seleccionar un servicio</p>
              <b-field label="Precio">
                <b-input type="number" disabled :value.sync="appointment.precio"></b-input>
              </b-field>
              <b-field label="Descuento">
                <p class="control has-icons-left">
                  <input
                    name="appointment_discount"
                    class="input"
                    :class="{'is-danger': errors.first(`appointment_discount`)}"
                    type="number"
                    step="0.01"
                    max="100"
                    v-model.number="appointment.descuento"
                    v-validate="'min_value:0'"
                    placeholder="Descuento"
                    @input="changeServicePrice"
                  >
                  <span class="icon is-small is-left">
                    <i class="fas fa-percent"></i>
                  </span>
                </p>
                <p class="help is-danger" v-if="errors.first(`appointment_discount`)">Inserte un porcentaje válido.</p>
              </b-field>
              <hr>
              <b-message :active.sync="atLeastOneSessionPaid" type="is-info" has-icon>
                Todas las sesiones que estén marcadas como pagadas, ya no serán listadas como agendamientos sino como ventas.
              </b-message>
              <table class="table is-fullwidth">
                <thead v-cloak v-show="!sessionsIsEmpty">
                  <tr v-cloak>
                    <th>
                      Fecha
                    </th>
                    <th>
                      Hora
                    </th>
                    <th>
                      Precio
                    </th>
                    <th>
                      Pagado
                    </th>
                    <th v-if="atLeastOneSessionPaid">
                      Fecha Pago
                    </th>
                    <th>
                    </th>
                  </tr>
                </thead>
                <tbody v-cloak>
                  <tr v-cloak v-show="!sessionsIsEmpty" v-for="(session,index) in sessions">
                    <td v-cloak>
                      <b-field>
                        <flat-pickr v-validate="'required'" @on-change="loadAvailableHours(index)"  v-model="session.fecha" :config="config"></flat-pickr>
                      </b-field>
                    </td>
                    <td v-cloak>
                      <div class="field" 
                        label="Error"
                        type="is-danger"
                        message="Something went wrong with this field"
                      >
                        <b-select v-validate="'required'" v-model.number="session.hora" @focus="loadAvailableHours(index)" :name="`session_hour_${index}`" expanded placeholder="Hora">
                          <option
                            v-for="(hour, h_index) in sessions[index].available_hours"
                            :value="hour.value"
                            :key="`hour_session_${index}_${h_index}`"
                          >{{ hour.text }}</option>
                        </b-select>
                        <p class="help is-danger" v-if="session.available_hours.length < 1"> No hay disponibilidad para esta fecha </p>
                        <p class="help is-danger" v-if="errors.first(`session_hour_${index}`)">Debe seleccionar una hora.</p>
                      </div>
                    </td>
                    <td v-cloak style="min-width: 125px;">
                      <b-field>
                        <b-input v-model.number="appointment.precioPorSesion" type="number" disabled>
                          
                        </b-input>
                      </b-field>
                    </td>
                    <td v-cloak>
                      <div class="field">
                        <b-switch v-model.boolean="session.pagado"></b-switch>
                      </div>
                    </td>
                    <td v-cloak v-if="atLeastOneSessionPaid">
                      <b-field v-if="session.pagado">
                        <flat-pickr v-model="session.fecha_pago" :config="config"></flat-pickr>
                      </b-field>
                    </td>
                    <td>
                      <div>
                        <span
                        class="icon clickable tooltip"
                        data-tooltip="Eliminar sesion"
                        @click="deleteSession(index)"
                        >
                          <i class="fas fa-trash"></i>
                        </span>
                      </div>
                    </td>
                  </tr>
                  <tr v-show="sessionsIsEmpty">
                    <td class="has-text-centered">
                      Agregue al menos una sesión del servicio.
                    </td>
                  </tr>
                </tbody>
              </table>

              <button @click="newSession" class="button">Añadir Sesión</button>
            </section>
            <footer class="modal-card-foot">
              <button @click.prevent="saveSessions" :disabled="invalidForm" class="button is-primary">Aceptar</button>
              <button @click.prevent="sessionsDialog = false" class="button">Cerrar</button>
            </footer>
          </div>
        </transition>
      </div>

      <!--
        Modal file
      -->

      <b-modal :active.sync="fileDialog" :width="640" scroll="keep">
        <div class="card">
          <div class="card-content">
          
          </div>
        </div>
      </b-modal>
    </div>
  </section>
</div>

<script>
  axios.defaults.baseURL = '<?PHP echo base_url(); ?>';
  
  Vue.component('flat-pickr', VueFlatpickr);
  Vue.use(VeeValidate);
  new Vue({
    el: '#app',
    data: {
      clients: [],
      appointments: [],
      appointment_search: '',
      appointment: {
        id: null,
        fecha: window.moment().format('YYYY-MM-DD'),
        cliente_id: null,
        precio: 0,
        servicio_id: null,
        descuento: 0,
        ventas: false,
      },
      columns_appointments: [
        {
          field: 'id',
          label: 'ID',
          width: '40',
          numeric: true,
          centered: true
        },
        {
          field: 'fecha',
          label: 'Fecha',
          centered: true
        },
        {
          field: 'nombre',
          label: 'Cliente',
          centered: true
        },        
        {
          field: 'titulo',
          label: 'Servicio',
          centered: true
        },
        {
          field: 'precio',
          label: 'Precio',
          centered: true
        },
        {
          field: 'descuento',
          label: 'Descuento',
          centered: true
        },
        {
          field: 'acciones',
          label: 'Acciones',
          centered: true
        },
      ],
      sessions: [],
      sessionsDialog: false,
      fileDialog: false,
      editmode: false,
      services: [],
      search: '',
      config: {
        wrap: true,
        altFormat: 'd/m/Y',
        altInput: true,
        dateFormat: 'Y-m-d',
        locale: flatpickr.l10ns.es        
      }              
    },
    computed: {
      appointmentsIsEmpty() {
        return this.appointments.length == 0;
      },
      atLeastOneSessionPaid() {
        return this.sessions.filter(v => v.pagado).length > 0
      },
      clientsIsEmpty() {
        return this.clients.length == 0;
      },
      clientsFiltered() {
        return this.clients.filter( val => val.nombre.toString().toLowerCase().indexOf(this.appointment_search.toString().toLowerCase()) >= 0 )
      },
      invalidForm() {
        return this.errors.all().length > 0 || this.sessions.length === 0;
      },
      id() {
        return this.form.id
      },
      sessionsIsEmpty() {
        return this.sessions.length == 0;
      },
      sessionsDialogTitle() {
        return this.appointment.id ? 'Editar agendamiento' : 'Nuevo agendamiento'
      }
    },
    methods: {
      changeServicePrice: async function() {
        let { data: { paid_sessions }} = this.appointment.ventas === false ? {data: {paid_sessions: 0}} : await axios.get(`agendamiento/getPaidSessions?id=${this.appointment.id}`);
        let appointed_sessions = this.sessions.length;

        let service_price = this.services.find(v => v.id == this.appointment.servicio_id).price;

        let total = service_price * (paid_sessions + appointed_sessions);

        this.appointment.precio = total - (total * parseFloat(this.appointment.descuento) / 100)

        this.appointment.precioPorSesion = service_price - (service_price * parseFloat(this.appointment.descuento) / 100)
      },
      loadAppointments: async function() {
        let response = await axios.get(`agendamiento/getAppointments`)
        .then(response => {
          this.appointments = response.data.appointments;

        })
        return response;
      },
      loadSessions: async function(id) {
        let response = axios.get(`agendamiento/getSessions?id=${id}`)
        .then(response => {
          let sessions = response.data.sessions;
          sessions.forEach( (val,index) => {
            val.hora = parseInt(val.hora)
            val.realizado = !!+val.realizado
            val.pagado = !!+val.pagado;
            val.available_hours = [];
          })
          this.sessions = sessions;

          this.sessions.forEach( (val,index) => {
            this.loadAvailableHours(index);
          })
        })
        
        return response;
      },
      openAppointment: async function(id) {
        let index = this.appointments.findIndex(val => val.id == id)
        this.appointment = Object.assign({}, this.appointments[index]);
        await this.loadSessions(id);

        let selectedClient = this.clients.find( v => v.id == this.appointment.cliente_id)
        this.$refs.autocomplete_client.setSelected(selectedClient);
        this.changeServicePrice();
        this.sessionsDialog = true;
      },
      deleteSession(index) {
        Swal({
          title: '¿Estás seguro?',
          text: "¡Las sesión será eliminada para siempre!",
          type: 'warning',
          showCancelButton: true,
          confirmButtonText: '¡Si! ¡eliminar!',
          cancelButtonText: '¡No! ¡cancelar!',
          reverseButtons: true
        }).then((result) => {
          if (result.value) {
            if(this.sessions[index].id == null) {
              this.sessions.pop(index);
              this.changeServicePrice();
            } else {
              let data = new FormData();
              data.append('id',this.sessions[index].id);
              axios.post('agendamiento/deleteSession',data)
              .then(response => {
                if(response) {
                  Swal(
                    '¡Eliminado!',
                    'La sesión ha sido eliminada.',
                    'success'
                  ).then(response => {
                    this.loadSessions(this.appointment.id);
                    this.changeServicePrice();
                  })
                } else {
                  Swal(
                    'Error',
                    'Ha ocurrido un error.',
                    'warning'
                  ).then(response => {
                    this.loadSessions(this.appointment.id);
                    this.changeServicePrice();
                  })
                }
              })
            }
          } else if (
            result.dismiss === Swal.DismissReason.cancel
          ) {
            Swal(
              'Cancelado',
              'La sesión no fue eliminada.',
              'success'
            )
          }
        })
      },
      deleteAppointment(id) {
        Swal({
          title: '¿Estás seguro?',
          text: "¡Este agendamiento y las sesiones que tenga registradas serán eliminadas para siempre!",
          type: 'warning',
          showCancelButton: true,
          confirmButtonText: '¡Si! ¡eliminar!',
          cancelButtonText: '¡No! ¡cancelar!',
          reverseButtons: true
        }).then((result) => {
          if (result.value) {
            let data = new FormData();
            data.append('id',id);
            axios.post(`agendamiento/deleteAppointment`,data)
            .then(response => {
              if(response) {
                Swal(
                  '¡Eliminado!',
                  'El agendamiento ha sido eliminado.',
                  'success'
                ).then(response => {
                  this.loadAppointments()
                })
              } else {
                Swal(
                  'Error',
                  'Ha ocurrido un error.',
                  'warning'
                ).then(response => {
                
                })
              }
            })
          } else if (
            result.dismiss === Swal.DismissReason.cancel
          ) {
            Swal(
              'Cancelado',
              'La orden no fue eliminada.',
              'success'
            )
          }
        })

      },
      sessionsDialogClose() {
        setTimeout(() => {
          this.editmode = false;
          this.sessions = [];
          this.appointment = { id: null, fecha: window.moment().format('YYYY-MM-DD'), cliente_id: null, precio: 0, service_id: null, descuento: 0, ventas: false };
          this.search = '';
          this.appointment_search = '';
          setTimeout(() => {
            this.errors.clear();
          },50)
        }, 300);
        this.loadAppointments();
      },
      saveSessions() {
        this.$validator.validate().then(result => {
          if(result) {
            let data = new FormData();
            let sessions = this.sessions.map( val => {
              let fecha_pago = val.pagado ? val.fecha_pago : null;
              return {
                "id": val.id,
                "orden_id": val.orden_id,
                "realizado": val.realizado,
                "pagado": val.pagado,
                "fecha": val.fecha,
                "hora": val.hora,
                "fecha_pago": val.fecha_pago
              }
            })
            data.append('appointment_form',JSON.stringify({ appointment: this.appointment, sessions: sessions}));
            axios.post('agendamiento/updateOrCreateOrderSessions',data)
            .then(response => {
              if(response.data.status == 200) {
                Swal.fire({
                  type: 'success',
                  title: 'Exito!',
                  text: 'Operación realizada con éxito'
                })
                this.sessionsDialog  = false;
                this.sessionsDialogClose();
              } else {
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
      newSession() {
        this.sessions.push({
          appointment_id: this.appointment.id,
          fecha: window.moment().format('YYYY-MM-DD'),
          hora: null,
          pagado: false,
          realizado: false,
          available_hours: [{}],
          id: null,
          fecha_pago: null
        })
        this.loadAvailableHours(this.sessions.length - 1);
        this.changeServicePrice();
      },
      loadClients() {
        axios.get('cliente/getClients')
        .then(({data: {clients}}) => {
          this.clients = clients
        })
      },
      loadServices: async function() {
        let a = axios.get('servicio/getServices')
        .then(({data: {services}}) => {
          this.services = services
        })

        return await a;
      },
      loadAvailableHours: async function(index) {
        await this.$nextTick();

        let a = axios.get(`agendamiento/getAvailable?date=${this.sessions[index].fecha}&order_id=${this.appointment.id}`)
        .then( response => {
          let available_in_bd = response.data;

          let sessions_same_day = this.sessions.filter( v => v.fecha === this.sessions[index].fecha );

          if(sessions_same_day.length > 1) {
            sessions_same_day.forEach(actual_session => {
              let hours_same_day = [];
              let original_index = this.sessions.indexOf(actual_session);
              let filtered = sessions_same_day.filter(v => this.sessions.indexOf(v) !== original_index);
              filtered.forEach(v => hours_same_day.push(v.hora))

              available_hours = available_in_bd.filter(v => hours_same_day.indexOf(v.value) === -1);

              this.sessions[original_index].available_hours = available_hours;
            })
          } else {
            this.sessions[index].available_hours = available_in_bd;
          }
          
        })

        return await a;
      },
      toDateObject(date) {
        return new Date(moment(date,'YYYY-MM-DD').utc().valueOf())
      },
      dateToBd(date) {
        return moment(date).format('YYYY-MM-DD');
      },
      dateToFront(date) {
        return moment(date).format('DD/MM/YYYY');
      }

    },
    watch: {
      sessionsDialog(val) {
        return val || this.sessionsDialogClose();
      }
    },
    created() {
      let t = this;
      this.loadAppointments();
      this.loadClients();
      this.loadServices();
    },
    filters: {
      parseDate(val) {
        return val != null ? val.split('-').reverse().join('/') : ''
      },
      pending(val) {
        return !!+val ? 'Realizado' : 'Pendiente';
      }
    }
  })
</script>

