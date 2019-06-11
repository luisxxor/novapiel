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
      <h2 id="pageTitle" class="title is-2 has-text-centered">Ventas</h2>
      <button @click="sessionsDialog = true" id="addOrderButton" class="button is-rounded">Añadir Venta</button>
      <div v-cloak class="card">
        <div v-cloak class="card-content">
          <div v-cloak class="content">
            <b-tabs v-model="activeTab">
              <b-tab-item label="Ventas por pedido">
                <b-table :data="sells">
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
                      <button class="button is-small" @click.prevent="openSell(props.row.id)">
                        <b-icon pack="fa" icon="edit" ></b-icon>
                      </button>
                      <button class="button is-small" @click.prevent="deleteSell(props.row.id)">
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
              </b-tab-item>

              <b-tab-item label="Ventas por sesiones">
                <b-field grouped>
                  <b-field label="Desde">
                    <flat-pickr v-model="per_session.min_date" :config="min_date_config"></flat-pickr>
                  </b-field>

                  <b-field label="Hasta">
                    <flat-pickr v-model="per_session.max_date" :config="max_date_config"></flat-pickr>
                  </b-field>

                </b-field>
                <b-table :data="filteredPaidSessions">
                  <template slot-scope="props">
                    <b-table-column field="id" label="ID" sortable>
                      {{ props.row.id }}
                    </b-table-column>

                    <b-table-column field="cliente" label="Cliente" sortable>
                      {{ props.row.cliente }}
                    </b-table-column>

                    <b-table-column field="fecha_pago" label="Fecha de Pago" sortable>
                      {{ props.row.fecha_pago | parseDate }}
                    </b-table-column>

                    <b-table-column field="servicio" label="Servicio" sortable>
                      {{ props.row.servicio }}
                    </b-table-column>

                    <b-table-column field="precio" label="Precio" sortable>
                      {{ props.row.precio }}
                    </b-table-column>

                    <b-table-column field="realizado" label="¿Servicio realizado?" sortable>
                      {{ props.row.realizado | parseBool }}
                    </b-table-column>
                  </template>

                  <template slot="empty">
                    <section class="section">
                      <div class="content has-text-grey has-text-centered">
                        <p>No hay pagos en este rango de fechas: {{ this.per_session.min_date | parseDate }} - {{ this.per_session.max_date | parseDate }}.</p>
                      </div>
                    </section>
                  </template>
                  <template slot="footer">
                    <div class="has-text-center">
                      Total en el rango seleccionado: {{ filteredTotal }} CLP
                    </div>
                  </template>
                </b-table>
              </b-tab-item>
            </b-tabs>

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
                  :value="sell.fecha | parseDate"
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
                  v-model="sell_search"
                  placeholder="Selecciona un cliente"
                  name="client_sells"
                  :open-on-focus="true"
                  @select="option => sell.cliente_id = option ? option.id : null"
                >
                  <template slot="header">
                    <small v-if="sell_search.length > 0"> Resultados de <i>{{ sell_search }}</i> </small>
                    <small v-else> Mostrando todos los clientes </small>
                    <hr>
                  </template>
                  <template slot="empty">No hay clientes que contengan: "{{ sell_search }}" </template>
                </b-autocomplete>
              </b-field>
              <p class="help is-danger" v-if="errors.first(`client_sell`)">Debe seleccionar un cliente</p>
              <b-field label="Servicio">
                <b-select
                  v-validate="'required'"
                  v-model="sell.servicio_id"
                  placeholder="Selecciona un servicio"
                  name="service_sell"
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
              <p class="help is-danger" v-if="errors.first(`service_sell`)">Debe seleccionar un servicio</p>
              <b-field label="Precio">
                <b-input type="number" disabled :value.sync="sell.precio"></b-input>
              </b-field>
              <b-field label="Descuento">
                <p class="control has-icons-left">
                  <input
                    name="sell_discount"
                    class="input"
                    :class="{'is-danger': errors.first(`sell_discount`)}"
                    type="number"
                    step="0.01"
                    max="100"
                    v-model.number="sell.descuento"
                    v-validate="'min_value:0'"
                    placeholder="Descuento"
                    @input="changeServicePrice"
                  >
                  <span class="icon is-small is-left">
                    <i class="fas fa-percent"></i>
                  </span>
                </p>
                <p class="help is-danger" v-if="errors.first(`sell_discount`)">Inserte un porcentaje válido.</p>
              </b-field>
              <hr>
              <b-message :active.sync="atLeastOneSessionUnpaid" type="is-info" has-icon>
                Todas las sesiones que estén marcadas como no pagadas, ya no serán listadas como ventas sino como agendamientos.
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
                    <th>
                      Fecha Pago
                    </th>
                    <th>
                      Realizado
                    </th>
                    <th>
                    </th>
                  </tr>
                </thead>
                <tbody v-cloak>
                  <tr v-cloak v-show="!sessionsIsEmpty" v-for="(session,index) in sessions">
                    <td v-cloak>
                      <b-field>
                        <flat-pickr @on-change="loadAvailableHours(index)"  v-model="session.fecha" :config="config"></flat-pickr>
                      </b-field>
                    </td>
                    <td v-cloak>
                      <div class="field" 
                        label="Error"
                        type="is-danger"
                        message="Something went wrong with this field"
                      >
                        <b-select v-model.number="session.hora" @focus="loadAvailableHours(index)" :name="`session_hour_${index}`" expanded placeholder="Hora">
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
                        <b-input v-model.number="sell.precioPorSesion" type="number" disabled>
                          
                        </b-input>
                      </b-field>
                    </td>
                    <td v-cloak>
                      <div class="field">
                        <b-switch v-model.boolean="session.pagado"></b-switch>
                      </div>
                    </td>
                    <td v-cloak>
                      <b-field>
                        <flat-pickr :disabled="!session.pagado" v-model="session.fecha_pago" :config="config"></flat-pickr>
                      </b-field>
                    </td>
                    <td v-cloak>
                      <div class="field">
                        <b-switch v-model.boolean="session.realizado"></b-switch>
                      </div>
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
      allPaidSessions: [],
      sells: [],
      sell_search: '',
      sell: {
        id: null,
        fecha: window.moment().format('YYYY-MM-DD'),
        cliente_id: null,
        precio: 0,
        servicio_id: null,
        descuento: 0,
        ventas: false
      },
      sessions: [],
      sessionsDialog: false,
      editmode: false,
      services: [],
      search: '',
      config: {
        wrap: true,
        altFormat: 'd/m/Y',
        altInput: true,
        dateFormat: 'Y-m-d',
        locale: flatpickr.l10ns.es
      },
      activeTab: 0,
      per_session: {
        min_date: moment().startOf('month').format('YYYY-MM-DD'),
        max_date: window.moment().format('YYYY-MM-DD')
      }              
    },
    computed: {
      sellsIsEmpty() {
        return this.sells.length == 0;
      },
      atLeastOneSessionUnpaid() {
        return this.sessions.filter(v => !v.pagado).length > 0
      },
      clientsIsEmpty() {
        return this.clients.length == 0;
      },
      clientsFiltered() {
        return this.clients.filter( val => val.nombre.toString().toLowerCase().indexOf(this.sell_search.toString().toLowerCase()) >= 0 )
      },
      filteredPaidSessions() {
        return this.allPaidSessions.filter( val => {
          let fecha_pago = moment(val.fecha_pago);
          let min = moment(this.per_session.min_date);
          let max = moment(this.per_session.max_date);
          return fecha_pago.isSameOrAfter(min) && fecha_pago.isSameOrBefore(max);
        })
      },
      filteredTotal() {
       return this.filteredPaidSessions.length > 0 ? this.filteredPaidSessions.reduce( (old_val, new_val) => {
        return parseFloat(old_val) + parseFloat(new_val.precio)
       }, 0) : 0;
      },
      invalidForm() {
        return this.errors.all().length > 0 || this.sessions.length === 0;
      },
      id() {
        return this.form.id
      },
      min_date_config() {
        return {
          wrap: true,
          altFormat: 'd/m/Y',
          altInput: true,
          dateFormat: 'Y-m-d',
          locale: flatpickr.l10ns.es,
          maxDate: this.per_session.max_date
        }
      },
      max_date_config() {
        return {
          wrap: true,
          altFormat: 'd/m/Y',
          altInput: true,
          dateFormat: 'Y-m-d',
          locale: flatpickr.l10ns.es,
          minDate: this.per_session.min_date
        }
      },
      sessionsIsEmpty() {
        return this.sessions.length == 0;
      },
      sessionsDialogTitle() {
        return this.sell.id ? 'Editar venta' : 'Nuevo venta'
      }
    },
    methods: {
      changeServicePrice: async function() {
        let { data: { unpaid_sessions }} = this.sell.ventas === false ? {data: {unpaid_sessions: 0}} : await axios.get(`ventas/getUnpaidSessions?id=${this.sell.id}`);
        let paid_sessions = this.sessions.length;

        let service_price = this.services.find(v => v.id == this.sell.servicio_id).price;

        let total = service_price * (unpaid_sessions + paid_sessions);

        this.sell.precio = total - (total * parseFloat(this.sell.descuento) / 100)

        this.sell.precioPorSesion = service_price - (service_price * parseFloat(this.sell.descuento) / 100)
      },
      loadSells: async function() {
        let response = await axios.get(`ventas/getSells`)
        .then(response => {
          this.sells = response.data.sells;

        })
        return response;
      },
      loadSessions: async function(id) {
        let response = axios.get(`ventas/getSessions?id=${id}`)
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
      loadAllPaidSessions: async function() {
        let response = axios.get(`ventas/getAllPaidSessions`)
        .then(response => {
          let allPaidSessions = response.data;
          allPaidSessions.forEach( (val,index) => {
            val.hora = parseInt(val.hora)
            val.realizado = !!+val.realizado
          })

          this.allPaidSessions = allPaidSessions;
        })
        
        return response;
      },
      openSell: async function(id) {
        let index = this.sells.findIndex(val => val.id == id)
        this.sell = Object.assign({}, this.sells[index]);
        await this.loadSessions(id);

        let selectedClient = this.clients.find( v => v.id == this.sell.cliente_id)
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
              axios.post('ventas/deleteSession',data)
              .then(response => {
                if(response) {
                  Swal(
                    '¡Eliminado!',
                    'La sesión ha sido eliminada.',
                    'success'
                  ).then(response => {
                    this.loadSessions(this.sell.id);
                    this.changeServicePrice();
                  })
                } else {
                  Swal(
                    'Error',
                    'Ha ocurrido un error.',
                    'warning'
                  ).then(response => {
                    this.loadSessions(this.sell.id);
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
      deleteSell(id) {
        Swal({
          title: '¿Estás seguro?',
          text: "¡Esta venta y las sesiones que tenga registradas serán eliminadas para siempre!",
          type: 'warning',
          showCancelButton: true,
          confirmButtonText: '¡Si! ¡eliminar!',
          cancelButtonText: '¡No! ¡cancelar!',
          reverseButtons: true
        }).then((result) => {
          if (result.value) {
            let data = new FormData();
            data.append('id',id);
            axios.post(`ventas/deleteSell`,data)
            .then(response => {
              if(response) {
                Swal(
                  '¡Eliminado!',
                  'La venta ha sido eliminada.',
                  'success'
                ).then(response => {
                  this.loadSells()
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
          this.sell = { id: null, fecha: window.moment().format('YYYY-MM-DD'), cliente_id: null, precio: 0, service_id: null, descuento: 0, ventas: false };
          this.search = '';
          this.sell_search = '';
          setTimeout(() => {
            this.errors.clear();
          },200)
        }, 300);
        this.loadSells();
        this.loadAllPaidSessions();
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
                "fecha_pago": fecha_pago
              }
            })
            data.append('sell_form',JSON.stringify({ sell: this.sell, sessions: sessions}));
            axios.post('ventas/updateOrCreateOrderSessions',data)
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
          sell_id: this.sell.id,
          fecha: window.moment().format('YYYY-MM-DD'),
          hora: null,
          pagado: true,
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

        let a = axios.get(`ventas/getAvailable?date=${this.sessions[index].fecha}&order_id=${this.sell.id}`)
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
      this.loadSells();
      this.loadClients();
      this.loadServices();
      this.loadAllPaidSessions();
    },
    filters: {
      parseDate(val) {
        return val != null ? val.split('-').reverse().join('/') : ''
      },
      pending(val) {
        return !!+val ? 'Realizado' : 'Pendiente';
      },
      parseBool(val) {
        return !!+val ? 'Si' : 'No';
      }
    }
  })
</script>

