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
    text-align: center;
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
      <h2 id="pageTitle" class="title is-2 has-text-centered">Listado de Ventas</h2>
      <button @click="sessionsDialog = true" id="addOrderButton" class="button is-rounded">Añadir Venta</button>
      <div v-cloak class="card">
        <div v-cloak class="card-content">
          <div v-cloak class="content">    
            <table v-cloak class="table">
              <thead v-cloak v-show="!ordersIsEmpty">
                <tr v-cloak>
                  <th>
                    ID
                  </th>
                  <th>
                    Fecha
                  </th>
                  <th>
                    Cliente
                  </th>
                  <th>
                    Acciones
                  </th>
                </tr>
              </thead>
              <tbody v-cloak>
                <tr v-cloak v-show="!ordersIsEmpty" v-for="(order,index) in orders">
                  <td v-cloak>{{ order.id }}</td>
                  <td v-cloak>{{ order.fecha | parseDate}}</td>
                  <td v-cloak>{{ order.nombre }}</td>
                  <td>
                    <div class="actionContainer">
                      <span
                      class="icon clickable tooltip"
                      data-tooltip="Editar"
                      @click="openOrder(index)"
                      >
                        <i class="fas fa-edit"></i>
                      </span>
                    </div>
                  </td>
                </tr>
                <tr v-show="ordersIsEmpty">
                  <td class="has-text-centered">
                    Lo sentimos, no hay ventas registradas.
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
              <b-field label="Cliente">
                <b-select
                  v-validate="'required'"
                  v-model="order.cliente_id"
                  placeholder="Selecciona un cliente"
                  expanded
                >
                  <option
                    v-for="client in clients"
                    :value="client.id"
                    :key="`client_${client.id}`">
                    {{ client.nombre }}
                  </option>
                </b-select>
              </b-field>
              <p class="help is-danger" v-if="errors.first(`client_order`)">Debe seleccionar un cliente</p>
              <b-field label="Fecha">
                <flat-pickr v-model="order.fecha" :config="config"></flat-pickr>
              </b-field>
              <hr>
              <table class="table is-fullwidth">
                <thead v-cloak v-show="!sessionsIsEmpty">
                  <tr v-cloak>
                    <th>
                      Servicio
                    </th>
                    <th id="service_price_header">
                      Precio
                    </th>
                    <th style="width: 90px;">
                      Descuento
                    </th>
                    <th id="total_price_header">
                      Total
                    </th>
                    <th style="width: 90px;">
                      Sesiones
                    </th>
                    <th>
                      Finalizadas
                    </th>
                    <th>
                    </th>
                  </tr>
                </thead>
                <tbody v-cloak>
                  <tr v-cloak v-show="!sessionsIsEmpty" v-for="(session,index) in sessions">
                    <td v-cloak>
                      <div class="field">
                        <div class="select" :class="{'is-danger': errors.first(`service_select_${index}`)}">
                          <select
                            :name="`service_select_${index}`"
                            v-validate="'required'"
                            v-model="session.servicio_id"
                            @change="changeServicePrice(index)"
                          >
                            <option v-if="services.length > 0" :value="service.id" v-for="(service,service_index) in services">{{ service.title }}</option>
                            <option v-if="services.length == 0" value="">No hay servicios registrados.</option>
                          </select>
                        </div>
                        <p class="help is-danger" v-if="errors.first(`service_select_${index}`)">Debe seleccionar un servicio</p>
                      </div>
                    </td>
                    <td v-cloak class="service_price_field">
                      <div class="field">
                        <input
                          :name="`service_price_${index}`"
                          class="input"
                          :class="{'is-danger': errors.first(`service_price_${index}`)}"
                          type="number"
                          v-model.number="session.precio_servicio"
                          v-validate="'min_value:0'"
                          placeholder="Precio"
                          readonly
                        >
                        <p class="help is-danger" v-if="errors.first(`service_price_${index}`)">No se permiten números negativos</p>
                      </div>
                    </td>
                    <td v-cloak style="min-width: 125px;">
                      <div class="field">
                        <p class="control has-icons-left">
                          <input
                            :name="`session_discount_${index}`"
                            class="input"
                            :class="{'is-danger': errors.first(`session_discount_${index}`)}"
                            type="number"
                            step="0.01"
                            max="100"
                            v-model.number="session.descuento"
                            v-validate="'min_value:0'"
                            placeholder="Descuento"
                            @input="calculateTotal"
                          >
                          <span class="icon is-small is-left">
                            <i class="fas fa-percent"></i>
                          </span>
                        </p>
                        <p class="help is-danger" v-if="errors.first(`session_discount_${index}`)">Inserte un porcentaje válido.</p>
                      </div>
                    </td>
                    <td v-cloak class="total_price_field">
                      <div class="field">
                        <input
                          :name="`total_price_${index}`"
                          class="input"
                          :class="{'is-danger': errors.first(`total_price_${index}`)}"
                          type="number"
                          v-model.number="session.precio"
                          v-validate="'min_value:0'"
                          placeholder="Precio total"
                          v-model="session.precio"
                          readonly
                        >
                        <p class="help is-danger" v-if="errors.first(`total_price_${index}`)">No se permiten números negativos</p>
                      </div>
                    </td>
                    <td v-cloak>
                      <div class="field">
                        <input
                          :name="`session_qty_${index}`"
                          class="input"
                          :class="{'is-danger': errors.first(`session_qty_${index}`)}"
                          type="number"
                          v-model.number="session.sesiones"
                          v-validate="'min_value:1'"
                          placeholder="Sesiones"
                          @input="calculateTotal"
                        >
                        <p class="help is-danger" v-if="errors.first(`session_qty_${index}`)">Se debe colocar al menos 1 sesión</p>
                      </div>
                    </td>
                    <td v-cloak stlye="padding-left: 0;">
                      <div class="field">                        
                        <div class="select" :class="{'is-danger': errors.first(`session_finished_${index}`)}">
                          <select
                            :name="`session_finished_${index}`"
                            v-model.number="session.finalizadas"
                            v-validate="'required'"
                          >
                            <option>0</option>
                            <option v-for="i in session.sesiones" :key="`session_finished_${index}_${i}`">{{ i }}</option>
                          </select>
                        </div>
                        <p class="help is-danger" v-if="errors.first(`session_finished_${index}`)">Seleccione una opción válida.</p>
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
                      Esta orden de venta está vacía.
                    </td>
                  </tr>
                </tbody>
              </table>

              <button @click="newSession" class="button">Añadir Sesión</button>
            </section>
            <footer class="modal-card-foot">
              <button @click.prevent="saveOrderSessions" :disabled="invalidForm" class="button is-primary">Aceptar</button>
              <button @click.prevent="sessionsDialog = false" class="button">Cancelar</button>
            </footer>
          </div>
        </transition>
      </div>
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
      orders: [],
      order: {
        id: null,
        fecha: window.moment().format('YYYY-MM-DD'),
        cliente_id: null
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
      }              
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
      ordersIsEmpty() {
        return this.orders.length == 0;
      },
      sessionsIsEmpty() {
        return this.sessions.length == 0;
      },
      sessionsDialogTitle() {
        return this.order.id ? 'Editar orden de venta' : 'Nueva orden de venta'
      }
    },
    methods: {
      changeServicePrice(index) {
        let servicio_id = this.sessions[index].servicio_id;
        let servicio = this.services.find(val => val.id == servicio_id);

        this.sessions[index].precio_servicio = parseInt(servicio.price)
        this.calculateTotal();
      },
      calculateTotal() {
        this.sessions.forEach(val => {
          val.precio = val.precio_servicio * val.sesiones;
          val.precio = val.precio - (val.precio * val.descuento / 100)
        })
      },
      loadOrders: async function() {
        let response = await axios.get(`ventas/getOrders`)
        .then(response => {
          this.orders = response.data.orders;

        })
        return response;
      },
      loadSessions: async function(id) {
        let response = axios.get(`ventas/getOrderSessions?id=${id}`)
        .then(response => {
          let sessions = response.data.sessions;
          sessions.forEach(val => {
            val.sesiones = parseInt(val.sesiones)
            val.finalizadas = parseInt(val.finalizadas)
          })
          this.sessions = sessions;
        })
        
        return response;
      },
      openOrder: async function(index) {
        this.order = Object.assign({}, this.orders[index]);
        await this.loadSessions(this.orders[index].id);

        this.sessions.forEach((val, index) => {
          this.changeServicePrice(index)
        })
        
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
                    this.loadSessions(this.order.id);
                  })
                } else {
                  Swal(
                    'Error',
                    'Ha ocurrido un error.',
                    'warning'
                  ).then(response => {
                    this.loadSessions(this.order.id);
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
      deleteOrder(index) {
        Swal({
          title: '¿Estás seguro?',
          text: "¡La orden de venta y las sesiones que tenga registradas serán eliminadas para siempre!",
          type: 'warning',
          showCancelButton: true,
          confirmButtonText: '¡Si! ¡eliminar!',
          cancelButtonText: '¡No! ¡cancelar!',
          reverseButtons: true
        }).then((result) => {
          if (result.value) {
            if(this.orders[index].id == null) {
              this.orders.pop(index);
            } else {
              let data = new FormData();
              data.append('id',this.orders[index].id);
              console.log(this.orders[index])
              axios.post(`ventas/deleteOrder`,data)
              .then(response => {
                if(response) {
                  Swal(
                    '¡Eliminado!',
                    'La orden de venta ha sido eliminada.',
                    'success'
                  ).then(response => {
                    
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
            }
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
          this.order = { id: null, fecha: window.moment().format('YYYY-MM-DD'), cliente_id: null };
          this.search = '';
          this.errors.clear();
        }, 300);
      },
      saveOrderSessions() {
        this.$validator.validate().then(result => {
          if(result) {
            let data = new FormData();
            let sessions = this.sessions.map( val => {
              return {
                "id": val.id,
                "orden_id": val.orden_id,
                "precio": val.precio,
                "servicio_id": val.servicio_id,
                "sesiones": val.sesiones,
                "finalizadas": val.finalizadas,
                "descuento": val.descuento
              }
            })
            data.append('ventas_form',JSON.stringify({ order: this.order, sessions: sessions}));
            axios.post('ventas/updateOrCreateOrderSessions',data)
            .then(response => {
              if(response.data.status == 200) {
                Swal.fire({
                  type: 'success',
                  title: 'Exito!',
                  text: 'Operación realizada con éxito'
                })
                this.sessionsDialog  = false;
                this.loadClients();
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
          servicio_id: null,
          precio: 0,
          descuento: 0,
          precio_servicio: 0,
          orden_id: this.order.id,
          sesiones: 1,
          finalizadas: 0,
          id: null
        })
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
      this.loadOrders();
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

