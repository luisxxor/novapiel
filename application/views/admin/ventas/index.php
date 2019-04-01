<link rel="stylesheet" href="<?PHP echo base_url('assets/bulma/bulma-tooltip.min.css'); ?>">
<link rel="stylesheet" href="<?PHP echo base_url('assets/animate/animate.css'); ?>">
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.18.0/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vee-validate@latest/dist/vee-validate.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@7.33.1/dist/sweetalert2.all.min.js" integrity="sha256-Qfxgn9jULeGAdbaeDjXeIhZB3Ra6NCK3dvjwAG8Y+xU=" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://unpkg.com/buefy/dist/buefy.min.css">
<script src="https://unpkg.com/buefy/dist/buefy.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>

<style>

  .table thead tr th,
  .table tbody tr td {
    text-align: center;
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

/*   .modal-card, .modal-card-body {
    overflow: visible!important;
  } */

  #modalSessions .modal-card {
    width: calc(70vw - 40px);
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

  .datepicker .datepicker-table .datepicker-cell {
    padding: .1rem 0.75rem;
  }

  .datepicker .datepicker-content {
    height: 10.25rem;
  }

  .datepicker .datepicker-table .datepicker-body .datepicker-row .datepicker-cell.is-selected {
    background-color: #00d1b2;
    color: #fff;
  }

  .select:not(.is-multiple):not(.is-loading):after {
    border-color: #00d1b2;
    right: 1.125em;
    z-index: 4;
  }

  .has-text-primary {
    color: #00d1b2!important;
  }

  .datepicker.control input[type=text] {
    text-align: center;
    padding: 0;
  }

  .switch:hover input[type=checkbox]:checked+.check {
    background: #00d1b2a8;
  }

  .switch input[type=checkbox]:checked+.check {
    background: #00d1b2;
  }

</style>

<div id="app">
  <section>
    <div class="container">
      <h2 id="pageTitle" class="title is-2 has-text-centered">Listado de Ventas</h2>
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
                    <div class="actionContainer">
                      <span
                      class="icon clickable tooltip"
                      data-tooltip="Ver ficha"
                      @click="openClientFile(index)"
                      >
                        <i class="fas fa-eye"></i>
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
      <div id="modalOrders" class="modal" :class="{'is-active': ordersDialog}">
        <transition name="fade">
          <div class="modal-background" v-show="ordersDialog"></div>
        </transition>
        <transition name="bounce">
          <div class="modal-card" v-show="ordersDialog">
            <header class="modal-card-head">
              <p v-cloak class="modal-card-title">Ordenes del cliente</p>
              <button @click="ordersDialog = false" class="delete" aria-label="close"></button>
            </header>
            <section class="modal-card-body" style="overflow-x: hidden;">
              <b-message v-show="ordersNotSaved.length > 0" type="is-warning">
                Ha creado órdenes de ventas y no ha guardado los cambios, haga click en ACEPTAR si desea añadir sesiones a una órden de venta recien creada.
              </b-message>
              <table class="table is-fullwidth">
                <thead v-cloak v-show="!ordersIsEmpty">
                  <tr v-cloak>
                    <th>
                      ID
                    </th>
                    <th>
                      Fecha
                    </th>
                    <th>
                      Acciones
                    </th>
                  </tr>
                </thead>
                <tbody v-cloak>
                  <tr v-cloak v-show="!ordersIsEmpty" v-for="(order,index) in orders">
                    <td v-cloak>{{ order.id ? order.id : '-' }}</td>
                    <td v-cloak>
                      <b-datepicker
                        placeholder="Fecha"
                        :date-formatter="(date) => dateToFront(date)"
                        :date-parser="(date) => toDateobject(date)"
                        icon="calendar"
                        @input="date => order.fecha = dateToBd(date)"
                        :value="toDateObject(order.fecha)"
                        :month-names="['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre']"
                        :day-names="['D','L','Ma','Mi','J','V','S']"
                        :name="`order_date_${index}`"
                        icon-pack="fa"
                      >
                      </b-datepicker>
                    </td>
                    <td>
                      <div>
                        <span
                        class="icon clickable tooltip"
                        data-tooltip="Ver sesiones"
                        @click="openOrder(index)"
                        v-if="order.id"
                        >
                          <i class="fas fa-eye"></i>
                        </span>
                        <span
                        class="icon clickable tooltip"
                        data-tooltip="Eliminar orden"
                        @click="deleteOrder(index)"
                        >
                          <i class="fas fa-trash"></i>
                        </span>
                      </div>
                    </td>
                  </tr>
                  <tr v-show="ordersIsEmpty">
                    <td class="has-text-centered">
                      Lo sentimos, este cliente no tiene ordenes de venta registradas.
                    </td>
                  </tr>
                </tbody>
              </table>

              <button @click="newOrder" class="button">Añadir Orden</button>
            </section>
            <footer class="modal-card-foot">
              <button @click="saveClientOrders" :disabled="invalidForm" class="button is-primary">Aceptar</button>
              <button @click="ordersDialog = false" class="button">Cancelar</button>
            </footer>
          </div>
        </transition>
      </div>

      <div id="modalSessions" class="modal" :class="{'is-active': sessionsDialog}">
        <transition name="fade">
          <div class="modal-background" v-show="sessionsDialog"></div>
        </transition>
        <transition name="bounce">
          <div class="modal-card" v-show="sessionsDialog">
            <header class="modal-card-head">
              <p v-cloak class="modal-card-title">Sesiones del cliente</p>
              <button @click="sessionsDialog = false" class="delete" aria-label="close"></button>
            </header>
            <section class="modal-card-body" style="overflow-x: hidden;">
              <table class="table is-fullwidth">
                <thead v-cloak v-show="!sessionsIsEmpty">
                  <tr v-cloak>
                    <th>
                      Servicio
                    </th>
                    <th>
                      Precio
                    </th>
                    <th>
                      Fecha
                    </th>
                    <th>
                      Status
                    </th>
                    <th>
                      Acciones
                    </th>
                  </tr>
                </thead>
                <tbody v-cloak>
                  <tr v-cloak v-show="!sessionsIsEmpty" v-for="(session,index) in sessions">
                    <td v-cloak>
                      <div class="field">
                        <div class="select" :class="{'is-danger': errors.first(`service_select_${index}`)}">
                          <select :name="`service_select_${index}`" v-validate="'required'" v-model="session.servicio_id">
                            <option :value="service.id" v-for="(service,service_index) in services">{{ service.title }}</option>
                          </select>
                        </div>
                        <p class="help is-danger" v-if="errors.first(`service_select_${index}`)">Debe seleccionar un servicio</p>
                      </div>
                    </td>
                    <td v-cloak>
                      <div class="field">
                        <input
                          :name="`service_price_${index}`"
                          class="input"
                          :class="{'is-danger': errors.first(`service_price_${index}`)}"
                          type="number"
                          v-model="session.precio"
                          v-validate="'min_value:0'"
                          placeholder="Precio"
                        >
                        <p class="help is-danger" v-if="errors.first(`service_price_${index}`)">No se permiten números negativos</p>
                      </div>
                    </td>
                    <td v-cloak>
                      <b-datepicker
                        placeholder="Fecha"
                        :date-formatter="(date) => dateToFront(date)"
                        :date-parser="(date) => toDateobject(date)"
                        icon="calendar"
                        @input="date => session.fecha = dateToBd(date)"
                        :value="toDateObject(session.fecha)"
                        :month-names="['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre']"
                        :day-names="['D','L','Ma','Mi','J','V','S']"
                        icon-pack="fa"
                      >
                      </b-datepicker>
                    </td>
                    <td v-cloak>
                      <div class="field">
                        <b-switch v-model="session.status"
                          size="is-small">
                          {{ session.status | pending }}
                        </b-switch>
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
                      Lo sentimos, el cliente no tiene sesiones registradas en esta orden de venta.
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
  const dict = {
    custom: {

    }
  };
  
  Vue.use(VeeValidate);
  new Vue({
    el: '#app',
    data: {
      clients: [],
      orders: [],
      client_id: null,
      client: null,
      order_id: null,
      sessions: [],
      ordersDialog: false,
      sessionsDialog: false,
      editmode: false,
      services: []
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
      ordersNotSaved() {
        return this.orders.filter(val => !val.id)
      }
    },
    methods: {
      loadOrders: async function(id) {
        let response = await axios.get(`ventas/getClientOrders?id=${id}`)
        .then(response => {
          this.orders = response.data.orders;

        })
        return response;
      },
      loadSessions: async function(id) {
        let response = axios.get(`ventas/getOrderSessions?id=${id}`)
        .then(response => {
          let sessions = response.data.sessions;
          this.sessions = sessions;
        })
        
        return response;
      },
      openClientFile: async function(index){
        this.client_id = this.clients[index].id;
        await this.loadOrders(this.client_id);
        this.ordersDialog = true;

      },
      openOrder: async function(index) {
        await this.loadSessions(this.orders[index].id);
        this.sessionsDialog = true;
        this.order_id = this.orders[index].id;
      },
      deleteSession(index)
      {
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
            if(this.sessions[index].id == null)
            {
              this.sessions.pop(index);
            }
            else
            {
              let data = new FormData();
              data.append('id',this.sessions[index].id);
              axios.post('ventas/deleteSession',data)
              .then(response => {
                if(response)
                {
                  Swal(
                    '¡Eliminado!',
                    'La sesión ha sido eliminada.',
                    'success'
                  ).then(response => {
                    this.loadSessions(this.order_id);
                  })
                }
                else
                {
                  Swal(
                    'Error',
                    'Ha ocurrido un error.',
                    'warning'
                  ).then(response => {
                    this.loadSessions(this.order_id);
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
      deleteOrder(index)
      {
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
            if(this.orders[index].id == null)
            {
              this.orders.pop(index);
            }
            else
            {
              let data = new FormData();
              data.append('id',this.orders[index].id);
              console.log(this.orders[index])
              axios.post(`ventas/deleteOrder`,data)
              .then(response => {
                if(response)
                {
                  Swal(
                    '¡Eliminado!',
                    'La orden de venta ha sido eliminada.',
                    'success'
                  ).then(response => {
                    this.loadOrders(this.client_id);
                  })
                }
                else
                {
                  Swal(
                    'Error',
                    'Ha ocurrido un error.',
                    'warning'
                  ).then(response => {
                    this.loadOrders(this.client_id);
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
      ordersDialogClose() {
        setTimeout(() => {
          this.editmode = false;
          this.orders = [];
          this.errors.clear();
          this.client_id = null;
        }, 300);
      },
      sessionsDialogClose() {
        setTimeout(() => {
          this.editmode = false;
          this.sessions = [];
          this.order_id = null;
          this.errors.clear();
        }, 300);
      },
      saveOrderSessions() {
        this.$validator.validate().then(result => {
          if(result)
          {
            let data = new FormData();
            data.append('ventas_form',JSON.stringify({ order_id: this.order_id, sessions: this.sessions}));
            axios.post('ventas/updateOrCreateOrderSessions',data)
            .then(response => {
              if(response.data.status == 200)
              {
                Swal.fire({
                  type: 'success',
                  title: 'Exito!',
                  text: 'Operación realizada con éxito'
                })
                this.sessionsDialog  = false;
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
        });
      },
      saveClientOrders() {
        this.$validator.validate().then(result => {
          let data = new FormData();
          data.append('ventas_form',JSON.stringify({ cliente_id: this.client_id, orders: this.orders}));
          axios.post('ventas/updateOrCreateClientOrders',data)
            .then(response => {
              if(response.data.status == 200)
              {
                Swal.fire({
                  type: 'success',
                  title: 'Exito!',
                  text: 'Operación realizada con éxito'
                })
                this.sessionsDialog  = false;
                this.loadClients();
                this.loadOrders(this.client_id);
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
        });
      },
      newSession() {
        this.sessions.push({
          servicio_id: null,
          precio: 0,
          orden_id: this.order_id,
          fecha: window.moment().format('YYYY-MM-DD'),
          status: false,
          id: null
        })
      },
      newOrder() {
        this.orders.push({
          id: null,
          cliente_id: this.client_id,
          fecha: window.moment().format('YYYY-MM-DD')
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
      ordersDialog(val) {
        return val || this.ordersDialogClose();
      },
      sessionsDialog(val) {
        return val || this.sessionsDialogClose();
      }
    },
    created() {
      this.$validator.localize('en',dict);
      let t = this;
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

